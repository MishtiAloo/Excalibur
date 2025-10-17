@extends('layouts.layout')

@section('title', 'Welcome')

@section('content')
    // show user information
    <h1>User Profile</h1>
    <p>Name: {{ $user->name }}</p>
    <p>Email: {{ $user->email }}</p>
    <p>Phone: {{ $user->phone }}</p>
    <p>NID: {{ $user->nid }}</p>
    <p>Role: {{ $user->role }}</p>
    <p>Status: {{ $user->status }}</p>
    <p>Info Credibility: {{ $user->info_credibility }}</p>
    <p>Responsiveness: {{ $user->responsiveness }}</p>
    <p>Permanent Location: ({{ $user->permanent_lat }}, {{ $user->permanent_lng }})</p>
    <p>Current Location: ({{ $user->current_lat }}, {{ $user->current_lng }})</p>
    <form method="GET" action="{{ route('profile.edit', $user->id) }}">
        <button type="submit" style="background-color: rgb(90, 90, 233)">Edit Profile</button>
    </form>
@endsection