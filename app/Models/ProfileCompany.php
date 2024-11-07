<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfileCompany extends Model
{
    use HasFactory;

    public function kepala(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
