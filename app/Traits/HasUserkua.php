<?php

namespace App\Traits;

use App\Models\Employee;
use App\Models\ProfileCompany;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Traits\HasRoles;

trait HasUserkua
{
    use HasRoles;
    public function isUserkua()
    {
        if (Filament::getTenant()?->id == 3 && $this->hasRole('Admin-KUA')) {
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

    public function kua(): BelongsToMany
    {
        return $this->belongsToMany(ProfileCompany::class, 'kua_user', 'user_id', 'id_kua', 'id', 'id_kua');
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('Admin');
    }
    public function isEditor(): bool
    {
        return $this->hasRole('Editor');
    }
    public function is_pegawai(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'kua_user',  'user_id', 'id_employee', 'id', 'id');
    }
}
