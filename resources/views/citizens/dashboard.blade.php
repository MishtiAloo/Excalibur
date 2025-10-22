@extends('layouts.layout')

@section('title', 'Welcome')

@section('content')
    <h1>helo</h1>
    <h2>Welcome citizen</h2>
    <p>Join us as a volunteer to make a difference in your community.</p>
    <a href="{{ route('profile.page') }}">
        <button style="background-color:#3b82f6; color:white; padding:8px 14px; border:none; border-radius:6px;">
            Join as Volunteer
        </button>
    </a>

    <div class="container fade-in case-summary">
        <h2>Welcome, {{ Auth::user()->name }}</h2>
        <p>Here's an overview of cases near your area (within 100km).</p>

        <div class="case-status-row">
            <div class="status-box active">
                <h2>Active</h2>
                <p class="count">{{ $statusCounts['active'] }}</p>
            </div>

            <div class="status-box investigation">
                <h2>Under Investigation</h2>
                <p class="count">{{ $statusCounts['under_investigation'] }}</p>
            </div>

            <div class="status-box resolved">
                <h2>Resolved</h2>
                <p class="count">{{ $statusCounts['resolved'] }}</p>
            </div>

            <div class="status-box closed">
                <h2>Closed</h2>
                <p class="count">{{ $statusCounts['closed'] }}</p>
            </div>
        </div>
    </div>


@endsection