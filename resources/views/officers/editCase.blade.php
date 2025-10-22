@extends('layouts.layout')

@section('title', 'Edit Case')

@section('content')
    <form action="{{ route('cases.update', $case->case_id) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="{{ $case->title }}" required><br><br>

        <label for="case_type">Case Type:</label>
        <select id="case_type" name="case_type" required>
            <option value="missing" {{ $case->case_type == 'missing' ? 'selected' : '' }}>Missing Person</option>
        </select><br><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required>{{ $case->description }}</textarea><br><br>

        <label for="status">Status:</label>
        <select id="status" name="status" required>
            <option value="active" {{ $case->status == 'active' ? 'selected' : '' }}>Active</option>
            <option value="under_investigation" {{ $case->status == 'under_investigation' ? 'selected' : '' }}>Under Investigation</option>
            <option value="resolved" {{ $case->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
            <option value="closed" {{ $case->status == 'closed' ? 'selected' : '' }}>Closed</option>
        </select><br><br>

        <label for="urgency">Urgency:</label>
        <select id="urgency" name="urgency" required>
            <option value="low" {{ $case->urgency == 'low' ? 'selected' : '' }}>Low</option>
            <option value="medium" {{ $case->urgency == 'medium' ? 'selected' : '' }}>Medium</option>
            <option value="high" {{ $case->urgency == 'high' ? 'selected' : '' }}>High</option>
            <option value="critical" {{ $case->urgency == 'critical' ? 'selected' : '' }}>Critical</option>
            <option value="national" {{ $case->urgency == 'national' ? 'selected' : '' }}>National</option>
        </select><br><br>

        <label for="coverage_lat">Coverage Latitude:</label>
        <input type="text" id="coverage_lat" name="coverage_lat" value="{{ $case->coverage_lat }}" required><br><br>

        <label for="coverage_lng">Coverage Longitude:</label>
        <input type="text" id="coverage_lng" name="coverage_lng" value="{{ $case->coverage_lng }}" required><br><br>

        <label for="coverage_radius">Coverage Radius (meters):</label>
        <input type="number" id="coverage_radius" name="coverage_radius" value="{{ $case->coverage_radius }}" min="0"><br><br>

        <button type="button" onclick="toggleMap()" style="background-color:#3b82f6; color:white; padding:8px 14px; border:none; border-radius:6px;">
            🗺️ Pick Location from Map
        </button>

        <div id="map" style="height: 400px; width: 100%; margin-top: 15px; display:none; border-radius:8px;"></div>

        <br><br>
        <button type="submit" style="background-color:#10b981; color:white; padding:8px 14px; border:none; border-radius:6px;">
            Update Case
        </button>
    </form>

    <hr style="margin:18px 0;" />

    {{-- Add Case Images (moved here from details view) --}}
    <h3 style="color:#e5e7eb;">Add Case Images</h3>
    <form method="POST" action="{{ route('cases.media.store', $case->case_id) }}" enctype="multipart/form-data" style="margin-top: 10px;">
        @csrf
        <button type="button" id="addCaseImageBtn" style="background:#6b7280; color:#fff; padding:6px 10px; border:none; border-radius:6px; cursor:pointer;">+ Add Image(s)</button>
        <input type="file" id="caseImages" name="images[]" accept="image/*" multiple style="display:none;" />
        <div id="caseImagesList" style="display:flex; flex-direction:column; gap:10px; margin-top:10px;"></div>
        <button type="submit" style="margin-top:8px; background:#3b82f6; color:#fff; padding:8px 12px; border:none; border-radius:6px;">Upload</button>
    </form>
@endsection

@section('scripts')
<script>
    let map, marker, circle;

    function toggleMap() {
        const mapDiv = document.getElementById("map");
        if (mapDiv.style.display === "none") {
            mapDiv.style.display = "block";
            initMap();
        } else {
            mapDiv.style.display = "none";
        }
    }

    function initMap() {
        const latInput = document.getElementById("coverage_lat");
        const lngInput = document.getElementById("coverage_lng");
        const radiusInput = document.getElementById("coverage_radius");

        const lat = parseFloat(latInput.value) || 23.8103; // default Dhaka
        const lng = parseFloat(lngInput.value) || 90.4125;
        const radius = parseInt(radiusInput.value) || 1000;

        map = new google.maps.Map(document.getElementById("map"), {
            center: { lat, lng },
            zoom: 10,
        });

        marker = new google.maps.Marker({
            position: { lat, lng },
            map: map,
            draggable: false,
        });

        // Draw initial circle
        circle = new google.maps.Circle({
            center: { lat, lng },
            radius: radius,
            map: map,
            fillColor: "#3b82f6",
            fillOpacity: 0.2,
            strokeColor: "#2563eb",
            strokeWeight: 2,
        });

        // When user clicks on the map, update the inputs and circle
        map.addListener("click", (event) => {
            const clickedLat = event.latLng.lat();
            const clickedLng = event.latLng.lng();

            latInput.value = clickedLat.toFixed(6);
            lngInput.value = clickedLng.toFixed(6);

            if (marker) marker.setMap(null); // remove old marker

            marker = new google.maps.Marker({
                position: { lat: clickedLat, lng: clickedLng },
                map: map,
            });

            const newRadius = parseInt(radiusInput.value) || radius;
            if (circle) circle.setMap(null);
            circle = new google.maps.Circle({
                center: { lat: clickedLat, lng: clickedLng },
                radius: newRadius,
                map: map,
                fillColor: "#3b82f6",
                fillOpacity: 0.2,
                strokeColor: "#2563eb",
                strokeWeight: 2,
            });
        });
    }
</script>
<script>
    // Multi-image accumulation for case media upload (edit page)
    const addCaseBtn = document.getElementById('addCaseImageBtn');
    const caseImagesInput = document.getElementById('caseImages');
    const caseImagesList = document.getElementById('caseImagesList');
    if (addCaseBtn && caseImagesInput && caseImagesList) {
        let dtCase = new DataTransfer();
        const descMapCase = new Map();
        const keyOf = (f) => `${f.name}__${f.lastModified}`;

        function rebuildCaseList() {
            caseImagesList.innerHTML = '';
            Array.from(dtCase.files).forEach((file, idx) => {
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
                if (descMapCase.has(key)) ta.value = descMapCase.get(key);
                ta.addEventListener('input', () => descMapCase.set(key, ta.value));
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
                rm.addEventListener('click', () => removeAtCase(idx));
                actions.appendChild(rm);
                row.appendChild(actions);

                caseImagesList.appendChild(row);
            });
            caseImagesInput.files = dtCase.files;
        }

        function removeAtCase(i) {
            const cur = Array.from(dtCase.files);
            const nd = new DataTransfer();
            cur.forEach((f, idx) => {
                if (idx !== i) nd.items.add(f);
                else descMapCase.delete(keyOf(f));
            });
            dtCase = nd;
            rebuildCaseList();
        }

        addCaseBtn.addEventListener('click', () => caseImagesInput.click());
        caseImagesInput.addEventListener('change', () => {
            const fresh = Array.from(caseImagesInput.files || []);
            fresh.forEach(f => {
                const exists = Array.from(dtCase.files).some(df => keyOf(df) === keyOf(f));
                if (!exists) dtCase.items.add(f);
            });
            caseImagesInput.value = '';
            rebuildCaseList();
        });
    }
</script>
@endsection
