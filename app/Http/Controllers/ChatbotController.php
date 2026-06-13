<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Service;
use App\Models\User;
use App\Services\Appointments\AiBookingService;
use App\Services\Appointments\AvailableSlotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class ChatbotController extends Controller
{
    /**
     * Gửi tin nhắn tới Gemini API (có hỗ trợ Vision)
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'nullable|string',
            'image'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ]);

        $userMessage = $request->input('message') ?? '';
        $hasImage    = $request->hasFile('image');
        $imageBase64 = null;
        $imageMime   = null;

        if ($hasImage) {
            $image       = $request->file('image');
            $imageMime   = $image->getMimeType();
            $imageBase64 = base64_encode(file_get_contents($image->getRealPath()));
        }

        // ========== DYNAMIC CONTEXT ==========
        $systemPrompt = $this->buildSystemPrompt();

        // ========== CHAT HISTORY ==========
        $chatHistory = session('chatbot_history', []);

        // ========== CHỌN API ==========
        $geminiKey = env('GEMINI_API_KEY');
        $openrouterKey = env('OPENROUTER_API_KEY');
        $result = null;
        $preferOpenRouterVision = $hasImage
            && $openrouterKey
            && (env('VISION_MODEL') || env('AI_VISION_MODEL') || env('OPENROUTER_VISION_MODEL'));

        if ($preferOpenRouterVision) {
            Log::info("Using OpenRouter vision model before Gemini");
            $result = $this->callOpenRouter($openrouterKey, $systemPrompt, $chatHistory, $userMessage, $hasImage, $imageBase64, $imageMime);
        }

        // Thử Gemini trước
        if ($result === null && $geminiKey) {
            $result = $this->callGemini($geminiKey, $systemPrompt, $chatHistory, $userMessage, $hasImage, $imageBase64, $imageMime);
        }

        // Fallback sang OpenRouter nếu Gemini thất bại hoặc không có key
        if ($result === null && $openrouterKey && !$preferOpenRouterVision) {
            Log::info("Gemini failed or unavailable, falling back to OpenRouter");
            $result = $this->callOpenRouter($openrouterKey, $systemPrompt, $chatHistory, $userMessage, $hasImage, $imageBase64, $imageMime);
        }

        if ($hasImage && $result !== null && $this->isImageAnalysisRefusal(is_string($result) ? $result : json_encode($result))) {
            Log::warning('Final chatbot response rejected because image analysis was refused.');
            $result = null;
        }

        if ($result === null) {
            if ($hasImage) {
                return response()->json([
                    'message' => 'Tôi chưa đọc được hình ảnh từ cấu hình AI hiện tại. Vui lòng kiểm tra GEMINI_API_KEY hoặc dùng một model OpenRouter có hỗ trợ vision, rồi thử gửi lại ảnh.',
                ]);
            }

            return response()->json([
                'message' => 'Lỗi phản hồi từ chatbot. Vui lòng kiểm tra lại cấu hình API Key.',
            ]);
        }

        // ========== LƯU LỊCH SỬ ==========
        $sessionMessage = $userMessage;
        if ($hasImage) {
            $sessionMessage = "[Đã gửi 1 hình ảnh] " . $userMessage;
        }
        $chatHistory[] = ["role" => "user", "content" => $sessionMessage];
        $chatHistory[] = ["role" => "assistant", "content" => $result];
        session(['chatbot_history' => $chatHistory]);

        return response()->json(['message' => $result]);
    }

    /**
     * Xóa lịch sử chat
     */
    public function clearHistory()
    {
        session()->forget('chatbot_history');
        return response()->json(['success' => true]);
    }

    // ================================================================
    // PRIVATE METHODS
    // ================================================================

    /**
     * Xây dựng System Prompt với dữ liệu động từ Database
     */
    private function buildSystemPrompt(): string
    {
        // Lấy danh sách bác sĩ từ DB, nhóm theo chuyên khoa
        $doctorsList = '';
        try {
            $doctors = Doctor::with('user')->get()->groupBy('specialty');
            foreach ($doctors as $specialty => $group) {
                $names = $group->map(function ($d) {
                    return $d->user ? ('  - BS. ' . $d->user->name) : null;
                })->filter()->implode("\n");
                $doctorsList .= "Chuyên khoa: {$specialty}\n{$names}\n";
            }
        } catch (\Exception $e) {
            $doctorsList = "(Không tải được danh sách bác sĩ)\n";
        }

        // Lấy danh sách dịch vụ từ DB
        $servicesList = '';
        try {
            $services = Service::pluck('name');
            $servicesList = $services->map(fn($s) => "  - {$s}")->implode("\n");
        } catch (\Exception $e) {
            $servicesList = "(Không tải được danh sách dịch vụ)\n";
        }

        return <<<PROMPT
Bạn là PhenikaaMec AI — trợ lý tư vấn của Phòng Khám Da Liễu Phenikaa.

NĂNG LỰC CỐT LÕI:
- Mô tả và phân tích sơ bộ triệu chứng da liễu (mụn, nám, viêm da, sẹo, dị ứng...)
- Phân tích hình ảnh da liễu nếu người dùng gửi ảnh (mô tả chi tiết triệu chứng nhìn thấy: màu sắc, kích thước, vị trí, mức độ...)
- Giới thiệu bác sĩ phù hợp theo chuyên khoa
- Hướng dẫn đặt lịch khám tại website
- Giải đáp thắc mắc về dịch vụ, giờ làm việc, quy trình khám

QUY TẮC BẮT BUỘC:
- TUYỆT ĐỐI KHÔNG đề xuất, gợi ý hay nhắc đến bất kỳ loại thuốc, hoạt chất, kem bôi hay sản phẩm điều trị nào. Đây là việc của bác sĩ chuyên môn.
- Trả lời ngắn gọn, súc tích, dùng danh sách gạch đầu dòng (-)
- In đậm (**) từ khóa quan trọng
- Chỉ giới thiệu bản thân 1 lần duy nhất ở tin nhắn đầu tiên
- Không hỏi lại thông tin bệnh nhân đã cung cấp
- Nếu triệu chứng nghiêm trọng (sưng phù, lở loét, đau dữ dội) → khuyên đi khám NGAY

CÁCH TƯ VẤN:
1. Phân tích sơ bộ triệu chứng, mô tả nguyên nhân có thể
2. Hướng dẫn chăm sóc cơ bản tại nhà (vệ sinh, giữ ẩm, tránh nắng... — KHÔNG đề cập thuốc)
3. Giới thiệu bác sĩ chuyên khoa phù hợp (từ danh sách bên dưới)
4. Khuyên bệnh nhân đặt lịch khám để được bác sĩ chẩn đoán và kê đơn điều trị chính xác

ĐẶT LỊCH KHÁM QUA CHATBOT:
- Sau khi tư vấn triệu chứng, hãy chủ động hỏi: "Bạn có muốn tôi hỗ trợ đặt lịch khám ngay không?"
- Nếu người dùng đồng ý đặt lịch, hãy kết thúc câu trả lời BẰNG ĐÚNG chuỗi ký tự: [SHOW_BOOKING_FORM]
- KHÔNG tự ý xác nhận đã đặt lịch thành công. Hệ thống sẽ hiển thị form để người dùng điền thông tin.
- Chỉ chèn [SHOW_BOOKING_FORM] khi người dùng RÕ RÀNG muốn đặt lịch (nói "có", "đặt lịch", "muốn khám", v.v.)

ĐỘI NGŨ BÁC SĨ PHÒNG KHÁM:
{$doctorsList}
→ Khi tư vấn, hãy gợi ý bác sĩ có chuyên khoa phù hợp với triệu chứng của bệnh nhân.
→ Thời gian làm việc: kiểm tra tại trang "Đội ngũ bác sĩ" trên website phòng khám.

DỊCH VỤ PHÒNG KHÁM CUNG CẤP:
{$servicesList}

LƯU Ý QUAN TRỌNG:
- Bạn KHÔNG phải bác sĩ. Mọi phân tích chỉ mang tính tham khảo ban đầu.
- KHÔNG BAO GIỜ gợi ý thuốc, hoạt chất hay sản phẩm điều trị. Nếu bệnh nhân hỏi về thuốc, hãy nói: "Việc kê đơn thuốc cần được bác sĩ chuyên khoa thực hiện sau khi thăm khám trực tiếp."
- Luôn khuyên bệnh nhân đến khám trực tiếp để được chẩn đoán và điều trị đúng cách.
KHI HỖ TRỢ ĐẶT LỊCH:
- Khi người dùng muốn đặt lịch, hãy nói hệ thống sẽ tự tìm bác sĩ còn lịch trống theo chuyên khoa/ngày/ca và kết thúc bằng [SHOW_BOOKING_FORM].
PROMPT;
    }

    private function isImageAnalysisRefusal(?string $text): bool
    {
        if ($text === null) {
            return true;
        }

        $normalized = mb_strtolower($text, 'UTF-8');
        $ascii = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $normalized);
        if (is_string($ascii) && $ascii !== '') {
            $normalized = $ascii;
        }
        $normalized = str_replace(['đ', 'Đ'], ['d', 'd'], $normalized);
        $normalized = str_replace(
            ['không', 'hình ảnh', 'phân tích', 'xem được', 'xin lỗi'],
            ['khong', 'hinh anh', 'phan tich', 'xem duoc', 'xin loi'],
            $normalized
        );

        $patterns = [
            'toi khong the phan tich hinh anh',
            'khong the phan tich hinh anh',
            'khong the phan tich hinh anh cu the',
            'khong the xem hinh anh',
            'khong xem duoc hinh anh',
            'cannot analyze the image',
            'cannot analyze images',
            'cannot view images',
            'can\'t analyze images',
            'unable to analyze images',
            'i cannot see the image',
            'i can\'t see the image',
            'as a text-based',
            'text based ai',
        ];

        foreach ($patterns as $pattern) {
            if (strpos($normalized, $pattern) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Gọi Google Gemini API trực tiếp
     */
    private function callGemini(string $apiKey, string $systemPrompt, array $chatHistory, string $userMessage, bool $hasImage, ?string $imageBase64, ?string $imageMime): ?string
    {
        $model = env('GEMINI_MODEL', 'gemini-2.0-flash');

        // Xây dựng contents theo format Gemini
        $contents = [];

        // System instruction qua phần đầu
        // Gemini hỗ trợ systemInstruction riêng
        $systemInstruction = ['parts' => [['text' => $systemPrompt]]];

        // Thêm lịch sử hội thoại (tối đa 10 tin gần nhất)
        $recentHistory = array_slice($chatHistory, -10);
        foreach ($recentHistory as $msg) {
            $role = $msg['role'] === 'assistant' ? 'model' : 'user';
            $contents[] = [
                'role'  => $role,
                'parts' => [['text' => $msg['content']]]
            ];
        }

        // Tin nhắn hiện tại
        $currentParts = [];
        $textContent = $userMessage !== '' ? $userMessage : ($hasImage ? 'Hãy phân tích hình ảnh da liễu này và tư vấn cho tôi.' : '');
        if ($textContent) {
            $currentParts[] = ['text' => $textContent];
        }

        if ($hasImage && $imageBase64 && $imageMime) {
            $currentParts[] = [
                'inline_data' => [
                    'mime_type' => $imageMime,
                    'data'      => $imageBase64
                ]
            ];
        }

        $contents[] = [
            'role'  => 'user',
            'parts' => $currentParts
        ];

        $payload = [
            'system_instruction' => $systemInstruction,
            'contents'           => $contents,
            'generationConfig'   => [
                'temperature'     => 0.7,
                'maxOutputTokens' => 1024,
            ]
        ];

        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

        try {
            $response = Http::timeout(30)->post($url, $payload);
            $data = $response->json();

            Log::info("Gemini Response", ['model' => $model, 'status' => $response->status()]);

            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                $text = $data['candidates'][0]['content']['parts'][0]['text'];
                if ($hasImage && $this->isImageAnalysisRefusal($text)) {
                    Log::warning('Gemini returned image-analysis refusal', ['model' => $model]);
                    return null;
                }

                return $text;
            }

            // Log lỗi chi tiết
            if (isset($data['error'])) {
                Log::error("Gemini API Error", $data['error']);
            }

            return null;
        } catch (\Exception $e) {
            Log::error("Gemini API Exception: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Fallback: Gọi OpenRouter API (giữ lại code cũ làm dự phòng)
     */
    private function callOpenRouter(string $apiKey, string $systemPrompt, array $chatHistory, string $userMessage, bool $hasImage, ?string $imageBase64, ?string $imageMime): ?string
    {
        $currentMessageContent = $userMessage;
        if ($hasImage && $imageBase64 && $imageMime) {
            $currentMessageContent = [
                [
                    "type" => "text",
                    "text" => $userMessage !== '' ? $userMessage : "Hãy phân tích hình ảnh này giúp tôi."
                ],
                [
                    "type"      => "image_url",
                    "image_url" => [
                        "url" => "data:{$imageMime};base64,{$imageBase64}"
                    ]
                ]
            ];
        }

        $messages = [["role" => "system", "content" => $systemPrompt]];
        $messages = array_merge($messages, array_slice($chatHistory, -10));
        $messages[] = ["role" => "user", "content" => $currentMessageContent];

        $modelList = [];
        if ($hasImage) {
            // Ưu tiên model miễn phí hỗ trợ vision khi có ảnh
            $modelList = [
                env('OPENROUTER_VISION_MODEL'),
                env('VISION_MODEL'),
                env('AI_VISION_MODEL'),
                'nex-agi/nex-n2-pro:free',
                'nvidia/nemotron-nano-12b-v2-vl:free',
                'google/gemini-2.0-flash-exp',
                'google/gemini-1.5-pro',
                'openai/gpt-4o-mini',
            ];
        } else {
            $modelList = [
                env('AI_MODEL', 'openai/gpt-4o-mini'),
                'google/gemini-2.0-flash-exp',
                'google/gemini-1.5-pro',
            ];
        }
        $modelList = array_values(array_unique(array_filter($modelList)));

        foreach ($modelList as $model) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'HTTP-Referer'  => env('APP_URL', 'http://localhost'),
                    'X-Title'       => 'PhenikaaMec'
                ])->timeout(30)->post("https://openrouter.ai/api/v1/chat/completions", [
                    "model"    => $model,
                    "messages" => $messages
                ]);

                $data = $response->json();
                Log::info("OpenRouter Model: $model", ['status' => $response->status()]);

                if (isset($data['choices'][0]['message']['content'])) {
                    $content = $data['choices'][0]['message']['content'];
                    $contentText = is_string($content) ? $content : json_encode($content);

                    if ($hasImage && $this->isImageAnalysisRefusal($contentText)) {
                        Log::warning('OpenRouter model returned image-analysis refusal', ['model' => $model]);
                        continue;
                    }

                    return $content;
                }

                if (isset($data['error'])) {
                    $errCode = $data['error']['code'] ?? 0;
                    if (in_array($errCode, [401, 402, 403])) break;
                }
            } catch (\Exception $e) {
                Log::error("OpenRouter Exception ($model): " . $e->getMessage());
                continue;
            }
        }

        return null;
    }

    /**
     * API: Lấy danh sách bác sĩ cho form đặt lịch trong chatbot
     */
    public function getDoctorsForChatbot()
    {
        try {
            $doctorModels = Doctor::with('user')->get();
            $doctors = $doctorModels->map(function ($d) {
                return [
                    'id'        => $d->id,
                    'name'      => $d->user ? $d->user->name : 'N/A',
                    'specialty'  => $d->specialty,
                ];
            });
            return response()->json([
                'doctors' => $doctors,
                'specialties' => $doctorModels->pluck('specialty')->filter()->unique()->values(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['doctors' => [], 'specialties' => []], 500);
        }
    }

    public function availableSlots(Request $request, AvailableSlotService $availableSlotService)
    {
        $data = $request->validate([
            'specialty' => 'nullable|string|max:255',
            'date_from' => 'nullable|date',
            'preferred_shift' => 'nullable|in:morning,afternoon',
        ]);

        $slots = $availableSlotService->findAvailableSlots([
            'specialty' => $data['specialty'] ?? null,
            'date_from' => $data['date_from'] ?? now()->toDateString(),
            'preferred_shift' => $data['preferred_shift'] ?? null,
            'days' => 14,
            'limit' => 5,
        ]);

        return response()->json(['slots' => $slots]);
    }

    /**
     * API: Đặt lịch khám từ chatbot
     */
    public function storeFromChatbot(Request $request, AiBookingService $aiBookingService)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'phone'            => 'required|string|max:20',
            'doctor_id'        => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'shift'            => 'required|in:morning,afternoon',
            'description'      => 'nullable|string|max:500',
        ]);

        try {
            // Tìm hoặc tạo user dựa vào SĐT
            $appointment = $aiBookingService->book($request->only([
                'name',
                'phone',
                'doctor_id',
                'appointment_date',
                'shift',
                'description',
            ]));

            $doctor = $appointment->doctor;
            $doctorName = $doctor && $doctor->user ? $doctor->user->name : 'bác sĩ';
            $shiftText = $request->shift === 'morning' ? 'Buổi sáng' : 'Buổi chiều';

            return response()->json([
                'success' => true,
                'message' => "Đặt lịch thành công! Bạn đã đặt lịch khám với BS. {$doctorName} vào ngày {$request->appointment_date} ({$shiftText}). Phòng khám sẽ liên hệ xác nhận qua số điện thoại {$request->phone}."
            ]);

        } catch (\Exception $e) {
            if ($e instanceof RuntimeException) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage() ?: 'Ca khám này vừa có người khác đặt. Vui lòng chọn một lịch trống khác.'
                ], 409);
            }

            Log::error('Chatbot Booking Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Đặt lịch thất bại. Vui lòng thử lại hoặc gọi hotline 1900 886648.'
            ], 500);
        }
    }
}
