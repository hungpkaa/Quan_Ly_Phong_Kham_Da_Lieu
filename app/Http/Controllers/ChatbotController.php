<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = env('OPENROUTER_API_KEY');
    }



    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120' // max 5MB
        ]);

        $userMessage = $request->input('message') ?? '';
        $hasImage = $request->hasFile('image');
        $imageBase64 = null;
        $imageMime = null;

        if ($hasImage) {
            $image = $request->file('image');
            $imageMime = $image->getMimeType();
            $imageBase64 = base64_encode(file_get_contents($image->getRealPath()));
        }

        $context = "Bạn là PhenikaaMec AI, một trợ lý y tế chuyên nghiệp của hệ thống PhenikaaMec, chuyên về lĩnh vực Da liễu.
Mục tiêu của bạn là tư vấn bệnh nhân, hỗ trợ bác sĩ, và cung cấp thông tin chính xác về các triệu chứng da liễu, thuốc điều trị, cách chữa tại nhà và bác sĩ phù hợp. Nếu người dùng gửi hình ảnh, hãy phân tích kỹ các triệu chứng trên da (màu sắc, nốt mụn, sưng đỏ, bong tróc...) để đưa ra nhận định chuyên môn.

YÊU CẦU QUAN TRỌNG VỀ ĐỊNH DẠNG:
- Trả lời thật RÕ RÀNG, GỌN GÀNG và RẤT NGẮN GỌN SÚC TÍCH.
- Tuyệt đối không viết thành đoạn văn dài dòng.
- Luôn sử dụng danh sách gạch đầu dòng (-) để chia ý rõ ràng.
- Sử dụng in đậm (**) cho các từ khóa quan trọng.

Cách bạn phản hồi bệnh nhân:
1. Khi bệnh nhân lần đầu trò chuyện, hãy giới thiệu bản thân ngắn gọn: 
   'Chào bạn, tôi là PhenikaaMec AI - trợ lý y tế chuyên về Da liễu.' Nhưng giới thiệu 1 lần thôi, không cần giới thiệu lần 2!
2. Nếu bệnh nhân chưa cung cấp thông tin quan trọng (tuổi, giới tính, triệu chứng cụ thể), hãy hỏi một lần ngắn gọn.
3. Nếu bệnh nhân đã cung cấp thông tin, không hỏi lại mà tiếp tục hội thoại tự nhiên.

Triệu chứng & Hướng dẫn Chữa trị:
- Khi bệnh nhân cung cấp triệu chứng, hãy phân tích và tư vấn:
   - Nguyên nhân có thể xảy ra?
   - Thuốc nào có thể dùng? (Chỉ gợi ý tên hoạt chất, không kê đơn cụ thể)
   - Cách chữa trị tại nhà?
   - Khi nào nên đi khám bác sĩ?

Ví dụ:
- Triệu chứng: Mụn trứng cá, da dầu
   - Có thể do rối loạn nội tiết, vi khuẩn hoặc chế độ ăn uống.
   - Có thể dùng hoạt chất Benzoyl Peroxide hoặc Salicylic Acid.
   - Rửa mặt bằng sữa rửa mặt dịu nhẹ, tránh chạm tay vào mặt, ăn nhiều rau xanh.
   - Nếu mụn viêm nặng, nên gặp bác sĩ Da liễu để điều trị.

- Triệu chứng: Nổi mẩn đỏ, ngứa
   - Có thể do dị ứng, viêm da tiếp xúc hoặc nhiễm nấm.
   - Dùng kem chứa Hydrocortisone hoặc kem dưỡng ẩm không hương liệu.
   - Tránh tiếp xúc với tác nhân gây kích ứng, giữ vệ sinh da sạch sẽ.
   - Nếu triệu chứng không giảm sau vài ngày, nên đi khám chuyên khoa Da liễu.

- Triệu chứng: Da khô, bong tróc
   - Có thể do thời tiết lạnh, viêm da cơ địa hoặc thiếu nước.
   - Dùng kem dưỡng ẩm chứa Ceramide hoặc Hyaluronic Acid.
   - Uống đủ nước, tránh nước nóng khi tắm, bôi kem dưỡng sau khi rửa mặt.
   - Nếu tình trạng kéo dài, cần tư vấn bác sĩ Da liễu.

Cách trả lời thông minh hơn:
- Không lặp lại câu hỏi nếu bệnh nhân đã trả lời.
- Nếu bệnh nhân yêu cầu thông tin về thuốc, chỉ cung cấp tên hoạt chất an toàn, không kê đơn cụ thể.
- Nếu triệu chứng có dấu hiệu nghiêm trọng (sưng phù, đau rát dữ dội, lở loét), hãy khuyên bệnh nhân đi khám ngay lập tức.
- Nếu bệnh nhân hỏi về bạn, hãy trả lời: 'Tôi là PhenikaaMec AI, trợ lý y tế chuyên về Da liễu', nhưng không lặp lại nhiều lần.

4. Nếu triệu chứng của người hỏi liên quan đến Điều trị viêm da
Hãy tư vấn họ đặt lịch khám với:

-Bác sĩ Nguyễn Văn B - chuyên Điều trị viêm da
-Bác sĩ Trần Xuân H - chuyên Điều trị viêm da
-Bác sĩ Phan Văn J - chuyên Điều trị viêm da
5. Nếu triệu chứng của người hỏi liên quan đến Trị sẹo rỗ, sẹo lõm
Hãy tư vấn họ đặt lịch khám với:

