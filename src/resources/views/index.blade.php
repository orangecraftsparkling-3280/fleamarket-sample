@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<main>
    <div class="list-tab">
        <a href="{{ route('index', ['keyword' => request()->query('keyword')]) }}"
            class="{{ request()->query('tab') !== 'mylist' ? 'active' : '' }}">
            おすすめ
        </a>

        <a href="{{ route('index', ['tab' => 'mylist', 'keyword' => request()->query('keyword')]) }}"
            class="{{ request()->query('tab') === 'mylist' ? 'active' : '' }}">
            マイリスト
        </a>
    </div>

    <div class="container">
        <div class="category-filter">
            <a href="{{ route('index', array_filter(['tab' => request('tab'), 'keyword' => request('keyword')])) }}"
                class="{{ !request('category') ? 'active' : '' }}">
                すべて
            </a>
            @foreach($categories as $category)
            <a href="{{ route('index', array_filter(['tab' => request('tab'), 'keyword' => request('keyword'), 'category' => $category->id])) }}"
                class="{{ (int) request('category') === $category->id ? 'active' : '' }}">
                {{ $category->name }}
            </a>
            @endforeach
        </div>

        <div class="item-grid">
            @foreach($items as $item)
            <a href="{{ route('item.show', $item->id) }}" class="item-card">
                <div class="item-image-wrapper">
                    <img src="{{ str_starts_with($item->image_url, 'http') ? $item->image_url : asset('storage/' . $item->image_url) }}" alt="{{ $item->name }}">
                    @if(isset($item->is_sold) && $item->is_sold)
                    <div class="sold-label">
                        <span>sold</span>
                    </div>
                    @endif
                </div>
                <p class="item-name">{{ $item->name }}</p>
            </a>
            @endforeach
        </div>

        <div class="pagination-wrapper">
            {{ $items->links() }}
        </div>
    </div>
</main>
@endsection