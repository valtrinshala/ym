<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $search = $request->get('q', '');
        $posts = Post::with('user', 'comments')
            ->search($search)
            ->latest()
            ->get();

        return PostResource::collection($posts);
    }

    public function store(PostRequest $request): PostResource
    {
        $validated = $request->validated();

        $post = Post::query()
            ->create([
                'user_id' => Auth::id() ?? 1,
                'title' => $validated['title'],
                'content' => $validated['content'],
            ]);

        return new PostResource($post);
    }


    public function show(Post $post): PostResource
    {
        $post->load('user', 'comments');

        return new PostResource($post);
    }

    public function update(PostRequest $request, Post $post): PostResource
    {
        $validated = $request->validated();

        $post->update($validated);

        return new PostResource($post);
    }


    public function destroy(Post $post): JsonResponse
    {
        $post->delete();

        return response()->json(null, 204);
    }
}
