<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\ProfileCompany;
use App\Models\User;

class ProfileCompanyPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any ProfileCompany');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ProfileCompany $profilecompany): bool
    {
        return $user->checkPermissionTo('view ProfileCompany');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create ProfileCompany');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProfileCompany $profilecompany): bool
    {
        return $user->checkPermissionTo('update ProfileCompany');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProfileCompany $profilecompany): bool
    {
        return $user->checkPermissionTo('delete ProfileCompany');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any ProfileCompany');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ProfileCompany $profilecompany): bool
    {
        return $user->checkPermissionTo('restore ProfileCompany');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any ProfileCompany');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, ProfileCompany $profilecompany): bool
    {
        return $user->checkPermissionTo('replicate ProfileCompany');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder ProfileCompany');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ProfileCompany $profilecompany): bool
    {
        return $user->checkPermissionTo('force-delete ProfileCompany');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any ProfileCompany');
    }
}
