@extends('layouts.layout')

@section('title', 'Report Details')

@section('content')
<div style="max-width: 900px; margin: 40px auto; border-radius: 16px; box-shadow: 0 0 12px rgba(0,0,0,0.1); padding: 30px;">
    <h1 style="text-align:center; color:#fff;">Report Details (Officer)</h1>
    <hr/>

    @if(session('success'))
        <div style="background:#10b981; color:#fff; padding:8px 12px; border-radius:6px; margin-bottom:10px;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div style="background:#ef4444; color:#fff; padding:8px 12px; border-radius:6px; margin-bottom:10px;">{{ session('error') }}</div>
    @endif

    <table style="width:100%;">
        <tr><th style="text-align:left; width:30%">Report ID</th><td>{{ $report->report_id }}</td></tr>
        <tr><th>Case</th><td>{{ optional($report->caseFile)->case_id }}</td></tr>
        <tr><th>Search Group</th><td>{{ $report->search_group_id }}</td></tr>
        <tr><th>Filed By</th><td>{{ optional($report->user)->name ?? '—' }}</td></tr>
        <tr><th>Type</th><td>{{ ucfirst($report->report_type) }}</td></tr>
        <tr><th>Description</th><td>{{ $report->description }}</td></tr>
        <tr><th>Location</th><td>{{ $report->location_lat }}, {{ $report->location_lng }}</td></tr>
        <tr><th>Sighted Person</th><td>{{ $report->sighted_person ?? '—' }}</td></tr>
        <tr><th>Reported At</th><td>{{ $report->reported_at }}</td></tr>
        <tr><th>Status</th><td>{{ ucfirst($report->status) }}</td></tr>
    </table>

    <div style="margin-top:18px; display:flex; gap:8px; align-items:center;">
        {{-- Show allowed actions based on current status --}}
        @if ($report->status === 'pending')
            <form method="POST" action="{{ route('reports.update', $report->report_id) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="verified">
                <button type="submit" style="background:#10b981;color:#fff;border:none;padding:8px 12px;border-radius:6px;cursor:pointer;">Verify</button>
            </form>
            <form method="POST" action="{{ route('reports.update', $report->report_id) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="falsed">
                <button type="submit" style="background:#ef4444;color:#fff;border:none;padding:8px 12px;border-radius:6px;cursor:pointer;">Mark Falsed</button>
            </form>
        @elseif (in_array($report->status, ['verified', 'falsed']))
            <form method="POST" action="{{ route('reports.update', $report->report_id) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="ressponded">
                <button type="submit" style="background:#2563eb;color:#fff;border:none;padding:8px 12px;border-radius:6px;cursor:pointer;">Responded</button>
            </form>
            <form method="POST" action="{{ route('reports.update', $report->report_id) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="dismissed">
                <button type="submit" style="background:#6b7280;color:#fff;border:none;padding:8px 12px;border-radius:6px;cursor:pointer;">Dismiss</button>
            </form>
        @else
            <em style="color:gray;">No further actions available.</em>
        @endif
    </div>

    <div style="margin-top:15px;">
        <a href="{{ route('search_groups.show', $report->search_group_id) }}" style="background:#3b82f6;color:#fff;padding:8px 12px;border-radius:6px;text-decoration:none;">Back to Group</a>
    </div>

    <hr/>
    <h2 style="color:#fff;">Attached Images</h2>
    @php $media = $report->relationLoaded('media') ? $report->media : $report->media()->get(); @endphp
    @if($media->count())
        <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(220px,1fr)); gap:12px; margin-top:10px;">
            @foreach($media as $m)
                <div style="border:1px solid #e5e7eb; border-radius:10px; overflow:hidden; background:#0b1220;">
                    <div style="width:100%; aspect-ratio:4/3; background:#111827; display:flex; align-items:center; justify-content:center;">
                        <img src="{{ $m->url }}" alt="report image" style="max-width:100%; max-height:100%; object-fit:cover;">
                    </div>
                    <div style="padding:10px;">
                        <p style="color:#d1d5db; font-size:14px; margin:0;">{{ $m->description ?? '—' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p style="color:#9ca3af;">No images attached to this report.</p>
    @endif
</div>
@endsection
