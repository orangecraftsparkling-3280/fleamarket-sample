<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Latte&amp;Item</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Shippori+Mincho:wght@400;600;800&family=M+PLUS+Rounded+1c:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
    @yield('css')
</head>

<body>
    <header class="site-header">
        <div class="container header-inner">
            <div class="header-logo">
                <a href="{{ url('/') }}" class="logo-link">
                    <svg class="logo-icon" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M10 18 H34 V26 A10 8 0 0 1 24 34 H20 A10 8 0 0 1 10 26 Z" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round" />
                        <path d="M34 21 h3 a4 4 0 0 1 0 8 h-3" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M15 14 q2 -3 0 -6" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        <path d="M22 14 q2 -3 0 -6" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                    <span class="logo-text">Latte&amp;Item</span>
                </a>
            </div>
            @if(!Route::is('login') && !Route::is('register'))
            <div class="header-search">
                <form action="{{ route('index') }}" method="GET">
                    <input type="text" name="keyword" placeholder="何をお探しですか？" value="{{ request('keyword') }}">

                    @if(request('tab') === 'mylist')
                    <input type="hidden" name="tab" value="mylist">
                    @endif
                </form>
            </div>

            <nav class="header-nav">
                @auth

                <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <button type="submit" class="header-nav__button">ログアウト</button>
                </form>
                @endauth

                @guest
                <a href="{{ route('login') }}">ログイン</a>
                @endguest

                <a href="{{ route('mypage') }}">マイページ</a>
                <a href="{{ route('item.create') }}" class="sell-btn">出品</a>
            </nav>
            @endif
        </div>
    </header>
    <main>
        @yield('content')
    </main>
    @stack('scripts')
</body>

</html>