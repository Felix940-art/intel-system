<x-app-layout>

    <div class="max-w-7xl mx-auto space-y-6">

        {{-- ========================================= --}}
        {{-- HEADER --}}
        {{-- ========================================= --}}
        <div class="flex justify-between items-center mb-6">

            <div>
                <h1 class="text-2xl font-bold text-white">
                    🛰 ISTAR Report
                </h1>

                <p class="text-xs text-slate-400 tracking-wider">
                    Intelligence Surveillance Target Acquisition & Reconnaissance
                </p>

            </div>

            <div class="flex gap-3">
                <a href="{{ route('geoint.create') }}"
                    class="btn-accent">
                    + Add Record
                </a>

                <a href="{{ route('geoint.uav-intel') }}"
                    class="btn-secondary">
                    UAV Intelligence
                </a>
            </div>

        </div>

        <div class="command-bar">
            <span class="live-indicator">
                <span class="live-dot"></span>
                LIVE
            </span>

            <span class="command-divider"></span>

            <span>{{ now()->format('d M Y') }}</span>

            <span class="command-divider"></span>

            <span>SECTOR: CENTRAL COMMAND</span>

            <span class="command-divider"></span>

            <span>MISSIONS: <strong>{{ $totalMissions }}</strong></span>

            <span class="command-divider"></span>

            <span>TODAY: <strong>{{ $todayMissions }}</strong></span>

            <span class="command-divider"></span>

            <span>WEEK: <strong>{{ $weekMissions }}</strong></span>

        </div>

        <div class="border-t border-slate-800 my-6"></div>

        {{-- ========================================= --}}
        {{-- FILTER BAR --}}
        {{-- ========================================= --}}
        <div class="sticky top-0 z-40">

            <div class="intel-filter-bar">

                <form method="GET"
                    action="{{ route('geoint.index') }}"
                    class="flex flex-wrap items-center gap-4">

                    <input type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search location, coordinates..."
                        class="filter-input w-80">

                    <input type="date"
                        name="date"
                        value="{{ request('date') }}"
                        class="filter-input w-44">

                    <div class="ml-auto flex items-center gap-3">

                        <span class="result-badge">
                            {{ $geointRecords->count() }} Results
                        </span>

                        <button type="submit" class="btn-primary">
                            Filter
                        </button>

                        <a href="{{ route('geoint.index') }}"
                            class="btn-secondary">
                            Clear
                        </a>

                    </div>

                </form>

            </div>
        </div>


        {{-- ========================================= --}}
        {{-- TABLE --}}
        {{-- ========================================= --}}
        <div class="intel-table-wrapper">

            <table class="intel-table">

                <thead class="bg-slate-900 border border-slate-800 text-xs uppercase tracking-widest text-slate-400">
                    <tr>
                        <th class="px-5 py-3 text-left">Date / Time</th>
                        <th class="px-5 py-3 text-left">Document</th>
                        <th class="px-5 py-3 text-left">UAV</th>
                        <th class="px-5 py-3 text-left">Home Point (MGRS)</th>
                        <th class="px-5 py-3 text-left">Threat</th>
                        <th class="px-5 py-3 text-right">Action</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($geointRecords as $record)

                    @php
                    $threatColors = [
                    'LOW' => 'bg-green-900 text-green-400 border-green-500',
                    'MEDIUM' => 'bg-yellow-900 text-yellow-400 border-yellow-500',
                    'HIGH' => 'bg-red-900 text-red-400 border-red-500',
                    ];
                    @endphp

                    <tr class="bg-slate-900/60 hover:bg-slate-800/60 transition duration-300 hover:scale-[1.01]">

                        {{-- DATE --}}
                        <td class="px-5 py-4 text-slate-300">
                            {{ optional($record->mission_datetime)->format('d M Y H:i') }}
                        </td>

                        {{-- DOCUMENT --}}
                        <td class="px-5 py-4">

                            @if($record->document_path)

                            <div class="flex items-center gap-3">

                                {{-- Preview --}}
                                <button onclick="openDocPreview('{{ asset('storage/'.$record->document_path) }}')"
                                    class="text-cyan-400 hover:text-cyan-300 text-sm">
                                    Preview
                                </button>

                                {{-- Download --}}
                                <a href="{{ asset('storage/'.$record->document_path) }}"
                                    download
                                    class="text-indigo-400 hover:text-indigo-300 text-sm">
                                    Download
                                </a>

                            </div>

                            @else
                            —
                            @endif

                        </td>

                        {{-- UAV --}}
                        <td class="px-5 py-4 text-slate-300 font-medium">
                            {{ $record->uav ?? '—' }}
                        </td>

                        {{-- HOME POINT --}}
                        <td class="px-5 py-4 text-slate-300">
                            {{ $record->home_point_mgrs ?? '—' }}
                        </td>

                        {{-- THREAT --}}
                        <td class="px-5 py-4 text-center">
                            <span class="px-3 py-1 text-xs rounded-full border
                                {{ $threatColors[$record->threat_confronted] ?? 'bg-slate-800 text-slate-400 border-slate-600' }}">
                                {{ $record->threat_confronted ?? 'N/A' }}
                            </span>
                        </td>

                        {{-- ACTION --}}
                        <td class="px-5 py-4 text-right">
                            <div class="flex justify-end gap-4">

                                <a href="{{ route('geoint.edit', $record->id) }}"
                                    class="text-blue-400 hover:text-blue-300">
                                    Edit
                                </a>

                                <button onclick="openDeleteModal({{ $record->id }})"
                                    class="text-red-400 hover:text-red-300">
                                    Delete
                                </button>

                            </div>
                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="6" class="p-12 text-center text-slate-500">
                            No GEOINT mission logs found.
                        </td>
                    </tr>

                    @endforelse

                </tbody>


            </table>
            <!-- DOCUMENT PREVIEW MODAL -->
            <div id="docPreviewModal" class="fixed inset-0 z-50 hidden">

                <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"
                    onclick="closeDocPreview()"></div>

                <div class="absolute inset-0 flex items-center justify-center p-6">

                    <div class="bg-slate-900 border border-slate-700
                    rounded-xl w-full max-w-4xl h-[80vh] p-4">

                        <iframe id="docPreviewFrame"
                            class="w-full h-full rounded-lg"
                            src="">
                        </iframe>

                    </div>

                </div>
            </div>


        </div>

    </div>


    {{-- ========================================= --}}
    {{-- STYLES --}}
    {{-- ========================================= --}}
    <style>
        .command-bar {
            display: flex;
            align-items: center;
            gap: 14px;
            font-size: 12px;
            letter-spacing: 1.5px;
            color: #94a3b8;
            margin-top: 8px;
        }

        .command-bar strong {
            color: #22d3ee;
            font-weight: 600;
        }

        .command-divider {
            width: 1px;
            height: 12px;
            background: #1e293b;
        }

        .live-indicator {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #22d3ee;
        }

        .live-dot {
            width: 5px;
            height: 5px;
            background: #4bee22;
            border-radius: 50%;
            box-shadow: 0 0 6px rgb(65, 238, 34);
            animation: livePulse 1.6s infinite;
        }

        .card-intel {
            background: linear-gradient(145deg, #0f172a, #0b1220);
            box-shadow: 0 0 15px rgba(0, 255, 255, 0.05);
        }

        .intel-filter-bar {
            background: rgba(2, 6, 23, 0.95);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(51, 65, 85, .6);
            padding: 1.25rem;
        }

        .filter-input {
            background: #020617;
            border: 1px solid #334155;
            border-radius: .75rem;
            padding: .6rem .9rem;
            color: #e2e8f0;
            font-size: .875rem;
        }

        .result-badge {
            background: rgba(14, 165, 233, .15);
            border: 1px solid rgba(14, 165, 233, .4);
            color: #7dd3fc;
            padding: .35rem .75rem;
            border-radius: 999px;
            font-size: .75rem;
        }

        .btn-primary {
            background: #2563eb;
            padding: .55rem 1rem;
            border-radius: .75rem;
            color: white;
        }

        .btn-secondary {
            background: #334155;
            padding: .55rem 1rem;
            border-radius: .75rem;
            color: white;
        }

        .btn-accent {
            background: #06b6d4;
            padding: .6rem 1.2rem;
            border-radius: .75rem;
            color: white;
        }

        .stat-card {
            background: rgba(15, 23, 42, .7);
            border: 1px solid #1e293b;
            border-radius: .75rem;
            padding: 1rem;
        }

        .stat-label {
            font-size: .75rem;
            color: #64748b;
            text-transform: uppercase;
        }

        .stat-value {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .intel-table-wrapper {
            background: #0f172a;
            border: 1px solid #1e293b;
            border-radius: .75rem;
            overflow: hidden;
        }

        .intel-table {
            width: 100%;
            font-size: .875rem;
        }

        .intel-table thead {
            background: #1e293b;
            color: #94a3b8;
            text-transform: uppercase;
            font-size: .75rem;
        }

        .intel-table th,
        .intel-table td {
            padding: 1rem;
        }

        .intel-card {
            background: linear-gradient(145deg, #0b1220, #0f172a);
            border: 1px solid #1e293b;
            border-radius: 14px;
            padding: 22px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: all .3s ease;
        }

        .intel-card:hover {
            border-color: #22d3ee;
            box-shadow: 0 0 18px rgba(34, 211, 238, 0.2);
        }

        .intel-label {
            font-size: 11px;
            letter-spacing: 2px;
            color: #64748b;
            margin-bottom: 8px;
        }

        .intel-value {
            font-size: 34px;
            font-weight: 700;
        }

        .table-row:hover {
            background: #1e293b;
        }

        .empty-state {
            padding: 4rem;
            text-align: center;
            color: #64748b;
        }

        .risk-badge {
            padding: .25rem .6rem;
            border-radius: 999px;
            font-size: .75rem;
        }

        .risk-low {
            background: rgba(34, 197, 94, .15);
            color: #4ade80;
        }

        .risk-medium {
            background: rgba(250, 204, 21, .15);
            color: #facc15;
        }

        .risk-high {
            background: rgba(239, 68, 68, .15);
            color: #f87171;
        }

        @keyframes livePulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.5);
                opacity: 0.5;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>

</x-app-layout>

<script>
    function openDocPreview(url) {
        document.getElementById('docPreviewFrame').src = url;
        document.getElementById('docPreviewModal').classList.remove('hidden');
    }

    function closeDocPreview() {
        document.getElementById('docPreviewModal').classList.add('hidden');
        document.getElementById('docPreviewFrame').src = '';
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('docPreviewModal');
            if (!modal.classList.contains('hidden')) {
                closeDocPreview();
            }
        }
    });
</script>