<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;


    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }


    protected $fillable = [
        'doctor_id',
        'service',
        'exam_date',
        'cost',
        'status',
        'diagnosis',
        'prescription',
        'notes',
        'follow_up_date',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}