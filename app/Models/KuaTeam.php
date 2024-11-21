<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KuaTeam extends Model
{
    use HasFactory;
    protected $table = 'kua_user';
    protected $guarded = ['id'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'kua_user')
            ->withTimestamps();
    }
}
