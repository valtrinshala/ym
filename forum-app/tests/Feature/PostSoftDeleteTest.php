<?php

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

test('posts with no comments for over a year are soft deleted', function () {
    $user = User::factory()->create();

    $post = Post::factory()->create([
        'user_id' => $user->id,
        'created_at' => Carbon::now()->subYears(2),
        'comments_count' => 0,
    ]);

    $this->assertDatabaseHas('posts', ['id' => $post->id]);

    Artisan::call('posts:soft-delete-old');

    $post->refresh();
    expect($post->trashed())->toBeTrue();
});

test('posts with only old comments for over a year are soft deleted', function () {
    $user = User::factory()->create();

    $post = Post::factory()->create([
        'user_id' => $user->id,
        'created_at' => Carbon::now()->subYears(2), // 2 years old
        'comments_count' => 1,
    ]);

    Comment::factory()->create([
        'post_id' => $post->id,
        'user_id' => $user->id,
        'created_at' => Carbon::now()->subYears(2),
    ]);

    $this->assertDatabaseHas('posts', ['id' => $post->id]);

    Artisan::call('posts:soft-delete-old');

    $post->refresh();
    expect($post->trashed())->toBeTrue();
});

test('posts with recent comments are NOT soft deleted', function () {
    $user = User::factory()->create();

    $post = Post::factory()->create([
        'user_id' => $user->id,
        'created_at' => Carbon::now()->subYears(2), // 2 years old
        'comments_count' => 1,
    ]);

    Comment::factory()->create([
        'post_id' => $post->id,
        'user_id' => $user->id,
        'created_at' => Carbon::now()->subMonths(6),
    ]);

    Artisan::call('posts:soft-delete-old');

    $post->refresh();
    expect($post->trashed())->toBeFalse();
});
