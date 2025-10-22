@extends('layouts.layout')

@section('title', 'Login')

@section('content')
<div style="min-height:80vh; display:flex; justify-content:center; align-items:center; padding:40px 20px;">

    <div style="background-color:#1e1e1e; padding:40px 30px; border-radius:16px; width:100%; max-width:400px; box-shadow:0 6px 20px rgba(0,0,0,0.5);">
        
        {{-- Page Title --}}
        <h1 style="color:#fed008; text-align:center; margin-bottom:24px; font-weight:600; font-size:2.4rem;">Login</h1>

        {{-- Display errors --}}
        @if ($errors->any())
            <div class="error-messages" style="margin-bottom:16px;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Display success message --}}
        @if(session('success'))
            <div class="success-message" style="margin-bottom:16px;">
                {{ session('success') }}
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('login.submit') }}">
            @csrf

            <div style="margin-bottom:16px;">
                <label for="email" style="color:#d1d5db; display:block; margin-bottom:6px;">Email</label>
                <input type="email" id="email" name="email" required
                       style="width:100%; padding:10px 14px; border-radius:8px; border:none; background:#333; color:#fff; font-size:16px; transition:all 0.3s;">
            </div>

            <div style="margin-bottom:16px;">
                <label for="password" style="color:#d1d5db; display:block; margin-bottom:6px;">Password</label>
                <input type="password" id="password" name="password" required
                       style="width:100%; padding:10px 14px; border-radius:8px; border:none; background:#333; color:#fff; font-size:16px; transition:all 0.3s;">
            </div>

            <div style="display:flex; align-items:center; margin-bottom:20px; color:#d1d5db;">
                <input type="checkbox" id="remember" name="remember_me" style="margin-right:8px;">
                <label for="remember">Remember Me</label>
            </div>

            <button type="submit" style="width:100%; background-color:#3b82f6; color:white; padding:12px 0; font-size:16px; font-weight:600; border:none; border-radius:10px; cursor:pointer; transition:all 0.3s;">
                Login
            </button>

            <div style="text-align:center; margin-top:16px;">
                <a href="{{ route('password.request') }}" style="color:#fed008; text-decoration:none; font-size:14px;">Forgot Password?</a>
            </div>
        </form>
    </div>
</div>

<style>
    input:focus {
        outline:none;
        box-shadow:0 0 8px rgba(254,208,8,0.5);
        transform:scale(1.02);
    }
    button:hover {
        background-color:#2563eb;
        transform:translateY(-2px);
        box-shadow:0 6px 12px rgba(59,130,246,0.4);
    }
</style>
@endsection
