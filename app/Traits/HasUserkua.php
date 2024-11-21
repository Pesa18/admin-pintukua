<?php

namespace App\Traits;

use App\Models\ProfileCompany;
use Filament\Facades\Filament;
use Spatie\Permission\Traits\HasRoles;

trait HasUserkua
{
    use HasRoles;
    public function isUserkua()
    {
        if (Filament::getTenant()->id == 3 && $this->hasRole('kua')) {
            return true;
        }
        return false;
    }
    public function isTeamkua()
    {
        if (Filament::getTenant()->id == 3) {
            return true;
        }
        return false;
    }

    public function kua()
    {
        return $this->belongsToMany(ProfileCompany::class, 'kua_user', 'user_id', 'id');
    }
}
