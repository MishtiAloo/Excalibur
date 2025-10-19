@extends('layouts.layout')

@section('title', 'Welcome')

@section('content')
    <h1>helo</h1>
    <h2>Welcome volunteer</h2>

    <table>
        <tr>
            <th>Case Title</th>
            <th>Case Type</th>
            <th>Status</th>
            <th>Urgency</th>
            <th>Action</th>
        </tr>
        @foreach ($activeCases as $case)
            <tr>
                <td>{{ $case->title }}</td>
                <td>{{ $case->case_type }}</td>
                <td>{{ $case->status }}</td>
                <td>{{ $case->urgency }}</td>
                <td>
                    <a href="{{ route('cases.show', $case->case_id) }}">
                        <button style="background-color:#3b82f6; color:white; padding:8px 14px; border:none; border-radius:6px;">
                            View Details
                        </button>
                    </a>
                </td>
            </tr>
        @endforeach
    </table>
@endsection