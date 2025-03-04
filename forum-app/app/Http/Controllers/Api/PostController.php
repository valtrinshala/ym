<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    use AuthorizesRequests;


    public function index(Request $request): AnonymousResourceCollection
    {
        $search = $request->get('q', '');
        $posts = Post::with('user', 'comments')
            ->search($search)
            ->orderBy('created_at', 'desc')
            ->get();

        return PostResource::collection($posts);
    }

    public function store(PostRequest $request): PostResource
    {
        $this->authorize('create', Post::class);

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

    public function update(PostRequest $request, Post $post): PostResource|\Illuminate\Http\JsonResponse
    {
        $this->authorize('update', $post);

        $validated = $request->validated();

        $post->update($validated);

        return new PostResource($post);
    }

    public function destroy(Post $post): \Illuminate\Http\JsonResponse
    {
        $this->authorize('delete', $post);

        $post->delete();

        return response()->json(null, 204);
    }
}
