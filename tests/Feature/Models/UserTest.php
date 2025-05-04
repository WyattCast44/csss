<?php

use App\Models\User;

test('user password is hashed', function () {
    $user = User::factory()->create([
        'password' => 'password',
    ]);

    expect($user->password)->not->toBe('password');

    $model = new User;
    $casts = $model->getCasts();

    expect($casts['password'])->toBe('hashed');
});
