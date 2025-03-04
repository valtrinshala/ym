<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SoftDeleteOldPosts extends Command
{
    protected $signature = 'posts:soft-delete-old';
    protected $description = 'Soft delete posts that have not received a comment for one year';

    public function handle()
    {
        $oneYearAgo = Carbon::now()->subYear();

        Post::query()
            ->whereNotExists(function ($query) use ($oneYearAgo) {
                $query->select(DB::raw(1))
                    ->from('comments')
                    ->whereRaw('comments.post_id = posts.id')
                    ->where('comments.created_at', '>=', $oneYearAgo);
            })
            ->whereDate('created_at', '<', $oneYearAgo)
            ->delete();

        return 0;
    }
}
