<?php

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it lists all posts', function () {
    $user = User::factory()->create();
    Post::factory()->count(3)->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->getJson('/api/posts');

    $response->assertStatus(200)
        ->assertJsonCount(3, 'data');
});

test('it creates a new post', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);

    $payload = [
        'title' => 'My First Post',
        'content' => 'This is a test post.',
    ];

    $response = $this->actingAs($user)->postJson('/api/posts', $payload);

    $response->assertStatus(201)
        ->assertJsonFragment(['title' => 'My First Post']);

    $this->assertDatabaseHas('posts', ['title' => 'My First Post']);
});

test('it updates a post', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);

    $post = Post::factory()->create([
        'user_id' => $user->id,
        'title' => 'Original Title',
        'content' => 'Original Content',
    ]);

    $payload = [
        'title' => 'Updated Title',
        'content' => 'Updated Content',
    ];

    $response = $this->actingAs($user)->putJson("/api/posts/{$post->id}", $payload);

    $response->assertStatus(200)
        ->assertJsonFragment(['title' => 'Updated Title']);

    $this->assertDatabaseHas('posts', ['id' => $post->id, 'title' => 'Updated Title']);
});

test('it deletes a post', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);

    $post = Post::factory()->create([
        'user_id' => $user->id,
        'title' => 'Post to Delete',
        'content' => 'This post will be deleted.',
    ]);

    $response = $this->actingAs($user)->deleteJson("/api/posts/{$post->id}");

    $response->assertStatus(204);

    $this->assertSoftDeleted('posts', ['id' => $post->id]);
});

test('a user cannot edit a post if it has comments', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);
    Comment::factory()->create(['post_id' => $post->id, 'user_id' => $user->id]);

    $payload = ['title' => 'Updated Title', 'content' => 'Updated Content'];

    // Try to update the post
    $response = $this->actingAs($user, 'web')->putJson("/api/posts/{$post->id}", $payload);

    // Expect forbidden response (403)
    $response->assertStatus(403);
});

test('a user cannot delete a post if it has comments', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);
    Comment::factory()->create(['post_id' => $post->id, 'user_id' => $user->id]);

    // Try to delete the post
    $response = $this->actingAs($user, 'web')->deleteJson("/api/posts/{$post->id}");

    // Expect forbidden response (403)
    $response->assertStatus(403);
});

test('posts are returned in descending order by creation date', function () {
    $user = User::factory()->create();

    // Create posts with different creation dates
    $oldestPost = Post::factory()->create([
        'user_id' => $user->id,
        'created_at' => now()->subDays(3),
    ]);
    $middlePost = Post::factory()->create([
        'user_id' => $user->id,
        'created_at' => now()->subDays(2),
    ]);
    $newestPost = Post::factory()->create([
        'user_id' => $user->id,
        'created_at' => now()->subDays(1),
    ]);

    // Call API to fetch posts
    $response = $this->actingAs($user, 'web')->getJson('/api/posts');

    // Extract post IDs from response
    $postIds = collect($response->json('data'))->pluck('id')->toArray();

    // Assert that posts are ordered correctly (newest first)
    expect($postIds)->toBe([$newestPost->id, $middlePost->id, $oldestPost->id]);
});





