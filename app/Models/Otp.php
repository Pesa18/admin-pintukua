<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $guarded = [];



    protected $casts = [
        'expires_at' => 'datetime',
    ];


    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Cek apakah OTP valid dan belum digunakan.
     */
    public function isValid(): bool
    {
        return !$this->is_used && !$this->isExpired();
    }

    public function user()
    {
        return $this->belongsTo(UserAccounts::class, 'user_id', 'uuid');
    }
}
