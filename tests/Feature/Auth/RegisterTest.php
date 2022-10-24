<?php

use App\Models\User;
use function Pest\Laravel\postJson;
use function PHPUnit\Framework\assertNotEmpty;

it('can register a user', function () {
    $user = User::factory()->make();

    postJson('/register', [
        'name' => $user->name,
        'email' => $user->email,
        'password' => 'Test1234',
        'password_confirmation' => 'Test1234',
    ]);

    assertNotEmpty(User::firstWhere('email', $user->email));
});
