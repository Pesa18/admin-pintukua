<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProfileCompany extends Model
{
    use HasFactory;


    protected $guarded = ['id'];


    public function kepala(): HasMany
    {
        return $this->hasMany(Employee::class, 'id_kua', 'id_kua')
            ->where('is_kepala', true);
    }
}
