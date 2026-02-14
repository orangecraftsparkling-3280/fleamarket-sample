<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fleamarket</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
    @yield('css')
</head>

<body>
    <header class="site-header">
        <div class="container header-inner">
            <div class="header-logo">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('images/COACHTECHヘッダーロゴ.png') }}" alt="ロゴ" class="logo-img">
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