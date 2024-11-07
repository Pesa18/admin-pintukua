<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAccounts extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'user_accounts';
    protected $primaryKey = 'uuid';
}
