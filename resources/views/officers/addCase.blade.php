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

    <form method="POST" action="{{ route('cases.store') }}" enctype="multipart/form-data">
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

            <label for="coverage_radius">Coverage Radius (meters)</label>
            <input type="number" name="coverage_radius" id="coverage_radius" min="0" placeholder="e.g., 1000">

            <button type="button" onclick="toggleMap()" style="background-color:#3b82f6; color:white; padding:8px 14px; border:none; border-radius:6px;">
            🗺️ Pick Location from Map
            </button>

            <div id="map" style="height: 400px; width: 100%; margin-top: 15px; display:none; border-radius:8px;"></div>

            <label for="urgency">Urgency Level</label>
            <select name="urgency" id="urgency">
                <option value="low">Low</option>
                <option value="medium" selected>Medium</option>
                <option value="high">High</option>
                <option value="critical">Critical</option>
                <option value="national">National</option>
            </select>

            <hr style="margin:18px 0;" />

            <h3>Case Images</h3>
            <p style="color:#9ca3af; margin:6px 0 10px;">Click add and select images; you can add more repeatedly and write a description for each.</p>
            <button type="button" id="addCaseFormImageBtn" style="background:#6b7280; color:#fff; padding:6px 10px; border:none; border-radius:6px; cursor:pointer;">+ Add Image(s)</button>
            <input type="file" id="imagesInput" name="images[]" accept="image/*" multiple style="display:none;" />
            <div id="imagesMeta" style="margin-top:10px; display:flex; flex-direction:column; gap:10px;"></div>

            <button type="submit">Submit Case</button>
        </form>
    </div>
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
    // Multi-image accumulation with per-image descriptions for Add Case
    const addBtn = document.getElementById('addCaseFormImageBtn');
    const imagesInput = document.getElementById('imagesInput');
    const imagesMeta = document.getElementById('imagesMeta');
    if (addBtn && imagesInput && imagesMeta) {
        let dt = new DataTransfer();
        const descMap = new Map();
        const keyOf = (f) => `${f.name}__${f.lastModified}`;

        function rebuildList() {
            imagesMeta.innerHTML = '';
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

                imagesMeta.appendChild(row);
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
            rebuildList();
        }

        addBtn.addEventListener('click', () => imagesInput.click());
        imagesInput.addEventListener('change', () => {
            const fresh = Array.from(imagesInput.files || []);
            fresh.forEach(f => {
                const exists = Array.from(dt.files).some(df => keyOf(df) === keyOf(f));
                if (!exists) dt.items.add(f);
            });
            imagesInput.value = '';
            rebuildList();
        });
    }
</script>
@endsection

