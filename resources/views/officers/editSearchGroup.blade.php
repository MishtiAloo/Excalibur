@extends('layouts.layout')

@section('title', 'Edit Search Group')

@section('content')
    <form action="{{ route('search_groups.update', $group->group_id) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="type">Group Type:</label>
        <select id="type" name="type" required>
            <option value="citizen" {{ $group->type === 'citizen' ? 'selected' : '' }}>Citizen</option>
            <option value="terrainSpecial" {{ $group->type === 'terrainSpecial' ? 'selected' : '' }}>Terrain Special</option>
        </select>
        <br><br>

        <label for="intensity">Intensity:</label>
        <select id="intensity" name="intensity" required>
            <option value="basic" {{ $group->intensity === 'basic' ? 'selected' : '' }}>Basic</option>
            <option value="rigorous" {{ $group->intensity === 'rigorous' ? 'selected' : '' }}>Rigorous</option>
            <option value="extreme" {{ $group->intensity === 'extreme' ? 'selected' : '' }}>Extreme</option>
        </select>
        <br><br>

        <label for="status">Status:</label>
        <select id="status" name="status" required>
            @foreach (['active','paused','completed','time_assigned','time_unassigned'] as $st)
                <option value="{{ $st }}" {{ $group->status === $st ? 'selected' : '' }}>{{ str_replace('_',' ', ucfirst($st)) }}</option>
            @endforeach
        </select>
        <br><br>

        <label for="start_time">Start Time:</label>
        <input type="datetime-local" id="start_time" name="start_time" value="{{ $group->start_time ? \Carbon\Carbon::parse($group->start_time)->format('Y-m-d\TH:i') : '' }}">
        <br><br>

        <label for="duration">Duration (minutes):</label>
        <input type="number" id="duration" name="duration" min="0" value="{{ $group->duration }}">
        <br><br>

        <label for="report_back_time">Report Back Time:</label>
        <input type="datetime-local" id="report_back_time" name="report_back_time" value="{{ $group->report_back_time ? \Carbon\Carbon::parse($group->report_back_time)->format('Y-m-d\TH:i') : '' }}">
        <br><br>

        <label for="max_volunteers">Max Volunteers:</label>
        <input type="number" id="max_volunteers" name="max_volunteers" min="1" value="{{ $group->max_volunteers }}">
        <br><br>

        <label for="available_volunteer_slots">Available Slots:</label>
        <input type="number" id="available_volunteer_slots" name="available_volunteer_slots" min="0" value="{{ $group->available_volunteer_slots }}">
        <br><br>

        <label for="instruction">Instructions:</label><br>
        <textarea id="instruction" name="instruction" rows="3" cols="40" placeholder="Enter group instructions...">{{ $group->instruction }}</textarea>
        <br><br>

        <label for="allocated_lat">Allocated Latitude:</label>
        <input type="number" step="0.0000001" id="allocated_lat" name="allocated_lat" value="{{ $group->allocated_lat }}">
        <br><br>

        <label for="allocated_lng">Allocated Longitude:</label>
        <input type="number" step="0.0000001" id="allocated_lng" name="allocated_lng" value="{{ $group->allocated_lng }}">
        <br><br>

        <label for="radius">Search Radius (meters):</label>
        <input type="number" id="radius" name="radius" min="0" value="{{ $group->radius }}">
        <br><br>

        <button type="submit" style="background-color:#10b981; color:white; padding:8px 14px; border:none; border-radius:6px;">
            Update Search Group
        </button>
    </form>
@endsection
