<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('unverified users cannot create a comment', function () {
    $user = User::factory()->create(['email_verified_at' => null]);
    $post = Post::factory()->create(['user_id' => $user->id]);

    $payload = ['body' => 'This comment should not be created.'];

    $response = $this->actingAs($user)->postJson("/api/posts/{$post->id}/comments", $payload);

    $response->assertStatus(403); // Expect a forbidden error
});
