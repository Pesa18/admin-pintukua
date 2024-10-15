<?php

namespace App\Models;

use App\Models\Team;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as ModelsRole;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\PermissionRegistrar;

class Role extends ModelsRole
{
    use HasFactory;

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
    // public function users(): BelongsToMany
    // {
    //     return $this->morphedByMany(
    //         getModelForGuard($this->attributes['guard_name'] ?? config('auth.defaults.guard')),
    //         'model',
    //         config('permission.table_names.model_has_roles'),
    //         app(PermissionRegistrar::class)->pivotRole,
    //         config('permission.column_names.model_morph_key')
    //     )
    //         ->withPivot(config('permission.column_names.team_foreign_key') . ' as model_team_id'); // Memberikan alias pada kolom team_id
    // }
}
