@extends('layouts.layout')

@section('title', 'Welcome')

@section('content')
<div style="max-width: 1100px; margin: 40px auto; padding: 20px;">

    <!-- Hero Section -->
    <section style="text-align:center; margin-bottom:40px;">
        <h1 style="font-size:40px; color:#fed008; margin-bottom:10px;">Welcome, {{ Auth::user()->name }}</h1>
        <h2 style="font-size:24px; color:#d1d5db; margin-bottom:16px;">Join us as a volunteer to make a difference in your community.</h2>
        <a href="{{ route('profile.page') }}">
            <button style="background-color:#3b82f6; color:white; padding:10px 18px; border:none; border-radius:8px; font-weight:600; font-size:16px; cursor:pointer; transition: all 0.3s;">
                Join as Volunteer
            </button>
        </a>
    </section>

    <!-- Case Summary Section -->
    <div class="container fade-in" style="background-color:#1e1e1e; border-radius:16px; padding:30px; box-shadow: 0 4px 12px rgba(0,0,0,0.5);">
        <h2 style="color:#fed008; margin-bottom:12px;">Cases Near You (within 100km)</h2>
        <p style="color:#d1d5db; margin-bottom:28px;">Keep track of the status of cases in your area and see where your help is needed most.</p>

        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:20px;">
            <!-- Active -->
            <div style="background:#0b1220; border:1px solid #111827; border-radius:14px; padding:20px; text-align:center; transition: transform 0.3s, box-shadow 0.3s;">
                <h3 style="color:#3b82f6; font-size:18px; margin-bottom:8px;">Active</h3>
                <p class="count" style="color:#ffffff; font-size:28px; font-weight:700;">{{ $statusCounts['active'] }}</p>
            </div>

            <!-- Under Investigation -->
            <div style="background:#0b1220; border:1px solid #111827; border-radius:14px; padding:20px; text-align:center; transition: transform 0.3s, box-shadow 0.3s;">
                <h3 style="color:#f59e0b; font-size:18px; margin-bottom:8px;">Under Investigation</h3>
                <p class="count" style="color:#ffffff; font-size:28px; font-weight:700;">{{ $statusCounts['under_investigation'] }}</p>
            </div>

            <!-- Resolved -->
            <div style="background:#0b1220; border:1px solid #111827; border-radius:14px; padding:20px; text-align:center; transition: transform 0.3s, box-shadow 0.3s;">
                <h3 style="color:#10b981; font-size:18px; margin-bottom:8px;">Resolved</h3>
                <p class="count" style="color:#ffffff; font-size:28px; font-weight:700;">{{ $statusCounts['resolved'] }}</p>
            </div>

            <!-- Closed -->
            <div style="background:#0b1220; border:1px solid #111827; border-radius:14px; padding:20px; text-align:center; transition: transform 0.3s, box-shadow 0.3s;">
                <h3 style="color:#ef4444; font-size:18px; margin-bottom:8px;">Closed</h3>
                <p class="count" style="color:#ffffff; font-size:28px; font-weight:700;">{{ $statusCounts['closed'] }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Hover Effects -->
<style>
    div[style*="transition: transform"] {
        cursor: pointer;
    }
    div[style*="transition: transform"]:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(255, 235, 59, 0.25);
    }
    button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(59,130,246,0.4);
    }
</style>
@endsection
