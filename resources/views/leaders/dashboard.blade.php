@extends('layouts.layout')

@section('title', 'Welcome')

@section('content')
    <h1>Leader Dashboard</h1>

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