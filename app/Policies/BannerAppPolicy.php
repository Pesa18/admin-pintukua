<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\BannerApp;
use App\Models\User;

class BannerAppPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any BannerApp');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, BannerApp $bannerapp): bool
    {
        return $user->checkPermissionTo('view BannerApp');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create BannerApp');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BannerApp $bannerapp): bool
    {
        return $user->checkPermissionTo('update BannerApp');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BannerApp $bannerapp): bool
    {
        return $user->checkPermissionTo('delete BannerApp');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any BannerApp');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, BannerApp $bannerapp): bool
    {
        return $user->checkPermissionTo('restore BannerApp');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any BannerApp');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, BannerApp $bannerapp): bool
    {
        return $user->checkPermissionTo('replicate BannerApp');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder BannerApp');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, BannerApp $bannerapp): bool
    {
        return $user->checkPermissionTo('force-delete BannerApp');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any BannerApp');
    }
}
