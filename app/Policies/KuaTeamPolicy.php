<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\KuaTeam;
use App\Models\User;

class KuaTeamPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any KuaTeam');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, KuaTeam $kuateam): bool
    {
        return $user->checkPermissionTo('view KuaTeam');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create KuaTeam');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, KuaTeam $kuateam): bool
    {
        return $user->checkPermissionTo('update KuaTeam');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, KuaTeam $kuateam): bool
    {
        return $user->checkPermissionTo('delete KuaTeam');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any KuaTeam');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, KuaTeam $kuateam): bool
    {
        return $user->checkPermissionTo('restore KuaTeam');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any KuaTeam');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, KuaTeam $kuateam): bool
    {
        return $user->checkPermissionTo('replicate KuaTeam');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder KuaTeam');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, KuaTeam $kuateam): bool
    {
        return $user->checkPermissionTo('force-delete KuaTeam');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any KuaTeam');
    }
}
