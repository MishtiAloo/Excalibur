@extends('layouts.layout')

@section('title', 'Welcome')

@section('content')
    <h1>Welcome to Your Profile Page</h1>
    <p>This is where you can view and edit your profile information.</p>

    <form method="POST" action="{{ route('users.update', $user->id) }}">
        @csrf
        @method('PUT')   
        
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="{{ $user->name }}"><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="{{ $user->email }}"><br>

        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" value="{{ $user->phone }}"><br>

        <label for="permanent_lat">Permanent Latitude:</label>
        <input type="text" id="permanent_lat" name="permanent_lat" value="{{ $user->permanent_lat }}"><br>

        <label for="permanent_lng">Permanent Longitude:</label>
        <input type="text" id="permanent_lng" name="permanent_lng" value="{{ $user->permanent_lng }}"><br>

        <button type="submit" style="background-color: rgb(90, 90, 233)">Update Profile</button>
    </form>

@endsection