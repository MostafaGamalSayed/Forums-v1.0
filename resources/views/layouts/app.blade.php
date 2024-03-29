<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Font awesome icons -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('argon/assets/css/nucleo-svg.css') }}" rel="stylesheet">
    <link href="{{ asset('argon/assets/css/argon.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto|Roboto+Condensed&display=swap" rel="stylesheet">
    <script>
        window.App = {!! json_encode([
            'signedIn'=> Auth::check(),
            'user' => Auth::User()
        ]) !!};
    </script>

    <style>
        [v-cloak]{ display: none;}
    </style>
    @yield('styles')
    <link href="{{ asset('css/vendor/trix.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/vendor/jquery.atwho.css') }}">
    <link href="{{ asset('css/atom-one-dark.css') }}" rel="stylesheet">
</head>
<body style="font-family: 'Roboto', sans-serif;">
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel bg-gradient-primary">
            <div class="container">
                <nav class="navbar navbar-light">
                    <a class="navbar-brand text-white" href="{{ url('/') }}">{{ config('app.name') }}</a>
                </nav>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdownBrowse" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Browse
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownBrowse">
                                <a class="dropdown-item" href="{{ route('thread.index') }}">
                                    All Threads
                                </a>
                                @auth
                                    <a class="dropdown-item" href="{{ route('thread.index', ['by' => auth()->user()->name]) }}">
                                        My Threads
                                    </a>
                                @endauth
                                <a class="dropdown-item" href="{{ route('thread.index', ['popular' => 1]) }}">
                                    Popular threads
                                </a>
                                <a class="dropdown-item" href="{{ route('thread.index', ['unanswered' => 1]) }}">
                                    Unanswered threads
                                </a>

                                <a class="dropdown-item" href="{{ route('thread.index', ['subscribed' => 1]) }}">
                                    Subscribed threads
                                </a>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('thread.create') }}">
                                New Thread
                            </a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Channels
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                @foreach($channels as $channel)
                                    <a class="dropdown-item" href="{{ route('channel.index', $channel->slug) }}">{{ $channel->name }}</a>
                                @endforeach
                            </div>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            <li class="nav-item">
                                @if (Route::has('register'))
                                    <a class="nav-link text-white" href="{{ route('register') }}">{{ __('Register') }}</a>
                                @endif
                            </li>
                        @else
                            <user-notification></user-notification>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle text-white font-weight-bold" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                                  <a class="dropdown-item" href="{{ route('user.profile', auth()->user()->name) }}">
                                      My profile
                                  </a>

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main>
            @yield('content')
            <flash></flash>
        </main>
    </div>
    <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('argon/assets/js/argon.js') }}"></script>
    @yield('scripts')
    <script src="{{ asset('js/highlight.pack.js') }}"></script>
    <script>hljs.initHighlightingOnLoad();</script>
</body>
</html>
