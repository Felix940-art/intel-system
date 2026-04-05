<x-app-layout>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/mgrs/dist/mgrs.min.js"></script>
    <script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>


    <div class="max-w-3xl mx-auto space-y-8">

        {{-- HEADER --}}
        <div>
            <h1 class="text-2xl font-bold text-white flex items-center gap-3">
                🚁 GEOINT Mission Log Entry
            </h1>
            <p class="text-sm text-slate-400 mt-1">
                Intelligence Surveillance & Reconnaissance Report
            </p>
        </div>

        {{-- FORM CARD --}}
        <div class="bg-slate-950/80 backdrop-blur-xl
                border border-slate-800 rounded-2xl
                shadow-2xl shadow-cyan-900/20
                p-8">

            <form method="POST"
                action="{{ route('geoint.store') }}"
                enctype="multipart/form-data"
                class="space-y-6">

                @csrf

                {{-- DATE & TIME --}}
                <div>
                    <label class="form-label">Date & Time</label>
                    <input type="datetime-local"
                        name="mission_datetime"
                        value="{{ old('mission_datetime') }}"
                        class="form-input"
                        required>
                </div>

                {{-- UAV --}}
                <div>
                    <label class="form-label">UAV Platform</label>

                    <select name="uav"
                        id="uavSelect"
                        class="form-input"
                        required>
                        <option value="">Select UAV</option>
                        <option value="THOR">THOR</option>
                        <option value="Griffin">Griffin</option>
                        <option value="DJI MATRICE 1">DJI MATRICE 1</option>
                        <option value="DJI MATRICE 2">DJI MATRICE 2</option>
                        <option value="DJI MATRICE 3">DJI MATRICE 3</option>
                        <option value="DJI MATRICE 4">DJI MATRICE 4</option>
                    </select>

                    {{-- LIVE UAV BADGE --}}
                    <div class="mt-3">
                        <span id="uavPreview"
                            class="hidden px-3 py-1 text-xs rounded-full border">
                        </span>
                    </div>
                </div>

                {{-- HOME POINT (MGRS) --}}
                <div>
                    <label class="form-label">Home Point (MGRS)</label>

                    <input type="text"
                        id="mgrsInput"
                        name="home_point_mgrs"
                        placeholder="51PXM1234567890"
                        value="{{ old('home_point_mgrs') }}"
                        class="form-input"
                        required>

                    <input type="hidden" name="latitude" id="latitudeInput">
                    <input type="hidden" name="longitude" id="longitudeInput">

                    <p id="mgrsStatus"
                        class="text-xs mt-2 text-slate-400">
                        Format: 51PXM1234567890
                    </p>
                </div>

                <div class="mt-6">

                    <label class="form-label">Map Preview</label>

                    <div id="geoMap"
                        class="rounded-xl border border-slate-800"
                        style="height: 350px;">
                    </div>

                    <p id="mapStatus"
                        class="text-xs text-slate-400 mt-2">
                        Waiting for valid MGRS input...
                    </p>

                </div>

                {{-- THREAT CONFRONTED --}}
                <div>
                    <label class="form-label">Threat Confronted</label>

                    <select name="threat_confronted"
                        id="threatSelect"
                        class="form-input"
                        required>

                        <option value="">Select Threat</option>
                        <option value="SRC">SRC</option>
                        <option value="SRGU">SRGU</option>
                        <option value="SRMA">SRMA</option>
                        <option value="SROC">SROC</option>
                        <option value="SRMA EMPORIUM">SRMA EMPORIUM</option>
                        <option value="SRMA ARCTIC">SRMA ARCTIC</option>
                        <option value="SRMA BROWSER">SRMA BROWSER</option>
                        <option value="SRMA SESAME">SRMA SESAME</option>
                        <option value="SRMA LEVOX">SRMA LEVOX</option>
                        <option value="COMTECH">COMTECH</option>
                        <option value="EV MRGU">EV MRGU</option>
                        <option value="FUNCTIONAL">FUNCTIONAL</option>
                    </select>

                    {{-- LIVE BADGE --}}
                    <div class="mt-3">
                        <span id="threatPreview"
                            class="hidden px-3 py-1 text-xs rounded-full border">
                        </span>
                    </div>
                </div>

                {{-- DOCUMENT --}}
                <div>
                    <label class="form-label">Mission Document</label>
                    <input type="file"
                        name="document"
                        accept=".pdf,.jpg,.jpeg,.png"
                        class="form-input"
                        required>
                </div>

                {{-- DOCUMENT CLASSIFICATION --}}
                <div>
                    <label class="form-label">Document Classification</label>

                    <select name="classification"
                        id="classificationSelect"
                        class="form-input"
                        required>
                        <option value="">Select Classification</option>
                        <option value="UNCLASSIFIED">UNCLASSIFIED</option>
                        <option value="RESTRICTED">RESTRICTED</option>
                        <option value="CONFIDENTIAL">CONFIDENTIAL</option>
                        <option value="SECRET">SECRET</option>
                    </select>

                    {{-- LIVE CLASSIFICATION BADGE --}}
                    <div class="mt-3">
                        <span id="classificationPreview"
                            class="hidden px-3 py-1 text-xs rounded-full border tracking-wider">
                        </span>
                    </div>
                </div>

                {{-- ACTION BUTTONS --}}
                <div class="flex justify-end gap-4 pt-4">

                    <a href="{{ route('geoint.index') }}"
                        class="btn-secondary">
                        Cancel
                    </a>

                    <button type="submit"
                        id="geoSubmitBtn"
                        class="btn-accent">
                        Save Mission Log
                    </button>

                </div>

            </form>

        </div>
    </div>


    {{-- STYLES --}}
    <style>
        .form-label {
            display: block;
            font-size: .875rem;
            color: #94a3b8;
            margin-bottom: .5rem;
        }

        .form-input {
            width: 100%;
            background: #020617;
            border: 1px solid #334155;
            border-radius: .75rem;
            padding: .75rem 1rem;
            color: #e2e8f0;
            font-size: .875rem;
            transition: .2s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #06b6d4;
            box-shadow: 0 0 0 2px rgba(6, 182, 212, .3);
        }

        .btn-accent {
            background: linear-gradient(135deg, #06b6d4, #0891b2);
            padding: .65rem 1.4rem;
            border-radius: .75rem;
            color: white;
            font-weight: 500;
        }

        .btn-secondary {
            background: #334155;
            padding: .65rem 1.4rem;
            border-radius: .75rem;
            color: white;
        }
    </style>

    <style>
        .tactical-glow {
            animation: pulseGlow 1.5s infinite;
        }

        @keyframes pulseGlow {
            0% {
                stroke-width: 2;
                opacity: .6;
            }

            50% {
                stroke-width: 5;
                opacity: 1;
            }

            100% {
                stroke-width: 2;
                opacity: .6;
            }
        }

        .flight-glow {
            animation: dashMove 2s linear infinite;
        }

        @keyframes dashMove {
            from {
                stroke-dashoffset: 0;
            }

            to {
                stroke-dashoffset: 100;
            }
        }
    </style>


    <script>
        const geoSubmitBtn = document.getElementById('geoSubmitBtn');
        document.querySelector('form').addEventListener('submit', function() {
            geoSubmitBtn.disabled = true;
            geoSubmitBtn.innerText = "Saving...";
            geoSubmitBtn.classList.add('opacity-80');
        });
    </script>

    <script>
        const mgrsInput = document.getElementById('mgrsInput');
        const mgrsStatus = document.getElementById('mgrsStatus');
        const form = document.querySelector('form');

        const mgrsRegex = /^[0-9]{1,2}[C-HJ-NP-X][A-HJ-NP-Z]{2}[0-9]{2,10}$/i;

        mgrsInput.addEventListener('input', function() {

            const value = mgrsInput.value.toUpperCase().trim();

            if (mgrsRegex.test(value)) {
                mgrsInput.classList.remove('border-red-500');
                mgrsInput.classList.add('border-emerald-500');
                mgrsStatus.innerText = "✓ Valid MGRS format";
                mgrsStatus.classList.remove('text-red-400');
                mgrsStatus.classList.add('text-emerald-400');
            } else {
                mgrsInput.classList.remove('border-emerald-500');
                mgrsInput.classList.add('border-red-500');
                mgrsStatus.innerText = "Invalid MGRS format";
                mgrsStatus.classList.remove('text-emerald-400');
                mgrsStatus.classList.add('text-red-400');
            }

        });

        // Prevent submit if invalid
        form.addEventListener('submit', function(e) {
            if (!mgrsRegex.test(mgrsInput.value.trim())) {
                e.preventDefault();
                mgrsInput.classList.add('border-red-500');
                mgrsStatus.innerText = "Invalid MGRS format — cannot submit";
                mgrsStatus.classList.add('text-red-400');
            }
        });
    </script>

    <script>
        const threatSelect = document.getElementById('threatSelect');
        const threatPreview = document.getElementById('threatPreview');

        const threatColors = {
            "SRC": "bg-blue-500/10 text-blue-300 border-blue-500/30",
            "SRGU": "bg-red-500/10 text-red-300 border-red-500/30",
            "SRMA": "bg-orange-500/10 text-orange-300 border-orange-500/30",
            "SROC": "bg-green-500/10 text-green-300 border-green-500/30",
            "SRMA EMPORIUM": "bg-yellow-500/10 text-yellow-300 border-yellow-500/30",
            "SRMA ARCTIC": "bg-teal-500/10 text-teal-300 border-teal-500/30",
            "SRMA BROWSER": "bg-cyan-500/10 text-cyan-300 border-cyan-500/30",
            "SRMA SESAME": "bg-purple-500/10 text-purple-300 border-purple-500/30",
            "SRMA LEVOX": "bg-pink-500/10 text-pink-300 border-pink-500/30",
            "COMTECH": "bg-indigo-500/10 text-indigo-300 border-indigo-500/30",
            "EV MRGU": "bg-red-500/10 text-red-300 border-red-500/30",
            "FUNCTIONAL": "bg-purple-500/10 text-purple-300 border-purple-500/30"
        };

        threatSelect.addEventListener('change', function() {

            const value = threatSelect.value;

            if (!value) {
                threatPreview.classList.add('hidden');
                return;
            }

            threatPreview.className =
                "px-3 py-1 text-xs rounded-full border " + threatColors[value];

            threatPreview.innerText = value;
            threatPreview.classList.remove('hidden');
        });
    </script>

    <script>
        const uavSelect = document.getElementById('uavSelect');
        const uavPreview = document.getElementById('uavPreview');

        const uavColors = {
            "THOR": "bg-cyan-500/10 text-cyan-300 border-cyan-500/30 shadow-[0_0_15px_rgba(6,182,212,.4)]",
            "Griffin": "bg-emerald-500/10 text-emerald-300 border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,.4)]",
            "DJI MATRICE 1": "bg-indigo-500/10 text-indigo-300 border-indigo-500/30 shadow-[0_0_15px_rgba(99,102,241,.4)]",
            "DJI MATRICE 2": "bg-fuchsia-500/10 text-fuchsia-300 border-fuchsia-500/30 shadow-[0_0_15px_rgba(217,70,239,.4)]",
            "DJI MATRICE 3": "bg-pink-500/10 text-pink-300 border-pink-500/30 shadow-[0_0_15px_rgba(236,72,153,.4)]",
            "DJI MATRICE 4": "bg-yellow-500/10 text-yellow-300 border-yellow-500/30 shadow-[0_0_15px_rgba(250,204,21,.4)]"
        };

        uavSelect.addEventListener('change', function() {

            const value = uavSelect.value;

            if (!value) {
                uavPreview.classList.add('hidden');
                return;
            }

            uavPreview.className =
                "px-3 py-1 text-xs rounded-full border transition-all duration-300 " + uavColors[value];

            uavPreview.innerText = value;
            uavPreview.classList.remove('hidden');
        });
    </script>

    <script>
        const classificationSelect = document.getElementById('classificationSelect');
        const classificationPreview = document.getElementById('classificationPreview');

        const classificationColors = {
            "UNCLASSIFIED": "bg-slate-600/20 text-slate-300 border-slate-500/30",
            "RESTRICTED": "bg-yellow-500/10 text-yellow-300 border-yellow-500/30 shadow-[0_0_15px_rgba(250,204,21,.3)]",
            "CONFIDENTIAL": "bg-orange-500/10 text-orange-300 border-orange-500/30 shadow-[0_0_15px_rgba(249,115,22,.3)]",
            "SECRET": "bg-red-600/10 text-red-400 border-red-600/40 shadow-[0_0_20px_rgba(239,68,68,.4)]"
        };

        classificationSelect.addEventListener('change', function() {

            const value = classificationSelect.value;

            if (!value) {
                classificationPreview.classList.add('hidden');
                return;
            }

            classificationPreview.className =
                "px-3 py-1 text-xs rounded-full border tracking-wider transition-all duration-300 " +
                classificationColors[value];

            classificationPreview.innerText = value;
            classificationPreview.classList.remove('hidden');
        });
    </script>

    <script>
        /* ===============================
       BASE MAP + LAYERS
    =============================== */

        const standardLayer = L.tileLayer(
            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }
        );

        const satelliteLayer = L.tileLayer(
            'https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
            }
        );

        let map = L.map('geoMap', {
            center: [10.3157, 123.8854],
            zoom: 6,
            layers: [standardLayer]
        });

        /* Layer Toggle Control */
        const baseMaps = {
            "Standard Map": standardLayer,
            "Satellite": satelliteLayer
        };

        let overlayMaps = {};

        L.control.layers(baseMaps, overlayMaps).addTo(map);


        /* ===============================
           HEATMAP LAYER
        =============================== */

        const threatPoints = [
            [10.3157, 123.8854, 0.8],
            [10.3200, 123.8800, 0.6],
            [10.3100, 123.8900, 0.9]
        ];

        const heatLayer = L.heatLayer(threatPoints, {
            radius: 25,
            blur: 20,
            maxZoom: 17,
            gradient: {
                0.2: 'blue',
                0.4: 'cyan',
                0.6: 'yellow',
                0.8: 'orange',
                1.0: 'red'
            }
        });

        overlayMaps["Threat Heatmap"] = heatLayer;


        /* ===============================
           MARKER + FLIGHT PATH
        =============================== */

        let marker = null;
        let flightPath = null;

        const mapStatus = document.getElementById('mapStatus');

        function drawFlightPath(lat, lon) {

            const pathCoords = [
                [lat - 0.005, lon - 0.005],
                [lat, lon],
                [lat + 0.005, lon + 0.004]
            ];

            if (flightPath) {
                map.removeLayer(flightPath);
            }

            flightPath = L.polyline(pathCoords, {
                color: '#06b6d4',
                weight: 3,
                dashArray: '5,8'
            }).addTo(map);

            flightPath._path.classList.add('flight-glow');
        }


        /* ===============================
           UPDATE FROM MGRS
        =============================== */

        function updateMapFromMGRS(mgrsValue) {

            try {

                const latlon = mgrs.toPoint(mgrsValue);
                const lat = latlon[1];
                const lon = latlon[0];

                document.getElementById('latitudeInput').value = lat;
                document.getElementById('longitudeInput').value = lon;

                map.setView([lat, lon], 15);

                if (marker) {
                    map.removeLayer(marker);
                }

                marker = L.circleMarker([lat, lon], {
                    radius: 12,
                    color: '#06b6d4',
                    fillColor: '#06b6d4',
                    fillOpacity: 0.6,
                    weight: 2
                }).addTo(map);

                marker._path.classList.add('tactical-glow');

                drawFlightPath(lat, lon);

                mapStatus.innerText = "Operational Coordinate Locked";
                mapStatus.classList.remove('text-red-400');
                mapStatus.classList.add('text-emerald-400');

            } catch (error) {

                mapStatus.innerText = "Invalid coordinate — map not updated";
                mapStatus.classList.remove('text-emerald-400');
                mapStatus.classList.add('text-red-400');
            }
        }


        /* ===============================
           INPUT HOOK
        =============================== */

        mgrsInput.addEventListener('input', function() {
            const value = mgrsInput.value.trim().toUpperCase();

            if (mgrsRegex.test(value)) {
                updateMapFromMGRS(value);
            }
        });
    </script>

</x-app-layout>