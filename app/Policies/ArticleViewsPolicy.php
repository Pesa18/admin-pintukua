<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\ArticleViews;
use App\Models\User;

class ArticleViewsPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any ArticleViews');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ArticleViews $articleviews): bool
    {
        return $user->checkPermissionTo('view ArticleViews');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create ArticleViews');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ArticleViews $articleviews): bool
    {
        return $user->checkPermissionTo('update ArticleViews');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ArticleViews $articleviews): bool
    {
        return $user->checkPermissionTo('delete ArticleViews');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any ArticleViews');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ArticleViews $articleviews): bool
    {
        return $user->checkPermissionTo('restore ArticleViews');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any ArticleViews');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, ArticleViews $articleviews): bool
    {
        return $user->checkPermissionTo('replicate ArticleViews');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder ArticleViews');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ArticleViews $articleviews): bool
    {
        return $user->checkPermissionTo('force-delete ArticleViews');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any ArticleViews');
    }
}
