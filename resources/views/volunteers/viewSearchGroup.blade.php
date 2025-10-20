@extends('layouts.layout')

@section('title', 'Search Group Details')

@section('content')
<div style="max-width: 900px; margin: 40px auto; border-radius: 16px; box-shadow: 0 0 12px rgba(0,0,0,0.1); padding: 30px;">

    <h1 style="text-align: center; color: #ffffff; margin-bottom: 20px;">Search Group Details</h1>
    <hr style="margin-bottom: 25px;">

    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <tr><th style="text-align:left; width: 30%;">Group ID</th><td>{{ $group->group_id }}</td></tr>
        <tr><th>Case ID</th><td>{{ $group->case_id }}</td></tr>
        <tr><th>Leader</th><td>{{ optional($group->leader)->name ?? '—' }} (ID: {{ $group->leader_id }})</td></tr>
        <tr><th>Type</th><td>{{ ucfirst($group->type) }}</td></tr>
        <tr><th>Intensity</th><td>{{ ucfirst($group->intensity) }}</td></tr>
        <tr><th>Status</th><td>{{ str_replace('_',' ', ucfirst($group->status)) }}</td></tr>
        <tr><th>Start Time</th><td>{{ $group->start_time ?? '—' }}</td></tr>
        <tr><th>Duration (min)</th><td>{{ $group->duration ?? '—' }}</td></tr>
        <tr><th>Report Back</th><td>{{ $group->report_back_time ?? '—' }}</td></tr>
        <tr><th>Max Volunteers</th><td>{{ $group->max_volunteers ?? '—' }}</td></tr>
        <tr><th>Available Slots</th><td>{{ $group->available_volunteer_slots ?? '—' }}</td></tr>
        <tr><th>Instruction</th><td>{{ $group->instruction ?? '—' }}</td></tr>
        <tr><th>Allocated (Lat, Lng)</th><td>{{ $group->allocated_lat }}, {{ $group->allocated_lng }}</td></tr>
        <tr><th>Radius</th><td>{{ $group->radius ? $group->radius.' m' : '—' }}</td></tr>
        <tr><th>Created At</th><td>{{ $group->created_at }}</td></tr>

        <div style="margin-top: 20px; text-align: center;">
            <button onclick="toggleMap()" style="background-color: #3b82f6; color: white; padding: 10px 20px; border-radius: 8px; border: none; cursor: pointer;">
                See on Map
            </button>
        </div>

        <!-- Hidden map container -->
        <div id="map" style="width: 100%; height: 400px; margin-top: 20px; display: none; border-radius: 12px; overflow: hidden;"></div>

        <!-- Hidden inputs for JS -->
        <input type="hidden" id="group_lat" value="{{ $group->allocated_lat }}">
        <input type="hidden" id="group_lng" value="{{ $group->allocated_lng }}">
        <input type="hidden" id="group_radius" value="{{ $group->radius }}">

        <input type="hidden" id="case_lat" value="{{ $case->coverage_lat ?? 23.8103 }}">
        <input type="hidden" id="case_lng" value="{{ $case->coverage_lng ?? 90.4125 }}">
        <input type="hidden" id="case_radius" value="{{ $case->coverage_radius ?? 1000 }}">

    </table>

        
    @php
        $isMember = $group->volunteers->contains('volunteer_id', Auth::user()->id);
    @endphp

    <div style="margin-top: 30px; text-align: center;">
        @if ($group->status === 'completed')
            <em style="color: gray;">The task of this search group has been completed.</em>

        @elseif ($group->status === 'active')
            @if ($isMember)
                <em style="color: gray;">You are already a member. The search is currently running.</em>
            @else
                <em style="color: gray;">This is a running search group. You cannot join now.</em>
            @endif

        @elseif ($group->status === 'paused' || $group->status === 'time_assigned' || $group->status === 'time_unassigned')
            @if ($isMember)
                <em style="color: gray;">You are already a member of this group.</em>
            @elseif ($group->available_volunteer_slots > 0)
                <form method="POST" action="{{ route('search-groups.members.add', $group->group_id) }}">
                    @csrf
                    <input type="hidden" name="volunteer_id" value="{{ Auth::user()->id }}">
                    <button type="submit" style="background-color: #10b981; color: white; padding: 10px 20px; border-radius: 8px; border: none; cursor: pointer;">
                        Join as Volunteer
                    </button>
                </form>
            @else
                <em style="color: gray;">No available volunteer slots.</em>
            @endif

        @else
            <em style="color: gray;">Group status not available.</em>
        @endif
    </div>




</div>
@endsection

@section('scripts')
<script>
    let map, groupCircle, caseCircle;

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
        const groupLat = parseFloat(document.getElementById("group_lat").value);
        const groupLng = parseFloat(document.getElementById("group_lng").value);
        const groupRadius = parseInt(document.getElementById("group_radius").value);

        const caseLat = parseFloat(document.getElementById("case_lat").value);
        const caseLng = parseFloat(document.getElementById("case_lng").value);
        const caseRadius = parseInt(document.getElementById("case_radius").value);

        const center = { lat: caseLat || 23.8103, lng: caseLng || 90.4125 };

        map = new google.maps.Map(document.getElementById("map"), {
            center: center,
            zoom: 11,
        });

        // Case coverage area (large circle)
        caseCircle = new google.maps.Circle({
            center: { lat: caseLat, lng: caseLng },
            radius: caseRadius,
            map: map,
            fillColor: "#22c55e",
            fillOpacity: 0.15,
            strokeColor: "#16a34a",
            strokeWeight: 2,
        });

        // Group allocation circle (smaller one)
        groupCircle = new google.maps.Circle({
            center: { lat: groupLat, lng: groupLng },
            radius: groupRadius,
            map: map,
            fillColor: "#3b82f6",
            fillOpacity: 0.25,
            strokeColor: "#2563eb",
            strokeWeight: 2,
        });

        // Add markers for both
        new google.maps.Marker({
            position: { lat: groupLat, lng: groupLng },
            map: map,
            label: "G",
            title: "Group Center",
        });

        new google.maps.Marker({
            position: { lat: caseLat, lng: caseLng },
            map: map,
            label: "C",
            title: "Case Coverage Center",
        });

        // Fit map to include both circles
        const bounds = new google.maps.LatLngBounds();
        bounds.extend({ lat: groupLat, lng: groupLng });
        bounds.extend({ lat: caseLat, lng: caseLng });
        map.fitBounds(bounds);
    }
</script>
@endsection

