<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Fruit of Things') }}</title>

    <!-- Styles --> {{--app.css includes bootstrap (check resources > css > app.css)--}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/index.css') }}" rel="stylesheet">
</head>
<body>
<div id="app" class="d-flex">
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
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
                        <li class="nav-item">
                            <a href="{{ route('sensor_types.index') }}" class="nav-link{{ Request::path() == 'sensor_types' ? ' active' : '' }}">{{ __('Sensortypen') }}</a>
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
                    <!--Notifications-->
                        <li id="notifications" class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                Meldingen <span class="badge badge-danger">{{ count(auth()->user()->unreadNotifications) }}</span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right py-0" aria-labelledby="notificationDropdown">
                                @if (auth()->user()->notifications()->count() !== 0)
                                    @if (count(auth()->user()->unreadNotifications) !== 0)
                                        <div class="dropdown-item bg-dark text-white">
                                            Ongelezen
                                        </div>
                                        @foreach(auth()->user()->unreadNotifications as $notification)
                                            <hr class="my-0">
                                            <a class="dropdown-item py-3 text-{{ $notification->data['severity'] }}" href="{{ route('notification.destroy', array('id' => $notification->id)) }}">
                                                {{ $notification->data['text'] }}
                                            </a>
                                        @endforeach
                                    @endif
                                    <div class="dropdown-item bg-dark text-white">
                                        Gelezen
                                    </div>
                                    @foreach(auth()->user()->notifications()->whereNotNull('read_at')->get() as $notification)
                                        <hr class="my-0">
                                        <a class="dropdown-item py-3 text-{{ $notification->data['severity'] }}" href="{{ route('notification.destroy', array('id' => $notification->id)) }}">
                                            {{ $notification->data['text'] }}
                                        </a>
                                    @endforeach
                                @else
                                    <div class="dropdown-item py-3">
                                        Geen meldingen
                                    </div>
                                @endif
                            </div>
                        </li>
                        <!--Profile-->
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right py-0" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item py-3" href="{{ route('profile.index') }}">{{ __('Profiel') }}</a>
                                <hr class="my-0">
                                <a class="dropdown-item py-3" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                                <form id="logout-form py-3" action="{{ route('logout') }}" method="POST"
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
</div>{{--VUE error fix--}}

<!-- Scripts --> {{--app.js includes jQuery, Popper, Bootstrap and chart.js (check resources > js > app.js)--}}
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/index.js') }}" defer></script>
<script>
    $(function () {
        $('#notifications').click(function () {
            $.get("{{ route('notification.markasread') }}");
        });
    });
</script>

@yield('script')

</body>
</html>
