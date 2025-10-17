@extends('layouts.layout')

@section('title', 'Welcome')

@section('content')
    <h1>User Profile</h1>

    <p><strong>Name:</strong> {{ $user->name }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Phone:</strong> {{ $user->phone }}</p>
    <p><strong>NID:</strong> {{ $user->nid }}</p>
    <p><strong>Role:</strong> {{ $user->role }}</p>
    <p><strong>Status:</strong> {{ $user->status }}</p>
    <p><strong>Info Credibility:</strong> {{ $user->info_credibility }}</p>
    <p><strong>Responsiveness:</strong> {{ $user->responsiveness }}</p>

    <p><strong>Permanent Location:</strong> ({{ $user->permanent_lat }}, {{ $user->permanent_lng }})</p>
    <button type="button" onclick="showLocationOnMap({{ $user->permanent_lat ?? 0 }}, {{ $user->permanent_lng ?? 0 }})">
        See Permanent Location on Map
    </button>

    <p><strong>Current Location:</strong> ({{ $user->current_lat }}, {{ $user->current_lng }})</p>
    <button type="button" onclick="showLocationOnMap({{ $user->current_lat ?? 0 }}, {{ $user->current_lng ?? 0 }})">
        See Current Location on Map
    </button>

    <form method="GET" action="{{ route('profile.edit', $user->id) }}">
        <button type="submit" style="background-color: rgb(90, 90, 233)">Edit Profile</button>
    </form>

    <div id="map" style="height: 400px; width: 100%; margin-top: 20px;"></div>
@endsection

@section('scripts')
    {{-- Google Maps API
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap" async defer></script> --}}

    <script>
        let map;
        let marker;

        // Initialize map (centered globally first)
        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                center: { lat: 0, lng: 0 },
                zoom: 2,
            });
        }

        // Show given coordinates on the map
        function showLocationOnMap(lat, lng) {
            if (!lat || !lng) {
                alert("This user has no location data.");
                return;
            }

            const position = { lat: parseFloat(lat), lng: parseFloat(lng) };

            map.setCenter(position);
            map.setZoom(15);

            if (marker) marker.setMap(null); // clear previous marker

            marker = new google.maps.Marker({
                position: position,
                map: map,
                title: "User Location",
            });
        }
    </script>
@endsection
