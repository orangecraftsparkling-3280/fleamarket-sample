<?php

namespace App\Http\Controllers;

use App\Models\Item;
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

        $address = $this->resolveShippingAddress($user);

        return view('purchase', compact('item', 'user', 'address'));
    }

    public function store(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();
        $method = $request->payment_method;

        Stripe::setApiKey(config('services.stripe.secret'));

        $payment_method_types = ($method === 'konbini') ? ['konbini'] : ['card'];
        $address = $this->resolveShippingAddress($user);

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
            'metadata' => [
                'user_id'   => (string) $user->id,
                'item_id'   => (string) $item->id,
                'post_code' => $address['post_code'],
                'address'   => $address['address'],
                'building'  => $address['building'],
            ],
        ]);

        return redirect($session->url, 303);
    }

    public function success($item_id)
    {
        Item::findOrFail($item_id);

        session()->forget('shipping_address');

        return redirect('/')->with('message', 'ご購入ありがとうございました。決済確認後、購入が確定します。');
    }

    private function resolveShippingAddress($user): array
    {
        return session('shipping_address', [
            'post_code' => $user->profile->post_code ?? '',
            'address'   => $user->profile->address ?? '',
            'building'  => $user->profile->building ?? '',
        ]);
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
