<!DOCTYPE html>
<html>

<head>

    <title>
        SIGINT Common Operating Picture
    </title>

    <link rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #020617;
            color: white;
            font-family: Arial, sans-serif;
        }

        .cop-header {
            text-align: center;
            padding: 30px 20px;
        }

        .cop-header h1 {
            font-size: 42px;
            margin-bottom: 10px;
        }

        .cop-header h2 {
            font-size: 24px;
            color: #94a3b8;
        }

        .cop-header hr {
            width: 60%;
            margin: 25px auto;
            border: 1px solid #2563eb;
        }

        .map-wrapper {
            width: 95%;
            margin: auto;
            margin-bottom: 30px;
        }

        #copMap {
            height: 800px;
            width: 100%;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #1e293b;
            box-shadow:
                0 0 20px rgba(37, 99, 235, .25);
        }

        #map {
            height: 700px;
            width: 100%;
        }

        .leaflet-popup-content {
            font-size: 13px;
        }

        .stat-card {

            background: #0f172a;

            border: 1px solid #1e293b;

            border-radius: 12px;

            padding: 15px;

            text-align: center;

            box-shadow:
                0 0 15px rgba(37, 99, 235, .15);

        }

        .stat-card div:first-child {

            color: #94a3b8;

            font-size: 12px;

            margin-bottom: 8px;

        }

        .stat-card div:last-child {

            font-size: 28px;

            font-weight: bold;

        }
    </style>

</head>

<body>

    <div class="cop-header">

        <h1>
            🛰️ SIGINT COMMON OPERATING PICTURE
        </h1>

        <h2>
            COMMAND CONTROL SYSTEM
        </h2>

        <hr>

    </div>

    <div class="map-wrapper">

        <div style="
