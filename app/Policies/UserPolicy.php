<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->checkUserPermission($user, 'user:view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return $this->checkUserPermission($user, 'user:view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->checkUserPermission($user, 'user:create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        return $this->checkUserPermission($user, 'user:update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return $this->checkUserPermission($user, 'user:delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return $this->checkUserPermission($user, 'user:update');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $this->checkUserPermission($user, 'user:delete');
    }

    /**
     * Determine whether the user can export models.
     */
    public function export(User $user): bool
    {
        return $this->checkUserPermission($user, 'user:export');
    }

    /**
     * Determine whether the user can import models.
     */
    public function import(User $user): bool
    {
        return $this->checkUserPermission($user, 'user:import');
    }

    /**
     * Determine whether the user can assign models.
     */
    public function assign(User $user): bool
    {
        return $this->checkUserPermission($user, 'user:assign');
    }

    /**
     * Determine whether the user can print models.
     */
    public function print(User $user): bool
    {
        return $this->checkUserPermission($user, 'user:print');
    }

    /**
     * Determine whether the user can manage user settings.
     */
    public function manage(User $user): bool
    {
        return $this->checkUserPermission($user, 'user:manage');
    }
}
