<x-app-layout>

    <div class="space-y-2">

        <!-- PAGE HEADER -->
        <div>
            <h1 class="text-2xl font-bold text-white">Dashboard</h1>
            <p class="text-sm text-slate-400">
                System overview and operational status
            </p>
        </div>

        <div id="top-alert-container">
            @if(!empty($alerts))
            @php
            $first = $alerts[0];

            if (is_string($first)) {
            $first = [
            'level' => 'LOW',
            'message' => $first,
            'details' => ''
            ];
            }

            $level = $first['level'] ?? 'LOW';
            @endphp

            <div class="
@if($level === 'HIGH') threat-high
@elseif($level === 'MEDIUM') bg-yellow-500/10 text-yellow-400
@else bg-blue-500/10 text-blue-400
@endif">

                <strong>{{ $level }}</strong> —
                {{ $first['message'] ?? '' }}
                {{ $first['details'] ?? '' }}

            </div>
            @endif
        </div>

        <div id="alert-container" class="space-y-3">
            @forelse($alerts as $alert)

            @php
            if (is_string($alert)) {
            $alert = [
            'level' => 'LOW',
            'message' => $alert,
            'details' => ''
            ];
            }

            $level = $alert['level'] ?? 'LOW';
            @endphp

            <div class="border-l-4 pl-3
            @if($level === 'HIGH') border-red-500
            @elseif($level === 'MEDIUM') border-yellow-500
            @else border-blue-500
            @endif">

                <p class="text-sm font-semibold">
                    {{ $alert['message'] ?? 'Unknown alert' }}
                </p>

                @if(!empty($alert['details']))
                <p class="text-xs text-gray-400">
                    {{ $alert['details'] }}
                </p>
                @endif

            </div>

            @empty
            <p class="text-gray-400 text-sm">No active threats</p>
            @endforelse
        </div>

        <div class="mb-4 p-4 rounded-xl border border-slate-700 bg-slate-900">
            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm text-gray-400">Threat Level</p>

                    @php
                    $level = match(true) {
                    $threatScore >= 70 => 'CRITICAL',
                    $threatScore >= 40 => 'ELEVATED',
                    $threatScore >= 20 => 'GUARDED',
                    default => 'LOW'
                    };

                    $color = match($level) {
                    'CRITICAL' => 'red',
                    'ELEVATED' => 'yellow',
                    'GUARDED' => 'blue',
                    default => 'green'
                    };
                    @endphp

                    <p class="text-xl font-bold text-{{ $color }}-400">
                        {{ $level }}
                    </p>
                </div>

                <div class="text-right">
                    <p class="text-sm text-gray-400">Score</p>
                    <p class="text-2xl font-bold" data-threat-score>
                        {{ $threatScore }}/100
                    </p>
                </div>

            </div>

            <!-- Progress Bar -->
            <div class="mt-3 w-full bg-slate-700 rounded-full h-2">
                <div class="h-2 rounded-full bg-{{ $color }}-500"
                    data-threat-bar
                    style="width: {{ $threatScore }}%"></div>
            </div>
        </div>

        <!-- STATUS CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-2">

            <div class="bg-slate-900 border border-slate-800 rounded-lg p-5">
                <p class="text-xs font-bold text-yellow-400 mt-2">Radio Frequency: {{ $rfCount ?? 0 }}</p>
                <p class="text-xs font-bold text-yellow-400 mt-1">SRE: {{ $sreCount ?? 0 }}</p>
                <span class="text-xs text-slate-500">
                    SIGINT</span>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-lg p-5">
                <p class="text-xl font-bold text-white mt-2">{{ $geoint ?? 0 }}</p>
                <span class="text-xs text-slate-500">GEOINT</span>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-lg p-5">
                <p class="text-xl font-bold text-red-400 mt-2">{{ $dforensics ?? 0 }}</p>
                <span class="text-xs text-slate-500">D-FORENSICS</span>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-lg p-5">
                <p class="text-xl font-bold text-white mt-2">{{ $totalRecords ?? 0 }}</p>
                <span class="text-xs text-slate-500">Total</span>
            </div>
        </div>

        <!-- 📈 TREND GRAPH + 🗺 MAP-->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-2">
            <div class="bg-slate-900 border border-slate-800 rounded-lg p-5">
                <h2 class="text-sm font-semibold mb-4">Intelligence Activity Trend</h2>
                <canvas id="activityChart"></canvas>
            </div>

            <!-- TACTICAL MAP -->
            <div class="bg-slate-900 border border-slate-800 rounded-lg p-5">
                <h2 class="text-sm font-semibold mb-4">Deployment Map</h2>
                <div id="map" style="height: 400px; border-radius: 12px;"></div>
                <div class="mt-2 text-xs text-gray-400">
                    <span class="text-red-400">● HIGH</span> |
                    <span class="text-orange-400">● MEDIUM</span> |
                    <span class="text-blue-400">● LOW</span>
                </div>
            </div>
        </div>

        <!-- FORENSICS + ALERTS -->
        <div class="grid grid-cols-2 xl:grid-cols-2 gap-2">
            <!-- DIGITAL FORENSICS PREVIEW -->
            <div class="bg-slate-900 border border-slate-800 rounded-lg p-5">
                <div class="p-1 border-b border-slate-800">
                    <h2 class="text-sm font-semibold mb-4">DIGITAL FORENSICS Analysis</h2>
                </div>

                <div class="p-5 spcae-y-4">
                    @forelse($forensics as $item)
                    <div class="border-b border-slate-800 py-3">
                        <div class="text-sm font-semibold">
                            {{ $item->equipment_type ?? 'Unknown Device' }}
                        </div>
                        <div class="text-xs text-slate-400">
                            {{ $item->created_at->format('M d, Y H:i') ?? '' }}
                        </div>
                    </div>
                    @empty
                    <div class="text-slate-400 text-sm">No forensic data available</div>
                    @endforelse
                </div>

            </div>

            <!-- ALERTS PANEL -->
            <div class="bg-slate-900 border border-slate-800 rounded-lg">
                <div class="p-5 border-b border-slate-800">
                    <h2 class="font-semibold text-sm">Alerts</h2>
                </div>

                <div class="p-5 space-y-4 text-sm" id="alerts-panel">

                    @forelse($alerts as $alert)

                    @php
                    if (is_string($alert)) {
                    $alert = [
                    'level' => 'LOW',
                    'message' => $alert,
                    'details' => ''
                    ];
                    }

                    $level = $alert['level'] ?? 'LOW';
                    @endphp

                    <div class="border-l-4 pl-3
        @if($level === 'HIGH') border-red-600
        @elseif($level === 'MEDIUM') border-yellow-500
        @else border-blue-500
        @endif">

                        <div class="font-semibold
            @if($level === 'HIGH') text-red-400
            @elseif($level === 'MEDIUM') text-yellow-400
            @else text-blue-400
            @endif">

                            {{ $alert['message'] ?? 'Unknown alert' }}
                        </div>

                        @if(!empty($alert['details']))
                        <div class="text-slate-300 text-xs">
                            {{ $alert['details'] }}
                        </div>
                        @endif

                    </div>

                    @empty
                    <div class="text-slate-400">
                        No active alerts
                    </div>
                    @endforelse

                </div>
            </div>

        </div>

        <!-- ACTIVITY -->
        <div class="grid grid-cols-1 xl:grid-cols-1">

            <!-- RECENT ACTIVITY -->
            <div class="xl:col-span-2 bg-slate-900 border border-slate-800 rounded-lg">
                <div class="p-5 border-b border-slate-800">
                    <h2 class="font-semibold text-white">Recent Activity</h2>
                </div>
                <div class="p-5 overflow-x-auto">
                    <table class="min-w-full text-sm border-collapse">
                        <thead class="bg-slate-800 text-slate-400 uppercase text-xs">
                            <tr>
                                <th class="px-4 py-3 text-left">Time</th>
                                <th class="px-4 py-3 text-left">Module</th>
                                <th class="px-4 py-3 text-left">Action</th>
                                <th class="px-4 py-3 text-left">User</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800 text-slate-200">
                            @forelse($activities as $activity)
                            <tr class="hover:bg-slate-800 transition">
                                <td class="px-4 py-3">
                                    {{ \Carbon\Carbon::parse($activity->created_at)->format('H:i') }}
                                </td>

                                <td class="px-4 py-3 font-medium">
                                    {{ $activity->module }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $activity->action }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $activity->user }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-4 py-3 text-center text-slate-500">
                                    No recent activity
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

    <!-- SCRIPTS -->

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{-- LIBRARIES --}}
    <!-- Leaflet (you already have this) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <!-- Marker Cluster -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.css" />
    <script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>

    <!-- Heatmap -->
    <script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>

    <!-- ✅ ADD THIS (DRAW TOOL) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-draw/dist/leaflet.draw.css" />
    <script src="https://unpkg.com/leaflet-draw/dist/leaflet.draw.js"></script>

    <script>
        const rawData = @json($heatData ?? []);

        const map = L.map('map').setView([10.3157, 123.8854], 6);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19
        }).addTo(map);

        const markerLayer = L.layerGroup().addTo(map);

        const heatLayer = L.heatLayer([], {
            radius: 35,
            blur: 28,
            maxZoom: 10,
            gradient: {
                0.2: "#1e3a8a",
                0.4: "#2563eb",
                0.6: "#22c55e",
                0.8: "#facc15",
                1.0: "#ef4444"
            }
        }).addTo(map);

        function renderMap() {

            markerLayer.clearLayers();
            heatLayer.setLatLngs([]);

            const heatPoints = [];

            rawData.forEach(r => {

                if (!r.lat || !r.lng) return;

                heatPoints.push([r.lat, r.lng, 0.7]);

                const marker = L.circleMarker([r.lat, r.lng], {
                    radius: 6,
                    color: "#ef4444",
                    fillColor: "#ef4444",
                    fillOpacity: 0.9,
                    weight: 2
                });

                marker.bindPopup(`
            <strong>UAV:</strong> ${r.uav}<br>
            <strong>MGRS:</strong> ${r.mgrs ?? 'N/A'}<br>
            <strong>Threat:</strong> ${r.threat ?? 'N/A'}
        `);

                markerLayer.addLayer(marker);
            });

            heatLayer.setLatLngs(heatPoints);
        }

        renderMap();


        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        const sigint = @json(array_values($sigintData));
        const geoint = @json(array_values($geoIntData));
        const dforensics = @json(array_values($forensicsData));

        new Chart(document.getElementById('activityChart'), {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                        label: 'SIGINT',
                        data: sigint,
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59,130,246,0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'GEOINT',
                        data: geoint,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16,185,129,0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'D-FORENSICS',
                        data: dforensics,
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239,68,68,0.1)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        labels: {
                            color: '#94a3b8'
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: '#64748b'
                        }
                    },
                    y: {
                        ticks: {
                            color: '#64748b'
                        }
                    }
                }
            }
        });
    </script>

    <script>
        async function fetchThreatData() {
            try {
                const res = await fetch('/api/threat-status');
                const data = await res.json();

                updateAlerts(data.alerts);
                updateScore(data.score);

            } catch (e) {
                console.error('Threat fetch failed', e);
            }
        }

        function normalizeAlert(alert) {
            if (typeof alert === 'string') {
                return {
                    level: 'LOW',
                    message: alert,
                    details: ''
                };
            }
            return alert;
        }

        function updateAlerts(alerts) {
            const container = document.getElementById('alerts-container');
            const panel = document.getElementById('alerts-panel');

            if (!container) return;

            let html = '';

            alerts.forEach(a => {
                const alert = normalizeAlert(a);

                let color = 'blue';
                if (alert.level === 'HIGH') color = 'red';
                else if (alert.level === 'MEDIUM') color = 'yellow';

                html += `
            <div class="border-l-4 pl-3 border-${color}-500">
                <p class="text-sm font-semibold">${alert.message}</p>
                <p class="text-xs text-gray-400">${alert.details ?? ''}</p>
            </div>
        `;
            });

            container.innerHTML = html || '<p class="text-gray-400 text-sm">No active threats</p>';

            // also update panel
            if (panel) panel.innerHTML = html;
        }

        function updateScore(score) {
            const scoreEl = document.querySelector('[data-threat-score]');
            const bar = document.querySelector('[data-threat-bar]');

            if (scoreEl) scoreEl.innerText = score + '/100';
            if (bar) bar.style.width = score + '%';

            if (score >= 70) {
                document.body.classList.add('bg-red-950');
            } else {
                document.body.classList.remove('bg-red-950');
            }
        }

        // auto refresh every 5s
        setInterval(fetchThreatData, 5000);

        // initial run
        fetchThreatData();
    </script>

    <style>
        @keyframes threat-blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.6;
            }
        }

        .threat-high {
            animation: threat-blink 1s infinite;
        }
    </style>

</x-app-layout>