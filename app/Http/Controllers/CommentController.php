<?php

namespace App\Http\Controllers;

use App\Events\CommentCreated;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:500',
        ]);

        $comment = $post->comments()->create([
            'content' => $validated['content'],
            'user_id' => Auth::id(),
        ]);

        $comment->load('user');

        event(new CommentCreated($comment));

        return redirect()->back();
    }
}
