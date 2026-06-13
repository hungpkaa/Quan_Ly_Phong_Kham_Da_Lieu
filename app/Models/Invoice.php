<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    public const STATUS_PAID = 'paid';
    public const STATUS_UNPAID = 'unpaid';

    protected $fillable = [
        'invoice_date', // Ngày lập hóa đơn
        'total_amount', // Tổng số tiền của hóa đơn
        'status',       // Trạng thái thanh toán (Đã thanh toán/Chưa thanh toán)
        'medical_record_id', // Liên kết với hồ sơ bệnh án
        'services_medicines', // Dịch vụ và thuốc
    ];

    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }

    public static function normalizeStatus(?string $status): ?string
    {
        $status = trim((string) $status);

        if ($status === self::STATUS_PAID || $status === 'Đã thanh toán' || $status === 'ÄĂ£ thanh toĂ¡n') {
            return self::STATUS_PAID;
        }

        if ($status === self::STATUS_UNPAID || $status === 'Chưa thanh toán' || $status === 'ChÆ°a thanh toĂ¡n') {
            return self::STATUS_UNPAID;
        }

        return null;
    }

    public static function paidStatusValues(): array
    {
        return [self::STATUS_PAID, 'Đã thanh toán', 'ÄĂ£ thanh toĂ¡n'];
    }

    public function statusCode(): string
    {
        return self::normalizeStatus($this->status) ?? self::STATUS_UNPAID;
    }

    public function isPaid(): bool
    {
        return $this->statusCode() === self::STATUS_PAID;
    }

    public function statusLabel(): string
    {
        return $this->isPaid() ? 'Đã thanh toán' : 'Chưa thanh toán';
    }
}
