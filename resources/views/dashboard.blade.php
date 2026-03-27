<x-app-layout>

    <div class="space-y-8">

        <!-- PAGE HEADER -->
        <div>
            <h1 class="text-2xl font-bold text-white">Dashboard</h1>
            <p class="text-sm text-slate-400">
                System overview and operational status
            </p>
        </div>

        <!-- STATUS CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">

            <div class="bg-slate-900 border border-slate-800 rounded-lg p-5">
                <p class="text-3xl font-bold text-white mt-2">{{ $sigint ?? 0 }}</p>
                <span class="text-xs text-slate-500">SIGINT</span>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-lg p-5">
                <p class="text-3xl font-bold text-white mt-2">{{ $geoint ?? 0 }}</p>
                <span class="text-xs text-slate-500">GEOINT</span>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-lg p-5">
                <p class="text-3xl font-bold text-red-400 mt-2">{{ $dforensics ?? 0 }}</p>
                <span class="text-xs text-slate-500">D-FORENSICS</span>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-lg p-5">
                <p class="text-3xl font-bold text-white mt-2">{{ $totalRecords ?? 0 }}</p>
                <span class="text-xs text-slate-500">Total</span>
            </div>
        </div>

        <!-- 📈 TREND GRAPH -->
        <div class="bg-slate-900 border border-slate-800 rounded-lg p-5">
            <h2 class="text-white font-semibold mb-4">Activity Trend</h2>
            <canvas id="trendChart"></canvas>
        </div>

        <!-- 🗺 MAP + FORENSICS -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

            <!-- TACTICAL MAP -->
            <div class="bg-slate-900 border border-slate-800 rounded-lg p-5">
                <h2 class="text-white font-semibold mb-4">Tactical Map</h2>
                <div id="map" class="h-96 rounded-lg"></div>
            </div>

            <!-- D-FORENSICS PREVIEW -->
            <div class="bg-slate-900 border border-slate-800 rounded-lg p-5">
                <h2 class="text-white font-semibold mb-4">D-FORENSICS Analysis</h2>

                @forelse($forensics ?? [] as $item)
                <div class="border-b border-slate-800 py-3">
                    <p class="text-white font-medium">
                        {{ $item->filename ?? 'File' }}
                    </p>
                    <span class="text-xs text-slate-400">
                        {{ $item->created_at ?? '' }}
                    </span>
                </div>
                @empty
                <p class="text-slate-400 text-sm">No forensic data available</p>
                @endforelse

            </div>

        </div>

        <!-- ACTIVITY + ALERTS -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

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

                            @forelse($recentActivities ?? [] as $activity)
                            <tr>
                                <td class="px-4 py-3">{{ $activity->time ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $activity->module ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $activity->action ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $activity->user ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-slate-500">
                                    No activity data
                                </td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ALERTS PANEL -->
            <div class="bg-slate-900 border border-slate-800 rounded-lg">
                <div class="p-5 border-b border-slate-800">
                    <h2 class="font-semibold text-white">Alerts</h2>
                </div>
                <div class="p-5 space-y-4 text-sm">
                    <div class="border-l-4 border-red-600 pl-3">
                        <div class="text-red-400 font-semibold">High Priority</div>
                        <div class="text-slate-300">Unverified SIGINT entry detected</div>
                    </div>
                    <div class="border-l-4 border-yellow-500 pl-3">
                        <div class="text-yellow-400 font-semibold">Pending Review</div>
                        <div class="text-slate-300">GEOINT data awaiting validation</div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- SCRIPTS -->

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const trendData = @json($trend ?? []);
        const labels = trendData.map(t => t.date);
        const values = trendData.map(t => t.count);

        new Chart(document.getElementById('trendChart'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Activity Trend',
                    data: values,
                    borderColor: '#3b82f6',
                    tension: 0.4
                }]
            }
        });
    </script>

    <!-- Leaflet Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        const map = L.map('map').setView([10.0, 122.0], 5);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        const points = @json($mapData ?? []);

        points.forEach(p => {
            if (p.latitude && p.longitude) {
                L.marker([p.latitude, p.longitude])
                    .addTo(map)
                    .bindPopup("Frequency: " + (p.frequency ?? 'N/A'));
            }
        });
    </script>

</x-app-layout>