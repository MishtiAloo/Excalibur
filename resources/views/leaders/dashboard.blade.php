@extends('layouts.layout')

@section('title', 'Welcome')

@section('content')
    <!-- Welcome Card -->
    <div style="background:#282a3a; color: #fff; border-radius: 12px; padding: 20px; margin-bottom: 30px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
        <h1 style="margin: 0 0 10px;">Hello, {{ Auth::user()->name ?? 'Volunteer' }}!</h1>
        <h2 style="margin: 0; font-weight: normal; color:#fff">Welcome back to your dashboard</h2>
        <p style="margin-top: 10px; font-size: 14px; opacity: 0.9;">Here you can see active cases and your assigned search groups. Stay alert and take action responsibly.</p>
    </div>

    <h2 style="margin-top:24px;">Currently Running Searches</h2>
    @if($activeSearchGroups->isEmpty())
        <p>No active searches right now.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Search Group ID</th>
                    <th>Location</th>
                    <th>Start time</th>
                    <th>duration</th>
                    <th>Case File</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activeSearchGroups as $searchGroup)
                    <tr>
                        <td>{{ $searchGroup->group_id }}</td>
                        <td>{{ $searchGroup->allocated_lat }}, {{ $searchGroup->allocated_lng }}</td>
                        <td>{{ $searchGroup->start_time }}</td>
                        <td>{{ $searchGroup->duration }} minutes</td>
                        <td>{{ $searchGroup->caseFile->title }}</td>
                        <td>{{ $searchGroup->status }}</td>
                        <td>
                            <a href="{{ route('search_groups.show', $searchGroup) }}">Details</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- Alerts are managed by officers and shown on the officer dashboard. --}}


    <h2>Assigned Search Groups & Schedules</h2>
    <table>
        <thead>
            <tr>
                <th>Search Group ID</th>
                <th>Location</th>
                <th>Start time</th>
                <th>duration</th>
                <th>Case File</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assignedSearchGroups as $searchGroup)
                <tr>
                    <td>{{ $searchGroup->group_id }}</td>
                    <td>{{ $searchGroup->allocated_lat }}, {{ $searchGroup->allocated_lng }}</td>
                    <td>{{ $searchGroup->start_time }}</td>
                    <td>{{ $searchGroup->duration }} minutes</td>
                    <td>{{ $searchGroup->caseFile->title }}</td>
                    <td>{{ $searchGroup->status }}</td>
                    <td>
                        <a href="{{ route('search_groups.show', $searchGroup) }}">Search Group Details</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection