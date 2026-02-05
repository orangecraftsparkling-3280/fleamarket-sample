@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="mypage-container">
    <div class="user-info">
        <div class="user-flex">
            <div class="user-avatar">
                @if($user->profile && $user->profile->img_path)
                <img src="{{ asset('storage/' . $user->profile->img_path) }}" alt="ユーザー画像" class="profile-circle">
                @else
                <div class="default-circle"></div>
                @endif
            </div>
            <h2 class="user-name">{{ $user->name }}</h2>
        </div>

        <a href="{{ url('/mypage/profile') }}" class="btn-edit-profile">プロフィールを編集</a>
    </div>

    <div class="mypage-tabs">
        <a href="{{ route('mypage', ['tab' => 'sell']) }}"
            class="tab-item {{ request('tab') != 'buy' ? 'active' : '' }}">
            出品した商品
        </a>
        <a href="{{ route('mypage', ['tab' => 'buy']) }}"
            class="tab-item {{ request('tab') == 'buy' ? 'active' : '' }}">
            購入した商品
        </a>
    </div>

    <div class="product-list">
        <div class="item-grid">
            @forelse($items as $item)
            <a href="{{ route('item.show', $item->id) }}" class="item-card">
                <div class="item-image-wrapper">
                    <img src="{{ str_starts_with($item->image_url, 'http') ? $item->image_url : asset('storage/' . $item->image_url) }}" alt="{{ $item->name }}">
                    @if($item->is_sold)
                    <span class="sold-label">SOLD</span>
                    @endif
                </div>
                <p class="item-name">{{ $item->name }}</p>
            </a>
            @empty
            <p class="empty-message">
                {{ request('tab') == 'buy' ? '購入した商品はありません' : '出品した商品はありません' }}
            </p>
            @endforelse
        </div>
    </div>
</div>
@endsection