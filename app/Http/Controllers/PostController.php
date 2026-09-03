<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // Public: List all posts
    public function index()
    {
        return response()->json(Post::with('user')->latest()->get());
    }

    // Public: Show single post
    public function show($id)
    {
        $post = Post::with('user')->findOrFail($id);
        return response()->json($post);
    }

    // Auth User: Create post
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
        ]);

        $post = $request->user()->posts()->create([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        $post->load('user');

        return response()->json($post, 201);
    }

    // Auth User (Owner) or Admin: Update post
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        if ($request->user()->id !== $post->user_id && $request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
        ]);

        $post->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        $post->load('user');

        return response()->json($post);
    }

    // Auth User (Owner) or Admin: Delete post
    public function destroy(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        if ($request->user()->id !== $post->user_id && $request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }

    // Auth User: Get own posts
    public function userPosts(Request $request)
    {
        $posts = $request->user()->posts()->latest()->get();
        return response()->json($posts);
    }

    // Admin: Get all system posts
    public function adminPosts()
    {
        $posts = Post::with('user')->latest()->get();
        return response()->json($posts);
    }
}
