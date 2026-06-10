<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

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
}