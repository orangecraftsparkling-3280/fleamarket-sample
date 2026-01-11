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
        <div class="header-logo">
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/COACHTECHヘッダーロゴ.png') }}" alt="ロゴ" width="300" height="26">
            </a>
        </div>

        <div class="header-search">
            <form action="{{ url('/search') }}" method="GET">
                <input type="text" name="keyword" placeholder="何をお探しですか？">
            </form>
        </div>

        <nav class="header-nav">
            <a href="{{ url('/mypage') }}">マイページ</a>
            <a href="{{ url('/login') }}">ログイン</a>
            <button class=listing-btn>出品</button>
        </nav>
    </header>
    <main>
        @yield('content')
    </main>
</body>

</html>