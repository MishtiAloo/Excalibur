@extends('layouts.layout')
@section('title', 'Sign Up')

@section('content')
<div style="min-height:80vh; display:flex; justify-content:center; align-items:center; padding:40px 20px;">
    <div style="background-color:#1e1e1e; padding:40px 30px; border-radius:16px; width:100%; max-width:480px; box-shadow:0 6px 20px rgba(0,0,0,0.5);">
        
        {{-- Page Title --}}
        <h1 style="color:#fed008; text-align:center; margin-bottom:24px; font-weight:600; font-size:2.4rem;">Create Your Account</h1>

        {{-- Display all errors --}}
        @if ($errors->any())
            <div style="background:#ef4444; color:#fff; padding:10px; border-radius:8px; margin-bottom:16px;">
                <ul style="margin:0; padding-left:18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Signup Form --}}
        <form method="POST" action="{{ route('signup') }}">
            @csrf

            <div style="margin-bottom:16px;">
                <label for="name" style="color:#d1d5db; display:block; margin-bottom:6px;">Full Name</label>
                <input type="text" id="name" name="name" required
                       style="width:100%; padding:10px 14px; border-radius:8px; border:none; background:#333; color:#fff; font-size:16px;">
            </div>

            <div style="margin-bottom:16px;">
                <label for="email" style="color:#d1d5db; display:block; margin-bottom:6px;">Email</label>
                <input type="email" id="email" name="email" required
                       style="width:100%; padding:10px 14px; border-radius:8px; border:none; background:#333; color:#fff; font-size:16px;">
            </div>

            <div style="margin-bottom:16px;">
                <label for="password" style="color:#d1d5db; display:block; margin-bottom:6px;">Password</label>
                <input type="password" id="password" name="password" required
                       style="width:100%; padding:10px 14px; border-radius:8px; border:none; background:#333; color:#fff; font-size:16px;">
            </div>

            <div style="margin-bottom:16px;">
                <label for="nid" style="color:#d1d5db; display:block; margin-bottom:6px;">NID</label>
                <input type="text" id="nid" name="nid"
                       style="width:100%; padding:10px 14px; border-radius:8px; border:none; background:#333; color:#fff; font-size:16px;">
            </div>

            <div style="margin-bottom:16px;">
                <label for="phone" style="color:#d1d5db; display:block; margin-bottom:6px;">Phone (optional)</label>
                <input type="text" id="phone" name="phone"
                       style="width:100%; padding:10px 14px; border-radius:8px; border:none; background:#333; color:#fff; font-size:16px;">
            </div>

            <div style="margin-bottom:20px;">
                <label for="role" style="color:#d1d5db; display:block; margin-bottom:6px;">Select Role (optional)</label>
                <select id="role" name="role" style="width:100%; padding:10px 14px; border-radius:8px; border:none; background:#333; color:#fff; font-size:16px;">
                    <option value="">Select Role</option>
                    <option value="citizen">Citizen</option>
                </select>
            </div>

            <button type="submit" style="width:100%; background-color:#10b981; color:white; padding:12px 0; font-size:16px; font-weight:600; border:none; border-radius:10px; cursor:pointer; transition:all 0.3s;">
                Sign Up
            </button>

            <div style="text-align:center; margin-top:16px; color:#d1d5db; font-size:14px;">
                Already have an account? 
                <a href="{{ route('login') }}" style="color:#3b82f6; text-decoration:none;">Login here</a>
            </div>
        </form>
    </div>
</div>

<style>
    input:focus, select:focus {
        outline:none;
        box-shadow:0 0 8px rgba(254,208,8,0.5);
        transform:scale(1.02);
    }
    button:hover {
        transform:translateY(-2px);
        box-shadow:0 6px 12px rgba(16,185,129,0.4);
    }
</style>
@endsection
