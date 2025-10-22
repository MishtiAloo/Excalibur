@extends('layouts.layout')

@section('title', 'Welcome')

@section('content')
    <h2>Add a new Report</h2>
    {{-- form to add a new report --}}
    <form method="POST" action="{{ route('reports.add', $group->group_id) }}" enctype="multipart/form-data">
        @csrf

    <input type="hidden" name="case_id" value="{{ $case->case_id }}">

        <label for="user_id">Reporter</label>  
        <select name="user_id" id="user_id">
            @foreach ($groupMembers as $member)
                <option value="{{ $member->id }}">{{ $member->name }}</option>
            @endforeach
        </select>

        <div style="margin-top:10px;"></div>
        <label for="report_type">Report Type</label>
        <select name="report_type" id="report_type">
            <option value="evidence">Evidence</option>
            <option value="sighting">Sighting</option>
            <option value="general">General</option>
        </select>

        <div style="margin-top:10px;"></div>
        <label for="description">Description</label>
        <textarea name="description" id="description" rows="4" style="width:100%;"></textarea>

        <div style="margin-top:10px;"></div>
        <label for="sighted_person">Sighted Person (optional)</label>
        <input type="text" name="sighted_person" id="sighted_person" style="width:100%;" />

        <div style="margin-top:10px;"></div>
        <label>Location</label>
        <div style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
            <div>
                <label for="location_lat" style="display:block;">Latitude</label>
                <input type="number" step="0.0000001" name="location_lat" id="location_lat" style="width:220px;" />
            </div>
            <div>
                <label for="location_lng" style="display:block;">Longitude</label>
                <input type="number" step="0.0000001" name="location_lng" id="location_lng" style="width:220px;" />
            </div>
            <button type="button" onclick="toggleMap()" style="background:#3b82f6;color:#fff;border:none;padding:8px 12px;border-radius:6px;cursor:pointer;">Pick from Map</button>
        </div>

        <!-- Map for picking location; shows group allocation circle -->
        <div id="map" style="width: 100%; height: 380px; margin-top: 12px; display: none; border-radius: 12px; overflow: hidden;"></div>
        <input type="hidden" id="group_lat" value="{{ $group->allocated_lat }}">
        <input type="hidden" id="group_lng" value="{{ $group->allocated_lng }}">
        <input type="hidden" id="group_radius" value="{{ $group->radius }}">

        

        <div style="margin-top:12px;"></div>
    <label style="display:block;">Attach Images</label>
    <button type="button" id="addReportImageBtn" style="background:#6b7280; color:#fff; padding:6px 10px; border:none; border-radius:6px; cursor:pointer;">+ Add Image(s)</button>
    <input type="file" id="images" name="images[]" accept="image/*" multiple style="display:none;" />
    <div id="imagesList" style="display:flex; flex-direction:column; gap:10px; margin-top:10px;"></div>

        <div style="margin-top:12px;">
            <button type="submit" style="background:#3b82f6; color:#fff; padding:8px 14px; border:none; border-radius:6px; cursor:pointer;">Submit Report</button>
        </div>
    </form>
@endsection

