@extends('layouts.layout')

@section('title', 'Welcome')

@section('content')
    <h1>helo</h1>
    <h2>Welcome officer</h2>

    {{-- table for pending volunteer applications --}}
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
@endsection