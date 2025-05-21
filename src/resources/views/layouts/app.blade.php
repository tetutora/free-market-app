<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>フリマる</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>

<body>
<header class="header">
    <div class="header__inner">
        <div class="header-utilities">
            <a class="header__logo" href="/">
                フリマる
            </a>
            <nav>
                <ul class="header-nav">
                    @if (Auth::check())
                        <li class="header-nav__item">
                            <a class="header-nav__link" href="/mypage">マイページ</a>
                        </li>
                        <li class="header-nav__item">
                            <a class="header-nav__link" href="/items/create">出品</a>
                        </li>
                        <li class="header-nav__item">
                            <form action="/logout" method="post">
                                @csrf
                                <button class="header-nav__button">ログアウト</button>
                            </form>
                        </li>
                        <li class="header-nav__item">
                            <a class="header-nav__link" href="/items/search">商品検索</a>
                        </li>
                    @else
                        <li class="header-nav__item">
                            <a class="header-nav__link" href="/login">ログイン</a>
                        </li>
                        <li class="header-nav__item">
                            <a class="header-nav__link" href="/register">会員登録</a>
                        </li>
                        <li class="header-nav__item">
                            <a class="header-nav__link" href="/items/search">商品検索</a>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>
</header>

<main>
    @yield('content')
</main>
</body>

</html>
