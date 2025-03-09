<?php

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it lists all comments for a post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);

    Comment::factory()->count(3)->create([
        'post_id' => $post->id,
        'user_id' => $user->id,
    ]);

    $response = $this->actingAs($user)->getJson("/api/posts/{$post->id}/comments");

    $response->assertStatus(200)
        ->assertJsonCount(3, 'data');
});

test('it creates a new comment on a post', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);
    $post = Post::factory()->create(['user_id' => $user->id]);

    $payload = ['body' => 'This is a test comment.'];

    $response = $this->actingAs($user)->postJson("/api/posts/{$post->id}/comments", $payload);

    $response->assertStatus(201)
        ->assertJsonFragment(['body' => 'This is a test comment.']);

    $this->assertDatabaseHas('comments', [
        'post_id' => $post->id,
        'body' => 'This is a test comment.',
    ]);
});

test('it updates a comment', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);
    $post = Post::factory()->create(['user_id' => $user->id]);
    $comment = Comment::factory()->create([
        'post_id' => $post->id,
        'user_id' => $user->id,
        'body' => 'Original comment text',
    ]);

    $payload = ['body' => 'Updated comment text'];

    $response = $this->actingAs($user)->putJson("/api/posts/{$post->id}/comments/{$comment->id}", $payload);

    $response->assertStatus(200)
        ->assertJsonFragment(['body' => 'Updated comment text']);

    $this->assertDatabaseHas('comments', [
        'id' => $comment->id,
        'body' => 'Updated comment text',
    ]);
});

test('it deletes a comment', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);
    $post = Post::factory()->create(['user_id' => $user->id]);
    $comment = Comment::factory()->create([
        'post_id' => $post->id,
        'user_id' => $user->id,
        'body' => 'Comment to be deleted',
    ]);

    $response = $this->actingAs($user)->deleteJson("/api/posts/{$post->id}/comments/{$comment->id}");

    $response->assertStatus(204);
    $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
});

test('editing a comment updates the edited_at timestamp', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);

    $post = Post::factory()->create(['user_id' => $user->id]);

    $comment = Comment::factory()->create([
        'post_id' => $post->id,
        'user_id' => $user->id,
        'body'    => 'Original comment text',
        'edited_at' => null,
    ]);

    sleep(1);

    $payload = ['body' => 'Updated comment text'];

    $response = $this->actingAs($user, 'web')->putJson("/api/posts/{$post->id}/comments/{$comment->id}", $payload);

    $response->assertStatus(200)
        ->assertJsonFragment(['body' => 'Updated comment text']);

    $comment->refresh();

    expect($comment->edited_at)->not->toBeNull()
        ->and($comment->edited_at)->not->toBe($comment->created_at);
});

