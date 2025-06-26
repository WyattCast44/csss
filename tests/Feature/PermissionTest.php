<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Run the permission seeding migration
    $this->artisan('migrate');
});

test('user can be assigned role', function () {
    $user = User::factory()->create();
    $role = Role::create(['name' => 'Test Role']);

    $user->assignRole($role);

    expect($user->hasRole('Test Role'))->toBeTrue();
});

test('user can be given permission', function () {
    $user = User::factory()->create();
    $permission = Permission::create(['name' => 'test:permission']);

    $user->givePermissionTo($permission);

    expect($user->hasPermissionTo('test:permission'))->toBeTrue();
});

test('user gets member role on creation', function () {
    $user = User::factory()->create();

    expect($user->hasRole('Member'))->toBeTrue();
});

test('super admin role exists', function () {
    expect(Role::where('name', 'Super Admin')->exists())->toBeTrue();
});

test('permissions exist', function () {
    expect(Permission::where('name', 'user:view')->exists())->toBeTrue();
    expect(Permission::where('name', 'user:create')->exists())->toBeTrue();
    expect(Permission::where('name', 'user:update')->exists())->toBeTrue();
    expect(Permission::where('name', 'user:delete')->exists())->toBeTrue();
});
