@extends('layouts.layout')

@section('title', 'Welcome')

@section('content')
    <form action="{{ route('search_groups.store') }}" method="POST">
        @csrf

        <input type="hidden" name="case_id" value="{{ $case->case_id }}">

        <h2>Create Search Group</h2>

        @if (!empty($selectedLeaderId))
            <div style="margin: 8px 0; padding:8px; border-radius:6px; background:#e6f2ff; color:#0b5394;">
                Selected Leader ID: <strong>{{ $selectedLeaderId }}</strong>
            </div>
            <input type="hidden" name="leader_id" value="{{ $selectedLeaderId }}">
        @else
            <input type="hidden" name="leader_id" value="">
        @endif

        <label for="type">Group Type:</label>
        <select id="type" name="type" required>
            <option value="citizen">Citizen</option>
            <option value="terrainSpecial">Terrain Special</option>
        </select>
        <br><br>

        <div style=" margin-bottom: 10px;">
            <a href="{{ route('chooseLeader', ['case_id' => $case->case_id]) }}" style="background-color: #3b82f6; color: white; padding: 8px 12px; border-radius: 4px; text-decoration: none;">Assign Group Leader</a>
        </div>


        <label for="intensity">Intensity:</label>
        <select id="intensity" name="intensity" required>
            <option value="basic" selected>Basic</option>
            <option value="rigorous">Rigorous</option>
            <option value="extreme">Extreme</option>
        </select>
        <br><br>

        <label for="status">Status:</label>
        <select id="status" name="status" required>
            <option value="time_assigned">Time Assigned</option>
            <option value="time_unassigned">Time Unassigned</option>
        </select>
        <br><br>

            <label for="start_time">Start Time:</label>
            <input type="datetime-local" id="start_time" name="start_time">
            <br><br>

            <label for="duration">Duration (minutes):</label>
            <input type="number" id="duration" name="duration" min="0">
            <br><br>

        <label for="report_back_time">Report Back Time:</label>
        <input type="datetime-local" id="report_back_time" name="report_back_time">
        <br><br>

        <label for="max_volunteers">Max Volunteers:</label>
        <input type="number" id="max_volunteers" name="max_volunteers" min="1">
        <br><br>

    <input type="hidden" id="available_volunteer_slots" name="available_volunteer_slots" value="">

        <script>
            // Listen for input changes
            const maxInput = document.getElementById('max_volunteers');
            const availInput = document.getElementById('available_volunteer_slots');
            const sync = () => { availInput.value = maxInput.value || ''; };
            maxInput.addEventListener('input', sync);
            sync();
        </script>

        <label for="instruction">Instructions:</label><br>
        <textarea id="instruction" name="instruction" rows="3" cols="40" placeholder="Enter group instructions..."></textarea>
        <br><br>

        <label for="allocated_lat">Allocated Latitude:</label>
        <input type="number" step="0.0000001" id="allocated_lat" name="allocated_lat">
        <br><br>

        <label for="allocated_lng">Allocated Longitude:</label>
        <input type="number" step="0.0000001" id="allocated_lng" name="allocated_lng">
        <br><br>

        <label for="radius">Search Radius (meters):</label>
        <input type="number" id="radius" name="radius" min="0">
        <br><br>

        <button type="submit" style="background-color:#10b981; color:white; padding:8px 14px; border:none; border-radius:6px;">
            Create Search Group
        </button>
    </form>

@endsection