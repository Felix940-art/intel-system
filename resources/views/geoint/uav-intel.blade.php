<x-app-layout>

    <div class="max-w-7xl mx-auto space-y-8">

        {{-- HEADER --}}
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-white">
                    🛰 UAV Intelligence Console
                </h1>
                <p class="text-xs text-slate-400 tracking-widest">
                    Operational Deployment & Threat Analytics
                </p>
            </div>

            <a href="{{ route('geoint.index') }}"
                class="btn-secondary">
                ← Back to ISTAR
            </a>
        </div>

        {{-- STRATEGIC SUMMARY --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

            <div class="intel-stat-card">
                <div class="intel-stat-label">TOTAL MISSIONS</div>
                <div class="intel-stat-value text-cyan-400">
                    {{ $totalMissions }}
                </div>
                <div class="intel-stat-sub">Operational Records</div>
            </div>

            <div class="intel-stat-card">
                <div class="intel-stat-label">LAST DEPLOYMENT</div>
                <div class="intel-stat-value text-green-400">
                    {{ $lastDeployment ? \Carbon\Carbon::parse($lastDeployment)->format('d M') : '—' }}
                </div>
                <div class="intel-stat-sub">
                    {{ $lastDeployment ? \Carbon\Carbon::parse($lastDeployment)->format('Y') : '' }}
                </div>
            </div>

            <div class="intel-stat-card">
                <div class="intel-stat-label">MOST FREQUENT THREAT</div>
                <div class="intel-stat-value text-yellow-400">
                    {{ $mostFrequentThreat ?? '—' }}
                </div>
                <div class="intel-stat-sub">Threat Signal</div>
            </div>

            <div class="intel-stat-card">
                <div class="intel-stat-label">ACTIVE UAV TYPES</div>
                <div class="intel-stat-value text-indigo-400">
                    {{ count($uavDistribution) }}
                </div>
                <div class="intel-stat-sub">Platforms</div>
            </div>

        </div>

        {{-- TIMELINE --}}
        <div class="intel-panel p-6">
            <h2 class="intel-section-title">Mission Timeline</h2>
            <canvas id="missionLineChart" height="90"></canvas>
        </div>

        {{-- ANALYTICS --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">

            <div class="intel-panel p-6">
                <h2 class="intel-section-title">Missions per UAV</h2>
                <canvas id="missionsBarChart"></canvas>
            </div>

            <div class="intel-panel p-6">
                <h2 class="intel-section-title">Threat Exposure per UAV</h2>
                <canvas id="threatStackedChart"></canvas>
            </div>

        </div>

        {{-- TACTICAL MAP --}}
        <div class="intel-panel p-6">
            <h2 class="intel-section-title">Operational Tactical Map</h2>

            <!-- FILTER BAR -->

            <div class="flex items-center justify-between mb-4">

                <select id="uavMapFilter" class="intel-select">
                    <option value="ALL">All UAV</option>

                    @foreach($uavDistribution as $uav => $count)
                    <option value="{{ $uav }}">
                        {{ $uav }}
                    </option>
                    @endforeach

                </select>

                <span class="intel-section-title text-left m-0">
                    {{ $totalMissions }} MISSIONS
                </span>

            </div>

            <div id="uavMap"
                class="rounded-xl border border-slate-800 mt-4"
                style="height:500px;"></div>
        </div>

    </div>

    {{-- STYLES --}}
    <style>
        .intel-panel {
            background: linear-gradient(145deg, #0b1220, #0f172a);
            border: 1px solid #1e293b;
            border-radius: 16px;
            box-shadow: 0 0 20px rgba(34, 211, 238, 0.05);
        }

        .intel-stat-card {
            background: linear-gradient(145deg, #0f172a, #020617);
            border: 1px solid #1e293b;
            border-radius: 14px;
            padding: 22px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            gap: 6px;
            transition: all .25s ease;
        }

        .intel-stat-card:hover {
            border-color: #22d3ee;
            box-shadow: 0 0 20px rgba(34, 211, 238, 0.15);
        }

        .intel-stat-label {
            font-size: 10px;
            letter-spacing: 2px;
            color: #64748b;
        }

        .intel-stat-value {
            font-size: 20px;
            font-weight: 700;
        }

        .intel-stat-sub {
            font-size: 11px;
            color: #475569;
        }

        .intel-section-title {
            font-size: 13px;
            letter-spacing: 2px;
            color: #94a3b8;
            margin-bottom: 14px;
        }

        .intel-select {
            background: #020617;
            border: 1px solid #1e293b;
            color: #e2e8f0;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 12px;
            letter-spacing: 1px;
            width: 180px;
            cursor: pointer;
            transition: all .2s ease;
        }

        .intel-select {
            appearance: none;
            background: #020617 url("data:image/svg+xml,%3Csvg fill='%2394a3b8' height='20' viewBox='0 0 20 20' width='20' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M5 7l5 5 5-5H5z'/%3E%3C/svg%3E") no-repeat right 10px center;
            background-size: 16px;
        }

        .intel-select:hover {
            border-color: #22d3ee;
        }

        .intel-select:focus {
            outline: none;
            border-color: #22d3ee;
            box-shadow: 0 0 6px rgba(34, 211, 238, 0.4);
        }

        .mission-counter {
            font-size: 12px;
            letter-spacing: 2px;
            color: #22d3ee;
            background: #020617;
            border: 1px solid #1e293b;
            padding: 6px 12px;
            border-radius: 6px;
        }

        .btn-secondary {
            background: #334155;
            padding: 8px 16px;
            border-radius: 8px;
            color: white;
        }

        /* Tactical Pulse Animation */
        .pulse-secret {
            animation: pulseRed 1.5s infinite;
        }

        @keyframes pulseRed {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.4);
                opacity: 0.6;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .red-tactical-marker {
            filter: drop-shadow(0 0 6px rgba(239, 68, 68, 0.8));
        }
    </style>

    {{-- LIBRARIES --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet.fullscreen@2.4.0/Control.FullScreen.css" />
    <script src="https://unpkg.com/leaflet.fullscreen@2.4.0/Control.FullScreen.js"></script>
    <script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>
    <script src="https://unpkg.com/mgrs@1.0.0/dist/mgrs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // ======================
            // Charts
            // ======================

            const timeline = @json($timeline ?? []);
            new Chart(document.getElementById('missionLineChart'), {
                type: 'line',
                data: {
                    labels: timeline.map(t => t.date),
                    datasets: [{
                        data: timeline.map(t => t.total),
                        borderColor: '#22d3ee',
                        backgroundColor: 'rgba(34,211,238,0.1)',
                        tension: 0.4
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            const missionsPerUav = @json($missionsPerUav ?? []);
            new Chart(document.getElementById('missionsBarChart'), {
                type: 'bar',
                data: {
                    labels: Object.keys(missionsPerUav),
                    datasets: [{
                        data: Object.values(missionsPerUav),
                        backgroundColor: '#06b6d4'
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            const threatExposure = @json($threatExposure ?? []);
            const threatTypes = new Set();
            Object.values(threatExposure).forEach(o => {
                Object.keys(o).forEach(t => threatTypes.add(t));
            });

            const stackedData = [];
            threatTypes.forEach(type => {
                stackedData.push({
                    label: type,
                    data: Object.keys(threatExposure).map(u => threatExposure[u][type] ?? 0),
                    backgroundColor: '#' + Math.floor(Math.random() * 16777215).toString(16)
                });
            });

            new Chart(document.getElementById('threatStackedChart'), {
                type: 'bar',
                data: {
                    labels: Object.keys(threatExposure),
                    datasets: stackedData
                },
                options: {
                    scales: {
                        x: {
                            stacked: true
                        },
                        y: {
                            stacked: true
                        }
                    }
                }
            });

            // ======================
            // Map
            // ======================

            const map = L.map('uavMap', {
                fullscreenControl: true,
                fullscreenControlOptions: {
                    position: 'topleft'
                }
            }).setView([10.3157, 123.8854], 6);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19
            }).addTo(map);

            const rawData = @json($heatData ?? []);

            // Layer groups so we can clear/update map dynamically
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


            // =======================================
            // MAP RENDER FUNCTION
            // =======================================

            function renderMap(uavFilter = "ALL") {

                markerLayer.clearLayers();
                heatLayer.setLatLngs([]);

                const heatPoints = [];

                const filtered = rawData.filter(r =>
                    uavFilter === "ALL" || r.uav === uavFilter
                );

                filtered.forEach(r => {

                    if (!r.lat || !r.lng) return;

                    heatPoints.push([r.lat, r.lng, 0.7]);

                    const marker = L.circleMarker([r.lat, r.lng], {
                        radius: 6,
                        color: "#ef4444",
                        fillColor: "#ef4444",
                        fillOpacity: 0.9,
                        weight: 2,
                        className: "red-tactical-marker"
                    });

                    marker.bindPopup(`
            <strong>UAV:</strong> ${r.uav}<br>
            <strong>MGRS:</strong> ${r.mgrs ?? 'N/A'}<br>
            <strong>Threat:</strong> ${r.threat ?? 'N/A'}<br>
        `);

                    markerLayer.addLayer(marker);

                });

                heatLayer.setLatLngs(heatPoints);

            }


            // =======================================
            // INITIAL MAP LOAD
            // =======================================

            renderMap();


            // =======================================
            // UAV FILTER LISTENER
            // =======================================

            const uavFilter = document.getElementById("uavMapFilter");

            if (uavFilter) {
                uavFilter.addEventListener("change", function() {

                    const selectedUav = this.value;

                    renderMap(selectedUav);

                });
            }

            document.getElementById("missionCounter").innerText =
                filtered.length + " MISSIONS";

        });
    </script>

</x-app-layout>