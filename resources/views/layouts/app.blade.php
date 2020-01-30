<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Fruit of Things') }}</title>

    <!-- Scripts --> {{--app.js includes jQuery, Popper, Bootstrap and chart.js (check resources > js > app.js)--}}
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles --> {{--app.css includes bootstrap (check resources > css > app.css)--}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/index.css') }}" rel="stylesheet">
</head>
<body>
<div id='app'></div>{{--VUE error fix--}}
<header>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <a class="navbar-brand" href="https://www.pcfruit.be/nl">
            <img src="{{ asset('img/logo.png') }}" height="50" alt="logo">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link{{ Request::path() == '/' ? ' active' : '' }}">{{ __('Home') }}</a>
                </li>
                @auth
                    <li class="nav-item">
                        <a href="{{ route('fruits.index') }}" class="nav-link{{ Request::path() == 'fruit_types' ? ' active' : '' }}">{{ __('Fruitsoorten') }}</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('fields.index') }}" class="nav-link{{ Request::path() == 'fields' ? ' active' : '' }}">{{ __('Velden') }}</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('modules.index') }}" class="nav-link{{ Request::path() == 'modules' ? ' active' : '' }}">{{ __('Modules') }}</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('sensors.index') }}" class="nav-link{{ Request::path() == 'sensors' ? ' active' : '' }}">{{ __('Sensoren') }}</a>
                    </li>
                @endauth
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('profile.index') }}">{{ __('Profiel') }}</a>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                  style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </nav>
</header>

<div id="content">
    @yield('content')
</div>

<footer>
    <p>&copy; Copyright {{ date('Y') }} Fruit Of Things</p>
</footer>

<script src="{{ asset('js/index.js') }}" defer></script>

</body>
</html>