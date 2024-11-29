<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Employee extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function is_user(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'kua_user', 'id_employee', 'user_id', 'id', 'id');
    }
}
