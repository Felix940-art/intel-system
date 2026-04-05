<x-app-layout>

    <div class="space-y-2">

        <!-- PAGE HEADER -->
        <div>
            <h1 class="text-2xl font-bold text-white">Dashboard</h1>
            <p class="text-sm text-slate-400">
                System overview and operational status
            </p>
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
                <div id="map"
                    class="rounded-xl border border-slate-800 mt-4"
                    style="height:300px;"></div>
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

                <div class="p-5 space-y-4 text-sm">
                    @forelse($alerts as $alert)

                    <div class="border-l-4 pl-3
                @if($alert['type'] == 'high') border-red-600
                @elseif($alert['type'] == 'warning') border-yellow-500
                @else border-blue-500
                @endif
            ">
                        <div class="
                    @if($alert['type'] == 'high') text-red-400
                    @elseif($alert['type'] == 'warning') text-yellow-400
                    @else text-blue-400
                    @endif
                    font-semibold
                ">
                            {{ $alert['title'] }}
                        </div>

                        <div class="text-slate-300">
                            {{ $alert['message'] }}
                        </div>
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
    {{-- LIBRARIES --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet.fullscreen@2.4.0/Control.FullScreen.css" />
    <script src="https://unpkg.com/leaflet.fullscreen@2.4.0/Control.FullScreen.js"></script>
    <script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>
    <script src="https://unpkg.com/mgrs@1.0.0/dist/mgrs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
</x-app-layout>