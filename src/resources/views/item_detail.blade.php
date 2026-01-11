@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endsection

@section('content')
<main class="container">
    <div class="item-detail-layout">

        {{-- 左側：商品画像セクション --}}
        <div class="item-image-section">
            <img src="{{ asset($item->image_url) }}" alt="{{ $item->name }}" width="500px" height="500px">
        </div>

        {{-- 右側：商品情報セクション --}}
        <div class="item-info-section">

            {{-- 商品基本情報 --}}
            <div class="main-info">
                <h1 class="item-name">{{ $item->name }}</h1>
                <p class="brand-name">{{ $item->brand}}</p>
                <p class="price">
                    <span class="currency">¥</span>{{ number_format($item->price) }} <span class="tax-in">(税込)</span>
                </p>

                {{-- アクション（いいね・コメント数） --}}
                <div class="action-icons">
                    {{-- いいねボタン（Formによる同期通信） --}}
                    {{-- いいねセクション --}}
                    <div class="icon-group">
                        @auth
                        @if($isFavorite)
                        {{-- いいね済み：赤いハートを表示 --}}
                        <form action="{{ route('favorite.destroy', $item->id) }}" method="POST" class="favorite-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="icon-btn">
                                <img src="{{ asset('images/ハートロゴ_ピンク.png') }}" alt="いいね解除">
                            </button>
                        </form>
                        @else
                        {{-- 未いいね：デフォルトのハートを表示 --}}
                        <form action="{{ route('favorite.store', $item->id) }}" method="POST" class="favorite-form">
                            @csrf
                            <button type="submit" class="icon-btn">
                                <img src="{{ asset('images/ハートロゴ_デフォルト.png') }}" alt="いいねする">
                            </button>
                        </form>
                        @endif
                        @else
                        {{-- 未ログイン時：デフォルト画像でログインへ誘導 --}}
                        <a href="{{ route('login') }}" class="icon-btn">
                            <img src="{{ asset('images/ハートロゴ_デフォルト.png') }}" alt="ログインしていいね">
                        </a>
                        @endauth

                        {{-- いいね数の表示 --}}
                        <span class="count">{{ $item->favorites_count }}</span>
                    </div>
                    {{-- コメント数表示セクション --}}
                    <div class="icon-group">
                        <a href="#comment-area" class="icon-btn">
                            <img src="{{ asset('images/ふきだしロゴ.png') }}" alt="コメント">
                        </a>
                        <span class="count">{{ $item->comments_count }}</span>
                    </div>
                </div>
                {{-- 購入ボタンなどはここに配置 --}}
                <div class="buy-action">
                    <a href="{{ route('purchase', ['id' => $item->id]) }}" class="btn-primary">購入手続きへ</a>
                </div>
            </div>

            {{-- 商品説明 --}}
            <section class="detail-section">
                <h2 class="section-title">商品説明</h2>
                <div class="description-text">
                    <p>{{ $item->description }}</p>
                </div>
            </section>

            {{-- 商品情報（カテゴリ・状態） --}}
            <section class="detail-section">
                <h2 class="section-title">商品情報</h2>
                <table class="info-table">
                    <tr>
                        <th>カテゴリー</th>
                        <td>
                            <div class="category-list">
                                @foreach($item->categories as $category)
                                <span class="category-badge">{{ $category->name }}</span>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                    </tr>
                    <tr>
                        <th>商品の状態</th>
                        <td>{{ $item->condition }}</td>
                    </tr>
                </table>
            </section>

            {{-- コメントセクション --}}
            <section class="comment-section" id="comment-area">
                <h2 class="section-title">コメント ({{ $item->comments_count }})</h2>

                <div class="comment-list">
                    @forelse($item->comments as $comment)
                    <div class="comment-card">
                        <div class="comment-user">
                            <div class="user-avatar-small">
                                {{-- プロフィール画像がある場合は表示、なければ初期アイコン --}}
                                <img src="{{ $comment->user->profile_image ? asset('storage/' . $comment->user->profile_image) : asset('images/default-user.png') }}" alt="">
                            </div>
                            <span class="user-name">{{ $comment->user->name }}</span>
                        </div>
                        <div class="comment-content">
                            <p>{{ $comment->content }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="no-comments">コメントはまだありません。</p>
                    @endforelse
                </div>

                {{-- コメント投稿フォーム --}}
                <div class="comment-form-wrapper" id="comment-form">
                    <h3 class="form-title">商品へのコメント</h3>

                    <form action="{{ route('comment.store', $item->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <textarea name="content" class="comment-textarea" required>{{ old('content') }}</textarea>

                            {{-- バリデーションエラー（未ログインや未入力など）の表示 --}}
                            @if ($errors->any())
                            <ul class="error-list">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            @endif
                        </div>

                        <button type="submit" class="btn-secondary">コメントを送信する</button>
                    </form>
                </div>
            </section>
        </div>
    </div>
</main>
@endsection