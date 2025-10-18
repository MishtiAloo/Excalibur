@extends('layouts.layout')

@section('title', 'Add New Case')

@section('content')
    <div class="container fade-in">
        <h1>Add New Case</h1>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="error-messages">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('cases.store') }}">
            @csrf

            <input type="hidden" name="created_by" value="{{ auth()->user()->id }}">

            <label for="case_type">Case Type</label>
            <select name="case_type" id="case_type">
                <option value="missing">Missing Person</option>
            </select>

            <label for="title">Title</label>
            <input type="text" name="title" id="title" placeholder="Case title..." required>

            <label for="description">Description</label>
            <textarea name="description" id="description" rows="4" placeholder="Brief description..."></textarea>

            <label for="coverage_lat">Coverage Latitude</label>
            <input type="text" name="coverage_lat" id="coverage_lat" placeholder="e.g., 23.8103">

            <label for="coverage_lng">Coverage Longitude</label>
            <input type="text" name="coverage_lng" id="coverage_lng" placeholder="e.g., 90.4125">

            <label for="coverage_radius">Coverage Radius (meters)</label>
            <input type="number" name="coverage_radius" id="coverage_radius" min="0" placeholder="e.g., 1000">

            <button type="button" onclick="toggleMap()" style="background-color:#3b82f6; color:white; padding:8px 14px; border:none; border-radius:6px;">
            🗺️ Pick Location from Map
            </button>

            <div id="map" style="height: 400px; width: 100%; margin-top: 15px; display:none; border-radius:8px;"></div>

            <label for="urgency">Urgency Level</label>
            <select name="urgency" id="urgency">
                <option value="low">Low</option>
                <option value="medium" selected>Medium</option>
                <option value="high">High</option>
                <option value="critical">Critical</option>
                <option value="national">National</option>
            </select>

            <button type="submit">Submit Case</button>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    let map, marker, circle;

    function toggleMap() {
        const mapDiv = document.getElementById("map");
        if (mapDiv.style.display === "none") {
            mapDiv.style.display = "block";
            initMap();
        } else {
            mapDiv.style.display = "none";
        }
    }

    function initMap() {
        const latInput = document.getElementById("coverage_lat");
        const lngInput = document.getElementById("coverage_lng");
        const radiusInput = document.getElementById("coverage_radius");

        const lat = parseFloat(latInput.value) || 23.8103; // default Dhaka
        const lng = parseFloat(lngInput.value) || 90.4125;
        const radius = parseInt(radiusInput.value) || 1000;

        map = new google.maps.Map(document.getElementById("map"), {
            center: { lat, lng },
            zoom: 10,
        });

        marker = new google.maps.Marker({
            position: { lat, lng },
            map: map,
            draggable: false,
        });

        // Draw initial circle
        circle = new google.maps.Circle({
            center: { lat, lng },
            radius: radius,
            map: map,
            fillColor: "#3b82f6",
            fillOpacity: 0.2,
            strokeColor: "#2563eb",
            strokeWeight: 2,
        });

        // When user clicks on the map, update the inputs and circle
        map.addListener("click", (event) => {
            const clickedLat = event.latLng.lat();
            const clickedLng = event.latLng.lng();

            latInput.value = clickedLat.toFixed(6);
            lngInput.value = clickedLng.toFixed(6);

            if (marker) marker.setMap(null); // remove old marker

            marker = new google.maps.Marker({
                position: { lat: clickedLat, lng: clickedLng },
                map: map,
            });

            const newRadius = parseInt(radiusInput.value) || radius;
            if (circle) circle.setMap(null);
            circle = new google.maps.Circle({
                center: { lat: clickedLat, lng: clickedLng },
                radius: newRadius,
                map: map,
                fillColor: "#3b82f6",
                fillOpacity: 0.2,
                strokeColor: "#2563eb",
                strokeWeight: 2,
            });
        });
    }
</script>
@endsection

