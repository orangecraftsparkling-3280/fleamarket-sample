@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('purchase.css') }}">
@endsection

@section('content')
<main class="container">

        <div class="purchase-layout">
            {{-- 左側：商品概要と支払い方法 --}}
            <div class="purchase-main">
                <div class="item-summary">
                    <img src="{{ asset($item->image_url) }}" alt="" width="150">
                    <div class="item-detail">
                        <h2>{{ $item->name }}</h2>
                        <p>¥{{ number_format($item->price) }}</p>
                    </div>
                </div>

                {{-- 支払い方法の選択など --}}
                <section class="payment-selection">
                    <h3>支払い方法</h3>
                    {{-- ここに支払い方法のプルダウンなど --}}
                </section>

                <section class="address-selection">
                    <h3>配送先</h3>
                    <p>〒{{ $user->postal_code }} {{ $user->address }}</p>
                    <a href="{{ route('address.edit') }}">変更する</a>
                </section>
            </div>

            {{-- 右側：決済確認カード --}}
            <div class="purchase-sidebar">
                <table class="summary-table">
                    <tr>
                        <th>商品代金</th>
                        <td>¥{{ number_format($item->price) }}</td>
                    </tr>
                    <tr>
                        <th>支払い合計</th>
                        <td>¥{{ number_format($item->price) }}</td>
                    </tr>
                    <tr>
                        <th>支払い方法</th>
                        <td>コンビニ払い</td>
                    </tr>
                </table>

                <form action="{{ route('purchase.store', $item->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-primary-wide">購入する</button>
                </form>
            </div>
        </div>

</main>
@endsection