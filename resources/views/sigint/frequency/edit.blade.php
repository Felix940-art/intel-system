<x-app-layout>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <div class="max-w-6xl mx-auto py-10">

        {{-- HEADER --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-white">Edit Frequency Entry</h1>
                <p class="text-sm text-slate-400">
                    Record #{{ $frequency->id }} • Created {{ $frequency->created_at->diffForHumans() }}
                </p>
            </div>

            <a href="{{ route('sigint.frequency.index') }}"
                class="text-slate-300 hover:text-white text-sm">
                ← Back
            </a>
        </div>

        {{-- ===================== UPDATE FORM (ONLY ONE FORM) ===================== --}}
        <form method="POST"
            action="{{ route('sigint.frequency.update', $frequency->id) }}"
            class="bg-slate-900 border border-slate-800 rounded-xl p-8 space-y-10">

            @csrf
            @method('PUT')

            {{-- ================= SIGNAL IDENTITY ================= --}}
            <section class="section-box">
                <h2 class="section-title">Signal Identity</h2>

                <div class="grid md:grid-cols-3 gap-6 items-end">
                    <div>
                        <label>Frequency *</label>
                        <input name="frequency"
                            value="{{ old('frequency', $frequency->frequency) }}"
                            class="input-dark"
                            required>
                    </div>

                    <div>
                        <label>Date & Time *</label>
                        <input name="datetime_code"
                            value="{{ old('datetime_code', $frequency->datetime_code) }}"
                            class="input-dark"
                            required>
                    </div>

                    {{-- WATCHLIST --}}
                    <div class="flex items-center gap-3 mt-6">
                        {{-- IMPORTANT: hidden field ensures OFF is sent --}}
                        <input type="hidden" name="is_watchlisted" value="0">

                        <input type="checkbox"
                            name="is_watchlisted"
                            value="1"
                            {{ old('is_watchlisted', $frequency->is_watchlisted) ? 'checked' : '' }}
                            class="w-5 h-5">

                        <span class="text-slate-200">Add to Watchlist</span>
                    </div>
                </div>
            </section>

            {{-- ================= SIGNAL QUALITY & ORIGIN ================= --}}
            <section class="section-box">
                <h2 class="section-title">Signal Quality & Origin</h2>

                <div class="grid md:grid-cols-2 gap-6 mb-6">

                    {{-- LINE OF BEARING --}}
                    <input name="lob"
                        id="lob"
                        type="number"
                        min="0" max="360"
                        value="{{ old('lob', $frequency->lob) }}"
                        class="input-dark"
                        placeholder="Line of Bearing (°)">

                    {{-- CLARITY --}}
                    <select name="clarity" class="input-dark">
                        <option value="">— Clarity —</option>

                        @foreach([
                        '1x1','1x2','1x3','1x4','1x5',
                        '2x1','2x2','2x3','2x4','2x5',
                        '3x1','3x2','3x3','3x4','3x5',
                        '4x1','4x2','4x3','4x4','4x5',
                        '5x1','5x2','5x3','5x4','5x5'
                        ] as $c)

                        <option value="{{ $c }}"
                            {{ old('clarity', $frequency->clarity) === $c ? 'selected' : '' }}>
                            {{ $c }}
                        </option>

                        @endforeach
                    </select>

                </div>

                {{-- MAP --}}
                <div id="lobMap"
                    class="mt-4 h-64 rounded-xl border border-slate-700">
                </div>

                {{-- ORIGIN LOCATION --}}
                <div class="mt-6">

                    <div class="grid md:grid-cols-3 gap-4">

                        <input id="barangay"
                            name="barangay"
                            value="{{ old('barangay', $frequency->barangay) }}"
                            class="input-dark"
                            placeholder="Barangay">

                        <input id="municipality"
                            name="municipality"
                            value="{{ old('municipality', $frequency->municipality) }}"
                            class="input-dark"
                            placeholder="Municipality">

                        <input id="province"
                            name="province"
                            value="{{ old('province', $frequency->province) }}"
                            class="input-dark"
                            placeholder="Province">

                        {{-- SAVED COORDINATES --}}
                        <input type="hidden"
                            id="origin_lat"
                            name="origin_lat"
                            value="{{ $frequency->origin_lat }}">

                        <input type="hidden"
                            id="origin_lng"
                            name="origin_lng"
                            value="{{ $frequency->origin_lng }}">

                    </div>

                </div>

            </section>

            {{-- ================= OPERATIONAL CONTEXT ================= --}}
            <section class="section-box">
                <h2 class="section-title">Operational Context</h2>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label>Site Location</label>
                        <input name="site_location"
                            value="{{ old('site_location', $frequency->site_location) }}"
                            class="input-dark"
                            placeholder="e.g. Catbalogan City, Samar">
                    </div>

                    <div>
                        <label>Conversation</label>
                        <textarea name="conversation"
                            rows="4"
                            class="input-dark"
                            placeholder="Raw intercepted content only">{{ old('conversation', $frequency->conversation) }}</textarea>
                    </div>
                </div>
            </section>

            {{-- ================= THREAT ASSESSMENT ================= --}}
            <section class="section-box">
                <h2 class="section-title">Threat Assessment</h2>

                <select name="threat_confronted" class="input-dark">
                    <option value="">— None —</option>
                    @foreach(['SRC','SRGU','SRMA','SROC','SRMA EMPORIUM','SRMA ARCTIC','SRMA BROWSER','SRMA SESAME','SRMA LEVOX','COMTECH','EV MRGU','FUNCTIONAL'] as $t)
                    <option value="{{ $t }}"
                        {{ old('threat_confronted', $frequency->threat_confronted) === $t ? 'selected' : '' }}>
                        {{ $t }}
                    </option>
                    @endforeach
                </select>
            </section>

            {{-- ================= ACTION BAR ================= --}}
            <div class="flex justify-between items-center border-t border-slate-800 pt-6">
                <a href="{{ route('sigint.frequency.index') }}"
                    class="px-4 py-2 bg-slate-700 rounded-lg text-white">
                    Cancel
                </a>

                <button type="submit"
                    class="px-6 py-2 bg-indigo-600 hover:bg-indigo-500 rounded-lg font-semibold text-white">
                    💾 Save Changes
                </button>
            </div>
        </form>

        {{-- ================= DELETE (SEPARATE FORM – IMPORTANT) ================= --}}
        <form method="POST"
            action="{{ route('sigint.frequency.destroy', $frequency->id) }}"
            onsubmit="return confirm('Delete this frequency entry permanently?')"
            class="mt-6 text-right">

            @csrf
            @method('DELETE')

            <button class="text-red-400 hover:text-red-300 text-sm">
                🗑 Delete Entry
            </button>
        </form>
    </div>

    {{-- ================= STYLES ================= --}}
    <style>
        .section-box {
            border: 1px solid #1e293b;
            border-radius: 1rem;
            padding: 1.5rem;
        }

        .section-title {
            font-size: .7rem;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 1rem;
        }

        .input-dark {
            width: 100%;
            background: #020617;
            color: #e5e7eb;
            border: 1px solid #334155;
            border-radius: .5rem;
            padding: .6rem .75rem;
        }

        .input-dark:focus {
            outline: none;
            border-color: #3b82f6;
        }

        #lobMap {
            height: 260px;
            width: 100%;
            background: #020617;
            border-radius: .75rem;
        }
    </style>

    {{-- MAP SCRIPT --}}
    <script>
        let map;
        let originMarker;
        let lobLine;

        function initMap(lat = 12.8797, lng = 121.7740) {

            map = L.map('lobMap').setView([lat, lng], 7);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18
            }).addTo(map);

        }

        function drawLOB(lat, lng, bearing) {

            if (lobLine) map.removeLayer(lobLine);

            const distance = 1; // degrees approx

            const rad = bearing * Math.PI / 180;

            const lat2 = lat + distance * Math.cos(rad);
            const lng2 = lng + distance * Math.sin(rad);

            lobLine = L.polyline([
                [lat, lng],
                [lat2, lng2]
            ], {
                color: "red",
                weight: 3
            }).addTo(map);

        }

        async function updateLocation() {

            const barangay = document.getElementById('barangay').value;
            const municipality = document.getElementById('municipality').value;
            const province = document.getElementById('province').value;

            if (!barangay || !municipality || !province) return;

            const query = `${barangay}, ${municipality}, ${province}, Philippines`;

            const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`;

            const res = await fetch(url);
            const data = await res.json();

            if (data.length === 0) return;

            const lat = parseFloat(data[0].lat);
            const lng = parseFloat(data[0].lon);

            map.setView([lat, lng], 12);

            if (originMarker) map.removeLayer(originMarker);

            originMarker = L.marker([lat, lng]).addTo(map);

            const bearing = parseFloat(document.getElementById('lob').value);

            if (bearing) {
                drawLOB(lat, lng, bearing);
            }

        }

        document.addEventListener("DOMContentLoaded", function() {

            initMap();

            updateLocation();

            document.getElementById('barangay').addEventListener('change', updateLocation);
            document.getElementById('municipality').addEventListener('change', updateLocation);
            document.getElementById('province').addEventListener('change', updateLocation);
            document.getElementById('lob').addEventListener('input', updateLocation);

        });
    </script>
</x-app-layout>