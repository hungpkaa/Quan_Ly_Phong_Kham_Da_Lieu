<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'specialty',
        'phone',
        'bio',
        'image',
        'working_hours'
    ];
    protected $casts = [
        'working_hours' => 'array', // Chuyển đổi dữ liệu thành mảng
    ];
    // Nếu cần hash password, bạn có thể thêm mutator
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::needsRehash($value) ? Hash::make($value) : $value;
    }
}
