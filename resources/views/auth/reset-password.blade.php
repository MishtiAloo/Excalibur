@extends('layouts.layout')

@section('title', 'Reset Password')

@section('content')
<div style="min-height:80vh; display:flex; justify-content:center; align-items:center; padding:40px 20px;">

    <div style="background-color:#1e1e1e; padding:40px 30px; border-radius:16px; width:100%; max-width:480px; box-shadow:0 6px 20px rgba(0,0,0,0.5);">
        
        <h1 style="color:#fed008; text-align:center; margin-bottom:24px; font-size:28px;">Reset Password</h1>
        <p style="color:#d1d5db; text-align:center; margin-bottom:20px;">Enter your account email and choose a new password.</p>

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

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <div style="margin-bottom:16px;">
                <label for="email" style="color:#d1d5db; display:block; margin-bottom:6px;">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                       style="width:100%; padding:10px 14px; border-radius:8px; border:none; background:#333; color:#fff; font-size:16px;">
            </div>

            <div style="margin-bottom:16px;">
                <label for="new_password" style="color:#d1d5db; display:block; margin-bottom:6px;">New Password</label>
                <input type="password" id="new_password" name="new_password" required minlength="8"
                       style="width:100%; padding:10px 14px; border-radius:8px; border:none; background:#333; color:#fff; font-size:16px;">
                <small style="color:#9ca3af;">Minimum 8 characters</small>
            </div>

            <div style="margin-bottom:20px;">
                <label for="new_password_confirmation" style="color:#d1d5db; display:block; margin-bottom:6px;">Confirm New Password</label>
                <input type="password" id="new_password_confirmation" name="new_password_confirmation" required
                       style="width:100%; padding:10px 14px; border-radius:8px; border:none; background:#333; color:#fff; font-size:16px;">
            </div>

            <button type="submit" style="width:100%; background-color:#3b82f6; color:white; padding:12px 0; font-size:16px; font-weight:600; border:none; border-radius:10px; cursor:pointer; transition:all 0.3s;">
                Update Password
            </button>

            <div style="text-align:center; margin-top:16px;">
                <a href="{{ route('login') }}" style="color:#fed008; text-decoration:none; font-size:14px;">Back to Login</a>
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
