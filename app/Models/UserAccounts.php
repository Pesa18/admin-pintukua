<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class UserAccounts extends Model
{
    use Notifiable;
    use HasFactory, HasUuids, HasApiTokens;
    protected $table = 'user_accounts';
    protected $primaryKey = 'uuid';
    protected $guarded = ['id'];

    public function otps()
    {
        return $this->hasMany(Otp::class, 'user_id', 'uuid');
    }
}
