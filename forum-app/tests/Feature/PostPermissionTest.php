<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('unverified users cannot create a post', function () {

    $user = User::factory()->create(['email_verified_at' => null]);

    $payload = [
        'title' => 'Unverified Post',
        'content' => 'This post should not be created.',
    ];

    $response = $this->actingAs($user)->postJson('/api/posts', $payload);

    $response->assertStatus(403);
});
