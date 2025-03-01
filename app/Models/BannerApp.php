<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannerApp extends Model
{
    use HasFactory;
    protected $casts = [
        'is_active' => 'boolean',
    ];
    protected $guarded = ['id'];
    protected $table = 'BannerApp';
}
