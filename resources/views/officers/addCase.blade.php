@extends('layouts.layout')

@section('title', 'Add New Case')

@section('content')
    <div class="container fade-in">
        <h1>Add New Case</h1>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="error-messages">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('cases.store') }}">
            @csrf

            <input type="hidden" name="created_by" value="{{ auth()->user()->id }}">

            <label for="case_type">Case Type</label>
            <select name="case_type" id="case_type">
                <option value="missing">Missing Person</option>
            </select>

            <label for="title">Title</label>
            <input type="text" name="title" id="title" placeholder="Case title..." required>

            <label for="description">Description</label>
            <textarea name="description" id="description" rows="4" placeholder="Brief description..."></textarea>

            <label for="coverage_lat">Coverage Latitude</label>
            <input type="text" name="coverage_lat" id="coverage_lat" placeholder="e.g., 23.8103">

            <label for="coverage_lng">Coverage Longitude</label>
            <input type="text" name="coverage_lng" id="coverage_lng" placeholder="e.g., 90.4125">

            <label for="urgency">Urgency Level</label>
            <select name="urgency" id="urgency">
                <option value="low">Low</option>
                <option value="medium" selected>Medium</option>
                <option value="high">High</option>
                <option value="critical">Critical</option>
                <option value="national">National</option>
            </select>

            <button type="submit">Submit Case</button>
        </form>
    </div>
@endsection
