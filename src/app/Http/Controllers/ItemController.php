<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Http\Requests\ExhibitionRequest;

class ItemController extends Controller
{

    public function index(Request $request)
    {
        $query = Item::query();

        if ($request->query('tab') === 'mylist') {
            if (Auth::check()) {
                /** @var \App\Models\User $user */
                $user = Auth::user();
                $query = $user->favoriteItems();
            } else {
                $items = collect([]);
                return view('index', compact('items'));
            }
        } else {
            if (Auth::check()) {
                $query->where('user_id', '!=', Auth::id());
            }
        }

        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $query->where('name', 'LIKE', "%{$keyword}%");
        }

        $items = $query->latest()->get();

        return view('index', compact('items'));
    }

    public function show($id)
    {
        $item = Item::with(['categories', 'comments.user'])
            ->withCount(['favorites', 'comments'])
            ->findOrFail($id);

        $Favorite = false;

        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $Favorite = $user->favoriteItems()->where('item_id', $id)->exists();
        }

        return view('item_detail', compact('item', 'Favorite'));
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
        return view('sell', compact('categories'));
    }

    public function store(ExhibitionRequest $request)
    {
        $path = $request->file('item_image')->store('items', 'public');

        $item = Item::create([
            'user_id'     => Auth::id(),
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'condition'   => $request->condition,
            'image_url' => $path,
            'brand' => $request->brand,
        ]);

        $item->categories()->attach($request->category_ids);

        return redirect('/')->with('message', '商品を出品しました！');
    }
}
