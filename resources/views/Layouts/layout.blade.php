<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Excalibur')</title>

    {{-- Load Vite assets (dev + prod) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    {{-- Navbar based on app.css styles --}}
    <nav class="navbar">
        <div class="container">
            <a href="{{ url('/') }}" class="navbar-brand">Excalibur</a>
            <ul class="navbar-nav">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li><a href="#features">Features</a></li>
                <li><a href="#pricing">Pricing</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </div>
    </nav>

    <main class="container">
        @yield('content')
    </main>

    <footer>
        <div class="container">
            <p>&copy; {{ date('Y') }} Excalibur. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
