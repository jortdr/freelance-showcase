<?php

declare(strict_types=1);

use App\Models\User;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'PasswordisSecret',
        'password_confirmation' => 'PasswordisSecret',
    ]);

    // Simulate email verification
    $user = User::where('email', 'test@example.com')->first();
    $user->markEmailAsVerified();

    $this->actingAs($user);
    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});