@section('scripts')
<script>
    let map, groupCircle, groupMarker, reportMarker;

    function toggleMap() {
        const mapDiv = document.getElementById('map');
        if (mapDiv.style.display === 'none') {
            mapDiv.style.display = 'block';
            initMap();

            // Fix resize after making visible
            setTimeout(() => {
                if (typeof google !== 'undefined' && map) {
                    google.maps.event.trigger(map, 'resize');
                    const groupLat = parseFloat(document.getElementById('group_lat').value) || 23.8103;
                    const groupLng = parseFloat(document.getElementById('group_lng').value) || 90.4125;
                    map.setCenter({ lat: groupLat, lng: groupLng });
                }
            }, 0);
        } else {
            mapDiv.style.display = 'none';
        }
    }

    function initMap() {
        if (map) return; // only initialize once

        const groupLat = parseFloat(document.getElementById('group_lat').value);
        const groupLng = parseFloat(document.getElementById('group_lng').value);
        const groupRadius = parseInt(document.getElementById('group_radius').value);

        const center = { lat: groupLat, lng: groupLng };
        map = new google.maps.Map(document.getElementById('map'), {
            center: center,
            zoom: 13,
        });

        // 🔵 Add search group circle + marker
        groupCircle = new google.maps.Circle({
            center: center,
            radius: groupRadius,
            map: map,
            fillColor: '#3b82f6',
            fillOpacity: 0.25,
            strokeColor: '#2563eb',
            strokeWeight: 2,
            clickable: false
        });

        groupMarker = new google.maps.Marker({
            position: center,
            map: map,
            title: "Search Group Center",
            icon: {
                path: google.maps.SymbolPath.CIRCLE,
                scale: 6,
                fillColor: "#2563eb",
                fillOpacity: 1,
                strokeColor: "#ffffff",
                strokeWeight: 2,
            },
        });

        // 📍 Click to add or move report marker
        map.addListener('click', (e) => {
            const lat = e.latLng.lat();
            const lng = e.latLng.lng();

            // Update input fields
            document.getElementById('location_lat').value = lat.toFixed(7);
            document.getElementById('location_lng').value = lng.toFixed(7);

            // Add or move report marker
            if (reportMarker) reportMarker.setMap(null);
            reportMarker = new google.maps.Marker({
                position: e.latLng,
                map: map,
                title: 'Report Location',
                icon: {
                    url: "https://maps.google.com/mapfiles/ms/icons/red-dot.png"
                }
            });
        });
    }
</script>
<script>
    // Robust multi-image accumulation for report images
    const addBtn = document.getElementById('addReportImageBtn');
    const imagesInput = document.getElementById('images');
    const imagesList = document.getElementById('imagesList');
    let dt = new DataTransfer();
    const descMap = new Map();

    function keyOf(f) { return `${f.name}__${f.lastModified}`; }

    function rebuildReportImages() {
        imagesList.innerHTML = '';
        Array.from(dt.files).forEach((file, idx) => {
            const key = keyOf(file);
            const row = document.createElement('div');
            row.style.display = 'grid';
            row.style.gridTemplateColumns = '120px 1fr auto';
            row.style.gap = '10px';
            row.style.alignItems = 'center';
            row.style.border = '1px solid #e5e7eb';
            row.style.borderRadius = '8px';
            row.style.padding = '10px';

            const img = document.createElement('img');
            img.style.width = '120px';
            img.style.height = '90px';
            img.style.objectFit = 'cover';
            const fr = new FileReader();
            fr.onload = e => img.src = e.target.result;
            fr.readAsDataURL(file);
            row.appendChild(img);

            const right = document.createElement('div');
            const label = document.createElement('label');
            label.textContent = file.name;
            label.style.display = 'block';
            label.style.color = '#6b7280';
            const ta = document.createElement('textarea');
            ta.name = 'image_descriptions[]';
            ta.rows = 2;
            ta.placeholder = 'Optional description...';
            ta.style.width = '100%';
            if (descMap.has(key)) ta.value = descMap.get(key);
            ta.addEventListener('input', () => descMap.set(key, ta.value));
            right.appendChild(label);
            right.appendChild(ta);
            row.appendChild(right);

            const actions = document.createElement('div');
            const rm = document.createElement('button');
            rm.type = 'button';
            rm.textContent = 'Remove';
            rm.style.backgroundColor = '#ef4444';
            rm.style.color = '#fff';
            rm.style.border = 'none';
            rm.style.padding = '6px 10px';
            rm.style.borderRadius = '6px';
            rm.addEventListener('click', () => removeAt(idx));
            actions.appendChild(rm);
            row.appendChild(actions);

            imagesList.appendChild(row);
        });
        imagesInput.files = dt.files;
    }

    function removeAt(i) {
        const cur = Array.from(dt.files);
        const nd = new DataTransfer();
        cur.forEach((f, idx) => {
            if (idx !== i) nd.items.add(f);
            else descMap.delete(keyOf(f));
        });
        dt = nd;
        rebuildReportImages();
    }

    addBtn?.addEventListener('click', () => imagesInput?.click());
    imagesInput?.addEventListener('change', () => {
        const fresh = Array.from(imagesInput.files || []);
        fresh.forEach(f => {
            const exists = Array.from(dt.files).some(df => keyOf(df) === keyOf(f));
            if (!exists) dt.items.add(f);
        });
        imagesInput.value = '';
        rebuildReportImages();
    });
</script>
@endsection
