@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<main class="purchase-container">
    <form action="{{ route('purchase.store', $item->id) }}" method="POST">
        @csrf
        <div class="purchase-layout">

            <div class="purchase-main">
                <div class="item-summary">
                    <img src="{{ str_starts_with($item->image_url, 'http') ? $item->image_url : asset('storage/' . $item->image_url) }}" alt="{{ $item->name }}" width="400" height="400">
                    <div class="item-detail">
                        <h2>{{ $item->name }}</h2>
                        <p>¥{{ number_format($item->price) }}</p>
                    </div>
                </div>

                <section class="payment-selection">
                    <h3>支払い方法</h3>
                    <div class="select-wrapper">
                        <select name="payment_method" id="payment_method" class="payment-select">
                            <option value="" disabled selected>選択してください</option>
                            <option value="konbini">コンビニ払い</option>
                            <option value="card">カード支払い</option>
                        </select>
                    </div>
                    <div class="form__error">
                        @error('payment_method')
                        {{ $message }}
                        @enderror
                    </div>
                </section>

                <section class="address-selection">
                    <div class="address-header">
                        <h3>配送先</h3>
                        <a href="{{ route('address.edit', ['item_id' => $item->id]) }}">変更する</a>
                    </div>
                    <p>〒 {{ $address['post_code'] }}</p>
                    <p>{{ $address['address'] }} {{ $address['building'] }}</p>
                    <input type="hidden" name="address" value="{{ $user->profile->address }}">
                </section>
                <div class="form__error">
                    @error('address')
                    {{ $message }}
                    @enderror
                </div>
            </div>

            <div class="purchase-sidebar">
                <div class="summary-card">
                    <table class="summary-table">
                        <tr>
                            <th>商品代金</th>
                            <td>¥{{ number_format($item->price) }}</td>
                        </tr>
                        <tr>
                            <th>支払い方法</th>
                            <td>(上記で選択してください)</td>
                        </tr>
                    </table>

                    <button type="submit" class="btn-primary-wide">購入する</button>
                </div>
            </div>
        </div>
    </form>
</main>
@endsection