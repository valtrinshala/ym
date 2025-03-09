<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['post_id', 'user_id', 'body', 'edited_at'];

    protected $casts = [
        'edited_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($comment) {
            $comment->post()->increment('comments_count');
        });

        static::deleted(function ($comment) {
            $comment->post()->decrement('comments_count');
        });
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
