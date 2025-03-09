<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Carbon;

class PostPolicy
{
    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, Post $post): bool
    {
        return false;
    }


    public function create(User $user): bool
    {
        return $user->hasVerifiedEmail();
    }

    public function update(User $user, Post $post): bool
    {
        return $post->user_id === $user->id && $post->comments_count === 0;
    }


    public function delete(User $user, Post $post): bool
    {
        $oneYearAgo = Carbon::now()->subYear();

        $isOwner = $post->user_id === $user->id;

        if (!$isOwner) {
            return false;
        }

        $hasNoComments = $post->comments_count === 0;

        if ($hasNoComments) {
            return true;
        }

        $hasRecentComments = $post->comments()->where('created_at', '>=', $oneYearAgo)->exists();

        return !$hasRecentComments && $post->created_at->lt($oneYearAgo);
    }

    public function restore(User $user, Post $post): bool
    {
        return false;
    }

    public function forceDelete(User $user, Post $post): bool
    {
        return false;
    }
}
