<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;

class PurchaseController extends Controller
{
    public function index($id)
    {
        $item = Item::findOrFail($id);
        $user = Auth::user();

        return view('purchase', compact('item', 'user'));
    }

    public function store(PurchaseRequest $request, $id)
    {
        $item = Item::findOrFail($id);
        $method = $request->payment_method;

        Stripe::setApiKey(config('services.stripe.secret'));

        $payment_method_types = ($method === 'konbini') ? ['konbini'] : ['card'];

        $session = Session::create([
            'payment_method_types' => $payment_method_types,
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('purchase.success', ['id' => $item->id]),
            'cancel_url' => route('purchase', ['id' => $item->id]),
        ]);

        return redirect($session->url, 303);
    }

    public function success($id)
    {
        $item = Item::findOrFail($id);

        if ($item->is_sold) {
            return redirect()->route('index')->with('error', 'この商品は既に売り切れています。');
        }

        $item->update([
            'is_sold' => true,
            'buyer_id' => Auth::id(),
        ]);
        return redirect('/')->with('message', '購入が完了しました！');
    }

    public function editAddress($id)
    {
        return view('address_edit', ['item_id' => $id]);
    }

    public function updateAddress(AddressRequest $request, $id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'post_code' => $request->post_code,
                'address'   => $request->address,
                'building'  => $request->building,
            ]
        );

        return redirect()->route('purchase', ['id' => $id])
            ->with('message', '配送先を更新しました');
    }
}
