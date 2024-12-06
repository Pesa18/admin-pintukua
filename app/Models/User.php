<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Althinect\FilamentSpatieRolesPermissions\Concerns\HasSuperAdmin;
use App\Models\Role as ModelsRole;
use Filament\Panel;
use App\Models\Team;
use App\Traits\HasUserkua;
use Filament\Facades\Filament;
use Illuminate\Support\Collection;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\HasTenants;
use Illuminate\Database\Eloquent\Builder;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Relations\HasMany;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class User extends Authenticatable implements HasTenants, HasAvatar
{
    use HasFactory, Notifiable, HasRoles, HasSuperAdmin, HasUserkua;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function getFilamentAvatarUrl(): ?string
    {
        if ($this->is_pegawai()->first()?->avatar) {
            return asset($this->is_pegawai()->first()?->avatar);
        };
        return null;
    }
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class);
    }

    public function getTenants(Panel $panel): Collection
    {
        return $this->teams;
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->teams()->whereKey($tenant)->exists();
    }
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
    protected static function booted(): void
    {
        static::addGlobalScope('team', function (Builder $query) {
            if (auth()->hasUser()) {
                $query->where('team_id', getPermissionsTeamId());
                // or with a `team` relationship defined:
                $query->whereBelongsTo(auth()->user()->teams);
            }
            // dd(auth()->check());
        });
    }
    public function kepalaEmployees()
    {
        return $this->hasMany(Employee::class, 'id_kua', 'id_kua')
            ->where('is_kepala', true);
    }
}
