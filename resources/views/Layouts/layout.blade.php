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

    {{-- Google Maps API --}}
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC7jztxro2FA6I2X7farGVYbPwiLOSWeaA&callback=initMap" async defer></script>
</head>
<body>
    {{-- Navbar based on app.css styles --}}
    <nav class="navbar">
        <div class="container">
            <a href="{{ url('/') }}" class="navbar-brand">Excalibur</a>
            <ul class="navbar-nav">
                <li><a href="{{ route('dashboardRouting') }}">Home</a></li>
                <li><a href="#features">Alerts</a></li>
                <li><a href="#contact">Contact</a></li>
                <li><a href="{{ route('profile.page') }}">Profile</a></li>
            </ul>

            @guest
                <div>
                    <form method="GET" style="all: unset;" action="{{ route('login') }}">
                        <button type="submit" style="background-color: rgb(90, 90, 233)">Login</button>
                    </form>
                    <form method="GET" style="all: unset;" action="{{ route('signupform') }}">
                        <button type="submit" style="background-color: rgb(48, 221, 48)">SignUp</button>
                    </form>
                </div>

            @endguest

            @auth
                <form method="POST" style="all: unset;" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" style="background-color: rgb(227, 96, 96)">Logout</button>
                </form>
            @endauth

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

    @yield('scripts')
</body>
</html>