display:grid;
grid-template-columns:repeat(7,1fr);
gap:15px;
margin-bottom:20px;
">

            <div class="stat-card">
                <div>TOTAL BTS</div>
                <div id="totalBts"></div>
            </div>

            <div class="stat-card">
                <div>HIGH THREAT</div>
                <div id="highThreatCount"></div>
            </div>

            <div class="stat-card">
                <div>MEDIUM THREAT</div>
                <div id="mediumThreatCount"></div>
            </div>

            <div class="stat-card">
                <div>LOW THREAT</div>
                <div id="lowThreatCount"></div>
            </div>

            <div class="stat-card">
                <div>ACTIVE TOWERS</div>
                <div id="activeTowerCount"></div>
            </div>

            <div class="stat-card">
                <div>CONNECTED TARGETS</div>
                <div id="targetCount"></div>
            </div>

            <div class="stat-card">
                <div>MOST ACTIVE BTS</div>
                <div id="mostActiveTower"></div>
            </div>
        </div>

        <!-- KPI CARDS -->

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">

            <div style="background:#0f172a;border:1px solid #1e293b;border-radius:12px;padding:20px;">

                <h3 style="color:#22d3ee;font-size:22px;font-weight:bold;margin-bottom:15px;">
                    Tactical BTS Ranking
                </h3>

                <div id="towerRanking"></div>

            </div>

            <div style="background:#0f172a;border:1px solid #1e293b;border-radius:12px;padding:20px;">

                <h3 style="color:#f87171;font-size:22px;font-weight:bold;margin-bottom:15px;">
                    Intelligence Hotspots
                </h3>

                <div id="intelHotspots"></div>

                <div id="intelTimeline"></div>

            </div>

        </div>

        <div id="targetIntelPanel"
            style="
        display:none;
        margin-bottom:20px;
        background:#09162e;
        border:1px solid #1e3a5f;
        border-radius:12px;
        padding:20px;
        color:white;
     ">

            <div style="
        font-size:20px;
        font-weight:bold;
        color:#22d3ee;
        margin-bottom:15px;">
                TARGET INTELLIGENCE PANEL
            </div>

            <div id="targetIntelContent"></div>

        </div>

        <!-- MAP -->
        <div id="map"></div>

    </div>

    <script>
        const btsMarkers = @json($btsMarkers);
        console.log('BTS:', btsMarkers);

        const frequencyLobs = @json($frequencyLobs);
        console.log('LOBS:', frequencyLobs);
    </script>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/mgrs/dist/mgrs.min.js"></script>

    <script>
        const highThreat =
            btsMarkers.filter(
                x => x.threat_level === 'HIGH'
            ).length;

        const mediumThreat =
            btsMarkers.filter(
                x => x.threat_level === 'MEDIUM'
            ).length;

        const lowThreat =
            btsMarkers.filter(
                x => x.threat_level === 'LOW'
            ).length;

        const activeTowers =
            btsMarkers.filter(
                x => x.target_count > 0
            ).length;

        const totalTargets =
            btsMarkers.reduce(
                (sum, tower) =>
                sum + tower.target_count,
                0
            );

        const mostActiveTower =
            btsMarkers.reduce(
                (max, tower) =>
                tower.target_count > max.target_count ?
                tower :
                max, {
                    target_count: 0
                }
            );

        document.getElementById('totalBts').innerText =
            btsMarkers.length;

        document.getElementById('highThreatCount').innerText =
            highThreat;

        document.getElementById('mediumThreatCount').innerText =
            mediumThreat;

        document.getElementById('lowThreatCount').innerText =
            lowThreat;

        document.getElementById('activeTowerCount').innerText =
            activeTowers;

        document.getElementById('targetCount').innerText =
            totalTargets;

        document.getElementById('mostActiveTower').innerText =
            mostActiveTower?.name ?? 'N/A';

        const rankedTowers = [...btsMarkers]
            .sort((a, b) => b.target_count - a.target_count);

        let rankingHtml = '';

        rankedTowers.forEach((tower, index) => {

            let threatColor = '#22c55e';

            if (tower.threat_level === 'HIGH') {
                threatColor = '#ef4444';
            }

            if (tower.threat_level === 'MEDIUM') {
                threatColor = '#f59e0b';
            }

            rankingHtml += `
<div style="margin-bottom:12px;padding:12px;background:#1e293b;border-radius:10px;">

    <div style="font-weight:bold;color:white;">
        #${index + 1} ${tower.name}
    </div>

    <div style="color:#cbd5e1;font-size:13px;">
        Targets: ${tower.target_count}
    </div>

    <div style="color:${threatColor};font-weight:bold;font-size:13px;">
        ${tower.threat_level}
    </div>

</div>
`;
        });

        document.getElementById('towerRanking').innerHTML =
            rankingHtml;

        const highestRiskTower =
            rankedTowers[0];

        const srmaTower =
            btsMarkers.find(tower =>
                tower.targets.some(target =>
                    target.threat_group &&
                    target.threat_group.includes('SRMA')
                )
            );

        const mostCongestedTower =
            rankedTowers[0];

        const networkStats = {};

        btsMarkers.forEach(tower => {

            if (!networkStats[tower.network]) {
                networkStats[tower.network] = 0;
            }

            networkStats[tower.network] +=
                tower.target_count;

        });

        let mostActiveNetwork = 'N/A';
        let highestCount = 0;

        Object.entries(networkStats)
            .forEach(([network, count]) => {

                if (count > highestCount) {

                    highestCount = count;
                    mostActiveNetwork = network;

                }

            });

        let hotspotHtml = `

<div style="
display:flex;
flex-direction:column;
gap:12px;
">

    <div style="
    background:#1e293b;
    padding:12px;
    border-radius:10px;
    border-left:4px solid #ef4444;
    ">
        <div style="
        color:#ef4444;
        font-weight:bold;
        margin-bottom:4px;
        ">
            Highest Risk Tower
        </div>

        <div style="
        color:white;
        font-size:18px;
        font-weight:bold;
        ">
            ${highestRiskTower?.name ?? 'N/A'}
        </div>

        <div style="color:#94a3b8;">
            ${highestRiskTower?.target_count ?? 0} Targets
        </div>
    </div>

    <div style="
    background:#1e293b;
    padding:12px;
    border-radius:10px;
    border-left:4px solid #dc2626;
    ">
        <div style="
        color:#f87171;
        font-weight:bold;
        margin-bottom:4px;
        ">
            SRMA Presence
        </div>

        <div style="
        color:white;
        font-size:18px;
        font-weight:bold;
        ">
            ${srmaTower?.name ?? 'None'}
        </div>
    </div>

    <div style="
    background:#1e293b;
    padding:12px;
    border-radius:10px;
    border-left:4px solid #f59e0b;
    ">
        <div style="
        color:#fbbf24;
        font-weight:bold;
        margin-bottom:4px;
        ">
            Most Congested BTS
        </div>

        <div style="
        color:white;
        font-size:18px;
        font-weight:bold;
        ">
            ${mostCongestedTower?.name ?? 'N/A'}
        </div>
    </div>

    <div style="
    background:#1e293b;
    padding:12px;
    border-radius:10px;
    border-left:4px solid #10b981;
    ">
        <div style="
        color:#34d399;
        font-weight:bold;
        margin-bottom:4px;
        ">
            Most Active Network
        </div>

        <div style="
        color:white;
        font-size:18px;
        font-weight:bold;
        ">
            ${mostActiveNetwork}
        </div>
    </div>

</div>
`;

        document.getElementById('intelHotspots').innerHTML =
            hotspotHtml;

        const map = L.map('map').setView([11.5, 125.0], 8);

        L.tileLayer(
            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: 'SIGINT COP'
            }
        ).addTo(map);

        // Layers
        const btsLayer = L.layerGroup().addTo(map);
        const coverageLayer = L.layerGroup().addTo(map);
        const neighborLayer = L.layerGroup();

        const btsLookup = {};
        const targetLookup = {};

        const intelLayer = L.layerGroup();
        const lobLayer = L.layerGroup().addTo(map);
        const highlightLayer = L.layerGroup().addTo(map);

        /*
        |--------------------------------------------------------------------------
        | BTS MARKERS
        |--------------------------------------------------------------------------
        */

        function showIntelTargets(site) {
            intelLayer.clearLayers();

            if (!site.targets || !site.targets.length) {
                return;
            }

            site.targets.forEach((target, index) => {

                const angle =
                    (index / site.targets.length) *
                    Math.PI * 2;

                const radius = 0.03;

                const targetLat =
                    site._lat +
                    Math.sin(angle) * radius;

                const targetLng =
                    site._lng +
                    Math.cos(angle) * radius;

                const targetMarker =
                    L.circleMarker(
                        [targetLat, targetLng], {
                            radius: 8,
                            color: '#00ffff',
                            fillColor: '#00ffff',
                            fillOpacity: 1
                        }
                    );

                targetMarker.bindPopup(`
            <b>${target.code_name}</b><br>

            Threat:
            ${target.threat_group}<br>

            IMEI:
            ${target.imei}<br>

            IMSI:
            ${target.imsi}
        `);

                targetLookup[target.code_name] = {
                    site,
                    target,
                    marker: targetMarker
                };

                const link =
                    L.polyline(
                        [
                            [site._lat, site._lng],
                            [targetLat, targetLng]
                        ], {
                            color: '#00ffff',
                            weight: 2,
                            dashArray: '5,5'
                        }
                    );

                intelLayer.addLayer(link);
                intelLayer.addLayer(targetMarker);

            });

            intelLayer.addTo(map);
        }

        function investigateTarget(site) {

            highlightLayer.clearLayers();

            map.flyTo(
                [site._lat, site._lng],
                12
            );

            const focusCircle = L.circle(
                [site._lat, site._lng], {
                    radius: 1000,
                    color: '#ffff00',
                    fillOpacity: 0.05,
                    weight: 4
                }
            );

            highlightLayer.addLayer(focusCircle);

            if (site.neighbor_cid) {

                const neighbor =
                    btsLookup[site.neighbor_cid];

                if (neighbor) {

                    const link =
                        L.polyline(
                            [
                                [site._lat, site._lng],
                                [neighbor.lat, neighbor.lng]
                            ], {
                                color: '#ffff00',
                                weight: 5
                            }
                        );

                    highlightLayer.addLayer(link);
                }
            }
        }

        let timelineEvents = [];

        function buildOperationalTimeline() {

            timelineEvents = [];

            btsMarkers.forEach(site => {

                site.targets.forEach(target => {

                    timelineEvents.push({

                        tower: site.name,

                        threat: target.threat_group,

                        target: target.code_name

                    });

                });

            });

            timelineEvents.sort((a, b) => {

                const priority = {

                    'SRMA ARCTIC': 3,
                    'SRMA LEVOX': 2,
                    'SRMA': 1

                };

                return (
                    (priority[b.threat] || 0) -
                    (priority[a.threat] || 0)
                );

            });

            let timelineHtml = `

    <div style="
    margin-top:20px;
    background:#0f172a;
    border:1px solid #1e293b;
    border-radius:12px;
    padding:15px;
    ">

        <h3 style="
        color:#22d3ee;
        margin-bottom:15px;
        ">
            Operational Timeline
        </h3>

    `;

            timelineEvents.forEach(event => {

                timelineHtml += `

        <div style="
        background:#1e293b;
        padding:10px;
        border-radius:8px;
        margin-bottom:10px;
        border-left:4px solid #ef4444;
        ">

            <div style="
            color:white;
            font-weight:bold;
            ">
                ${event.target}
            </div>

            <div style="
            color:#f87171;
            ">
                ${event.threat}
            </div>

            <div style="
            color:#38bdf8;
            font-size:13px;
            ">
                ${event.tower}
            </div>

        </div>
        `;

            });

            timelineHtml += `</div>`;

            document.getElementById(
                'intelTimeline'
            ).innerHTML = timelineHtml;

        }

        buildOperationalTimeline();

        function showTargetIntel(site) {
            const panel =
                document.getElementById(
                    'targetIntelPanel'
                );

            const content =
                document.getElementById(
                    'targetIntelContent'
                );

            if (site.targets.length === 0) {
                content.innerHTML =
                    `
            <div style="color:#94a3b8;">
                No connected selectors detected.
            </div>
        `;

                panel.style.display = 'block';

                return;
            }

            let html = '';

            site.targets.forEach(target => {
                html += `
            <div style="
                background:#1e293b;
                padding:15px;
                border-radius:10px;
                margin-bottom:10px;
            ">

                <div style="
                    color:#22d3ee;
                    font-size:18px;
                    font-weight:bold;
                ">
                    ${target.code_name || 'UNKNOWN'}
                </div>

                <div>
                   Threat Group:
<b style="
color:
${target.threat_group?.includes('SRMA')
    ? '#ef4444'
    : '#22c55e'};
">
${target.threat_group || 'N/A'}
</b>
                </div>

                <div>
                    IMEI:
                    ${target.imei || 'N/A'}
                </div>

                <div>
                    IMSI:
                    ${target.imsi || 'N/A'}
                </div>

                <div>
                    BTS:
                    ${site.name}
                </div>

                <div>
                    Network:
                    ${site.network}
                </div>

            </div>
        `;
            });

            content.innerHTML = html;

            panel.style.display = 'block';
        }

        btsMarkers.forEach(site => {
            const point = mgrs.toPoint(site.mgrs);

            const lat = point[1];
            const lng = point[0];

            let radius = 5000;

            switch (site.mode) {

                case '2G':
                    radius = 20000;
                    break;

                case '3G':
                    radius = 12000;
                    break;

                case '4G LTE':
                    radius = 8000;
                    break;

                case '5G':
                    radius = 3000;
                    break;
            }

            if (site.threat_level === 'HIGH') {

                radius *= 1.5;

            } else if (site.threat_level === 'LOW') {

                radius *= 0.75;

            }

            btsLookup[site.cid] = {
                lat,
                lng,
                site
            };

            site._lat = lat;
            site._lng = lng;

            let iconColor = 'blue';

            if (site.threat_level === 'HIGH') {
                iconColor = 'red';
            } else if (site.threat_level === 'MEDIUM') {
                iconColor = 'orange';
            }

            L.circle([lat, lng], {

                radius: radius,
                color: iconColor,
                fillColor: iconColor,
                fillOpacity: 0.08,
                weight: 1

            }).addTo(coverageLayer);

            const marker = L.circleMarker(
                [lat, lng], {
                    radius: 12 + (site.target_count * 2),
                    color: iconColor,
                    fillColor: iconColor,
                    fillOpacity: 0.9,
                    weight: 2
                }
            );


            const targetList = site.targets.length ?
                site.targets.map(t => `
        <div style="margin-bottom:8px;">
            <b>${t.code_name}</b><br>
            Threat: ${t.threat_group}<br>
            IMEI: ${t.imei ?? 'N/A'}<br>
            IMSI: ${t.imsi ?? 'N/A'}
        </div>
    `).join('<hr>') :
                'No connected targets';

            marker.bindPopup(`
    <div style="min-width:300px">

        <h4 style="margin:0;">
            ${site.name}
        </h4>

        <hr>

        Network: ${site.network}<br>
        Mode: ${site.mode}<br>

        <br>

        LAC: ${site.lac}<br>
        CID: ${site.cid}<br>
        Neighbor CID: ${site.neighbor_cid ?? 'N/A'}<br>

        <br>

        <b>Connected Targets: ${site.target_count}</b>

        <hr>

        ${targetList}

<hr>

<button
onclick="investigateTargetByCid('${site.cid}')"
style="
padding:8px 12px;
background:#2563eb;
color:white;
border:none;
border-radius:6px;
cursor:pointer;
">
Investigate
</button>

    </div>
`);

            marker.on('click', function() {

                showIntelTargets(site);

                showTargetIntel(site);

                document
                    .getElementById('targetIntelPanel')
                    .scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });

            });

            marker.on('popupclose', function() {

                intelLayer.clearLayers();

            });

            marker.addTo(btsLayer);

        });

        /*
        |--------------------------------------------------------------------------
        | FREQUENCY LOBS
        |--------------------------------------------------------------------------
        */

        frequencyLobs.forEach(signal => {

            // Tactical LOB Length (~15-20 km)
            const distance = 0.15;

            const radians =
                signal.lob * Math.PI / 180;

            const endLat =
                signal.lat +
                distance * Math.cos(radians);

            const endLng =
                signal.lng +
                distance * Math.sin(radians);

            const lobLine = L.polyline(
                [
                    [signal.lat, signal.lng],
                    [endLat, endLng]
                ], {
                    color: signal.watchlisted ?
                        '#ef4444' : '#f59e0b',

                    weight: signal.watchlisted ?
                        5 : 3,

                    opacity: 0.95,

                    dashArray: signal.watchlisted ?
                        null : '10,10'
                }
            );

            lobLine.addTo(lobLayer);

            if (signal.watchlisted) {

                map.flyTo(
                    [signal.lat, signal.lng],
                    10
                );

            }

            lobLine.bindPopup(`
            <b>Frequency:</b> ${signal.frequency}<br>
            <b>LOB:</b> ${signal.lob}°<br>
            <b>Municipality:</b> ${signal.municipality}<br>
            <b>Barangay:</b> ${signal.barangay}<br>
            <b>Clarity:</b> ${signal.clarity}<br>
            <b>Date/Time:</b> ${signal.datetime}<br>
            <b>Threat:</b>
                ${signal.watchlisted
                    ? 'WATCHLISTED'
                    : 'NORMAL'}<br>

            <hr>

            <b>Conversation:</b><br>
            ${signal.conversation ?? 'No transcript'}
        `);

            // Origin Point
            L.circleMarker(
                [
                    signal.lat,
                    signal.lng
                ], {
                    radius: signal.watchlisted ?
                        8 : 6,

                    color: signal.watchlisted ?
                        '#ef4444' : '#f59e0b',

                    fillColor: signal.watchlisted ?
                        '#ef4444' : '#f59e0b',

                    fillOpacity: 1,

                    weight: 2
                }
            ).addTo(lobLayer);

        });

        /*
|--------------------------------------------------------------------------
| NEIGHBOR CELL LINKS
|--------------------------------------------------------------------------
*/

        btsMarkers.forEach(site => {

            if (!site.neighbor_cid)
                return;

            const source = btsLookup[site.cid];
            const target = btsLookup[site.neighbor_cid];

            if (!source || !target)
                return;

            L.polyline(
                [
                    [source.lat, source.lng],
                    [target.lat, target.lng]
                ], {
                    color: '#00ffff',
                    weight: 3,
                    dashArray: '8,8',
                    opacity: 0.8
                }
            ).addTo(neighborLayer);

        });

        /*
        |--------------------------------------------------------------------------
        | LAYER CONTROL
        |--------------------------------------------------------------------------
        */

        L.control.layers({}, {
            BTS: btsLayer,
            Targets: intelLayer,
            Neighbors: neighborLayer,
            LOBs: lobLayer,
            Coverage: coverageLayer,
            Highlights: highlightLayer
        }).addTo(map);

        window.investigateTargetByCid =
            function(cid) {

                const targetSite =
                    btsMarkers.find(
                        x => x.cid == cid
                    );

                if (!targetSite)
                    return;

                investigateTarget(targetSite);
            };
    </script>
</body>

</html>