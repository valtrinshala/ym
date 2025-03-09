<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;

uses(RefreshDatabase::class);

test('a user can request a verification email', function () {
    Notification::fake();

    $user = User::factory()->create(['email_verified_at' => null]);

    $response = $this->actingAs($user)->postJson('/api/email/verify/send');

    $response->assertStatus(200);
    Notification::assertSentTo($user, VerifyEmail::class);
});

test('a user can verify their email', function () {
    $user = User::factory()->create(['email_verified_at' => null]);

    $verificationUrl = URL::signedRoute('verification.verify', [
        'id' => $user->id,
        'hash' => sha1($user->getEmailForVerification()),
    ]);

    $response = $this->getJson($verificationUrl);

    $response->assertRedirect();
    $this->assertNotNull($user->fresh()->email_verified_at);
});

test('email verification fails with an invalid hash', function () {
    $user = User::factory()->create(['email_verified_at' => null]);

    $invalidUrl = URL::signedRoute('verification.verify', [
        'id' => $user->id,
        'hash' => 'invalid-hash', // Incorrect hash
    ]);

    $response = $this->getJson($invalidUrl);

    $response->assertStatus(403);
    $this->assertNull($user->fresh()->email_verified_at);
});

