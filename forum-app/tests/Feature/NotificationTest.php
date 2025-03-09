<?php

use App\Models\Post;
use App\Models\User;
use App\Notifications\NewCommentNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('post author receives an email notification when a new comment is added', function () {
    Notification::fake();

    $postAuthor = User::factory()->create(['email_verified_at' => now()]);
    $commenter = User::factory()->create(['email_verified_at' => now()]);

    $post = Post::factory()->create(['user_id' => $postAuthor->id]);

    $payload = ['body' => 'This is a test comment.'];

    $this->actingAs($commenter)->postJson("/api/posts/{$post->id}/comments", $payload);

    Notification::assertSentTo($postAuthor, NewCommentNotification::class);
});


