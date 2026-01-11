<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function index($id)
    {
        $item = Item::findOrFail($id);
        $user = Auth::user();

        // 購入画面（住所変更や支払い方法選択）を表示
        return view('purchase', compact('item', 'user'));
    }

    public function store(Request $request, $id)
    {
        // ここで決済処理や在庫減算、Orderモデルへの保存を行う
    }
}
