@extends('layouts.layout')

@section('title', 'Welcome')

@section('content')
    <!-- Welcome Card -->
    <div style="background:#2a1e24; color: #fff; border-radius: 12px; padding: 20px; margin-bottom: 30px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
        <h1 style="margin: 0 0 10px;">Hello, {{ Auth::user()->name ?? 'Volunteer' }}!</h1>
        <h2 style="margin: 0; font-weight: normal; color:#fff">Welcome back to your dashboard</h2>
        <p style="margin-top: 10px; font-size: 14px; opacity: 0.9;">Here you can see active cases and your assigned search groups. Stay alert and take action responsibly.</p>
    </div>

    <table>
        <h2>Active Cases Open for volunteering</h2>
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Coverage Location</th>
            <th>Coverage Radius</th>
            <th>Urgency</th>
            <th>actions</th>
        </tr>
        @foreach ($activeCases as $case)
            <tr>
                <td>{{ $case->title }}</td>
                <td>{{ $case->description }}</td>
                <td>({{ $case->coverage_lat }}, {{ $case->coverage_lng }})</td>
                <td>{{ $case->coverage_radius ? $case->coverage_radius.' m' : '—' }}</td>   
                <td style="background-color: 
                    {{ $case->urgency == 'national' || $case->urgency == 'high' 
                        ? 'rgb(227, 96, 96)' 
                        : ($case->urgency == 'medium' 
                            ? 'rgb(255, 255, 48)' 
                            : 'rgb(48, 221, 48)') 
                    }};
                    color: {{ $case->urgency == 'medium' ? '#000' : '#fff' }};
                    font-weight: bold;
                    text-align: center;
                ">
                    {{ ucfirst($case->urgency) }}
                </td>

                <td>
                    <form method="GET" action="{{ route('cases.show', $case->case_id) }}">
                        <button type="submit" style="background-color: rgb(90, 90, 233)">View Details</button>
                    </form>
            </tr>
        @endforeach
    </table>

    
    <table>
        <h2>Assigned Search Groups</h2>
        <tr>
            <th>Search Group ID</th>
            <th>Case Title</th>
            <th>Leader Name</th>
            <th>Leader cellphone</th>
            <th>Start time</th>
            <th>Duration</th>
            <th>Assigned Area</th>
            <th>Current Status</th>
            <th>actions</th>
        </tr>
        @foreach ($assignedSearchGroups as $group)
            <tr>
                <td>{{ $group->group_id }}</td>
                <td>{{ $group->caseFile->title }}</td>
                <td>{{ $group->leader->name }}</td>
                <td>{{ $group->leader->phone }}</td>
                <td>{{ $group->start_time }}</td>
                <td>{{ $group->duration ? $group->duration.' mins' : '—' }}</td>
                <td>({{ $group->allocated_lat }}, {{ $group->allocated_lng }})</td>
                <td>{{ str_replace('_',' ', ucfirst($group->status)) }}</td>

                <td>
                    <form method="GET" action="{{ route('search_groups.show', $group->group_id) }}">
                        <button type="submit" style="background-color: rgb(90, 90, 233)">View Details</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>

@endsection