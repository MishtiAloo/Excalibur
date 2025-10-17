
@extends('layouts.layout')
@section('title', 'Sign Up')
@section('content')
    <form method="POST" action="{{ route('signup') }}">
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

        <h1>Sign Up</h1>
        <div>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div>
            <label for="nid">NID (optional):</label>
            <input type="text" id="nid" name="nid">
        </div>
        <div>
            <label for="phone">Phone (optional):</label>
            <input type="text" id="phone" name="phone">
        </div>
        <div>
            <label for="role">Role (optional):</label>
            <select id="role" name="role">
                <option value="">Select Role</option>
                <option value="citizen">Citizen</option>
                <option value="officer">Officer</option>
                <option value="volunteer">Volunteer</option>
                <option value="specialVolunteer">Special Volunteer</option>
                <option value="group_leader">Group Leader</option>
            </select>
        </div>

        <button type="submit">Sign Up</button>
    </form>
@endsection