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
        if (Filament::getTenant()?->id == config('kua.tenant_kua.id') && $this->hasRole(config('kua.admin_kua_role'))) {
            return true;
        }
        return false;
    }
    public function isTeamkua()
    {
        if (Filament::getTenant()->id == config('kua.tenant_kua.id')) {
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
        return $this->hasRole(config('kua.admin_role'));
    }
    public function isEditor(): bool
    {
        return $this->hasRole(config('kua.editor_role'));
    }
    public function is_pegawai(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'kua_user',  'user_id', 'id_employee', 'id', 'id');
    }
}
