<?php

use App\Models\User;
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
