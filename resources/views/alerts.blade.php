@extends('layouts.layout')

@section('title', 'Nearby Alerts')

@section('content')
<div style="max-width:900px;margin:40px auto;padding:20px;background:rgba(0,0,0,0.05);border-radius:8px;">
    <h2>Alerts Near You</h2>
    <p>Showing active alerts within a customizable radius of your permanent location.</p>

    @if($alerts->isEmpty())
        <p>No alerts nearby.</p>
    @else
        <table style="width:100%; border-collapse: collapse;">
            <thead>
                <tr style="background:#f1f1f1;">
                    <th>Alert ID</th>
                    <th>Case</th>
                    <th>Type</th>
                    <th>Message</th>
                    <th>Expires</th>
                </tr>
            </thead>
            <tbody>
                @foreach($alerts as $a)
                    @php
                        $case = $cases->firstWhere('case_id', $a->case_id);
                    @endphp

                    @if($case)
                        <tr class="alert-row"
                            data-case-lat="{{ $case->coverage_lat }}"
                            data-case-lng="{{ $case->coverage_lng }}"
                            data-case-radius="{{ $case->coverage_radius }}"
                            style="cursor:pointer;"
                        >
                            <td>{{ $a->alert_id }}</td>
                            <td>{{ $a->case_id }}</td>
                            <td>{{ ucfirst($a->alert_type) }}</td>
                            <td>{{ $a->message }}</td>
                            <td>{{ $a->expires_at }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

        {{-- Radius control --}}
        <div style="margin-top:20px; text-align:center;">
            <label for="radiusSlider"><strong>Alert Radius:</strong> <span id="radiusValue">10</span> km</label><br>
            <input type="range" id="radiusSlider" min="1" max="50" value="10" step="1"
                style="width:60%; margin-top:10px;">
            <button id="updateRadiusBtn" style="margin-left:10px;">Update Radius</button>
        </div>

        {{-- Map --}}
        <div id="map" style="width:100%;height:400px;margin-top:20px;border-radius:8px;display:none;"></div>

        {{-- Hidden inputs for user permanent location --}}
        <input type="hidden" id="user_lat" value="{{ Auth::user()->permanent_lat }}">
        <input type="hidden" id="user_lng" value="{{ Auth::user()->permanent_lng }}">
    @endif
</div>
@endsection


@section('scripts')
<script>
    let map, userCircle, caseCircle, userMarker, caseMarker;
    let selectedCase = null;

    document.addEventListener("DOMContentLoaded", function () {
        const rows = document.querySelectorAll(".alert-row");
        const radiusSlider = document.getElementById("radiusSlider");
        const radiusValue = document.getElementById("radiusValue");
        const updateButton = document.getElementById("updateRadiusBtn");

        // Update display text while sliding
        radiusSlider.addEventListener("input", function() {
            radiusValue.textContent = this.value;
        });

        // When a row is clicked, store its case data and show map
        rows.forEach(row => {
            row.addEventListener("click", function () {
                selectedCase = {
                    lat: parseFloat(this.dataset.caseLat),
                    lng: parseFloat(this.dataset.caseLng),
                    radius: parseInt(this.dataset.caseRadius)
                };
                showMap(selectedCase.lat, selectedCase.lng, selectedCase.radius);
            });
        });

        // Update radius on button click
        updateButton.addEventListener("click", function () {
            if (!selectedCase) {
                alert("Please click on a case row first!");
                return;
            }
            const newRadiusKm = parseInt(radiusSlider.value);
            showMap(selectedCase.lat, selectedCase.lng, selectedCase.radius, newRadiusKm);
        });
    });

    function showMap(caseLat, caseLng, caseRadius, customRadiusKm = 10) {
        const mapDiv = document.getElementById("map");
        mapDiv.style.display = "block";

        const userLat = parseFloat(document.getElementById("user_lat").value);
        const userLng = parseFloat(document.getElementById("user_lng").value);

        const userCenter = { lat: userLat, lng: userLng };
        const caseCenter = { lat: caseLat, lng: caseLng };

        if (!map) {
            map = new google.maps.Map(mapDiv, {
                center: userCenter,
                zoom: 8,
            });
        }

        // Clear old markers/circles
        [userCircle, caseCircle, userMarker, caseMarker].forEach(obj => obj && obj.setMap(null));

        // User’s adjustable radius circle (Blue)
        userCircle = new google.maps.Circle({
            center: userCenter,
            radius: customRadiusKm * 1000, // convert km to meters
            map: map,
            fillColor: "#3b82f6",
            fillOpacity: 0.15,
            strokeColor: "#2563eb",
            strokeWeight: 2,
        });

        // Case coverage circle (Green)
        caseCircle = new google.maps.Circle({
            center: caseCenter,
            radius: caseRadius,
            map: map,
            fillColor: "#22c55e",
            fillOpacity: 0.15,
            strokeColor: "#16a34a",
            strokeWeight: 2,
        });

        // Markers
        userMarker = new google.maps.Marker({
            position: userCenter,
            map: map,
            label: "U",
            title: "Your Permanent Location",
        });

        caseMarker = new google.maps.Marker({
            position: caseCenter,
            map: map,
            label: "C",
            title: "Case Coverage Center",
        });

        // Fit map to show both
        const bounds = new google.maps.LatLngBounds();
        bounds.extend(userCenter);
        bounds.extend(caseCenter);
        map.fitBounds(bounds);
    }
</script>
@endsection
