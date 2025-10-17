@extends('layouts.layout')

@section('title', 'Login')

@section('content')
    <form method="POST" action="{{ route('login.submit') }}">
        @csrf

        {{-- Display all errors --}}
        @if ($errors->any())
            <div class="error-messages">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Display success message --}}
        @if(session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif


        <h1>Login</h1>
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <input type="checkbox" id="remember" name="remember_me">
        <label for="remember">Remember Me</label>

        <button type="submit">Login</button>
@endsection