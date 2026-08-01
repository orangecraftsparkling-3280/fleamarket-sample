<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    public function store(CommentRequest $request, $item_id)
    {
        Comment::create([
            'user_id' => Auth::id(),
            'item_id' => $item_id,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'コメントを投稿しました');
    }

    public function update(CommentRequest $request, $comment_id)
    {
        $comment = Comment::findOrFail($comment_id);

        abort_if($comment->user_id !== Auth::id(), 403);

        $comment->update(['comment' => $request->comment]);

        return back()->with('success', 'コメントを更新しました');
    }

    public function destroy($comment_id)
    {
        $comment = Comment::findOrFail($comment_id);

        abort_if($comment->user_id !== Auth::id(), 403);

        $comment->delete();

        return back()->with('success', 'コメントを削除しました');
    }
}
