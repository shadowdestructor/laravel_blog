<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|min:10|max:1000',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        $comment = Comment::create([
            'content' => $request->content,
            'user_id' => auth()->id(),
            'post_id' => $post->id,
            'parent_id' => $request->parent_id,
            'status' => 'approved' // Auto-approve for now
        ]);

        return back()->with('success', 'Yorumunuz başarıyla eklendi.');
    }

    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $request->validate([
            'content' => 'required|string|min:10|max:1000'
        ]);

        $comment->update([
            'content' => $request->content
        ]);

        return back()->with('success', 'Yorumunuz güncellendi.');
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return back()->with('success', 'Yorum silindi.');
    }
}