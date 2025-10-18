@extends('layouts.layout')

@section('title', 'Case Details')

@section('content')
<div style="max-width: 900px; margin: 40px auto; border-radius: 16px; box-shadow: 0 0 12px rgba(0,0,0,0.1); padding: 30px;">

    <h1 style="text-align: center; color: #ffffff; margin-bottom: 20px;">Case Details</h1>
    <hr style="margin-bottom: 25px;">

    {{-- Case Info --}}
    <h2 style="color: #ffffff;">Case Information</h2>
    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <tr><th style="text-align:left; width: 30%;">Case ID</th><td>{{ $case->case_id }}</td></tr>
        <tr><th>Title</th><td>{{ $case->title }}</td></tr>
        <tr><th>Type</th><td>{{ ucfirst($case->case_type) }}</td></tr>
        <tr><th>Description</th><td>{{ $case->description ?? 'N/A' }}</td></tr>
        <tr><th>Status</th><td>{{ ucfirst($case->status) }}</td></tr>
        <tr>
            <th>Urgency</th>
            <td style="
                background-color:
                    {{ $case->urgency == 'national' ? 'rgb(153, 0, 0)' :
                       ($case->urgency == 'high' ? 'rgb(227, 96, 96)' :
                       ($case->urgency == 'medium' ? 'rgb(255, 255, 48)' :
                       'rgb(48, 221, 48)')) }};
                color: {{ in_array($case->urgency, ['high', 'national']) ? 'white' : 'black' }};
                padding: 4px 8px;
                border-radius: 6px;
                display: inline-block;
            ">
                {{ ucfirst($case->urgency) }}
            </td>
        </tr>
        <tr><th>Coverage (Lat, Lng)</th><td>{{ $case->coverage_lat }}, {{ $case->coverage_lng }}</td></tr>
        <tr><th>Created At</th><td>{{ $case->created_at }}</td></tr>
    </table>

    <hr style="margin: 25px 0;">

    {{-- Creator Info --}}
    <h2 style="color: #ffffff;">Creator Information</h2>
    @if ($case->creator)
    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <tr><th style="text-align:left; width: 30%;">Name</th><td>{{ $case->creator->name }}</td></tr>
        <tr><th>Phone</th><td>{{ $case->creator->phone }}</td></tr>
    </table>
    @else
        <p style="color: gray;">Creator information not available.</p>
    @endif

    <hr style="margin: 25px 0;">

    {{-- Search Groups --}}
    <h2 style="color: #ffffff;">Search Groups</h2>
    @if (!empty($case->searchGroups) && count($case->searchGroups) > 0)
    <table style="width: 100%; border=1; border-collapse: collapse; margin-top: 10px;">
        <thead style="background-color: #f2f2f2;">
            <tr>
                <th>Group ID</th>
                <th>Leader ID</th>
                <th>Type</th>
                <th>Intensity</th>
                <th>Status</th>
                <th>Allocated Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($case->searchGroups as $group)
            <tr>
                <td>{{ $group->group_id }}</td>
                <td>{{ $group->leader_id }}</td>
                <td>{{ ucfirst($group->type) }}</td>
                <td>{{ ucfirst($group->intensity) }}</td>
                <td>{{ ucfirst($group->status) }}</td>
                <td>{{ $group->allocated_time }} hrs</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <p style="color: rgb(129, 129, 129);">No search groups assigned yet.</p>
    @endif

    <div style="margin-top: 30px; text-align: center;">
        <a href="{{ route('dashboard.officer') }}" style="background-color: #3b82f6; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none;">← Back to All Cases</a>
    </div>
</div>
@endsection
