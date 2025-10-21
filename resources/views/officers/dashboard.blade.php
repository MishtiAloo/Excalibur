@extends('layouts.layout')

@section('title', 'Welcome')

@section('content')
    <h1>helo</h1>
    <h2>Welcome officer</h2>

    {{-- table for pending volunteer applications --}}
    <h2>Pending Volunteer Applications</h2>
    <table>
        <tr>
            <th>Name</th>
            <th>NID</th>
            <th>ApplicationStatus</th>
            <th>Action</th>
        </tr>
        @foreach ($pendingVolunteers as $pendings)
            <tr>
                <td>{{ $pendings->user->name }}</td>
                <td>{{ $pendings->user->nid }}</td>
                <td>{{ $pendings->vetting_status }}</td>
                <td>
                    <form method="POST" action="{{ route('volunteers.update', $pendings->volunteer_id) }}">
                        @csrf
                        @method('PUT')
                        <button type="submit" name="action" value="approve" style="background-color: rgb(48, 221, 48)">Approve</button>
                        <button type="submit" name="action" value="reject" style="background-color: rgb(227, 96, 96)">Reject</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
        
    {{-- table for pending special volunteer applications --}}
    <h2>Pending Special Volunteer Applications</h2>
    <table>
        <tr>
            <th>Name</th>
            <th>NID</th>
            <th>Terrain Type</th>
            <th>Application Status</th>
            <th>Action</th>
        </tr>
        @foreach ($pendingSpecialVolunteers as $specials)
            <tr>
                <td>{{ $specials->volunteer->user->name }}</td>
                <td>{{ $specials->volunteer->user->nid }}</td>
                <td>{{ $specials->terrain_type }}</td>
                <td>{{ $specials->vetting_status }}</td>
                <td>
                    <form method="POST" action="{{ route('special_volunteers.update', $specials->special_volunteer_id) }}">
                        @csrf
                        @method('PUT')
                        <button type="submit" name="action" value="approve" style="background-color: rgb(48, 221, 48)">Approve</button>
                        <button type="submit" name="action" value="reject" style="background-color: rgb(227, 96, 96)">Reject</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>

    {{-- table for all active case --}}
    <h2>Active Cases</h2>
    <form method="GET" action="{{ route('cases.showCreatePage') }}">
        <button type="submit" style="background-color: rgb(48, 221, 48)">Add New Case</button>
    </form>

    <table>
        <tr>
            <th>Case ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>Coverage Location</th>
            <th>Coverage Radius</th>
            <th>Created At</th>
            <th>Urgency</th>
            <th>actions</th>
        </tr>
        @foreach ($activeCases as $case)
            <tr>
                <td>{{ $case->case_id }}</td>
                <td>{{ $case->title }}</td>
                <td>{{ $case->description }}</td>
                <td>({{ $case->coverage_lat }}, {{ $case->coverage_lng }})</td>
                <td>{{ $case->coverage_radius ? $case->coverage_radius.' m' : '—' }}</td>
                <td>{{ $case->created_at }}</td>
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

    <h2 style="margin-top:24px;">Active Alerts</h2>
    @if(isset($activeAlerts) && $activeAlerts->isNotEmpty())
        <table>
            <thead>
                <tr>
                    <th>Alert ID</th>
                    <th>Case ID</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Expires At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activeAlerts as $alert)
                    <tr>
                        <td>{{ $alert->alert_id }}</td>
                        <td>{{ $alert->case_id }}</td>
                        <td>{{ ucfirst($alert->alert_type) }}</td>
                        <td>{{ ucfirst($alert->status) }}</td>
                        <td>{{ $alert->expires_at }}</td>
                        <td>
                            <form method="POST" action="{{ route('alerts.update', $alert->alert_id) }}" style="display:inline;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="expired">
                                <button type="submit" style="background:#ef4444;color:#fff;border:none;padding:6px 8px;border-radius:6px;">Expire</button>
                            </form>
                            <form method="POST" action="{{ route('alerts.update', $alert->alert_id) }}" style="display:inline;margin-left:8px;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="cancelled">
                                <button type="submit" style="background:#6b7280;color:#fff;border:none;padding:6px 8px;border-radius:6px;">Cancel</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No active alerts.</p>
    @endif

@endsection