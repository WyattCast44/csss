<?php

namespace App\Guards;

use App\Models\Organization;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;

class OrganizationPermissionGuard
{
    public function hasPermission(string $permission): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        $currentOrganization = Filament::getTenant();

        // If no current organization (e.g., in testing), just check the permission
        if (! $currentOrganization) {
            return $user->hasPermissionTo($permission);
        }

        // Check if user belongs to the current organization
        if (! $user->organizations()->where('organization_id', $currentOrganization->id)->exists()) {
            return false;
        }

        // Check if user has the permission
        return $user->hasPermissionTo($permission);
    }

    public function hasRole(string $role): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        $currentOrganization = Filament::getTenant();

        // If no current organization (e.g., in testing), just check the role
        if (! $currentOrganization) {
            return $user->hasRole($role);
        }

        // Check if user belongs to the current organization
        if (! $user->organizations()->where('organization_id', $currentOrganization->id)->exists()) {
            return false;
        }

        // Check if user has the role
        return $user->hasRole($role);
    }
}
