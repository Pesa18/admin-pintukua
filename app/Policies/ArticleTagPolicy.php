<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\ArticleTag;
use App\Models\User;

class ArticleTagPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any ArticleTag');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ArticleTag $articletag): bool
    {
        return $user->checkPermissionTo('view ArticleTag');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create ArticleTag');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ArticleTag $articletag): bool
    {
        return $user->checkPermissionTo('update ArticleTag');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ArticleTag $articletag): bool
    {
        return $user->checkPermissionTo('delete ArticleTag');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any ArticleTag');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ArticleTag $articletag): bool
    {
        return $user->checkPermissionTo('restore ArticleTag');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any ArticleTag');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, ArticleTag $articletag): bool
    {
        return $user->checkPermissionTo('replicate ArticleTag');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder ArticleTag');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ArticleTag $articletag): bool
    {
        return $user->checkPermissionTo('force-delete ArticleTag');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any ArticleTag');
    }
}
