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
                        <h1>{{ $item->name }}</h1>
                        <p>¥{{ number_format($item->price) }}</p>
                    </div>
                </div>

                <section class="payment-selection">
                    <h2>支払い方法</h2>
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
                        <h2>配送先</h2>
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
                            <td id="display_payment_method">(上記で選択してください)</td>
                        </tr>
                    </table>
                </div>
                <button type="submit" class="btn-primary-wide">購入する</button>
            </div>
        </div>
    </form>
</main>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentSelect = document.getElementById('payment_method');
        const displayElement = document.getElementById('display_payment_method');

        const paymentNames = {
            'konbini': 'コンビニ払い',
            'card': 'カード支払い'
        };

        function updateDisplay() {
            const selectedValue = paymentSelect.value;
            if (paymentNames[selectedValue]) {
                displayElement.textContent = paymentNames[selectedValue];
            } else {
                displayElement.textContent = '(上記で選択してください)';
            }
        }

        paymentSelect.addEventListener('change', updateDisplay);

        updateDisplay();
    });
</script>
@endpush
@endsection