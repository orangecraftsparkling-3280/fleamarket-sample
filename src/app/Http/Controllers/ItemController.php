<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Condition;
use App\Http\Requests\ExhibitionRequest;

class ItemController extends Controller
{

    public function index(Request $request)
    {
        $tab = $request->query('tab');
        $keyword = $request->query('keyword');
        $categoryId = $request->query('category');
        $categories = Category::all();

        if ($tab === 'mylist') {
            if (Auth::check()) {
                /** @var \App\Models\User $user */
                $user = Auth::user();
                $query = $user->favoriteItems();
            } else {
                $items = collect([]);
                return view('index', compact('items', 'categories'));
            }
        } else {
            $query = Item::query();

            if (Auth::check()) {
                $query->where('user_id', '!=', Auth::id());
            }
        }

        if ($keyword) {
            $query->where('name', 'like', '%' . $keyword . '%');
        }

        if ($categoryId) {
            $query->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        }

        $items = $query->latest('items.created_at')->get();

        return view('index', compact('items', 'categories'));
    }

    public function favorite($item_id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user->favoriteItems()->syncWithoutDetaching([$item_id]);

        return back();
    }

    public function unfavorite($item_id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->favoriteItems()->detach($item_id);
        return back();
    }

    public function create()
    {
        $categories = Category::all();
        $conditions = Condition::all();
        return view('sell', compact('categories', 'conditions'));
    }
    public function show($item_id)
    {
        $item = Item::with(['condition', 'categories', 'comments.user'])
            ->withCount(['favorites', 'comments'])
            ->findOrFail($item_id);

        $is_favorite = false;
        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $is_favorite = $user->favoriteItems()->where('item_id', $item_id)->exists();
        }

        return view('item_detail', compact('item', 'is_favorite'));
    }
    public function store(ExhibitionRequest $request)
    {
        $path = null;

        if ($request->hasFile('item_image')) {
            $path = $request->file('item_image')->store('items', 'public');
        }

        $item = Item::create([
            'user_id'      => Auth::id(),
            'name'         => $request->name,
            'description'  => $request->description,
            'price'        => $request->price,
            'condition_id' => $request->condition_id,
            'image_url'    => $path,
            'brand'        => $request->brand ?? 'なし',
        ]);

        $item->categories()->attach($request->category_ids);

        return redirect('/')->with('message', '商品を出品しました！');
    }
}
