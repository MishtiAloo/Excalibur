@extends('layouts.layout')

@section('title', 'Welcome')

@section('content')
    <h2>Candidate Leaders</h2>
    <table id="leadersTable" style="border=1; cellpadding=6;">
        <tr>
            <th>Name</th>
            <th>NID</th>
            <th>Phone</th>
            <th>Info Credibility</th>
            <th>Responsiveness</th>
            <th>Action</th>
        </tr>
        @foreach ($candidateLeaders as $leader)
            <tr data-lat="{{ $leader->user->permanent_lat }}"
                data-lng="{{ $leader->user->permanent_lng }}">
                <td>{{ $leader->user->name }}</td>
                <td>{{ $leader->user->nid }}</td>
                <td>{{ $leader->user->phone }}</td>
                <td>{{ $leader->user->info_credibility }}</td>
                <td>{{ $leader->user->responsiveness }}</td>
                <td>
                    <form method="POST" action="{{ route('search-groups.assignLeader', $leader->user->id) }}">
                        @csrf
                        <button type="submit" style="background-color: #3b82f6; color: white; padding: 6px 10px; border-radius: 4px; border: none;">Select as Leader</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>

    <h2>Candidate Officers</h2>
    <table id="officersTable" style="border=1; cellpadding=6;" >
        <tr>
            <th>Name</th>
            <th>NID</th>
            <th>Phone</th>
            <th>Action</th>
        </tr>
        @foreach ($candidateOfficers as $officer)
            <tr data-lat="{{ $officer->permanent_lat }}"
                data-lng="{{ $officer->permanent_lng }}">
                <td>{{ $officer->name }}</td>
                <td>{{ $officer->nid }}</td>
                <td>{{ $officer->phone }}</td>
                <td>
                        <form method="POST" action="{{ route('search-groups.assignLeader', $officer->id) }}">
                            @csrf
                            <button type="submit" style="background-color: #3b82f6; color: white; padding: 6px 10px; border-radius: 4px; border: none;">Select as Leader</button>
                        </form>
                </td>
            </tr>
        @endforeach
    </table>
@endsection
