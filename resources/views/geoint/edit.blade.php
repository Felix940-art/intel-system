<x-app-layout>

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/mgrs/dist/mgrs.min.js"></script>
    <script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>

    <div class="max-w-3xl mx-auto space-y-8">

        {{-- HEADER --}}
        <div>
            <h1 class="text-3xl font-semibold text-cyan-400 tracking-wide flex items-center gap-3">
                ✏ Edit GEOINT Mission
            </h1>

            <p class="text-sm text-slate-500 mt-2 tracking-widest uppercase">
                Intelligence Surveillance & Reconnaissance Update
            </p>
        </div>

        {{-- FORM CARD --}}
        <div class="intel-console p-10 rounded-xl border border-slate-800">

            <form method="POST"
                action="{{ route('geoint.update', $record->id) }}"
                enctype="multipart/form-data"
                class="space-y-6">

                @csrf
                @method('PUT')

                {{-- DATE --}}
                <div>
                    <label class="form-label">Date & Time</label>
                    <input type="datetime-local"
                        name="mission_datetime"
                        value="{{ \Carbon\Carbon::parse($record->mission_datetime)->format('Y-m-d\TH:i') }}"
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
                        @foreach(['THOR','Griffin','Matrix 1','Matrix 2'] as $uav)
                        <option value="{{ $uav }}"
                            {{ $record->uav == $uav ? 'selected' : '' }}>
                            {{ $uav }}
                        </option>
                        @endforeach
                    </select>

                    <div class="mt-3">
                        <span id="uavPreview"
                            class="hidden px-3 py-1 text-xs rounded-full border">
                        </span>
                    </div>
                </div>

                {{-- MGRS --}}
                <div>
                    <label class="form-label">Home Point (MGRS)</label>

                    <input type="text"
                        id="mgrsInput"
                        name="home_point_mgrs"
                        value="{{ $record->home_point_mgrs }}"
                        class="form-input"
                        required>

                    <input type="hidden" name="latitude" id="latitudeInput">
                    <input type="hidden" name="longitude" id="longitudeInput">

                    <p id="mgrsStatus"
                        class="text-xs mt-2 text-slate-400">
                        Format: 51PXM1234567890
                    </p>
                </div>

                {{-- MAP --}}
                <div>
                    <label class="form-label">Map Preview</label>
                    <div class="tactical-map-frame">
                        <div id="geoMap" style="height: 380px;"></div>
                    </div>
                    <p id="mapStatus"
                        class="text-xs text-slate-400 mt-2">
                        Loading coordinate...
                    </p>
                </div>

                {{-- THREAT --}}
                <div>
                    <label class="form-label">Threat Confronted</label>

                    <select name="threat_confronted"
                        id="threatSelect"
                        class="form-input"
                        required>

                        <option value="">Select Threat</option>
                        @foreach(['SRC','SRGU','SRMA','SROC','SRMA EMPORIUM','SRMA ARCTIC','SRMA BROWSER','SRMA SESAME','SRMA LEVOX','COMTECH','EV MRGU','UNKNOWN'] as $threat)
                        <option value="{{ $threat }}"
                            {{ $record->threat_confronted == $threat ? 'selected' : '' }}>
                            {{ $threat }}
                        </option>
                        @endforeach
                    </select>

                    <div class="mt-3">
                        <span id="threatPreview"
                            class="hidden px-3 py-1 text-xs rounded-full border">
                        </span>
                    </div>
                </div>

                {{-- CLASSIFICATION --}}
                <div>
                    <label class="form-label">Classification</label>

                    <select name="classification"
                        id="classificationSelect"
                        class="form-input"
                        required>
                        @foreach(['UNCLASSIFIED','RESTRICTED','CONFIDENTIAL','SECRET'] as $class)
                        <option value="{{ $class }}"
                            {{ $record->classification == $class ? 'selected' : '' }}>
                            {{ $class }}
                        </option>
                        @endforeach
                    </select>

                    <div class="mt-3">
                        <span id="classificationPreview"
                            class="hidden px-3 py-1 text-xs rounded-full border tracking-wider">
                        </span>
                    </div>
                </div>

                {{-- DOCUMENT --}}
                <div>
                    <label class="form-label">Replace Document</label>
                    <input type="file"
                        name="document"
                        accept=".pdf,.jpg,.jpeg,.png"
                        class="form-input">
                </div>

                <div class="flex justify-end gap-4 pt-4">
                    <a href="{{ route('geoint.index') }}"
                        class="btn-secondary">
                        Cancel
                    </a>

                    <button type="submit"
                        id="geoSubmitBtn"
                        class="btn-accent">
                        Update Mission
                    </button>
                </div>

            </form>

        </div>
    </div>

    <style>
        .tactical-map-frame {
            border-radius: 16px;
            padding: 6px;
            background: linear-gradient(145deg, #0b1220, #0f172a);
            border: 1px solid #1e293b;
            box-shadow:
                0 0 20px rgba(6, 182, 212, 0.15);
        }

        .form-input {
            width: 100%;
            background: linear-gradient(145deg, #0b1220, #0f172a);
            border: 1px solid #1e293b;
            border-radius: 10px;
            padding: 12px 14px;
            color: #e2e8f0;
            font-size: 14px;
            transition: all .25s ease;
        }

        .form-input::placeholder {
            color: #64748b;
        }

        .form-input:focus {
            outline: none;
            border-color: #06b6d4;
            box-shadow:
                0 0 0 1px #06b6d4,
                0 0 15px rgba(6, 182, 212, 0.3);
        }

        select.form-input {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3Csvg width='16' height='16' fill='%2394a3b8' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill-rule='evenodd' d='M5.23 7.21a.75.75 0 011.06.02L10 11.148l3.71-3.917a.75.75 0 111.08 1.04l-4.25 4.487a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z' clip-rule='evenodd'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px;
        }

        .intel-console {
            background: linear-gradient(180deg, #060d1a, #0b1220);
            border: 1px solid #1f2937;
            border-radius: 20px;
            box-shadow:
                inset 0 0 60px rgba(0, 255, 255, 0.03),
                0 0 40px rgba(0, 255, 255, 0.05);
        }

        .btn-accent {
            background: linear-gradient(135deg, #06b6d4, #0891b2);
            padding: 10px 22px;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            letter-spacing: 1px;
            transition: .3s;
        }

        .btn-accent:hover {
            box-shadow: 0 0 20px rgba(6, 182, 212, .4);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #1e293b;
            padding: 10px 22px;
            border-radius: 12px;
            color: #cbd5e1;
            border: 1px solid #334155;
        }

        @keyframes intelPulse {
            0% {
                opacity: .6;
            }

            50% {
                opacity: 1;
            }

            100% {
                opacity: .6;
            }
        }

        .text-emerald-400 {
            animation: intelPulse 1.8s infinite;
        }
    </style>

    <script>
        const standardLayer = L.tileLayer(
            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'
        );

        let map = L.map('geoMap', {
            center: [10.3157, 123.8854],
            zoom: 6,
            layers: [standardLayer]
        });

        let marker = null;
        const mgrsInput = document.getElementById('mgrsInput');
        const mapStatus = document.getElementById('mapStatus');

        function updateMapFromMGRS(value) {

            try {

                const latlon = mgrs.toPoint(value);
                const lat = latlon[1];
                const lon = latlon[0];

                document.getElementById('latitudeInput').value = lat;
                document.getElementById('longitudeInput').value = lon;

                map.setView([lat, lon], 15);

                if (marker) map.removeLayer(marker);

                marker = L.marker([lat, lon])
                    .addTo(map)
                    .bindPopup("Mission Location")
                    .openPopup();

                mapStatus.innerText = "Operational Coordinate Locked";
                mapStatus.classList.remove('text-red-400');
                mapStatus.classList.add('text-emerald-400', 'font-semibold');

            } catch (error) {

                mapStatus.innerText = "Invalid coordinate";
                mapStatus.classList.remove('text-red-400');
                mapStatus.classList.add('text-emerald-400', 'font-semibold');
            }
        }

        /* Initial load */
        updateMapFromMGRS(mgrsInput.value);

        /* Live update */
        mgrsInput.addEventListener('input', function() {
            updateMapFromMGRS(this.value);
        });
    </script>

</x-app-layout>