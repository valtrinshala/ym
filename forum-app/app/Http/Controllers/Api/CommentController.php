<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use App\Notifications\NewCommentNotification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    use AuthorizesRequests;

    public function index(Post $post): AnonymousResourceCollection
    {
        $comments = $post->comments()->with('user')->latest()->get();

        return CommentResource::collection($comments);
    }


    public function store(CommentRequest $request, Post $post): CommentResource
    {
        $this->authorize('create', [Comment::class, $post]);

        $validated = $request->validated();

        $comment = $post->comments()->create([
            'user_id' => Auth::id() ?? 1,
            'body' => $validated['body'],
        ]);

        $comment->load('user', 'post');

        if ($post->user && $post->user->id !== Auth::id()) {
            $post->user->notify(new NewCommentNotification($comment));
        }

        return new CommentResource($comment);
    }

    public function show(Post $post, Comment $comment): CommentResource
    {
        if ($comment->post_id !== $post->id) {
            abort(404);
        }

        $comment->load('user');

        return new CommentResource($comment);
    }

    public function update(CommentRequest $request, Post $post, Comment $comment): CommentResource|JsonResponse
    {
        $this->authorize('update', $post);

        if ($comment->post_id !== $post->id) {
            abort(404);
        }

        $validated = $request->validated();

        $comment->update($validated);
        $comment->load('user');

        return new CommentResource($comment);
    }

    public function destroy(Post $post, Comment $comment): JsonResponse
    {
         if ($comment->user_id !== Auth::id()) {
             return response()->json(['error' => 'Forbidden'], 403);
         }

        if ($comment->post_id !== $post->id) {
            abort(404);
        }

        $comment->delete();

        return response()->json(null, 204);
    }
}
