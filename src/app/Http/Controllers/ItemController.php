<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use App\Models\Condition;
use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\ItemUpdateRequest;

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
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('description', 'like', '%' . $keyword . '%')
                    ->orWhere('brand', 'like', '%' . $keyword . '%');
            });
        }

        if ($categoryId) {
            $query->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        }

        $items = $query->latest('items.created_at')->paginate(12)->withQueryString();

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
        $item = Item::with(['condition', 'categories', 'comments.user.profile'])
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

    public function edit($item_id)
    {
        $item = Item::with('categories')->findOrFail($item_id);

        abort_if($item->user_id !== Auth::id(), 403);

        $categories = Category::all();
        $conditions = Condition::all();
        $categoryIds = $item->categories->pluck('id')->toArray();

        return view('sell', compact('item', 'categories', 'conditions', 'categoryIds'));
    }

    public function update(ItemUpdateRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        abort_if($item->user_id !== Auth::id(), 403);

        $data = [
            'name'         => $request->name,
            'description'  => $request->description,
            'price'        => $request->price,
            'condition_id' => $request->condition_id,
            'brand'        => $request->brand ?? 'なし',
        ];

        if ($request->hasFile('item_image')) {
            if ($item->image_url) {
                Storage::disk('public')->delete($item->image_url);
            }
            $data['image_url'] = $request->file('item_image')->store('items', 'public');
        }

        $item->update($data);
        $item->categories()->sync($request->category_ids);

        return redirect()->route('item.show', $item->id)->with('message', '商品を更新しました！');
    }

    public function destroy($item_id)
    {
        $item = Item::findOrFail($item_id);

        abort_if($item->user_id !== Auth::id(), 403);

        if ($item->is_sold) {
            return back()->with('error', '売却済みの商品は削除できません');
        }

        if ($item->image_url) {
            Storage::disk('public')->delete($item->image_url);
        }

        $item->delete();

        return redirect()->route('mypage')->with('message', '商品を削除しました');
    }
}
