<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;

class PurchaseController extends Controller
{
    public function index($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        $address = session('shipping_address', [
            'post_code' => $user->profile->post_code ?? '',
            'address'   => $user->profile->address ?? '',
            'building'  => $user->profile->building ?? '',
        ]);

        return view('purchase', compact('item', 'user', 'address'));
    }

    public function store(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
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
            'success_url' => route('purchase.success', ['item_id' => $item->id]),
            'cancel_url' => route('purchase', ['item_id' => $item->id]),
        ]);

        return redirect($session->url, 303);
    }

    public function success($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        if ($item->is_sold) {
            return redirect()->route('index')->with('error', '売り切れです');
        }

        app('db')->transaction(function () use ($item, $user) {
            $tempAddress = session('shipping_address');

            $item->purchase()->create([
                'user_id'   => $user->id,
                'post_code' => $tempAddress['post_code'] ?? $user->profile->post_code,
                'address'   => $tempAddress['address']   ?? $user->profile->address,
                'building'  => $tempAddress['building']  ?? $user->profile->building,
            ]);

            $item->update(['is_sold' => true]);

            session()->forget('shipping_address');
        });

        return redirect('/')->with('message', '購入完了！');
    }

    public function editAddress($item_id)
    {
        return view('address_edit', ['item_id' => $item_id]);
    }

    public function updateAddress(AddressRequest $request, $item_id)
    {
        session([
            'shipping_address' => [
                'post_code' => $request->post_code,
                'address'   => $request->address,
                'building'  => $request->building,
            ]
        ]);

        return redirect()->route('purchase', ['item_id' => $item_id])
            ->with('message', '配送先を一時的に変更しました');
    }
}
