<?php

namespace App\Policies;

use App\Models\User;
use App\Support\Traits\HasOrganizationPermissions;
use Illuminate\Auth\Access\HandlesAuthorization;

abstract class BasePolicy
{
    use HandlesAuthorization, HasOrganizationPermissions;

    protected function checkUserPermission(User $user, string $permission): bool
    {
        return $this->checkPermission($permission);
    }

    protected function checkAnyPermission(User $user, array $permissions): bool
    {
        return $this->canAny($permissions);
    }

    protected function checkAllPermissions(User $user, array $permissions): bool
    {
        return $this->canAll($permissions);
    }

    protected function checkRole(User $user, string $role): bool
    {
        return $this->hasRole($role);
    }

    protected function getResourceName(): string
    {
        // Extract resource name from class name (e.g., UserPolicy -> user)
        $className = class_basename($this);
        $resourceName = str_replace('Policy', '', $className);

        return strtolower($resourceName);
    }

    protected function getPermission(string $action): string
    {
        return $this->getResourceName().':'.$action;
    }
}
