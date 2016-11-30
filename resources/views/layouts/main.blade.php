<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <link href="/css/app.css" rel="stylesheet">
    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!}
    </script>
</head>
<body>

<nav class="main-navbar">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Переключити навігацію</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ url('/') }}">Петиції КНЕУ</a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="{{ active_class(if_route('petitions.index.open')) }}"><a href="{{ route('petitions.index.open') }}">Триває збір підписів</a></li>
                <li class="{{ active_class(if_route('petitions.index.successful')) }}"><a href="{{ route('petitions.index.successful') }}">На розгляді</a></li>
                <li class="{{ active_class(if_route('petitions.index')) }}"><a href="{{ route('petitions.index') }}">Всі петиції</a></li>

                <li class="{{ active_class(if_route('petitions.create')) }}"><a href="{{ route('petitions.create') }}">
                    <button class="new-petition-button">Подати петицію</button>
                </a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                @if(Auth::check())
                    <li><a><i class="user-icon"></i> Раді бачити, {{ session('userName', 'шановний пане') }}!</a></li>

                    <li><a href="#">
                        <form class="logout-form" action="{{ url('/logout') }}" method="POST">
                            {{ csrf_field() }}
                            <button><i class="logout-icon"></i> Вийти</button>
                        </form>
                    </a></li>
                @else
                    <li><a href="{{ route('login') }}">
                        <i class="login-icon"></i> Увійти
                    </a></li>
                @endif
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>


<div class="container">

    <div class="clearfix"></div>
    @yield('content')

    <footer>
        © 2016 КНЕУ
        <div id="developer">Розробка: Володимир Гужва, Антон Бережний</div>
    </footer>
</div>

    <script src="/js/app.js"></script>
</body>
</html>