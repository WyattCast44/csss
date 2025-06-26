<?php

namespace App\Support\Traits;

use App\Guards\OrganizationPermissionGuard;
use Filament\Facades\Filament;

trait HasOrganizationPermissions
{
    protected function checkPermission(string $permission): bool
    {
        $guard = new OrganizationPermissionGuard;

        return $guard->hasPermission($permission);
    }

    protected function canAny(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->checkPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    protected function canAll(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (! $this->checkPermission($permission)) {
                return false;
            }
        }

        return true;
    }

    protected function hasRole(string $role): bool
    {
        $guard = new OrganizationPermissionGuard;

        return $guard->hasRole($role);
    }

    protected function getCurrentOrganization()
    {
        return Filament::getTenant();
    }
}
