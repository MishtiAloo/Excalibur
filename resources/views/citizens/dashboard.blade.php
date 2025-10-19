@extends('layouts.layout')

@section('title', 'Welcome')

@section('content')
    join as a volunteer message with a short description and a button to go to volunteer profile page
    <h1>helo</h1>
    <h2>Welcome citizen</h2>
    <p>Join us as a volunteer to make a difference in your community.</p>
    <a href="{{ route('profile.page') }}">
        <button style="background-color:#3b82f6; color:white; padding:8px 14px; border:none; border-radius:6px;">
            Join as Volunteer
        </button>
    </a>
@endsection