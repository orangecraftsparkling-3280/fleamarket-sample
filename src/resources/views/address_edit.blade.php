@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
<main class="address-container">
    <h1 class="page-title">住所の変更</h1>

    <form action="{{ route('address.update', ['item_id' => $item_id]) }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="post_code">郵便番号</label>
            <input type="text" name="post_code" id="post_code"
                value="{{ old('post_code', session('shipping_address.post_code', $user->profile->post_code ?? '')) }}">
            <div class="form__error">
                @error('post_code')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="address">住所</label>
            <input type="text" name="address" id="address"
                value="{{ old('address', session('shipping_address.address', $user->profile->address ?? '')) }}">
            <div class="form__error">
                @error('address')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="building">建物名</label>
            <input type="text" name="building" id="building"
                value="{{ old('building', session('shipping_address.building', $user->profile->building ?? '')) }}">
            <div class="form__error">
                @error('building')
                {{ $message }}
                @enderror
            </div>
        </div>

        <button type="submit" class="btn-update">更新する</button>
    </form>
</main>
@endsection