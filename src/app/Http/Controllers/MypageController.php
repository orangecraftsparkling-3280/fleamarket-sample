<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class MypageController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $tab = $request->query('tab', 'sell');

        if ($tab === 'buy') {
            $items = Item::where('buyer_id', $user->id)
                ->where('is_sold', true)
                ->get();
        } else {
            $items = Item::where('user_id', $user->id)->get();
        }

        return view('mypage', compact('user', 'items', 'tab'));
    }
}
