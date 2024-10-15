<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Categorie;
use App\Models\User;

class CategoriePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Categorie');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Categorie $categorie): bool
    {
        return $user->checkPermissionTo('view Categorie');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Categorie');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Categorie $categorie): bool
    {
        return $user->checkPermissionTo('update Categorie');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Categorie $categorie): bool
    {
        return $user->checkPermissionTo('delete Categorie');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Categorie');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Categorie $categorie): bool
    {
        return $user->checkPermissionTo('restore Categorie');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Categorie');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Categorie $categorie): bool
    {
        return $user->checkPermissionTo('replicate Categorie');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Categorie');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Categorie $categorie): bool
    {
        return $user->checkPermissionTo('force-delete Categorie');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Categorie');
    }
}
