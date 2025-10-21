@extends('layouts.layout')

@section('title', 'Profile')

@section('content')
<div class="container fade-in">

    {{-- Success + Error Messages --}}
    @if (session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="error-messages">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>        
    @endif

    {{-- Profile Header --}}
    <div class="card" style="text-align:center; padding:2.5rem 2rem; margin-bottom:2rem;">
        <h1 style="font-size:2.2rem; margin-bottom:0.5rem;"> {{ $user->name }}</h1>
        <p style="color:var(--accent-yellow); font-size:1.1rem; margin-bottom:1rem;">
            {{ ucfirst($user->role) }}
        </p>
        <hr style="border:1px solid var(--bg-gray); margin:1.5rem 0;">
        <div style="display:flex; flex-wrap:wrap; gap:2rem; justify-content:center;">
            <div>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Phone:</strong> {{ $user->phone }}</p>
                <p><strong>NID:</strong> {{ $user->nid }}</p>
            </div>
            <div>
                <p><strong>Status:</strong> {{ ucfirst($user->status) }}</p>
                <p><strong>Info Credibility:</strong> {{ $user->info_credibility }}</p>
                <p><strong>Responsiveness:</strong> {{ $user->responsiveness }}</p>
            </div>
        </div>
    </div>

    {{-- Location Section --}}
    <div class="card" style="margin-bottom:2rem;">
        <h2 style="margin-bottom:1rem;">Location Information</h2>

        <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(250px,1fr)); gap:1.5rem;">
            <div>
                <h4>Permanent Location</h4>
                <p>({{ $user->permanent_lat }}, {{ $user->permanent_lng }})</p>
                <button type="button" onclick="showLocationOnMap({{ $user->permanent_lat ?? 0 }}, {{ $user->permanent_lng ?? 0 }})">
                    See on Map
                </button>
            </div>
            <div>
                <h4>Current Location</h4>
                <p>({{ $user->current_lat }}, {{ $user->current_lng }})</p>
                <button type="button" onclick="showLocationOnMap({{ $user->current_lat ?? 0 }}, {{ $user->current_lng ?? 0 }})">
                    See on Map
                </button>
            </div>
        </div>

        <div id="map" style="height: 400px; width: 100%; margin-top: 20px; border-radius: 12px; overflow:hidden;"></div>
    </div>

    {{-- Role Upgrade Section --}}
    <div class="card" style="margin-bottom:2rem;">
        <h2 style="margin-bottom:1rem;">Role Change</h2>

        @php
            $volunteer = $user->volunteer;
            $special = $volunteer ? $volunteer->specialVolunteer : null;
        @endphp

        {{-- Citizen → Volunteer --}}
        @if ($user->role == 'citizen')
            @if ($volunteer)
                @if ($volunteer->vetting_status === 'pending')
                    <p style="color: orange;">Your volunteer application is pending approval.</p>
                @elseif ($volunteer->vetting_status === 'approved')
                    <p style="color: green;">✅ You are already a volunteer.</p>
                @endif
            @else
                <form method="POST" action="{{ route('volunteer.apply') }}">
                    @csrf
                    <input type="hidden" name="volunteer_id" value="{{ $user->id }}">
                    <button type="submit" style="background-color:rgb(48,221,48);">Sign Up as Volunteer</button>
                </form>
            @endif
        @endif

        {{-- Volunteer → Special Volunteer --}}
        @if ($user->role == 'volunteer')
            @if ($special)
                @if ($special->vetting_status === 'pending')
                    <p style="color: orange;">Your special volunteer application is pending approval.</p>
                @elseif ($special->vetting_status === 'approved')
                    <p style="color: green;">✅ You are already a special volunteer.</p>
                @endif
            @else
                <form method="POST" action="{{ route('specialvolunteer.apply') }}">
                    @csrf
                    <input type="hidden" name="volunteer_id" value="{{ $user->id }}">
                    <select name="terrain_type" style="max-width:220px;">
                        <option value="water">Water</option>
                        <option value="forest">Forest</option>
                        <option value="hilltrack">Hill Track</option>
                        <option value="urban">Urban</option>
                    </select>
                    <button type="submit" style="background-color:rgb(48,221,48);">Apply as Special Volunteer</button>
                </form>
            @endif
        @endif
    </div>

    {{-- Edit Button --}}
    <div style="text-align:center;">
        <form method="GET" action="{{ route('profile.edit', $user->id) }}">
            <button type="submit" style="background-color:#3b82f6; color:white; padding:0.6rem 1.5rem;">✏️ Edit Profile</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let map;
    let marker;

    function initMap() {
        map = new google.maps.Map(document.getElementById("map"), {
            center: { lat: 23.8103, lng: 90.4125 }, // Dhaka as default
            zoom: 5,
            mapTypeControl: false,
            streetViewControl: false,
        });
    }

    function showLocationOnMap(lat, lng) {
        if (!lat || !lng) {
            alert("This user has no location data.");
            return;
        }

        const position = { lat: parseFloat(lat), lng: parseFloat(lng) };
        map.setCenter(position);
        map.setZoom(14);

        if (marker) marker.setMap(null);
        marker = new google.maps.Marker({
            position: position,
            map: map,
            title: "User Location",
            animation: google.maps.Animation.DROP,
        });
    }
</script>
@endsection
