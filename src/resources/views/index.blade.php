@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<main>
    <div class="list-tab">
        <a href="{{ route('index') }}"
            class="{{ request()->query('tab') !== 'mylist' ? 'active' : '' }}">
            おすすめ
        </a>

        <a href="{{ route('index', ['tab' => 'mylist']) }}"
            class="{{ request()->query('tab') === 'mylist' ? 'active' : '' }}">
            マイリスト
        </a>
    </div>

    <div class="container">
        <div class="item-grid">
            @foreach($items as $item)
            <a href="{{ route('item.show', $item->id) }}" class="item-card">
                <div class="item-image-wrapper">
                    <img src="{{ $item->image_url }}" alt="{{ $item->name }}">
                </div>
                <p class="item-name">{{ $item->name }}</p>
            </a>
            @endforeach
        </div>
    </div>
</main>
@endsection