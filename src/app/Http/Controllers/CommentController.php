<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, $item_id)
    {
        // バリデーション
        $request->validate([
            'comment' => 'required|max:255',
        ]);

        // 保存処理
        Comment::create([
            'user_id' => Auth::id(),
            'item_id' => $item_id,
            'comment' => $request->comment,
        ]);

        // 元の商品詳細ページに戻る
        return back()->with('success', 'コメントを投稿しました');
    }
}
