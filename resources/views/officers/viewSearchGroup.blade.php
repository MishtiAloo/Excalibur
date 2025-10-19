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
    </table>

    <div style="margin-top: 30px; text-align: center;">
        <a href="{{ route('search-groups.showEditPage', $group->group_id) }}" style="background-color: #10b981; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none;">Edit Group</a>
    </div>
</div>
@endsection
