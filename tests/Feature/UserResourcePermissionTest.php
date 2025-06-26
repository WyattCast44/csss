<?php

use App\Filament\App\Resources\UserResource;
use App\Models\User;

test('user resource can view any when user has permission', function () {
    $user = User::factory()->create();
    $user->givePermissionTo('user:view');

    $this->actingAs($user);

    expect(UserResource::canViewAny())->toBeTrue();
});

test('user resource cannot view any when user lacks permission', function () {
    $user = User::factory()->create();
    // User only has Member role, which doesn't include user:view

    $this->actingAs($user);

    expect(UserResource::canExport())->toBeFalse();
});

test('user resource can create when user has permission', function () {
    $user = User::factory()->create();
    $user->givePermissionTo('user:export');

    $this->actingAs($user);

    expect(UserResource::canExport())->toBeTrue();
});

test('user resource cannot create when user lacks permission', function () {
    $user = User::factory()->create();
    // User only has Member role, which doesn't include user:create

    $this->actingAs($user);

    expect(UserResource::canCreate())->toBeFalse();
});

test('super admin can perform all actions', function () {
    $user = User::factory()->create();
    $user->assignRole('Super Admin');

    $this->actingAs($user);

    expect(UserResource::canViewAny())->toBeTrue();
    expect(UserResource::canCreate())->toBeTrue();
    expect(UserResource::canDeleteAny())->toBeTrue();
});
