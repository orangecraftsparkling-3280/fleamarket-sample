@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
<main class="sell-container">
    <h2 class="sell-title">商品の出品</h2>

    <form action="{{ route('item.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">商品画像</span>
            </div>

            <div class="image-upload-area">
                <label for="item_image" class="btn-select-image">画像を選択する</label>
                <input type="file" name="item_image" id="item_image" accept="image/*" class="hidden-file-input">
            </div>
            <div class="form__error">
                @error('item_image')
                {{ $message }}
                @enderror
            </div>
        </div>

        <h3 class="sub-title">商品の詳細</h3>
        <hr>

        <div class="form-group">
            <label class="form-label">カテゴリー</label>
            <div class="category-group">
                @foreach($categories as $category)
                <div class="category-checkbox">
                    <input type="checkbox"
                        name="category_ids[]"
                        value="{{ $category->id }}"
                        id="cat{{ $category->id }}"
                        {{ (is_array(old('category_ids')) && in_array($category->id, old('category_ids'))) ? 'checked' : '' }}>
                    <label for="cat{{ $category->id }}">{{ $category->name }}</label>
                </div>
                @endforeach
            </div>
            <div class="form__error">
                @error('category_ids')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="condition" class="form-label">商品の状態</label>
            <select name="condition_id" id="condition" class="form-input condition-select">
                <option value="" disabled {{ old('condition_id') === null ? 'selected' : '' }}>選択してください</option>
                @foreach($conditions as $condition)
                <option value="{{ $condition->id }}"
                    {{ old('condition_id') == $condition->id ? 'selected' : '' }}>
                    {{ $condition->condition }}
                </option>
                @endforeach
            </select>
            <div class="form__error">
                @error('condition_id')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="name" class="form-label">商品名</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-input">
            <div class="form__error">
                @error('name')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="name" class="form-label">ブランド名</label>
            <input type="text" name="brand" id="brand" value="{{ old('brand') }}" class="form-input">
            <div class="form__error">
                @error('brand')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="description" class="form-label">商品の説明</label>
            <textarea name="description" id="description" rows="5" class="form-input">{{ old('description') }}</textarea>
            <div class="form__error">
                @error('description')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="price" class="form-label">販売価格</label>
            <div class="price-input-container">
                <span class="currency-unit">¥</span>
                <input type="number" name="price" id="price" value="{{ old('price') }}" class="form-input price-input">
            </div>
            <div class="form__error">
                @error('price')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">出品する</button>
        </div>
    </form>
</main>
@endsection