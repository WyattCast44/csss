# Authorization

This document describes how the roles and permissions system works in the CSSS application.

## Overview

The application uses the [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission) package to implement a role-based access control (RBAC) system. The system is designed to work with multi-tenant organizations, where users can belong to multiple organizations and have different permissions within each context.

## Core Components

### 1. Permission Package

-   **Package**: `spatie/laravel-permission`
-   **Configuration**: `config/permission.php`
-   **Database Tables**:
    -   `permissions` - Stores individual permissions
    -   `roles` - Stores roles
    -   `model_has_permissions` - Links permissions to users
    -   `model_has_roles` - Links roles to users
    -   `role_has_permissions` - Links permissions to roles

### 2. User Model Integration

The `User` model uses the `HasRoles` trait from Spatie:

```php
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;

    // Users automatically get 'Member' role on creation
    protected static function booted(): void
    {
        static::created(function (User $user) {
            $user->createPersonalOrganization();
            $user->assignRole('Member');
        });
    }
}
```

### 3. Organization-Scoped Permissions

The system includes a custom `OrganizationPermissionGuard` that checks if users belong to the current organization before granting permissions.

## Current Roles and Permissions

### Roles

1. **Super Admin** - Full system access
2. **Organization Admin** - Organization-level administrative access
3. **Manager** - Management-level access within organization
4. **Member** - Basic access (default role for new users)

### Permission Structure

Permissions follow the pattern: `{resource}:{action}`

**Available Actions:**

-   `view` - View records
-   `create` - Create new records
-   `update` - Edit existing records
-   `delete` - Delete records
-   `export` - Export data
-   `import` - Import data
-   `assign` - Assign records to others
-   `approve` - Approve records
-   `print` - Print records
-   `manage` - Manage settings

**Current Resources:**

-   `user` - User management
-   `inbound_user` - Inbound user management
-   `outbound_user` - Outbound user management
-   `attached_user` - Attached user management
-   `building` - Building management
-   `room` - Room management
-   `safe` - Safe management
-   `entry_access_list` - Entry access list management
-   `purchase_request` - Purchase request management
-   `inprocessing_action` - Inprocessing action management
-   `organization` - Organization management

## How Permissions Work

### 1. Permission Checking in Filament Resources

Resources use the `HasOrganizationPermissions` trait and implement permission checks:

```php
use App\Support\Traits\HasOrganizationPermissions;

class UserResource extends Resource
{
    use HasOrganizationPermissions;

    public static function canViewAny(): bool
    {
        return (new static)->checkPermission('user:view');
    }

    public static function canCreate(): bool
    {
        return (new static)->checkPermission('user:create');
    }
}
```

### 2. Permission Checking in Policies

Policies extend `BasePolicy` which provides permission checking methods:

```php
class UserPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $this->checkUserPermission($user, 'user:view');
    }
}
```

### 3. Organization-Scoped Checking

The `OrganizationPermissionGuard` ensures users can only access resources within organizations they belong to:

```php
class OrganizationPermissionGuard
{
    public function hasPermission(string $permission): bool
    {
        $user = Auth::user();
        $currentOrganization = Filament::getTenant();

        // Check if user belongs to current organization
        if (!$user->organizations()->where('organization_id', $currentOrganization->id)->exists()) {
            return false;
        }

        // Check if user has the permission
        return $user->hasPermissionTo($permission);
    }
}
```

## Adding New Roles

### 1. Create a Migration

Create a new migration to add the role:

```bash
php artisan make:migration add_new_role
```

### 2. Add Role in Migration

```php
use Spatie\Permission\Models\Role;

public function up(): void
{
    $newRole = Role::create(['name' => 'New Role Name']);

    // Assign permissions to the role
    $newRole->givePermissionTo([
        'user:view',
        'user:create',
        // ... other permissions
    ]);
}
```

### 3. Update Existing Migration (Recommended)

Alternatively, add the role to the existing permission seeding migration:

```php
// In database/migrations/0001_01_01_000001_seed_permissions_and_roles.php
$newRole = Role::create(['name' => 'New Role Name']);
$newRole->givePermissionTo([
    // permissions here
]);
```

## Adding New Permissions

### 1. Add Permission to Migration

Add the new permission to the permissions array in the seeding migration:

```php
// In database/migrations/0001_01_01_000001_seed_permissions_and_roles.php
$permissions = [
    // ... existing permissions
    'new_resource:view',
    'new_resource:create',
    'new_resource:update',
    'new_resource:delete',
    // ... other actions
];
```

### 2. Assign to Roles

Assign the new permissions to appropriate roles:

```php
// Super Admin gets all permissions automatically
$superAdmin->givePermissionTo($permissions);

// Assign to other roles as needed
$organizationAdmin->givePermissionTo([
    // ... existing permissions
    'new_resource:view',
    'new_resource:create',
    'new_resource:update',
]);
```

### 3. Update Filament Resource

Add permission checks to your new Filament resource:

```php
use App\Support\Traits\HasOrganizationPermissions;

class NewResourceResource extends Resource
{
    use HasOrganizationPermissions;

    public static function canViewAny(): bool
    {
        return (new static)->checkPermission('new_resource:view');
    }

    public static function canCreate(): bool
    {
        return (new static)->checkPermission('new_resource:create');
    }

    public static function canEdit(Model $record): bool
    {
        return (new static)->checkPermission('new_resource:update');
    }

    public static function canDelete(Model $record): bool
    {
        return (new static)->checkPermission('new_resource:delete');
    }
}
```

### 4. Create Policy (Optional)

Create a policy for additional authorization logic:

```bash
php artisan make:policy NewResourcePolicy
```

```php
class NewResourcePolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $this->checkUserPermission($user, 'new_resource:view');
    }

    public function create(User $user): bool
    {
        return $this->checkUserPermission($user, 'new_resource:create');
    }
}
```

## Permission Assignment

### Assigning Roles to Users

```php
$user = User::find(1);
$user->assignRole('Manager');
```

### Assigning Direct Permissions

```php
$user = User::find(1);
$user->givePermissionTo('user:export');
```

### Checking Permissions

```php
// Check if user has permission
if ($user->hasPermissionTo('user:view')) {
    // User can view users
}

// Check if user has role
if ($user->hasRole('Manager')) {
    // User is a manager
}
```

## Testing Permissions

The application includes tests for the permission system in `tests/Feature/PermissionTest.php`. You can run these tests to verify permission functionality:

```bash
php artisan test --filter=PermissionTest
```

## Best Practices

1. **Use Resource-Based Permissions**: Follow the `{resource}:{action}` pattern
2. **Implement in Resources**: Always add permission checks to Filament resources
3. **Use Policies for Complex Logic**: Create policies for additional authorization rules
4. **Test Permissions**: Write tests to verify permission behavior
5. **Document New Permissions**: Update this documentation when adding new permissions
6. **Consider Organization Context**: Remember that permissions are scoped to organizations

## Migration and Deployment

When deploying changes to roles and permissions:

1. Run migrations: `php artisan migrate`
2. Clear permission cache: `php artisan permission:cache-reset`
3. Test permission functionality in staging environment
4. Verify existing users have appropriate roles

## Troubleshooting

### Permission Cache Issues

If permissions aren't working as expected, clear the permission cache:

```bash
php artisan permission:cache-reset
```

### Organization Context Issues

Ensure users are properly associated with organizations:

```php
$user->organizations()->attach($organizationId);
```

### Testing Issues

When testing permissions, you may need to set up the organization context:

```php
// In tests
$organization = Organization::factory()->create();
$user->organizations()->attach($organization);
Filament::setTenant($organization);
```
