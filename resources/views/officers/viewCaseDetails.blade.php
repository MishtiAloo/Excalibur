@extends('layouts.layout')

@section('title', 'Case Details')

@section('content')
<div style="max-width: 900px; margin: 40px auto; border-radius: 16px; box-shadow: 0 0 12px rgba(0,0,0,0.1); padding: 30px;">

    <h1 style="text-align: center; color: #ffffff; margin-bottom: 20px;">Case Details</h1>
    <hr style="margin-bottom: 25px;">

    {{-- Case Info --}}
    <h2 style="color: #ffffff;">Case Information</h2>
    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <tr><th style="text-align:left; width: 30%;">Case ID</th><td>{{ $case->case_id }}</td></tr>
        <tr><th>Title</th><td>{{ $case->title }}</td></tr>
        <tr><th>Type</th><td>{{ ucfirst($case->case_type) }}</td></tr>
        <tr><th>Description</th><td>{{ $case->description ?? 'N/A' }}</td></tr>
        <tr><th>Status</th><td>{{ ucfirst($case->status) }}</td></tr>
        <tr>
            <th>Urgency</th>
            <td style="
                background-color:
                    {{ $case->urgency == 'national' ? 'rgb(153, 0, 0)' :
                       ($case->urgency == 'high' ? 'rgb(227, 96, 96)' :
                       ($case->urgency == 'medium' ? 'rgb(255, 255, 48)' :
                       'rgb(48, 221, 48)')) }};
                color: {{ in_array($case->urgency, ['high', 'national']) ? 'white' : 'black' }};
                padding: 4px 8px;
                border-radius: 6px;
                display: inline-block;
            ">
                {{ ucfirst($case->urgency) }}
            </td>
        </tr>

        {{-- 🔽 Coverage row with onclick --}}
        <tr style="cursor: pointer; background-color: rgba(59,130,246,0.15);"
            onclick="showLocationOnMap({{ $case->coverage_lat ?? 0 }}, {{ $case->coverage_lng ?? 0 }})">
            <th>Coverage (Lat, Lng)</th>
            <td>
                {{ $case->coverage_lat }}, {{ $case->coverage_lng }}
                <span style="color:#3b82f6; font-weight: bold; margin-left: 6px;">(View on Map)</span>
            </td>
        </tr>
        <tr>
            <th>Coverage Radius</th>
            <td>{{ $case->coverage_radius ? $case->coverage_radius.' m' : '—' }}</td>
        </tr>

        <tr><th>Created At</th><td>{{ $case->created_at }}</td></tr>
    </table>

    {{-- 🗺️ Map container --}}
    <div id="map" style="height: 400px; width: 100%; margin-top: 20px; display:none; border-radius: 8px;"></div>

    <hr style="margin: 25px 0;">

    {{-- Creator Info --}}
    <h2 style="color: #ffffff;">Creator Information</h2>
    @if ($case->creator)
    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <tr><th style="text-align:left; width: 30%;">Name</th><td>{{ $case->creator->name }}</td></tr>
        <tr><th>Phone</th><td>{{ $case->creator->phone }}</td></tr>
    </table>
    @else
        <p style="color: gray;">Creator information not available.</p>
    @endif

    <hr style="margin: 25px 0;">

    {{-- Search Groups --}}
    <h2 style="color: #ffffff;">Search Groups</h2>

    @if (Auth::user()->role === 'officer' || Auth::user()->role === 'admin')
        <div style=" margin-bottom: 10px;">
            <a href="{{ route('search-groups.create', ['case_id' => $case->case_id]) }}" style="background-color: #3b82f6; color: white; padding: 8px 12px; border-radius: 4px; text-decoration: none;">+ Add Search Group</a>
        </div>
    @endif


    @if (!empty($case->searchGroups) && count($case->searchGroups) > 0)
    <table style="width: 100%; border=1; border-collapse: collapse; margin-top: 10px;">
        <thead style="background-color: #f2f2f2;">
            <tr>
                <th>Group ID</th>
                <th>Leader ID</th>
                <th>Type</th>
                <th>Intensity</th>
                <th>Status</th>
                <th>Start Time</th>
                <th>Duration (min)</th>
                <th>Report Back</th>
                <th>Max Volunteers</th>
                <th>Available Slots</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($case->searchGroups as $group)
            <tr onclick="window.location='{{ route('search_groups.show', $group->group_id) }}'" style="cursor:pointer;">

                <td>{{ $group->group_id }}</td>
                <td>{{ $group->leader_id }}</td>
                <td>{{ ucfirst($group->type) }}</td>
                <td>{{ ucfirst($group->intensity) }}</td>
                <td>{{ str_replace('_',' ', ucfirst($group->status)) }}</td>
                <td>{{ $group->start_time ?? '—' }}</td>
                <td>{{ $group->duration ?? '—' }}</td>
                <td>{{ $group->report_back_time ?? '—' }}</td>
                <td>{{ $group->max_volunteers ?? '—' }}</td>
                <td>{{ $group->available_volunteer_slots ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>    
    @else
        <p style="color: rgb(129, 129, 129);">No search groups assigned yet.</p>
    @endif

    <div style="margin-top: 30px; text-align: center;">
        <a href="{{ route('dashboard.officer') }}" style="background-color: #3b82f6; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none;">← Back to All Cases</a>

        @if (Auth::user()->role === 'officer' || Auth::user()->role === 'admin')
            <a href="{{ route('cases.showEditPage', $case->case_id) }}" style="background-color: #10b981; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; margin-left: 15px;">Edit Case</a>
            <a href="{{ route('alerts.create.case', $case->case_id) }}" style="background-color: #ef4444; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; margin-left: 15px;">+ Add Alert</a>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    let map;
    let marker;
    let circle;

    // 🗺️ Initialize map
    function initMap() {
        map = new google.maps.Map(document.getElementById("map"), {
            center: { lat: 23.8103, lng: 90.4125 }, // Default center (Dhaka)
            zoom: 6,
        });

        // 🖱️ Allow clicking on map to pick new coordinates
        map.addListener("click", function (event) {
            const clickedLat = event.latLng.lat();
            const clickedLng = event.latLng.lng();

            alert(`Clicked location:\nLatitude: ${clickedLat}\nLongitude: ${clickedLng}`);

            // 🧭 Place or move marker
            if (marker) marker.setMap(null);
            marker = new google.maps.Marker({
                position: { lat: clickedLat, lng: clickedLng },
                map: map,
                title: "Selected Location",
            });

            // 🟢 Draw a circular area
            if (circle) circle.setMap(null);
            circle = new google.maps.Circle({
                center: { lat: clickedLat, lng: clickedLng },
                radius: 1000, // meters
                map: map,
                fillColor: "#3b82f6",
                fillOpacity: 0.2,
                strokeColor: "#2563eb",
                strokeWeight: 2,
            });

            // 💾 Optional: update form fields if present
            const latInput = document.getElementById("coverage_lat");
            const lngInput = document.getElementById("coverage_lng");
            if (latInput && lngInput) {
                latInput.value = clickedLat;
                lngInput.value = clickedLng;
            }
        });
    }

    // 📍 Show coverage area on map when clicking the “View on Map” row
    function showLocationOnMap(lat, lng) {
        if (!lat || !lng) {
            alert("This case has no coverage coordinates.");
            return;
        }

        document.getElementById("map").style.display = "block";

        const position = { lat: parseFloat(lat), lng: parseFloat(lng) };
        map.setCenter(position);
        map.setZoom(12);

        // 🧭 Show marker
        if (marker) marker.setMap(null);
        marker = new google.maps.Marker({
            position: position,
            map: map,
            title: "Coverage Area",
        });

        // 🟢 Draw circular coverage zone
        if (circle) circle.setMap(null);
        circle = new google.maps.Circle({
            center: position,
            radius: {{ $case->coverage_radius ?? 1000 }}, // meters
            map: map,
            fillColor: "#10b981",
            fillOpacity: 0.25,
            strokeColor: "#059669",
            strokeWeight: 2,
        });
    }
</script>
@endsection

