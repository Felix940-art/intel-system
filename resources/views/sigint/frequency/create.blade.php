<x-app-layout>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <div class="max-w-5xl mx-auto">

        <h1 class="text-3xl font-bold text-slate-100 mb-6">
            ➕ Add RADIO FREQUENCY
        </h1>

        <form method="POST"
            action="{{ route('sigint.frequency.store') }}"
            class="bg-slate-900 border border-slate-800 rounded-xl p-8 space-y-8">

            @csrf

            <!-- ================= SIGNAL IDENTITY ================= -->
            <section class="card">
                <h2 class="section-title">Signal Identity</h2>

                <div class="grid md:grid-cols-3 gap-6 items-end">
                    <div>
                        <label class="label">Frequency (MHz) *</label>
                        <input id="frequency" name="frequency" required class="input-dark">
                        <p id="freq-warning" class="hidden text-sm mt-1"></p>
                    </div>

                    <div>
                        <label class="label">Date & Time *</label>
                        <input id="datetime_code" name="datetime_code" required class="input-dark">
                    </div>

                    <div>
                        <label class="flex items-center gap-3">
                            <input type="hidden" name="is_watchlisted" value="0">

                            <label class="flex items-center gap-3">
                                <input type="checkbox"
                                    name="is_watchlisted"
                                    value="1"
                                    {{ old('is_watchlisted') ? 'checked' : '' }}>
                                <span class="text-slate-300">Add to Watchlist</span>
                            </label>
                        </label>
                    </div>


                </div>
            </section>

            <!-- ================= SIGNAL QUALITY ================= -->
            <section class="card">
                <h2 class="section-title">Signal Quality & Location</h2>

                <div class="grid md:grid-cols-2 gap-6">
                    <input id="lob" name="lob" type="number" min="0" max="360"
                        placeholder="Line of Bearing (°)"
                        class="input-dark">


                    <select name="clarity" class="input-dark">
                        <option value="">——</option>
                        <option>1x1</option>
                        <option>1x2</option>
                        <option>1x3</option>
                        <option>1x4</option>
                        <option>1x5</option>

                        <option>2x1</option>
                        <option>2x2</option>
                        <option>2x3</option>
                        <option>2x4</option>
                        <option>2x5</option>

                        <option>3x1</option>
                        <option>3x2</option>
                        <option>3x3</option>
                        <option>3x4</option>
                        <option>3x5</option>

                        <option>4x1</option>
                        <option>4x2</option>
                        <option>4x3</option>
                        <option>4x4</option>
                        <option>4x5</option>

                        <option>5x1</option>
                        <option>5x2</option>
                        <option>5x3</option>
                        <option>5x4</option>
                        <option>5x5</option>
                    </select>

                </div>

                <div id="lobMap" class="mt-4 h-64 rounded-xl border border-slate-700"></div>

                <!-- ORIGIN -->
                <div class="mt-6 inner-card">
                    <p class="section-subtitle">📍 Site Location</p>

                    <div class="grid md:grid-cols-3 gap-4">
                        <input id="barangay" name="barangay" placeholder="Barangay" class="input-dark">
                        <input id="municipality" name="municipality" placeholder="Municipality" class="input-dark">
                        <input id="province" name="province" placeholder="Province" class="input-dark">
                        <input type="hidden" id="origin_lat" name="origin_lat">
                        <input type="hidden" id="origin_lng" name="origin_lng">
                    </div>
                </div>
            </section>

            <!-- ================= OPERATIONAL CONTEXT ================= -->
            <section class="card">
                <h2 class="section-title">Operational Context</h2>

                <div class="grid md:grid-cols-2 gap-6">
                    <input name="site_location" placeholder="Possible Origin" class="input-dark">
                    <textarea name="conversation" rows="4" placeholder="Conversation" class="input-dark"></textarea>
                </div>
            </section>



            <!-- ================= THREAT ================= -->
            <section class="card">
                <h2 class="section-title">Threat Assessment</h2>

                <select id="threat_group" name="threat_confronted" class="input-dark mb-4">
                    <option value="">— None —</option>
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
            </section>

            <div class="flex justify-between">
                <a href="{{ route('sigint.frequency.index') }}" class="btn-secondary">← Back</a>
                <button class="btn-primary">Save Entry</button>
            </div>
        </form>
    </div>

    <!-- ================= STYLES ================= -->
    <style>
        .card {
            background: #020617;
            border: 1px solid #1e293b;
            border-radius: 1rem;
            padding: 1.5rem
        }

        .inner-card {
            background: #020617;
            border: 1px solid #1e293b;
            border-radius: .75rem;
            padding: 1rem
        }

        .section-title {
            font-size: .75rem;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 1rem
        }

        .section-subtitle {
            font-size: .7rem;
            color: #94a3b8;
            margin-bottom: .75rem
        }

        .label {
            font-size: .8rem;
            color: #cbd5f5;
            margin-bottom: .25rem;
            display: block
        }

        .input-dark {
            width: 100%;
            background: #020617 !important;
            color: #e5e7eb !important;
            border: 1px solid #334155;
            border-radius: .5rem;
            padding: .55rem .75rem;
        }

        .input-dark::placeholder {
            color: #64748b
        }

        .input-dark:focus {
            outline: none;
            border-color: #3b82f6
        }

        .input-dark[readonly] {
            background: #020617 !important;
            color: #e5e7eb !important;
            cursor: not-allowed;
        }

        .checkbox-dark {
            width: 1.1rem;
            height: 1.1rem
        }

        .btn-primary {
            background: #2563eb;
            color: white;
            padding: .5rem 1.5rem;
            border-radius: .5rem
        }

        .btn-secondary {
            background: #334155;
            color: #e5e7eb;
            padding: .5rem 1.5rem;
            border-radius: .5rem
        }
    </style>


    <!-- ================= MAP + REAL-TIME LOB LOGIC ================= -->
    <script>
        /* ================= ELEMENTS ================= */
        const barangayInput = document.getElementById('barangay');
        const municipalityInput = document.getElementById('municipality');
        const provinceInput = document.getElementById('province');

        const originLatInput = document.getElementById('origin_lat');
        const originLngInput = document.getElementById('origin_lng');
        const lobInput = document.querySelector('input[name="lob"]');

        /* ================= MAP STATE ================= */
        let map, originMarker, lobLine;

        /* ================= INIT MAP ================= */
        function initMap(lat = 12.8797, lng = 121.7740, zoom = 6) {
            if (!map) {
                map = L.map('lobMap').setView([lat, lng], zoom);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap'
                }).addTo(map);
            } else {
                map.setView([lat, lng], zoom);
                map.invalidateSize();
            }
        }

        /* ================= DRAW ORIGIN ================= */
        function setOrigin(lat, lng) {
            initMap(lat, lng, 13);

            if (originMarker) map.removeLayer(originMarker);
            originMarker = L.marker([lat, lng]).addTo(map);

            // 🔥 IF BEARING EXISTS → REDRAW LOB
            const bearing = parseFloat(lobInput.value);
            if (!isNaN(bearing)) {
                drawLOB(lat, lng, bearing);
            }
        }

        /* ================= DRAW LOB ================= */
        function drawLOB(lat, lng, bearing) {
            if (!map) return;

            if (lobLine) map.removeLayer(lobLine);

            const distanceKm = 20;
            const rad = bearing * Math.PI / 180;

            const destLat = lat + (distanceKm / 111) * Math.cos(rad);
            const destLng = lng + (distanceKm / (111 * Math.cos(lat * Math.PI / 180))) * Math.sin(rad);

            lobLine = L.polyline([
                [lat, lng],
                [destLat, destLng]
            ], {
                color: 'red',
                weight: 3
            }).addTo(map);
        }

        /* ================= GEOCODE ORIGIN ================= */
        async function geocodeOriginAndUpdate() {
            const query = [
                barangayInput.value,
                municipalityInput.value,
                provinceInput.value,
                'Philippines'
            ].filter(Boolean).join(', ');

            if (!query) return;

            const res = await fetch(
                `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`
            );

            const data = await res.json();
            if (!data.length) return;

            const lat = parseFloat(data[0].lat);
            const lng = parseFloat(data[0].lon);

            originLatInput.value = lat;
            originLngInput.value = lng;

            setOrigin(lat, lng);
        }

        /* ================= EVENTS ================= */

        /* 🔁 Origin changes → ALWAYS re-anchor map + LOB */
        [barangayInput, municipalityInput, provinceInput].forEach(el => {
            el?.addEventListener('input', () => {
                // Clear old coords so we don't use stale origin
                originLatInput.value = '';
                originLngInput.value = '';
            });

            el?.addEventListener('blur', geocodeOriginAndUpdate);
        });

        /* 🔁 Bearing changes → ALWAYS draw from CURRENT origin */
        lobInput?.addEventListener('input', async () => {
            const bearing = parseFloat(lobInput.value);
            if (isNaN(bearing)) return;

            if (!originLatInput.value || !originLngInput.value) {
                await geocodeOriginAndUpdate();
            }

            if (!originLatInput.value || !originLngInput.value) {
                Swal.fire({
                    icon: 'info',
                    title: 'Origin Required',
                    text: 'Set Possible Origin before applying bearing.'
                });
                lobInput.value = '';
                return;
            }

            drawLOB(
                parseFloat(originLatInput.value),
                parseFloat(originLngInput.value),
                bearing
            );
        });

        /* ================= INIT ================= */
        document.addEventListener('DOMContentLoaded', () => {
            initMap(); // Default PH view
        });
    </script>

    <style>
        #lobMap {
            height: 260px;
            width: 100%;
            background: #020617;
            border-radius: 0.75rem;
        }
    </style>

</x-app-layout>