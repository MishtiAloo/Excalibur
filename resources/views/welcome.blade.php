@extends('layouts.layout')

@section('title', 'Welcome')

@section('content')
<div style="max-width: 1200px; margin: 40px auto; padding: 20px;">

    <!-- Hero Section -->
    <section style="display:flex; flex-wrap:wrap; align-items:center; gap:30px;">
        <!-- Left Column -->
        <div style="flex:1 1 520px; min-width:320px;">
            <h1 style="font-size:48px; line-height:1.1; margin:0 0 16px; color:#fed008; font-weight:bold;">
                Excalibur — Coordinate, Mobilize, Rescue
            </h1>
            <p style="color:#d1d5db; font-size:18px; margin:0 0 20px;">
                Rapid-response platform for Missing Person cases. Create cases, organize search groups, gather real-time reports, and broadcast alerts to the right people instantly.
            </p>
            <ul style="color:#9ca3af; margin:0 0 20px 20px; list-style-type: disc;">
                <li>Lead structured Search Groups efficiently</li>
                <li>Submit geo-tagged Reports with images</li>
                <li>Push critical Alerts to nearby volunteers</li>
                <li>Track progress in real-time dashboards</li>
            </ul>
            <div style="display:flex; gap:12px; align-items:center; flex-wrap:wrap; margin-top:12px;">
                <a href="{{ route('login') }}" style="background:#3b82f6; color:#fff; padding:14px 22px; border-radius:12px; text-decoration:none; font-weight:600; box-shadow: 0 4px 12px rgba(59,130,246,0.4); transition: all 0.3s;">
                    Sign In to Excalibur
                </a>
                <a href="{{ route('contact') }}" style="color:#fed008; text-decoration:none; font-weight:500; transition: all 0.3s;">
                    Contact Us
                </a>
            </div>
        </div>

        <!-- Right Column -->
        <div style="flex:1 1 420px; min-width:320px;">
            <div style="background:linear-gradient(135deg, rgba(59,130,246,0.15), rgba(16,185,129,0.15)); border:1px solid #1f2937; border-radius:20px; padding:20px; display:grid; grid-template-columns:repeat(2,1fr); gap:14px;">
                @php
                    $cards = [
                        ['title'=>'Active Cases','desc'=>'Track zones & urgency','color'=>'#3b82f6'],
                        ['title'=>'Search Groups','desc'=>'Manage leaders & volunteers','color'=>'#10b981'],
                        ['title'=>'Reports + Media','desc'=>'Upload evidence & sightings','color'=>'#f59e0b'],
                        ['title'=>'Nearby Alerts','desc'=>'Notify within radius fast','color'=>'#ef4444']
                    ];
                @endphp

                @foreach($cards as $card)
                <div style="background:#0b1220; border:1px solid #111827; border-radius:14px; padding:16px; transition: transform 0.3s, box-shadow 0.3s;">
                    <h3 style="margin:0 0 8px; color:{{ $card['color'] }}; font-size:17px; font-weight:700;">{{ $card['title'] }}</h3>
                    <p style="margin:0; color:#9ca3af; font-size:14px;">{{ $card['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Highlights Section -->
    <section style="margin-top:40px;">
        <h2 style="color:#fed008; font-size:32px; margin-bottom:20px; text-align:center;">Empower Every Role</h2>
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(260px, 1fr)); gap:18px;">
            @php
                $highlights = [
                    ['title'=>'Officer Workflow','desc'=>'Create cases, form groups, approve alerts, monitor activity.','color'=>'#3b82f6'],
                    ['title'=>'Leader Control','desc'=>'Submit reports with locations/images, coordinate volunteers.','color'=>'#10b981'],
                    ['title'=>'Volunteer Power','desc'=>'Join search groups, contribute findings, stay informed.','color'=>'#f59e0b'],
                    ['title'=>'Signal the City','desc'=>'Send targeted alerts and respond faster together.','color'=>'#ef4444']
                ];
            @endphp

            @foreach($highlights as $hl)
            <div style="background:#0b1220; border:1px solid #111827; border-radius:16px; padding:18px; transition: transform 0.3s, box-shadow 0.3s;">
                <div style="font-weight:700; color:{{ $hl['color'] }}; margin-bottom:8px; font-size:16px;">{{ $hl['title'] }}</div>
                <p style="color:#d1d5db; margin:0; font-size:14px;">{{ $hl['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </section>

    <!-- CTA Footer -->
    <section style="text-align:center; margin-top:50px;">
        <a href="{{ route('login') }}" style="background:#3b82f6; color:#fff; padding:14px 22px; border-radius:12px; font-weight:600; text-decoration:none; box-shadow: 0 4px 16px rgba(59,130,246,0.4); transition: transform 0.3s;">Get Started</a>
        <p style="color:#9ca3af; margin-top:10px; font-size:14px;">Already part of a group? Sign in to file reports and track assignments.</p>
    </section>

</div>

<!-- Hover Effects -->
<style>
    div[style*="transition: transform"] {
        cursor: pointer;
    }
    div[style*="transition: transform"]:hover {
        transform: translateY(-6px);
        box-shadow: 0 8px 20px rgba(255, 235, 59, 0.25);
    }
    a[href][style*="transition: transform"]:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(59,130,246,0.35);
    }
</style>
@endsection
