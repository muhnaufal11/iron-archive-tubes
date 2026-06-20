<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Iron Archive') }}</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
    {{-- <link href="{{ asset('css/style.css') }}" rel="stylesheet"> --}}
</head>
<body style="background-color: #F4F1EA;"> <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark shadow-sm" style="background-color: #3F3B2E;">
            <div class="container">
                <a class="navbar-brand fw-bold" href="{{ url('/') }}" style="font-family: 'Courier New', monospace;">
                    🪖 {{ config('app.name', 'Iron Archive') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link fw-bold" href="{{ route('vehicles.index') }}">
                                    DATABASE KENDARAAN
                                </a>
                            </li>

                            {{-- 🔥 LOGIKA ADMIN BARU (PAKAI ROLE) 🔥 --}}
                            @if(Auth::user()->role === 'admin')
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle text-warning fw-bold" href="#" role="button" data-bs-toggle="dropdown">
                                        ⭐ COMMAND CENTER
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('users.index') }}">👮‍♂️ Manage Personel</a></li>
                                        <li><a class="dropdown-item" href="{{ route('categories.index') }}">🏷️ Manage Kategori</a></li>
                                        <li><a class="dropdown-item" href="{{ route('nations.index') }}">🌍 Manage Negara</a></li>
                                    </ul>
                                </li>
                            @endif
                        @endauth
                    </ul>

                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    👤 {{ Auth::user()->name }} 
                                    
                                    {{-- Badge Admin --}}
                                    @if(Auth::user()->role === 'admin')
                                        <span class="badge bg-danger ms-1">CMDR</span>
                                    @endif
                                </a>

                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }} 🚪
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>