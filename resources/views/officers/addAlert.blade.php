@extends('layouts.layout')

@section('title', 'Add Alert')

@section('content')
<div style="max-width:700px;margin:40px auto;padding:20px;background:rgba(0,0,0,0.05);border-radius:8px;">
    <h2>Create Alert for Case #{{ $case->case_id }}</h2>
    <form method="POST" action="{{ route('alerts.store') }}">
        @csrf
        <input type="hidden" name="case_id" value="{{ $case->case_id }}">

        <label for="alert_type">Alert Type</label>
        <select name="alert_type" id="alert_type">
            <option value="amber">Amber</option>
            <option value="silver">Silver</option>
            <option value="red">Red</option>
            <option value="yellow">Yellow</option>
        </select>

        <div style="margin-top:12px;"></div>
        <label for="expires_at">Expires At</label>
        <input type="datetime-local" name="expires_at" id="expires_at" />

        <div style="margin-top:12px;"></div>
        <label for="message">Message</label>
        <textarea name="message" id="message" rows="4" style="width:100%;"></textarea>

        <div style="margin-top:12px;"></div>
        <button type="submit" style="background:#3b82f6;color:#fff;padding:8px 12px;border:none;border-radius:6px;">Create Alert</button>
        <a href="{{ route('cases.show', $case->case_id) }}" style="margin-left:8px;">Cancel</a>
    </form>
</div>
@endsection
