@extends('layouts.layout')

@section('title', 'Edit Case')

@section('content')
    <form action="{{ route('cases.update', $case->case_id) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="{{ $case->title }}" required><br><br>

        <label for="case_type">Case Type:</label>
        <select id="case_type" name="case_type" required>
            <option value="missing" {{ $case->case_type == 'missing' ? 'selected' : '' }}>Missing Person</option>
        </select><br><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required>{{ $case->description }}</textarea><br><br>

        <label for="status">Status:</label>
        <select id="status" name="status" required>
            <option value="active" {{ $case->status == 'active' ? 'selected' : '' }}>Active</option>
            <option value="under_investigation" {{ $case->status == 'under_investigation' ? 'selected' : '' }}>Under Investigation</option>
            <option value="resolved" {{ $case->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
            <option value="closed" {{ $case->status == 'closed' ? 'selected' : '' }}>Closed</option>
        </select><br><br>

        <label for="urgency">Urgency:</label>
        <select id="urgency" name="urgency" required>
            <option value="low" {{ $case->urgency == 'low' ? 'selected' : '' }}>Low</option>
            <option value="medium" {{ $case->urgency == 'medium' ? 'selected' : '' }}>Medium</option>
            <option value="high" {{ $case->urgency == 'high' ? 'selected' : '' }}>High</option>
            <option value="critical" {{ $case->urgency == 'critical' ? 'selected' : '' }}>Critical</option>
            <option value="national" {{ $case->urgency == 'national' ? 'selected' : '' }}>National</option>
        </select><br><br>

        <label for="coverage_lat">Coverage Latitude:</label>
        <input type="text" id="coverage_lat" name="coverage_lat" value="{{ $case->coverage_lat }}" required><br><br>

        <label for="coverage_lng">Coverage Longitude:</label>
        <input type="text" id="coverage_lng" name="coverage_lng" value="{{ $case->coverage_lng }}" required><br><br>

        <label for="coverage_radius">Coverage Radius (meters):</label>
        <input type="number" id="coverage_radius" name="coverage_radius" value="{{ $case->coverage_radius }}" min="0"><br><br>

        <button type="button" onclick="toggleMap()" style="background-color:#3b82f6; color:white; padding:8px 14px; border:none; border-radius:6px;">
            🗺️ Pick Location from Map
        </button>

        <div id="map" style="height: 400px; width: 100%; margin-top: 15px; display:none; border-radius:8px;"></div>

        <br><br>
        <button type="submit" style="background-color:#10b981; color:white; padding:8px 14px; border:none; border-radius:6px;">
            Update Case
        </button>
    </form>
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
