<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('resets password for existing user', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('oldpassword123'),
    ]);

    $response = $this->post(route('password.update'), [
        'email' => 'test@example.com',
        'new_password' => 'newpassword123',
        'new_password_confirmation' => 'newpassword123',
    ]);

    $response->assertRedirect(route('login'));
    $response->assertSessionHas('success');

    $user->refresh();
    expect(Hash::check('newpassword123', $user->password))->toBeTrue();
});

it('returns error for unknown email', function () {
    $response = $this->post(route('password.update'), [
        'email' => 'unknown@example.com',
        'new_password' => 'newpassword123',
        'new_password_confirmation' => 'newpassword123',
    ]);

    $response->assertSessionHasErrors(['email']);
});
