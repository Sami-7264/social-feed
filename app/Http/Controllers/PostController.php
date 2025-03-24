<?php

namespace App\Http\Controllers;

use App\Events\PostCreated;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PostController extends Controller
{
    /**
     * Display the posts index page.
     */
    public function index()
    {
        $posts = Post::with(['user', 'comments.user'])
            ->latest()
            ->get()
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'content' => $post->content,
                    'user' => [
                        'id' => $post->user->id,
                        'name' => $post->user->name,
                    ],
                    'comments' => $post->comments->map(function ($comment) {
                        return [
                            'id' => $comment->id,
                            'content' => $comment->content,
                            'user' => [
                                'id' => $comment->user->id,
                                'name' => $comment->user->name,
                            ],
                            'created_at' => $comment->created_at->diffForHumans(),
                        ];
                    }),
                    'created_at' => $post->created_at->diffForHumans(),
                ];
            });

        return Inertia::render('Home', [
            'posts' => $posts,
        ]);
    }

    /**
     * Store a newly created post in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:255',
        ]);

        $post = Auth::user()->posts()->create($validated);

        $post->load('user');

        event(new PostCreated($post));

        return redirect()->back();
    }

    /**
     * Show a specific post with its comments.
     */
    public function show(Post $post)
    {
        $post->load(['user', 'comments.user']);

        $formattedPost = [
            'id' => $post->id,
            'content' => $post->content,
            'user' => [
                'id' => $post->user->id,
                'name' => $post->user->name,
            ],
            'comments' => $post->comments->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'user' => [
                        'id' => $comment->user->id,
                        'name' => $comment->user->name,
                    ],
                    'created_at' => $comment->created_at->diffForHumans(),
                ];
            }),
            'created_at' => $post->created_at->diffForHumans(),
        ];

        return Inertia::render('Post/Show', [
            'post' => $formattedPost,
        ]);
    }
}
