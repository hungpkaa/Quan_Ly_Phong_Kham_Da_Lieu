<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientProgress extends Model
{
    use HasFactory;

    protected $table = 'patient_progresses';

    protected $fillable = [
        'user_id',
        'doctor_id',
        'image_path',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
