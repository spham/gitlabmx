<?php

use App\Events\UserLoggedInEvent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

it('shows the login form', function () {
    // Act & Assert
    get(route('login'))
        ->assertSeeText([
            'LabMx Login',
            'Enter your email address',
            'Enter your password',
        ]);
});

it('shows error if email and password is not added', function () {
    // Act & Assert
    post(route('login.handle'), [])
        ->assertSessionHasErrors(['email', 'password']);
});

it('shows error if password is wrong', function () {
    // Arrange
    $user = User::factory()->create(['password' => 'safepass']);

    // Act & Assert
    $credentials = ['email' => $user->email, 'password' => 'wrongpass'];
    post(route('login.handle'), $credentials)
        ->assertSessionHasErrors(['password']);
});

it('redirects to home if login is successful', function () {
    // Arrange
    $safeWord = 'safepass';
    $user = User::factory()->create(['password' => $safeWord]);

    // Act & Assert
    $credentials = ['email' => $user->email, 'password' => $safeWord];
    post(route('login.handle'), $credentials)
        ->assertRedirectToRoute('dashboard')
        ->assertSessionHas('success');
});

it('remembers login if checked', function () {
    // Arrange
    $safeWord = 'safepass';
    $user = User::factory()->create(['password' => $safeWord]);

    // Act & Assert
    $credentials = ['email' => $user->email, 'password' => $safeWord];
    post(route('login.handle'), $credentials)
        ->assertRedirect(route('dashboard')); // TODO: Amitav to check how we can assert remember me cookie
});

it('raises an event when user logs in', function () {
    // Arrange
    Event::fake();
    $safeWord = 'safepass';
    $user = User::factory()->create(['password' => $safeWord]);

    // Act
    $credentials = ['email' => $user->email, 'password' => $safeWord];
    post(route('login.handle'), $credentials);

    // Assert
    Event::assertDispatched(UserLoggedInEvent::class);
});
