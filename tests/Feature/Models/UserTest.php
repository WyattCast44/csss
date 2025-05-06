<?php

use App\Models\User;
use Filament\Models\Contracts\HasTenants;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;

test('user password is hashed', function () {
    $user = User::factory()->create([
        'password' => 'password',
    ]);

    expect($user->password)->not->toBe('password');

    $model = new User;
    $casts = $model->getCasts();

    expect($casts['password'])->toBe('hashed');
});

test('the user model uses the activity logging traits', function () {
    expect(User::class)->toUseTraits([LogsActivity::class, CausesActivity::class]);
});

test('the user model implements the HasTenants contract', function () {
    expect(User::class)->toImplement(HasTenants::class);
});