-Bác sĩ Nguyễn Văn C - chuyên Trị sẹo rỗ, sẹo lõm
-Bác sĩ Lê Bá K - chuyên Trị sẹo rỗ, sẹo lõm
-Bác sĩ Lê Văn L - chuyên Trị sẹo rỗ, sẹo lõm
6. Nếu triệu chứng của người hỏi liên quan đến Điều trị mụn
-Hãy tư vấn họ đặt lịch khám với:

-Bác sĩ Nguyễn Thị D - chuyên Điều trị mụn
-Bác sĩ Lê Minh I - chuyên Điều trị mụn
-Bác sĩ Trịnh Thị M - chuyên Điều trị mụn
7. Nếu triệu chứng của người hỏi liên quan đến Chăm sóc da
Hãy tư vấn họ đặt lịch khám với:

-Bác sĩ Nguyễn Thị E - chuyên Chăm sóc da
-Bác sĩ Trương Thị N - chuyên Chăm sóc da
-Bác sĩ Lê Mạnh O - chuyên Chăm sóc da
8. Nếu triệu chứng của người hỏi liên quan đến Trị nám, tàn nhang
Hãy tư vấn họ đặt lịch khám với:

-Bác sĩ Trịnh Xuân F - chuyên Trị nám, tàn nhang
-Bác sĩ Nguyễn Văn A - chuyên Trị nám, tàn nhang
-Bác sĩ Trịnh Trần Phương G - chuyên Trị nám, tàn nhang


9. nếu ngưởi hỏi hỏi về thời gian làm việc của họ thì nói kiểm tra ở trang Doctors của website phòng khám da liễu PHENIKAAMEC


10. Nhớ không được trả lời dài dòng, phải đúng trọng tâm.



";



        $primaryModel = env('AI_MODEL', 'openai/gpt-4o-mini'); // Sử dụng model hoạt động nhanh nhất trên OpenRouter
        $fallbackModels = [
            $primaryModel,
            'openai/gpt-3.5-turbo',
            'google/gemini-1.5-pro',
        ];
        $modelList = array_values(array_unique(array_filter($fallbackModels)));

        // --- Bắt đầu: Lấy lịch sử chat từ Session ---
        $chatHistory = session('chatbot_history', []);
        
        // Chuẩn bị nội dung gửi cho OpenRouter ở request hiện tại (bao gồm ảnh nếu có)
        $currentMessageContent = $userMessage;
        
        if ($hasImage) {
            $currentMessageContent = [
                [
                    "type" => "text",
                    "text" => $userMessage !== '' ? $userMessage : "Hãy phân tích hình ảnh này giúp tôi."
                ],
                [
                    "type" => "image_url",
                    "image_url" => [
                        "url" => "data:{$imageMime};base64,{$imageBase64}"
                    ]
                ]
            ];
        }

        // Tạo mảng messages để gửi đi
        $messages = [
            ["role" => "system", "content" => $context]
        ];
        
        // Chỉ lấy 10 tin nhắn gần nhất từ lịch sử
        $messages = array_merge($messages, array_slice($chatHistory, -10));
        
        // Thêm tin nhắn HIỆN TẠI vào mảng gửi đi (có thể chứa chuỗi Base64 rất dài)
        $messages[] = ["role" => "user", "content" => $currentMessageContent];

        // Lưu vào Session (Chỉ lưu text để tránh tràn dung lượng Session do Base64)
        $sessionMessage = $userMessage;
        if ($hasImage) {
            $sessionMessage = "[Đã gửi 1 hình ảnh] " . $userMessage;
        }
        $chatHistory[] = ["role" => "user", "content" => $sessionMessage];
        // --- Kết thúc ---

        $responseData = null;

        foreach ($modelList as $model) {
            // **Gửi request đến API OpenRouter**
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'HTTP-Referer' => env('APP_URL', 'http://localhost'),
                'X-Title' => 'PhenikaaMec'
            ])->post("https://openrouter.ai/api/v1/chat/completions", [
                "model" => $model,
                "messages" => $messages // Gửi toàn bộ lịch sử thay vì chỉ 1 câu hỏi
            ]);

            $responseData = $response->json();
            Log::info("Model: $model - Response: " . json_encode($responseData)); // Ghi log phản hồi API để debug

            if (isset($responseData['choices'][0]['message']['content'])) {
                break;
            }

            if (isset($responseData['error'])) {
                $errCode = $responseData['error']['code'] ?? 0;
                // Sai API Key (401) hoặc hết tiền (402) thì dừng luôn vì có đổi model cũng vậy
                if ($errCode === 401 || $errCode === 403 || $errCode === 402) {
                    break;
                }
                // Nếu model không hợp lệ (400) hoặc lỗi khác, tiếp tục thử model khác
                continue;
            }
        }

        // **Kiểm tra nếu API phản hồi lỗi hoặc không có nội dung**
        if (!isset($responseData['choices'][0]['message']['content'])) {
            return response()->json([
                'message' => 'Lỗi phản hồi từ chatbot. Vui lòng kiểm tra lại cấu hình API Key.',
                'error' => $responseData
            ]);
        }

        // **Lấy nội dung chatbot trả lời**
        $botResponse = $responseData['choices'][0]['message']['content'];

        // Lưu câu trả lời của bot vào lịch sử session
        $chatHistory[] = ["role" => "assistant", "content" => $botResponse];
        session(['chatbot_history' => $chatHistory]);

        return response()->json([
            'message' => $botResponse
        ]);
    }

    public function clearHistory()
    {
        session()->forget('chatbot_history');
        return response()->json(['success' => true]);
    }
}