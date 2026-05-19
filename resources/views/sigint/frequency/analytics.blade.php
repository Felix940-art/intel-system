@php
$analyticsData = [
'monthly' => [
'labels' => $monthly->keys()->values()->all(),
'values' => $monthly->values()->values()->all(),
],

'clarity' => [
'labels' => collect($clarityDist)->keys()->values()->all(),
'values' => collect($clarityDist)->values()->values()->all(),

],

'origin' => [
'labels' => $origin->keys()->values()->all(),
'values' => $origin->values()->values()->all(),
],

'daily' => [
'labels' => $daily->keys()->values()->all(),
'values' => $daily->values()->values()->all(),
],
];
@endphp


<x-app-layout>

    <!-- LIVE COMMAND HEADER -->

    <div class="mb-5 rounded-lg border border-cyan-500/20 bg-[#081225] overflow-visible relative z-40">

        <!-- GLOW -->

        <div class="absolute inset-0 bg-cyan-500/5"></div>

        <div class="relative z-10 p-8">

            <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-8">

                <!-- LEFT -->

                <div>

                    <div class="flex items-center gap-4 mb-4">

                        <div class="w-4 h-4 rounded-full bg-green-400 animate-pulse"></div>

                        <div class="text-green-400 tracking-[0.35em] uppercase text-sm font-semibold">

                            FREQUENCY NETWORK ONLINE

                        </div>

                    </div>

                    <h1 class="text-5xl font-black text-white leading-tight">

                        FREQUENCY INTELLIGENCE
                        <br>
                        COMMAND CENTER

                    </h1>

                    <p class="mt-5 text-slate-400 text-lg max-w-3xl leading-relaxed">

                        Real-time tactical signal intelligence monitoring,
                        predictive surveillance analytics,
                        and operational threat reconstruction environment.

                    </p>

                </div>

                <!-- RIGHT -->

                <div class="grid grid-cols-2 gap-4 min-w-[320px]">

                    <!-- ACTIVE NODES -->

                    <div class="relative group">

                        <div class="rounded-2xl bg-black/30 border border-white/5 p-5
                    hover:border-cyan-400/30 transition duration-300">

                            <div class="text-slate-500 text-xs tracking-[0.25em] uppercase">
                                Active Nodes
                            </div>

                            <div class="mt-3 text-4xl font-black text-cyan-400">

                                {{ $activeNodeList->count() }}

                            </div>

                        </div>

                        <!-- HOVER PANEL -->

                        <div class="absolute right-0 top-full mt-3 w-72
                    opacity-0 invisible
                    group-hover:opacity-100
                    group-hover:visible
                    transition-all duration-300
                    z-50">

                            <div class="bg-slate-950/95 backdrop-blur-xl
                        border border-cyan-500/20
                        rounded-2xl p-5 shadow-2xl">

                                <div class="text-cyan-400 text-xs tracking-[0.3em] uppercase mb-4">
                                    Active Municipalities
                                </div>

                                <div class="space-y-2 max-h-60 overflow-y-auto">

                                    @foreach($activeNodeList as $node)

                                    <div class="flex items-center justify-between
                                border-b border-white/5 pb-2">

                                        <span class="text-white/90 text-sm">
                                            {{ $node }}
                                        </span>

                                        <span class="text-green-400 text-xs">
                                            ONLINE
                                        </span>

                                    </div>

                                    @endforeach

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- THREAT ENTITIES -->

                    <div class="relative group">

                        <div class="rounded-2xl bg-black/30 border border-white/5 p-5
                    hover:border-red-400/30 transition duration-300">

                            <div class="text-slate-500 text-xs tracking-[0.25em] uppercase">
                                Threat Entities
                            </div>

                            <div class="mt-3 text-4xl font-black text-red-400">

                                {{ $aiThreatScores->count() }}

                            </div>

                        </div>

                        <!-- HOVER PANEL -->

                        <div class="absolute right-0 top-full mt-3 w-72
                    opacity-0 invisible
                    group-hover:opacity-100
                    group-hover:visible
                    transition-all duration-300
                    z-50">

                            <div class="bg-slate-950/95 backdrop-blur-xl
                        border border-red-500/20
                        rounded-2xl p-5 shadow-2xl">

                                <div class="text-red-400 text-xs tracking-[0.3em] uppercase mb-4">
                                    Threat Groups
                                </div>

                                <div class="space-y-2 max-h-60 overflow-y-auto">

                                    @foreach($threatEntityList as $threat)

                                    <div class="flex items-center justify-between
                                border-b border-white/5 pb-2">

                                        <span class="text-white/90 text-sm">
                                            {{ $threat }}
                                        </span>

                                        <span class="w-2 h-2 rounded-full bg-red-400"></span>

                                    </div>

                                    @endforeach

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- WATCHLIST -->

                    <div class="relative group">

                        <div class="rounded-2xl bg-black/30 border border-white/5 p-5
                    hover:border-yellow-400/30 transition duration-300">

                            <div class="text-slate-500 text-xs tracking-[0.25em] uppercase">
                                Watchlisted
                            </div>

                            <div class="mt-3 text-4xl font-black text-yellow-400">

                                {{ $watchlistedCount ?? 0 }}

                            </div>

                        </div>

                        <!-- HOVER PANEL -->

                        <div class="absolute right-0 top-full mt-3 w-80
                    opacity-0 invisible
                    group-hover:opacity-100
                    group-hover:visible
                    transition-all duration-300
                    z-50">

                            <div class="bg-slate-950/95 backdrop-blur-xl
                        border border-yellow-500/20
                        rounded-2xl p-5 shadow-2xl">

                                <div class="text-yellow-400 text-xs tracking-[0.3em] uppercase mb-4">
                                    Watchlisted Frequencies
                                </div>

                                <div class="space-y-4 max-h-72 overflow-y-auto">

                                    @foreach($watchlistSignals as $signal)

                                    <div class="border-b border-white/5 pb-3">

                                        <div class="text-yellow-300 font-bold">

                                            {{ $signal->frequency }}

                                        </div>

                                        <div class="text-xs text-white/60 mt-1">

                                            {{ $signal->municipality }}

                                        </div>

                                        <div class="text-xs text-red-400 mt-1">

                                            {{ $signal->threat_confronted }}

                                        </div>

                                    </div>

                                    @endforeach

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- STATUS -->

                    <div class="relative group">

                        <div class="rounded-2xl bg-black/30 border border-white/5 p-5
                    hover:border-green-400/30 transition duration-300">

                            <div class="text-slate-500 text-xs tracking-[0.25em] uppercase">
                                Operational Status
                            </div>

                            <div class="mt-3 text-2xl font-black text-green-400">

                                ACTIVE

                            </div>

                        </div>

                        <!-- HOVER PANEL -->

                        <div class="absolute right-0 top-full mt-3 w-72
                    opacity-0 invisible
                    group-hover:opacity-100
                    group-hover:visible
                    transition-all duration-300
                    z-50">

                            <div class="bg-slate-950/95 backdrop-blur-xl
                        border border-green-500/20
                        rounded-2xl p-5 shadow-2xl">

                                <div class="text-green-400 text-xs tracking-[0.3em] uppercase mb-4">
                                    System Diagnostics
                                </div>

                                <div class="space-y-3">

                                    <div class="flex justify-between">
                                        <span class="text-white/70 text-sm">ISR Relay</span>
                                        <span class="text-green-400 text-sm">ONLINE</span>
                                    </div>

                                    <div class="flex justify-between">
                                        <span class="text-white/70 text-sm">Threat Tracking</span>
                                        <span class="text-red-400 text-sm">MONITORING</span>
                                    </div>

                                    <div class="flex justify-between">
                                        <span class="text-white/70 text-sm">Encryption</span>
                                        <span class="text-cyan-400 text-sm">AES-256</span>
                                    </div>

                                    <div class="flex justify-between">
                                        <span class="text-white/70 text-sm">Signal Analysis</span>
                                        <span class="text-green-400 text-sm">ACTIVE</span>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- LIVE EVENT FEED -->

    <div class="mb-5 grid grid-cols-1 xl:grid-cols-3 gap-6">

        <!-- LIVE FEED -->

        <div class="xl:col-span-2 rounded-2xl border border-red-500/20 bg-[#081225] overflow-hidden">

            <div class="px-5 py-4 border-b border-white/5 flex items-center justify-between">

                <div class="flex items-center gap-3">

                    <div class="w-3 h-3 rounded-full bg-red-400 animate-pulse"></div>

                    <h2 class="text-xl font-black text-white">

                        Live Tactical Feed

                    </h2>

                </div>

                <div class="text-red-400 text-xs tracking-[0.3em] uppercase">

                    Real-Time Monitoring

                </div>

            </div>

            <div class="max-h-[500px] overflow-y-auto">

                @foreach($tacticalTimeline->take(8) as $event)

                <div class="px-5 py-4 border-b border-white/5 hover:bg-white/[0.02] transition">

                    <div class="flex items-start justify-between gap-5">

                        <div class="flex gap-4">

                            <div class="mt-2">

                                <div class="
                                    w-3 h-3 rounded-full

                                    @if($event['eventColor'] === 'red')
                                        bg-red-400
                                    @elseif($event['eventColor'] === 'yellow')
                                        bg-yellow-400
                                    @else
                                        bg-cyan-400
                                    @endif
                                "></div>

                            </div>

                            <div>

                                <div class="text-white font-black text-base">

                                    {{ $event['eventType'] }}

                                </div>

                                <div class="mt-1 text-sm text-slate-400 leading-relaxed">

                                    Signal detected near

                                    <span class="text-cyan-400">
                                        {{ $event['location'] }}
                                    </span>

                                    linked to

                                    <span class="text-orange-400">
                                        {{ $event['threat'] }}
                                    </span>

                                </div>

                            </div>

                        </div>

                        <div class="text-cyan-400 text-xs tracking-[0.25em] uppercase">

                            {{ $event['time'] }}

                        </div>

                    </div>

                </div>

                @endforeach

            </div>

        </div>

        <!-- SYSTEM STATUS -->

        <div class="rounded-2xl border border-cyan-500/20 bg-[#081225] p-5">

            <div class="flex items-center gap-3 mb-5">

                <div class="w-3 h-3 rounded-full bg-green-400 animate-pulse"></div>

                <h2 class="text-xl font-black text-white">
                    System Status
                </h2>

            </div>

            <div class="space-y-4">

                <!-- ITEMS -->

                <div class="flex items-center justify-between">

                    <div class="text-slate-400 text-sm">
                        ISR Relay
                    </div>

                    <div class="text-green-400 text-sm font-bold">
                        ONLINE
                    </div>

                </div>

                <div class="flex items-center justify-between">

                    <div class="text-slate-400 text-sm">
                        Tactical Uplink
                    </div>

                    <div class="text-green-400 text-sm font-bold">
                        STABLE
                    </div>

                </div>

                <div class="flex items-center justify-between">

                    <div class="text-slate-400 text-sm">
                        Signal Analysis
                    </div>

                    <div class="text-cyan-400 text-sm font-bold">
                        ACTIVE
                    </div>

                </div>

                <div class="flex items-center justify-between">

                    <div class="text-slate-400 text-sm">
                        Threat Tracking
                    </div>

                    <div class="text-red-400 text-sm font-bold">
                        MONITORING
                    </div>

                </div>

                <div class="flex items-center justify-between">

                    <div class="text-slate-400 text-sm">
                        Encryption Layer
                    </div>

                    <div class="text-yellow-400 text-sm font-bold">
                        AES-256
                    </div>

                </div>

            </div>

            <!-- COMMAND PULSE -->

            <div class="mt-7">

                <div class="text-slate-500 text-xs tracking-[0.25em] uppercase mb-3">

                    Command Pulse

                </div>

                <div class="h-3 rounded-full bg-black/30 overflow-hidden">

                    <div class="h-full w-[72%] bg-gradient-to-r from-cyan-500 to-blue-500 animate-pulse rounded-full"></div>

                </div>

            </div>

        </div>

    </div>

    {{-- ========================================= --}}
    {{-- THREAT PRIORITY INDEX --}}
    {{-- ========================================= --}}

    <div class="mb-8">

        <div class="flex items-center gap-3 mb-4">
            <div class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></div>

            <h2 class="text-xl font-bold text-white tracking-wide">
                Threat Priority Index
            </h2>
        </div>

        <div class="grid grid-cols-8 gap-2">

            @foreach($threatPriority as $threat)

            <div class="bg-[#081225] border border-white/5 rounded-lg p-2 relative overflow-hidden">

                {{-- Glow Effect --}}
                <div class="absolute inset-0 opacity-10
                    @if($threat['color'] == 'red') bg-red-500
                    @elseif($threat['color'] == 'orange') bg-orange-500
                    @elseif($threat['color'] == 'yellow') bg-yellow-500
                    @else bg-green-500
                    @endif">
                </div>

                <div class="relative z-10">

                    {{-- Threat Group --}}
                    <div class="flex items-center justify-between mb-3">

                        <h3 class="text-white font-semibold text-xs tracking-wide">
                            {{ $threat['name'] }}
                        </h3>

                        <div class="w-3 h-3 rounded-full
                            @if($threat['color'] == 'red') bg-red-500
                            @elseif($threat['color'] == 'orange') bg-orange-500
                            @elseif($threat['color'] == 'yellow') bg-yellow-400
                            @else bg-green-500
                            @endif
                            animate-pulse">
                        </div>
                    </div>

                    {{-- Threat Level --}}
                    <div class="mb-2">

                        <span class="
                            text-[10px] font-bold tracking-wide px-2 py-1 rounded-full

                            @if($threat['color'] == 'red')
                                bg-red-500/20 text-red-400
                            @elseif($threat['color'] == 'orange')
                                bg-orange-500/20 text-orange-300
                            @elseif($threat['color'] == 'yellow')
                                bg-yellow-500/20 text-yellow-300
                            @else
                                bg-green-500/20 text-green-300
                            @endif
                        ">
                            {{ $threat['level'] }}
                        </span>

                    </div>

                    {{-- Score --}}
                    <div class="mt-4">

                        <p class="text-slate-500 text-[9px] uppercase tracking-wide mb-1">
                            THREAT PRIORITY INDEX
                        </p>

                        <h1 class="text-2xl font-bold text-white">
                            {{ $threat['score'] }}
                        </h1>

                    </div>

                    <div class="mt-3 text-[10px] text-slate-500 space-y-1">
                        <div>Occurrence: {{ $threat['occurrence'] }}</div>
                        <div>Severity: {{ $threat['severity'] }}</div>
                        <div>Detection: {{ $threat['detection'] }}</div>
                    </div>

                </div>

            </div>

            @endforeach

        </div>

    </div>

    {{-- SIGNAL HEAT INDEX --}}
    <div class="mb-5">

        <div class="flex items-center gap-3 mb-4">
            <div class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></div>

            <h2 class="text-xl font-bold text-white tracking-wide">
                Signal Heat Index
            </h2>
        </div>

        <div class="grid grid-cols-6 gap-3">

            @foreach($signalHeat as $signal)

            <div class="bg-[#081225] border border-white/5 rounded-lg p-3 relative overflow-hidden">

                <div class="absolute inset-0 opacity-10
                @if($signal->color == 'red') bg-red-500
                @elseif($signal->color == 'orange') bg-orange-500
                @elseif($signal->color == 'yellow') bg-yellow-500
                @else bg-green-500
                @endif">
                </div>

                <div class="relative z-10">

                    <div class="flex items-center justify-between mb-3">

                        <h3 class="text-white font-semibold text-sm tracking-wide">
                            {{ $signal->frequency }}
                        </h3>

                        <div class="w-3 h-3 rounded-full
                        @if($signal->color == 'red') bg-red-500
                        @elseif($signal->color == 'orange') bg-orange-500
                        @elseif($signal->color == 'yellow') bg-yellow-400
                        @else bg-green-500
                        @endif
                        animate-pulse">
                        </div>

                    </div>

                    <div class="mb-2">

                        <span class="
                        text-[10px] font-bold tracking-wide px-2 py-1 rounded-full

                        @if($signal->color == 'red')
                            bg-red-500/20 text-red-400
                        @elseif($signal->color == 'orange')
                            bg-orange-500/20 text-orange-300
                        @elseif($signal->color == 'yellow')
                            bg-yellow-500/20 text-yellow-300
                        @else
                            bg-green-500/20 text-green-300
                        @endif
                    ">
                            {{ $signal->heat_level }}
                        </span>

                    </div>

                    <div class="grid grid-cols-2 gap-2">

                        <div>
                            <p class="text-slate-500 text-[9px] uppercase tracking-wide mb-1">
                                Hits
                            </p>

                            <h1 class="text-sm font-bold text-white">
                                {{ $signal->hits }}
                            </h1>
                        </div>

                        <div>
                            <p class="text-slate-500 text-[9px] uppercase tracking-wide mb-1">
                                Heat Score
                            </p>

                            <h1 class="text-sm font-bold text-cyan-400">
                                {{ $signal->score }}
                            </h1>
                        </div>

                    </div>

                </div>

            </div>

            @endforeach

        </div>

    </div>

    {{-- WATCHLIST ACTIVITY --}}
    <div class="mb-5">

        <div class="flex items-center gap-3 mb-5">

            <div class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></div>

            <h2 class="text-xl font-bold text-white tracking-wide">
                Watchlist Activity Monitor
            </h2>

        </div>

        <div class="bg-[#081225] border border-white/5 rounded-lg overflow-hidden">

            <table class="w-full">

                <thead class="bg-white/5">

                    <tr class="text-left text-slate-400 text-xs uppercase tracking-widest">

                        <th class="px-6 py-4">Threat Group</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Hits</th>
                        <th class="px-6 py-4">Latest Activity</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($watchlistActivity as $watch)

                    <tr class="border-t border-white/5 hover:bg-white/[0.02] transition">

                        {{-- THREAT GROUP --}}
                        <td class="px-6 py-5">

                            <div class="flex items-center gap-3">

                                <div class="
                                w-2 h-2 rounded-full animate-pulse

                                @if($watch->color == 'red')
                                    bg-red-500
                                @elseif($watch->color == 'yellow')
                                    bg-yellow-400
                                @else
                                    bg-green-500
                                @endif
                            ">
                                </div>

                                <span class="text-white font-semibold tracking-wide">
                                    {{ $watch->threat_group }}
                                </span>

                            </div>

                        </td>

                        {{-- STATUS --}}
                        <td class="px-6 py-5">

                            <span class="
                            text-[10px] font-bold tracking-wide px-2 py-1 rounded-full

                            @if($watch->color == 'red')
                                bg-red-500/20 text-red-400
                            @elseif($watch->color == 'yellow')
                                bg-yellow-500/20 text-yellow-300
                            @else
                                bg-green-500/20 text-green-300
                            @endif
                        ">
                                {{ $watch->status }}
                            </span>

                        </td>

                        {{-- HITS --}}
                        <td class="px-6 py-5">

                            <span class="text-cyan-400 font-bold text-lg">
                                {{ $watch->hits }}
                            </span>

                        </td>

                        {{-- LATEST ACTIVITY --}}
                        <td class="px-6 py-5 text-slate-400 text-sm">

                            {{ $watch->latest_activity ?? 'No activity' }}

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

    {{-- ORIGIN INTELLIGENCE --}}
    <div class="mb-5">

        <div class="flex items-center gap-3 mb-5">

            <div class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></div>

            <h2 class="text-xl font-bold text-white tracking-wide">
                Origin Intelligence Mapping
            </h2>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

            @foreach($originIntel as $origin)

            <div class="rounded-3xl border border-white/10 bg-[#081225] p-5">

                {{-- HEADER --}}
                <div class="flex items-center justify-between mb-4">

                    <h3 class="text-white font-semibold text-lg tracking-wide">
                        {{ $origin->origin }}
                    </h3>

                    <div class="
            w-3 h-3 rounded-full animate-pulse

            @if($origin->color == 'red')
                bg-red-500
            @elseif($origin->color == 'yellow')
                bg-yellow-400
            @else
                bg-green-500
            @endif
        ">
                    </div>

                </div>

                {{-- STATUS --}}
                <div class="mb-4">

                    <span class="
            text-[10px] font-bold tracking-wide px-3 py-1 rounded-full

            @if($origin->color == 'red')
                bg-red-500/20 text-red-400
            @elseif($origin->color == 'yellow')
                bg-yellow-500/20 text-yellow-300
            @else
                bg-green-500/20 text-green-300
            @endif
        ">
                        {{ $origin->level }}
                    </span>

                </div>

                {{-- METRICS --}}
                <div class="grid grid-cols-2 gap-3">

                    <div>

                        <p class="text-slate-500 text-[10px] uppercase tracking-wide">
                            Signals
                        </p>

                        <h1 class="text-lg font-bold text-white">
                            {{ $origin->signals }}
                        </h1>

                    </div>

                    <div>

                        <p class="text-slate-500 text-[10px] uppercase tracking-wide">
                            Origin
                        </p>

                        <h1 class="text-sm font-semibold text-cyan-400">
                            ACTIVE
                        </h1>

                    </div>

                </div>

            </div>

            @endforeach

        </div>

    </div>

    <!-- ACTIVITY TIMELINE HEATMAP -->
    <div class="mt-7">

        <div class="flex items-center gap-3 mb-5">
            <div class="w-3 h-3 rounded-full bg-cyan-400"></div>

            <h2 class="text-3xl font-bold text-white">
                Activity Timeline Heatmap
            </h2>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-5">

            @foreach($hourlyActivity as $activity)

            <div class="rounded-3xl border border-white/10 bg-[#081225] p-5">

                <div class="flex items-center justify-between mb-5">

                    <div class="text-white text-2xl font-bold">
                        {{ $activity['hour'] }}
                    </div>

                    <div class="w-3 h-3 rounded-full
                        {{ str_contains($activity['level'], 'CRITICAL') ? 'bg-red-500' :
                           (str_contains($activity['level'], 'HIGH') ? 'bg-orange-400' :
                           (str_contains($activity['level'], 'MODERATE') ? 'bg-yellow-400' :
                           'bg-cyan-400')) }}">
                    </div>

                </div>

                <div class="inline-flex px-4 py-1 rounded-full text-xs font-bold tracking-widest border {{ $activity['color'] }}">

                    {{ $activity['level'] }}

                </div>

                <div class="mt-6">

                    <div class="text-slate-500 text-sm tracking-[0.25em] uppercase">
                        Signals
                    </div>

                    <div class="text-cyan-400 text-5xl font-black mt-2">
                        {{ $activity['count'] }}
                    </div>

                </div>

            </div>

            @endforeach

        </div>

    </div>

    <!-- SIGNAL SURGE DETECTION -->
    <div class="mt-7">

        <div class="flex items-center gap-3 mb-5">

            <div class="w-3 h-3 rounded-full bg-red-400"></div>

            <h2 class="text-3xl font-bold text-white">
                Signal Surge Detection
            </h2>

        </div>

        <div class="rounded-3xl border border-white/10 bg-[#081225] p-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <!-- TODAY -->
                <div>

                    <div class="text-slate-500 text-sm tracking-[0.25em] uppercase">
                        Today's Signals
                    </div>

                    <div class="text-cyan-400 text-6xl font-black mt-3">
                        {{ $todaySignals }}
                    </div>

                </div>

                <!-- AVERAGE -->
                <div>

                    <div class="text-slate-500 text-sm tracking-[0.25em] uppercase">
                        Daily Average
                    </div>

                    <div class="text-white text-6xl font-black mt-3">
                        {{ $averageSignals }}
                    </div>

                </div>

                <!-- STATUS -->
                <div>

                    <div class="text-slate-500 text-sm tracking-[0.25em] uppercase mb-4">
                        Operational Status
                    </div>

                    <div class="inline-flex px-5 py-2 rounded-full text-sm font-black tracking-[0.2em] border
                    {{ $surgeColor }}">

                        {{ $surgeLevel }}

                    </div>

                </div>

            </div>

            <!-- ALERT BAR -->

            <div class="mt-7">

                <div class="w-full h-4 rounded-full bg-slate-800 overflow-hidden">

                    @php
                    $percentage = min(100, ($todaySignals / max(1, $averageSignals * 3)) * 100);
                    @endphp

                    <div
                        class="h-full rounded-full transition-all duration-1000
                    {{ str_contains($surgeLevel, 'CRITICAL') ? 'bg-red-500' :
                       (str_contains($surgeLevel, 'HIGH') ? 'bg-orange-400' :
                       (str_contains($surgeLevel, 'ELEVATED') ? 'bg-yellow-400' :
                       'bg-cyan-400')) }}"
                        style="width: {{ $percentage }}%">
                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- THREAT ESCALATION TREND -->
    <div class="mt-7">

        <div class="flex items-center gap-3 mb-5">

            <div class="w-3 h-3 rounded-full bg-orange-400"></div>

            <h2 class="text-3xl font-bold text-white">
                Threat Escalation Trend
            </h2>

        </div>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-5 mt-6">

            <!-- ESCALATION TREND -->
            <div class="bg-[#09152b] border border-cyan-500/10 rounded-2xl p-5">

                <div class="flex items-center justify-between mb-4">
                    <div>
                        <div class="text-cyan-300 tracking-[0.3em] text-xs uppercase">
                            Escalation Timeline
                        </div>

                        <div class="text-white text-lg font-semibold mt-1">
                            Threat Growth Pattern
                        </div>
                    </div>

                    <div class="text-red-400 text-sm tracking-widest uppercase">
                        Active Monitoring
                    </div>
                </div>

                <div class="h-[260px]">
                    <canvas id="escalationTrendChart"></canvas>
                </div>
            </div>

            <!-- THREAT DISTRIBUTION -->
            <div class="bg-[#09152b] border border-cyan-500/10 rounded-2xl p-5">

                <div class="flex items-center justify-between mb-4">
                    <div>
                        <div class="text-cyan-300 tracking-[0.3em] text-xs uppercase">
                            Threat Density
                        </div>

                        <div class="text-white text-lg font-semibold mt-1">
                            Escalation Distribution
                        </div>
                    </div>

                    <div class="text-yellow-400 text-sm tracking-widest uppercase">
                        Intelligence Scan
                    </div>
                </div>

                <div class="h-[260px]">
                    <canvas id="threatDensityChart"></canvas>
                </div>
            </div>

        </div>

    </div>

    <!-- REAL-TIME INTELLIGENCE FEED -->
    <div class="mt-7">

        <div class="flex items-center gap-3 mb-5">

            <div class="w-3 h-3 rounded-full bg-green-400 animate-pulse"></div>

            <h2 class="text-3xl font-bold text-white">
                Real-Time Intelligence Feed
            </h2>

        </div>

        <div class="rounded-3xl border border-white/10 bg-[#081225] overflow-hidden">

            <!-- HEADER -->

            <div class="flex items-center justify-between px-6 py-4 border-b border-white/5">

                <div class="text-sm tracking-[0.3em] uppercase text-cyan-400">
                    Live Operational Stream
                </div>

                <div class="flex items-center gap-2 text-green-400 text-sm">

                    <div class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></div>

                    ACTIVE

                </div>

            </div>

            <!-- FEED -->

            <div class="max-h-[420px] overflow-y-auto">

                @foreach($intelFeed as $feed)

                <div class="px-6 py-4 border-b border-white/5 hover:bg-white/[0.02] transition">

                    <div class="flex items-start gap-5">

                        <!-- TIME -->

                        <div class="text-slate-500 text-sm font-mono min-w-[80px]">

                            {{ $feed['time'] }}

                        </div>

                        <!-- TYPE -->

                        <div class="min-w-[110px]">

                            <span class="px-3 py-1 rounded-full text-xs font-black tracking-[0.2em]
                                bg-white/5 border border-white/10 {{ $feed['color'] }}">

                                {{ $feed['type'] }}

                            </span>

                        </div>

                        <!-- MESSAGE -->

                        <div class="text-white/90 tracking-wide">

                            {{ $feed['message'] }}

                        </div>

                    </div>

                </div>

                @endforeach

            </div>

        </div>

    </div>

    <!-- THREAT FORECAST ENGINE -->
    <div class="mt-7">

        <div class="flex items-center gap-3 mb-5">

            <div class="w-3 h-3 rounded-full bg-red-400 animate-pulse"></div>

            <h2 class="text-3xl font-bold text-white">
                Predictive Threat Forecast
            </h2>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

            @foreach($forecastThreats as $forecast)

            <div class="rounded-3xl border border-white/10 bg-[#081225] p-6 relative overflow-hidden">

                <!-- GLOW -->

                <div class="absolute inset-0 opacity-10
                    {{ str_contains($forecast['threatLevel'], 'CRITICAL') ? 'bg-red-500' :
                       (str_contains($forecast['threatLevel'], 'HIGH') ? 'bg-orange-400' :
                       (str_contains($forecast['threatLevel'], 'MODERATE') ? 'bg-yellow-400' :
                       'bg-green-400')) }}">
                </div>

                <div class="relative z-10">

                    <!-- HEADER -->

                    <div class="flex items-start justify-between">

                        <div>

                            <div class="text-white text-2xl font-black">
                                {{ $forecast['threat'] }}
                            </div>

                            <div class="mt-3 inline-flex px-4 py-1 rounded-full text-xs font-black tracking-[0.2em] border
                                {{ $forecast['color'] }}">

                                {{ $forecast['forecast'] }}

                            </div>

                        </div>

                        <div class="text-right">

                            <div class="text-slate-500 text-xs tracking-[0.2em] uppercase">
                                Threat Level
                            </div>

                            <div class="text-cyan-400 text-lg font-black mt-1">
                                {{ $forecast['threatLevel'] }}
                            </div>

                        </div>

                    </div>

                    <!-- METRICS -->

                    <div class="grid grid-cols-2 gap-6 mt-10">

                        <div>

                            <div class="text-slate-500 text-xs tracking-[0.2em] uppercase">
                                Signals
                            </div>

                            <div class="text-white text-5xl font-black mt-2">
                                {{ $forecast['signals'] }}
                            </div>

                        </div>

                        <div>

                            <div class="text-slate-500 text-xs tracking-[0.2em] uppercase">
                                Prediction Score
                            </div>

                            <div class="text-cyan-400 text-5xl font-black mt-2">
                                {{ $forecast['score'] }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

            @endforeach

        </div>

    </div>

    <!-- HOT ZONE PREDICTION ENGINE -->
    <div class="mt-7">

        <div class="flex items-center gap-3 mb-5">

            <div class="w-3 h-3 rounded-full bg-orange-400 animate-pulse"></div>

            <h2 class="text-xl font-black text-white">
                Hot Zone Prediction Engine
            </h2>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">

            @foreach($hotZones as $zone)

            <div class="rounded-3xl border border-white/10 bg-[#081225] p-6 relative overflow-hidden">

                <!-- BACKGROUND GLOW -->

                <div class="absolute inset-0 opacity-10
                    {{ str_contains($zone['level'], 'CRITICAL') ? 'bg-red-500' :
                       (str_contains($zone['level'], 'HIGH') ? 'bg-orange-400' :
                       (str_contains($zone['level'], 'MODERATE') ? 'bg-yellow-400' :
                       'bg-green-400')) }}">
                </div>

                <div class="relative z-10">

                    <!-- HEADER -->

                    <div class="flex items-start justify-between">

                        <div>

                            <div class="text-white text-2xl font-black">
                                {{ $zone['municipality'] }}
                            </div>

                            <div class="mt-3 inline-flex px-4 py-1 rounded-full text-xs font-black tracking-[0.2em] border
                                {{ $zone['color'] }}">

                                {{ $zone['status'] }}

                            </div>

                        </div>

                        <div class="w-3 h-3 rounded-full
                            {{ str_contains($zone['level'], 'CRITICAL') ? 'bg-red-500' :
                               (str_contains($zone['level'], 'HIGH') ? 'bg-orange-400' :
                               (str_contains($zone['level'], 'MODERATE') ? 'bg-yellow-400' :
                               'bg-green-400')) }}">
                        </div>

                    </div>

                    <!-- METRICS -->

                    <div class="grid grid-cols-2 gap-5 mt-10">

                        <div>

                            <div class="text-slate-500 text-xs tracking-[0.2em] uppercase">
                                Signals
                            </div>

                            <div class="text-white text-5xl font-black mt-2">
                                {{ $zone['signals'] }}
                            </div>

                        </div>

                        <div>

                            <div class="text-slate-500 text-xs tracking-[0.2em] uppercase">
                                Risk Score
                            </div>

                            <div class="text-cyan-400 text-5xl font-black mt-2">
                                {{ $zone['riskScore'] }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

            @endforeach

        </div>

    </div>

    <!-- BEHAVIORAL PATTERN ANALYSIS -->
    <div class="mt-7">

        <div class="flex items-center gap-3 mb-5">

            <div class="w-3 h-3 rounded-full bg-cyan-400 animate-pulse"></div>

            <h2 class="text-xl font-black text-white">
                Behavioral Pattern Analysis
            </h2>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- PEAK HOURS -->

            <div class="rounded-3xl border border-white/10 bg-[#081225] p-6">

                <div class="text-cyan-400 text-sm tracking-[0.3em] uppercase mb-6">
                    Peak Operational Hours
                </div>

                <div class="space-y-4">

                    @foreach($peakHours as $hour)

                    <div class="flex items-center justify-between">

                        <div class="text-white text-lg font-semibold">
                            {{ str_pad($hour->hour, 2, '0', STR_PAD_LEFT) }}:00H
                        </div>

                        <div class="flex items-center gap-4">

                            <div class="w-40 h-3 rounded-full bg-slate-800 overflow-hidden">

                                <div class="h-full bg-cyan-400 rounded-full"
                                    style="width: {{ min(100, $hour->total * 10) }}%">
                                </div>

                            </div>

                            <div class="text-cyan-400 font-black text-xl w-10 text-right">
                                {{ $hour->total }}
                            </div>

                        </div>

                    </div>

                    @endforeach

                </div>

            </div>

            <!-- ACTIVE DAYS -->

            <div class="rounded-3xl border border-white/10 bg-[#081225] p-6">

                <div class="text-orange-400 text-sm tracking-[0.3em] uppercase mb-6">
                    Operational Activity Days
                </div>

                <div class="space-y-4">

                    @foreach($activeDays as $day)

                    <div class="flex items-center justify-between">

                        <div class="text-white text-lg font-semibold">
                            {{ $day->day }}
                        </div>

                        <div class="flex items-center gap-4">

                            <div class="w-40 h-3 rounded-full bg-slate-800 overflow-hidden">

                                <div class="h-full bg-orange-400 rounded-full"
                                    style="width: {{ min(100, $day->total * 10) }}%">
                                </div>

                            </div>

                            <div class="text-orange-400 font-black text-xl w-10 text-right">
                                {{ $day->total }}
                            </div>

                        </div>

                    </div>

                    @endforeach

                </div>

            </div>

        </div>

        <!-- PATTERN INSIGHTS -->

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

            <!-- WATCHLIST -->

            <div class="rounded-3xl border border-white/10 bg-[#081225] p-6">

                <div class="text-red-400 text-sm tracking-[0.3em] uppercase mb-4">
                    Watchlist Activity Pattern
                </div>

                @if($watchlistPattern)

                <div class="text-white text-5xl font-black">
                    {{ str_pad($watchlistPattern->hour, 2, '0', STR_PAD_LEFT) }}:00H
                </div>

                <div class="text-slate-400 mt-3">
                    Highest concentration of watchlisted transmissions detected.
                </div>

                @else

                <div class="text-slate-500">
                    No watchlist activity detected.
                </div>

                @endif

            </div>

            <!-- LOW CLARITY -->

            <div class="rounded-3xl border border-white/10 bg-[#081225] p-6">

                <div class="text-yellow-400 text-sm tracking-[0.3em] uppercase mb-4">
                    Low Clarity Pattern
                </div>

                @if($lowClarityPattern)

                <div class="text-white text-5xl font-black">
                    {{ str_pad($lowClarityPattern->hour, 2, '0', STR_PAD_LEFT) }}:00H
                </div>

                <div class="text-slate-400 mt-3">
                    Repeated degraded transmissions identified.
                </div>

                @else

                <div class="text-slate-500">
                    No low clarity patterns detected.
                </div>

                @endif

            </div>

        </div>

    </div>

    <!-- AI THREAT SCORING ENGINE -->
    <div class="mt-7">

        <div class="flex items-center gap-3 mb-5">

            <div class="w-3 h-3 rounded-full bg-red-500 animate-pulse"></div>

            <h2 class="text-xl font-black text-white">
                AI Threat Scoring Engine
            </h2>

        </div>

        <div class="rounded-3xl border border-white/10 bg-[#081225] overflow-hidden">

            <!-- HEADER -->

            <div class="grid grid-cols-12 gap-4 px-6 py-5 border-b border-white/5 text-xs uppercase tracking-[0.25em] text-slate-500 font-bold">

                <div class="col-span-3">Threat Group</div>
                <div class="col-span-3">AI Risk Analysis</div>
                <div class="col-span-2">Signals</div>
                <div class="col-span-2">Geo Spread</div>
                <div class="col-span-2">Threat Score</div>

            </div>

            <!-- ROWS -->

            @foreach($aiThreatScores as $ai)

            <div class="grid grid-cols-12 gap-4 px-6 py-6 border-b border-white/5 hover:bg-white/[0.02] transition">

                <!-- THREAT -->

                <div class="col-span-3">

                    <div class="text-white text-xl font-black">
                        {{ $ai['threat'] }}
                    </div>

                </div>

                <!-- LEVEL -->

                <div class="col-span-3">

                    <div class="inline-flex px-4 py-1 rounded-full text-xs font-black tracking-[0.2em] border
                        {{ $ai['color'] }}">

                        {{ $ai['level'] }}

                    </div>

                </div>

                <!-- SIGNALS -->

                <div class="col-span-2">

                    <div class="text-cyan-400 text-3xl font-black">
                        {{ $ai['signals'] }}
                    </div>

                </div>

                <!-- GEO -->

                <div class="col-span-2">

                    <div class="text-white text-3xl font-black">
                        {{ $ai['spread'] }}
                    </div>

                </div>

                <!-- SCORE -->

                <div class="col-span-2">

                    <div class="flex items-center gap-4">

                        <div class="w-full h-3 rounded-full bg-slate-800 overflow-hidden">

                            <div
                                class="h-full rounded-full {{ $ai['bar'] }}"
                                style="width: {{ $ai['score'] }}%">
                            </div>

                        </div>

                        <div class="text-white font-black text-xl w-12 text-right">
                            {{ $ai['score'] }}
                        </div>

                    </div>

                </div>

            </div>

            @endforeach

        </div>

    </div>

    <!-- AUTOMATED INTELLIGENCE SUMMARY -->
    <div class="mt-7">

        <div class="flex items-center gap-3 mb-5">

            <div class="w-3 h-3 rounded-full bg-cyan-400 animate-pulse"></div>

            <h2 class="text-xl font-black text-white">
                Automated Intelligence Summary
            </h2>

        </div>

        <div class="rounded-2xl border border-cyan-500/20 bg-[#081225] p-5 relative overflow-hidden">

            <!-- GLOW -->

            <div class="absolute inset-0 bg-cyan-500/5"></div>

            <div class="relative z-10">

                <!-- HEADER -->

                <div class="flex items-center justify-between mb-8">

                    <div>

                        <div class="text-cyan-400 text-sm tracking-[0.3em] uppercase">
                            Operational Intelligence Brief
                        </div>

                        <div class="text-slate-400 mt-2">
                            AI-assisted surveillance interpretation engine
                        </div>

                    </div>

                    <div class="flex items-center gap-3">

                        <div class="w-3 h-3 rounded-full bg-green-400 animate-pulse"></div>

                        <div class="text-green-400 text-sm tracking-[0.25em] uppercase">
                            ACTIVE
                        </div>

                    </div>

                </div>

                <!-- SUMMARY ITEMS -->

                <div class="space-y-4">

                    @foreach($automatedSummary as $summary)

                    <div class="flex items-start gap-4">

                        <!-- ICON -->

                        <div class="mt-1 w-2 h-2 rounded-full bg-cyan-400"></div>

                        <!-- TEXT -->

                        <div class="text-white/90 text-lg leading-relaxed tracking-wide">

                            {{ $summary }}

                        </div>

                    </div>

                    @endforeach

                </div>

            </div>

        </div>

    </div>

    <!-- TACTICAL INTELLIGENCE TIMELINE -->

    <div class="mt-7">

        <div class="flex items-center gap-3 mb-5">

            <div class="w-3 h-3 rounded-full bg-orange-400 animate-pulse"></div>

            <h2 class="text-xl font-black text-white">
                Tactical Intelligence Timeline
            </h2>

        </div>

        <div class="rounded-2xl border border-orange-500/20 bg-[#081225] overflow-hidden">

            @foreach($tacticalTimeline as $event)

            <div class="border-b border-white/5 p-6 hover:bg-white/[0.02] transition">

                <div class="flex items-start justify-between gap-6">

                    <!-- LEFT -->

                    <div class="flex gap-5">

                        <!-- DOT -->

                        <div class="mt-2">

                            <div class="w-3 h-3 rounded-full
                                @if($event['eventColor'] === 'red') bg-red-400
                                @elseif($event['eventColor'] === 'yellow') bg-yellow-400
                                @else bg-cyan-400
                                @endif
                            "></div>

                        </div>

                        <!-- CONTENT -->

                        <div>

                            <div class="flex items-center gap-4 flex-wrap">

                                <div class="text-lg font-bold text-white">

                                    {{ $event['eventType'] }}

                                </div>

                                <div class="text-xs tracking-[0.25em] uppercase text-slate-500">

                                    {{ $event['date'] }}

                                </div>

                            </div>

                            <div class="mt-3 text-slate-300 leading-relaxed">

                                Frequency
                                <span class="text-cyan-400 font-semibold">
                                    {{ $event['frequency'] }}
                                </span>

                                activity detected near

                                <span class="text-white font-semibold">
                                    {{ $event['location'] }}
                                </span>

                                associated with

                                <span class="text-orange-400 font-semibold">
                                    {{ $event['threat'] }}
                                </span>

                                using clarity level

                                <span class="text-red-400 font-semibold">
                                    {{ $event['clarity'] }}
                                </span>

                            </div>

                        </div>

                    </div>

                    <!-- RIGHT -->

                    <div class="text-cyan-400 text-[11px] tracking-[0.15em] uppercase font-mono whitespace-nowrap">

                        {{ strtoupper($event['time']) }}

                    </div>

                </div>

            </div>

            @endforeach

        </div>

    </div>

    <!-- PREDICTIVE THREAT MODELING -->

    <div class="mt-7">

        <div class="flex items-center gap-3 mb-5">

            <div class="w-3 h-3 rounded-full bg-red-400 animate-pulse"></div>

            <h2 class="text-xl font-black text-white">
                Predictive Threat Modeling
            </h2>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

            @foreach($predictiveThreats as $forecast)

            <div class="rounded-2xl border border-white/10 bg-[#081225] p-5 relative overflow-hidden">

                <!-- GLOW -->

                <div class="
                    absolute inset-0 opacity-10

                    @if($forecast['riskColor'] === 'red')
                        bg-red-500
                    @elseif($forecast['riskColor'] === 'orange')
                        bg-orange-500
                    @elseif($forecast['riskColor'] === 'yellow')
                        bg-yellow-500
                    @else
                        bg-green-500
                    @endif
                "></div>

                <div class="relative z-10">

                    <!-- TOP -->

                    <div class="flex items-center justify-between">

                        <div class="text-xl font-bold text-white">

                            {{ $forecast['threat'] }}

                        </div>

                        <div class="
                            w-3 h-3 rounded-full

                            @if($forecast['riskColor'] === 'red')
                                bg-red-400
                            @elseif($forecast['riskColor'] === 'orange')
                                bg-orange-400
                            @elseif($forecast['riskColor'] === 'yellow')
                                bg-yellow-400
                            @else
                                bg-green-400
                            @endif
                        "></div>

                    </div>

                    <!-- STATUS -->

                    <div class="mt-5">

                        <span class="
                            px-4 py-2 rounded-full text-xs font-bold tracking-[0.25em]

                            @if($forecast['riskColor'] === 'red')
                                bg-red-500/20 text-red-300
                            @elseif($forecast['riskColor'] === 'orange')
                                bg-orange-500/20 text-orange-300
                            @elseif($forecast['riskColor'] === 'yellow')
                                bg-yellow-500/20 text-yellow-300
                            @else
                                bg-green-500/20 text-green-300
                            @endif
                        ">

                            {{ $forecast['riskLevel'] }}

                        </span>

                    </div>

                    <!-- PROBABILITY -->

                    <div class="mt-7">

                        <div class="text-slate-400 text-sm tracking-[0.2em] uppercase">
                            Escalation Probability
                        </div>

                        <div class="mt-2 text-5xl font-black text-white">

                            {{ $forecast['probability'] }}%

                        </div>

                    </div>

                    <!-- SIGNALS -->

                    <div class="mt-8 flex justify-between">

                        <div>

                            <div class="text-slate-500 text-xs tracking-[0.25em] uppercase">
                                Signals
                            </div>

                            <div class="mt-2 text-3xl font-bold text-cyan-400">

                                {{ $forecast['signals'] }}

                            </div>

                        </div>

                        <div>

                            <div class="text-slate-500 text-xs tracking-[0.25em] uppercase">
                                Forecast
                            </div>

                            <div class="mt-2 text-lg font-semibold text-white">

                                {{ $forecast['riskLevel'] }} RISK

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            @endforeach

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <script>
        console.log('Analytics JS loaded')

        // ✅ Blade → JS (DIRECT, NO JSON.parse)
        const analytics = @json($analyticsData)

        console.log('Analytics data:', analytics);

        const monthlyLabels = analytics.monthly.labels;
        const monthlyData = analytics.monthly.values;

        const clarityLabels = analytics.clarity.labels;
        const clarityData = analytics.clarity.values;

        const originLabels = analytics.origin.labels;
        const originData = analytics.origin.values;

        const dailyLabels = analytics.daily.labels;
        const dailyData = analytics.daily.values;

        Chart.defaults.color = '#cbd5f5';
        Chart.defaults.borderColor = '#334155';

        new Chart(document.getElementById('monthlyChart'), {
            type: 'bar',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'Records',
                    data: monthlyData,
                    backgroundColor: 'rgba(59,130,246,0.6)'
                }]
            }
        });

        new Chart(document.getElementById('clarityChart'), {
            type: 'pie',
            data: {
                labels: clarityLabels,
                datasets: [{
                    data: clarityData,
                    backgroundColor: ['#ef4444', '#f97316', '#3b82f6', '#22c55e']
                }]
            }
        });

        new Chart(document.getElementById('originChart'), {
            type: 'bar',
            data: {
                labels: originLabels,
                datasets: [{
                    label: 'Count',
                    data: originData,
                    backgroundColor: 'rgba(14,165,233,0.6)'
                }]
            }
        });

        new Chart(document.getElementById('dailyChart'), {
            type: 'line',
            data: {
                labels: dailyLabels,
                datasets: [{
                    label: 'Daily',
                    data: dailyData,
                    borderColor: '#38bdf8',
                    tension: 0.4
                }]
            }
        });
    </script>

    <script>
        new Chart(document.getElementById('escalationTrendChart'), {
            type: 'line',
            data: {
                labels: @json($monthlyLabels),
                datasets: [{
                    label: 'Threat Escalation',
                    data: @json($monthlyCounts),
                    borderColor: '#22d3ee',
                    backgroundColor: 'rgba(34,211,238,0.15)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
            }
        });

        new Chart(document.getElementById('threatDensityChart'), {
            type: 'doughnut',
            data: {
                labels: ['Critical', 'Moderate', 'Low'],
                datasets: [{
                    data: [3, 5, 8],
                    backgroundColor: [
                        '#ef4444',
                        '#facc15',
                        '#22c55e'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
            }
        });
    </script>


</x-app-layout>