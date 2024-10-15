<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\UserAccounts;
use App\Models\User;

class UserAccountsPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any UserAccounts');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, UserAccounts $useraccounts): bool
    {
        return $user->checkPermissionTo('view UserAccounts');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create UserAccounts');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UserAccounts $useraccounts): bool
    {
        return $user->checkPermissionTo('update UserAccounts');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UserAccounts $useraccounts): bool
    {
        return $user->checkPermissionTo('delete UserAccounts');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any UserAccounts');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, UserAccounts $useraccounts): bool
    {
        return $user->checkPermissionTo('restore UserAccounts');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any UserAccounts');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, UserAccounts $useraccounts): bool
    {
        return $user->checkPermissionTo('replicate UserAccounts');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder UserAccounts');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, UserAccounts $useraccounts): bool
    {
        return $user->checkPermissionTo('force-delete UserAccounts');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any UserAccounts');
    }
}
