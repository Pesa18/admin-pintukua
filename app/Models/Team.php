<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function articleTeams(): HasMany
    {
        return $this->hasMany(Article::class);
    }
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
    public function userTeams(): HasMany
    {
        return $this->hasMany(User::class);
    }
    public function roles(): HasMany
    {
        return $this->hasMany(Role::class);
    }
}
