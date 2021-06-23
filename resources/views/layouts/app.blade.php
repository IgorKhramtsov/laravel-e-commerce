<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">



    @auth
    <script> window.Laravel = <?php echo json_encode(['api_token' => Auth::user()->api_token]) ?> </script>
    @endauth
    @guest
        <script> window.Laravel = <?php echo json_encode(['api_token' => '']) ?> </script>
    @endguest

    @yield('js-data')

    <script src="{{ mix('js/app.js') }}" defer></script>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app" >
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}"> {{ config('app.name', 'Laravel') }} </a>
                @guest
                    <button class="button text">
                        <a href="{{ route('login') }}"><i class="fa fa-sign-in"></i></a>
                    </button>
                @endguest
                @auth
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <button class="button text"><i class="fa fa-sign-out"></i></button>
                    </form>
                        @if(Auth::user()->isAdmin())
                            <button class="button text">
                                <a href="{{ route('admin') }}"><i class="fa fa-cogs"></i></a>
                            </button>
                        @endif
                @endauth
                @if(!(Request::is('cart/*') | Request::is('cart')))
                    <cart-nav-icon  :url="'{{ route('cart') }}'"></cart-nav-icon>
                @endif
            </div>
        </nav>
            @yield('content')
    </div>
</body>
</html>
