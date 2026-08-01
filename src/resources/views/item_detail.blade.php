@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endsection

@section('content')
<main class="container">
    <div class="item-detail-layout">

        <div class="item-image-section">
            <img src="{{ str_starts_with($item->image_url, 'http') ? $item->image_url : asset('storage/' . $item->image_url) }}" alt="{{ $item->name }}">
        </div>

        <div class="item-info-section">

            <div class="main-info">
                <h1 class="item-name">{{ $item->name }}</h1>
                <h2 class="brand-name">ブランド名</h2>
                <p class="brand-name">{{ $item->brand}}</p>
                <p class="price">
                    <span class="currency">¥</span>{{ number_format($item->price) }} <span class="tax-in">(税込)</span>
                </p>

                <div class="action-icons">
                    <div class="icon-group">
                        @auth
                        @if($is_favorite)
                        <form action="{{ route('favorite.destroy', ['item_id' => $item->id]) }}" method="POST" class="favorite-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="icon-btn">
                                <img src="{{ asset('images/ハートロゴ_ピンク.png') }}" alt="いいね解除">
                            </button>
                        </form>
                        @else
                        <form action="{{ route('favorite.store', ['item_id' => $item->id]) }}" method="POST" class="favorite-form">
                            @csrf
                            <button type="submit" class="icon-btn">
                                <img src="{{ asset('images/ハートロゴ_デフォルト.png') }}" alt="いいねする">
                            </button>
                        </form>
                        @endif
                        @else
                        <a href="{{ route('login') }}" class="icon-btn">
                            <img src="{{ asset('images/ハートロゴ_デフォルト.png') }}" alt="ログインしていいね">
                        </a>
                        @endauth

                        <span class="count">{{ $item->favorites_count }}</span>
                    </div>
                    <div class="icon-group">
                        <a href="#comment-area" class="icon-btn">
                            <img src="{{ asset('images/ふきだしロゴ.png') }}" alt="コメント">
                        </a>
                        <span class="count">{{ $item->comments_count }}</span>
                    </div>
                </div>

                <div class="buy-action">
                    @if($item->is_sold)
                    <button class="btn-primary is-sold" disabled>sold out</button>
                    @else
                    <a href="{{ route('purchase', ['item_id' => $item->id]) }}" class="btn-primary">購入手続きへ</a>
                    @endif
                </div>
            </div>

            <section class="detail-section">
                <h2 class="section-title">商品説明</h2>
                <div class="description-text">
                    <p>{{ $item->description }}</p>
                </div>
            </section>

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
                        <td>{{ $item->condition->condition ?? '不明' }}</td>
                    </tr>
                </table>
            </section>

            <section class="comment-section" id="comment-area">
                <h2 class="section-title">コメント ({{ $item->comments_count }})</h2>

                <div class="comment-list">
                    @forelse($item->comments as $comment)
                    <div class="comment-card">
                        <div class="comment-user">
                            <div class="user-avatar-small">
                                <img src="{{ $comment->user->profile?->getAvatarUrl() ?? asset('images/default-user.png') }}" alt="ユーザーアイコン">
                            </div>
                            <span class="user-name">{{ $comment->user->name }}</span>
                        </div>
                        <div class="comment-content" id="comment-view-{{ $comment->id }}">
                            <p>{{ $comment->comment }}</p>
                            @auth
                            @if(Auth::id() === $comment->user_id)
                            <div class="comment-actions">
                                <button type="button" class="btn-link" onclick="toggleCommentEdit({{ $comment->id }})">編集</button>
                                <form action="{{ route('comment.destroy', $comment->id) }}" method="POST" class="inline-form" onsubmit="return confirm('このコメントを削除しますか？');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-link btn-link--danger">削除</button>
                                </form>
                            </div>
                            @endif
                            @endauth
                        </div>
                        @auth
                        @if(Auth::id() === $comment->user_id)
                        <form action="{{ route('comment.update', $comment->id) }}" method="POST" class="comment-edit-form" id="comment-edit-{{ $comment->id }}" style="display:none;">
                            @csrf
                            @method('PUT')
                            <textarea name="comment" class="comment-textarea">{{ $comment->comment }}</textarea>
                            <div class="form__error">
                                @error('comment')
                                {{ $message }}
                                @enderror
                            </div>
                            <button type="submit" class="btn-secondary">更新する</button>
                        </form>
                        @endif
                        @endauth
                    </div>
                    @empty
                    <p class="no-comments">コメントはまだありません。</p>
                    @endforelse
                </div>

                <div class="comment-form-wrapper" id="comment-form">
                    <h3 class="form-title">商品へのコメント</h3>

                    <form action="{{ route('comment.store', ['item_id' => $item->id]) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <textarea name="comment" class="comment-textarea">{{ old('comment') }}</textarea>

                            <div class="form__error">
                                @error('comment')
                                {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn-secondary">コメントを送信する</button>
                    </form>
                </div>
            </section>
        </div>
    </div>
</main>
@push('scripts')
<script>
    function toggleCommentEdit(commentId) {
        var view = document.getElementById('comment-view-' + commentId);
        var form = document.getElementById('comment-edit-' + commentId);
        var isHidden = form.style.display === 'none';
        form.style.display = isHidden ? 'block' : 'none';
        view.style.display = isHidden ? 'none' : 'block';
    }
</script>
@endpush
@endsection