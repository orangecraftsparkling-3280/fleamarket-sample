<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    /**
     * 商品一覧画面（検索 ＆ マイリスト切り替え）
     */
    public function index(Request $request)
    {
        // 1. クエリビルダを開始
        $query = Item::query();

        // 2. タブ判定：マイリストの場合
        if ($request->query('tab') === 'mylist') {
            // ログインしていない場合は空の結果を返すか、ログイン画面へリダイレクト
            if (Auth::check()) {
                /** @var \App\Models\User $user */
                $user = Auth::user();
                $query = $user->favoriteItems();
            } else {
                $items = collect([]);
                return view('index', compact('items'));
            }
        }

        // 3. 検索キーワードがある場合（マイリスト内検索も可能）
        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $query->where('name', 'LIKE', "%{$keyword}%");
        }

        // 4. 最終的な結果を取得
        $items = $query->latest()->get();

        return view('index', compact('items'));
    }

    /**
     * 商品詳細画面
     */
    public function show($id)
    {
        // withCountでリレーションの数を取得、withで関連データを一括取得（N+1問題対策）
        $item = Item::with(['brand', 'categories', 'comments.user'])
            ->withCount(['favorites', 'comments'])
            ->findOrFail($id);

        return view('item_detail', compact('item'));
    }
}
