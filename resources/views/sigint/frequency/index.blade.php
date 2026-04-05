<x-app-layout>

    {{-- HEADER --}}
    <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-100 flex items-center gap-3">
                📡 RADIO FREQUENCY
            </h1>
            <p class="text-sm text-slate-400 mt-1">
                Radio Frequency System
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            {{-- IMPORT EXCEL --}}
            <form id="importForm"
                action="{{ route('sigint.frequency.import') }}"
                method="POST"
                enctype="multipart/form-data"
                class="flex items-center gap-2">
                @csrf

                <input type="file"
                    name="file"
                    id="importFile"
                    class="hidden"
                    accept=".xlsx,.xls,.csv"
                    required>

                <button type="button"
                    onclick="document.getElementById('importFile').click()"
                    class="cursor-pointer px-4 py-2 rounded-lg
                   bg-slate-700 text-slate-200
                   hover:bg-slate-600 transition text-sm">
                    Choose File
                </button>

                <button type="button"
                    id="importBtn"
                    class="px-4 py-2 rounded-lg
                   bg-emerald-600 text-white
                   hover:bg-emerald-700 transition text-sm">
                    Import Excel
                </button>
            </form>

            {{-- DIVIDER --}}
            <div class="max-w-7xl mx-auto py-8 space-y-8 animate-[fadeIn_.4s_ease-in-out]"></div>

            {{-- ADD --}}
            <a href="{{ route('sigint.frequency.create') }}"
                class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition text-sm">
                + Add
            </a>

            {{-- EXPORT EXCEL --}}
            <a href="{{ route('sigint.frequency.export') }}"
                class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 transition text-sm">
                Export Excel
            </a>
            {{-- EXPORT PDF --}}
            <a href="{{ route('sigint.frequency.export.pdf') }}"
                class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition text-sm">
                Export PDF
            </a>

        </div>
    </div>


    {{-- FILTER + ANALYTICS ROW --}}
    <div class="sticky top-16 z-30
            bg-slate-900/70 backdrop-blur-md
            border border-slate-800
            rounded-2xl
            shadow-xl shadow-black/40
            p-5 mb-6">

        <form method="GET"
            action="{{ route('sigint.frequency.index') }}">
            <div class="flex items-center gap-4">

                {{-- LEFT: GLOBAL SEARCH (FLEXIBLE) --}}
                <input type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Search frequency, date, location, origin, clarity, threat…"
                    class="filter-input flex-1 min-w-[280px]">

                {{-- MIDDLE: FILTERS --}}
                <div class="flex items-center gap-3 shrink-0">

                    <select name="month" class="filter-input filter-select w-[150px]">
                        <option value="">All Months</option>
                        @foreach($distinctMonths as $m)
                        <option value="{{ $m }}" {{ request('month') === $m ? 'selected' : '' }}>
                            {{ $m }}
                        </option>
                        @endforeach
                    </select>

                    <select name="year" class="filter-input filter-select w-[130px]">
                        <option value="">All Years</option>
                        @foreach($distinctYears as $y)
                        <option value="{{ $y }}" {{ request('year') === $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                        @endforeach
                    </select>

                    <label class="flex items-center gap-2 text-sm text-slate-300 ml-1">
                        <input type="checkbox"
                            name="watchlisted"
                            value="1"
                            class="accent-blue-500"
                            {{ request()->boolean('watchlisted') ? 'checked' : '' }}>
                        Watchlisted
                    </label>
                </div>

                {{-- RIGHT: ACTIONS --}}
                <div class="flex items-center gap-2 ml-auto shrink-0">

                    <button class="px-4 py-2 bg-blue-600 hover:bg-blue-500 rounded-lg text-sm font-medium">
                        Filter
                    </button>

                    <a href="{{ route('sigint.frequency.index') }}"
                        class="px-4 py-2 bg-slate-700 hover:bg-slate-600 rounded-lg text-sm">
                        Reset
                    </a>

                    <a href="{{ route('sigint.frequency.analytics') }}"
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 rounded-lg text-sm font-semibold flex items-center gap-2">
                        📊 Analytics
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div id="cmdPalette"
        class="fixed inset-0 bg-black/60 hidden z-50 flex items-start justify-center pt-32">

        <div class="bg-slate-900 border border-slate-700 rounded-xl w-full max-w-lg p-4">
            <input id="cmdInput"
                placeholder="Type a command…"
                class="input-dark w-full mb-3">

            <ul id="cmdResults" class="space-y-2 text-sm"></ul>
        </div>
    </div>
    <div class="h-px w-full bg-gradient-to-r
            from-transparent
            via-blue-500/30
            to-transparent
            mb-4">
    </div>
    {{-- TABLE --}}
    <div
        x-data="{ openRow: null }"
        class="bg-slate-900/80 backdrop-blur-md
       border border-slate-800
       rounded-2xl
       shadow-lg shadow-black/40
       overflow-hidden">
        <!-- top gradient overlay -->
        <div class="pointer-events-none absolute top-0 left-0 right-0 h-6
                bg-gradient-to-b from-slate-900 to-transparent z-10">
        </div>
        <table class="w-full text-sm border-separate border-spacing-y-1">
            <thead class="bg-slate-800 text-slate-400 text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-4 py-3">Frequency</th>
                    <th class="px-3 py-3 text-center">Watchlist</th>
                    <th class="px-4 py-3">Date & Time</th>
                    <th class="px-4 py-3">Site Location</th>
                    <th class="px-3 py-3 text-center">LOB</th>
                    <th class="px-4 py-3">Possible Origin</th>
                    <th class="px-3 py-3 text-center">Clarity</th>
                    <th class="px-3 py-3 text-center">Convo</th>
                    <th class="px-4 py-3">Threat Group</th>
                    <th class="px-4 py-3 text-right">Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach($frequencies as $f)
                @php
                $originParts = array_filter([
                $f->barangay,
                $f->municipality,
                $f->province
                ]);

                $origin = $originParts ? implode(', ', $originParts) : '—';
                @endphp

                <tr class="border-b border-slate-800
           hover:bg-slate-800/60
           transition duration-200
           hover:scale-[1.002]">

                    {{-- Frequency --}}
                    <td class="px-4 py-3 font-semibold text-slate-100">
                        {{ $f->frequency }}
                    </td>

                    {{-- Watchlist --}}
                    <td class="px-3 py-3 text-center">
                        @if($f->is_watchlisted)
                        <span class="px-2 py-1 text-xs rounded bg-yellow-500/20 text-yellow-400">Watch</span>
                        @else
                        <span class="text-slate-500">Normal</span>
                        @endif
                    </td>

                    {{-- Date --}}
                    <td class="px-4 py-3 text-slate-300">
                        {{ $f->datetime_code }}
                    </td>

                    {{-- Site Location --}}
                    <td class="px-4 py-3 text-slate-300">
                        {{ $origin }}
                    </td>

                    {{-- LOB --}}
                    <td class="px-3 py-3 text-center">
                        @if($f->lob)
                        <span class="px-3 py-1 text-xs rounded-full
             bg-blue-500/10 text-blue-300 border border-blue-500/30">
                            {{ $f->lob }}°
                        </span>
                        @else
                        <span class="text-slate-500">—</span>
                        @endif
                    </td>

                    {{-- Possible Origin --}}
                    <td class="px-4 py-3 text-slate-300">
                        {{ $f->site_location ?: '—' }}
                    </td>

                    {{-- Clarity --}}
                    <td class="px-3 py-3 text-center">
                        @if($f->clarity)
                        <span class="px-3 py-1 text-xs rounded-full
             bg-emerald-500/10 text-emerald-300 border border-emerald-500/30">
                            {{ $f->clarity }}
                        </span>
                        @else
                        —
                        @endif
                    </td>

                    {{-- Conversation --}}
                    <td class="px-4 py-3 text-center">
                        @if($f->conversation)
                        <button
                            type="button"
                            class="flex items-center justify-center gap-1 text-blue-400 hover:text-blue-300 text-sm"
                            onclick="togglePreview({{ $f->id }}, this)">
                            <span>View</span>
                            <svg class="w-4 h-4 transition-transform" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                        @else
                        —
                        @endif
                    </td>


                    {{-- Threat Confronted --}}
                    <td class="px-4 py-3">
                        @if($f->threat_confronted)
                        @php
                        $threatColors = [
                        'SRC' => 'bg-blue-500/10 text-blue-300 border-blue-500/30',
                        'SRGU' => 'bg-red-500/10 text-red-300 border-red-500/30',
                        'SRMA' => 'bg-orange-500/10 text-orange-300 border-orange-500/30',
                        'SROC' => 'bg-green-500/10 text-green-300 border-green-500/30',
                        'SRMA EMPORIUM' => 'bg-yellow-500/10 text-yellow-300 border-yellow-500/30',
                        'SRMA ARCTIC' => 'bg-teal-500/10 text-teal-300 border-teal-500/30',
                        'SRMA BROWSER' => 'bg-cyan-500/10 text-cyan-300 border-cyan-500/30',
                        'SRMA SESAME' => 'bg-purple-500/10 text-purple-300border-purple-500/30',
                        'SRMA LEVOX' => 'bg-pink-500/10 text-pink-300 border-pink-500/30',
                        'COMTECH' => 'bg-indigo-500/10 text-indigo-300 border-indigo-500/30',
                        'EV MRGU' => 'bg-red-500/10 text-red-300 border-red-500/30',
                        'FUNCTIONAL' => 'bg-purple-500/10 text-purple-300 border-purple-500/30',
                        'UNKNOWN' => 'bg-slate-600/20 text-slate-300 border-slate-500/30',
                        ];

                        $threatStyle = $threatColors[$f->threat_confronted]
                        ?? 'bg-slate-600/20 text-slate-300 border-slate-500/30';
                        @endphp

                        <span class="px-3 py-1 text-xs rounded-full border {{ $threatStyle }}">
                            {{ $f->threat_confronted }}
                        </span>
                        @else
                        —
                        @endif
                    </td>

                    {{-- Actions --}}
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('sigint.frequency.edit', $f->id) }}"
                            class="text-blue-400 hover:underline">Edit</a>

                        <button
                            onclick="openFrequencyDeleteModal({{ $f->id }}, '{{ $f->frequency }}')"
                            class="text-red-400 hover:underline">
                            Delete
                        </button>

                    </td>

                </tr>

                <tr x-show="openRow === {{ $f->id }}" x-transition>
                    <td colspan="10" class="bg-slate-800 p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-slate-300">

                            {{-- Conversation --}}
                            <div>
                                <p class="font-semibold text-slate-100 mb-1">Intercepted Conversation</p>
                                <p class="whitespace-pre-wrap">
                                    {{ $f->conversation ?? '—' }}
                                </p>
                            </div>

                            {{-- Intel Summary --}}
                            <div class="space-y-1">
                                <p><strong>Frequency:</strong> {{ $f->frequency }}</p>
                                <p><strong>Date/Time:</strong> {{ $f->datetime_code }}</p>
                                <p><strong>LOB:</strong> {{ $f->lob }}</p>
                                <p><strong>Origin:</strong> {{ $origin }}</p>
                                <p><strong>Threat Level:</strong>
                                    <span class="px-2 py-1 text-xs rounded
                        @if($f->threat_level === 'High') bg-red-600/20 text-red-400
                        @elseif($f->threat_level === 'Medium') bg-yellow-600/20 text-yellow-400
                        @else bg-green-600/20 text-green-400 @endif">
                                        {{ $f->threat_level ?? 'Low' }}
                                    </span>
                                </p>
                            </div>



                            <!-- Collapsible Panel -->
                <tr id="preview-{{ $f->id }}" class="hidden">
                    <td colspan="11" class="px-6 py-0">
                        <div
                            class="preview-panel overflow-hidden max-h-0 transition-all duration-300 ease-in-out">

                            <div class="mt-4 mb-6 bg-slate-900/80 border border-slate-700 rounded-xl p-5 grid grid-cols-1 md:grid-cols-2 gap-6">

                                <!-- Conversation -->
                                <div>
                                    <p class="text-xs uppercase text-slate-400 mb-2">Conversation</p>
                                    <div class="preview-panel">
                                        {{ $f->conversation ?: '—' }}
                                    </div>
                                </div>

                                <!-- Signal Context -->
                                <div class="text-sm text-slate-300 leading-relaxed space-y-2">
                                    <p class="italic text-slate-400">
                                        Automated Signal Analysis
                                    </p>

                                    <p>
                                        This radio signal was intercepted
                                        @if($f->lob)
                                        at a bearing of <strong>{{ $f->lob }}°</strong>,
                                        @endif

                                        @if($f->barangay || $f->municipality || $f->province)
                                        suggesting a probable origin near
                                        <strong>
                                            {{ collect([$f->barangay, $f->municipality, $f->province])
                    ->filter()
                    ->implode(', ') }}
                                        </strong>.
                                        @else
                                        with an undetermined point of origin.
                                        @endif
                                    </p>

                                    <p>
                                        The signal clarity is assessed as
                                        <strong>{{ $f->clarity ?? 'undetermined' }}</strong>,
                                        indicating
                                        @switch($f->clarity)
                                        @case('1x1') fading signal with heavy interference. @break
                                        @case('1x2') fading signal with distortion. @break
                                        @case('1x3') fading signal with unreadable context. @break
                                        @case('1x4') fading signal with readable context. @break
                                        @case('1x5') fading signal with clear reception. @break

                                        @case('2x1') very weak signal with heavy interference. @break
                                        @case('2x2') very weak signal with distortion. @break
                                        @case('2x3') very weak signal with unreadable context. @break
                                        @case('2x4') very weak signal with readable context. @break
                                        @case('2x5') very weak signal with clear reception. @break

                                        @case('3x1') weak signal with heavy interference. @break
                                        @case('3x2') weak signal with distortion. @break
                                        @case('3x3') weak signal with unreadable context. @break
                                        @case('3x4') weak signal with readable context. @break
                                        @case('3x5') weak signal with clear reception. @break

                                        @case('4x1') good clarity with interference. @break
                                        @case('4x2') good clarity with minor distortion. @break
                                        @case('4x3') good clarity with mostly readable context. @break
                                        @case('4x4') good clarity with readable context. @break
                                        @case('4x5') good clarity with clear reception. @break

                                        @case('5x1') loud signal with interference. @break
                                        @case('5x2') loud signal with minor distortion. @break
                                        @case('5x3') loud signal with mostly readable context. @break
                                        @case('5x4') loud signal with readable context. @break
                                        @case('5x5') loud signal with clear reception. @break
                                        @default unknown signal quality.
                                        @endswitch
                                    </p>

                                    <p>
                                        Monitoring operations were conducted from
                                        <strong>{{ $f->site_location ?? 'an unspecified location' }}</strong>.
                                    </p>

                                    @if($f->is_watchlisted)
                                    <p class="text-yellow-400">
                                        ⚠ This frequency has been flagged for continued monitoring.
                                    </p>
                                    @endif

                                    @if($f->threat_confronted)
                                    <p class="text-red-400">
                                        Threat confronted identified as <strong>{{ $f->threat_confronted }}</strong>.
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>

    </div>
    </td>
    </tr>

    @endforeach
    </tbody>
    </table>

    <div class="p-4">
        {{ $frequencies->links() }}
    </div>

    </div>

    <style>
        /* Collapsible preview scrollbar */
        .preview-panel::-webkit-scrollbar {
            width: 6px;
        }

        .preview-panel::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 4px;
        }
    </style>

    <style>
        .filter-input {
            background: #020617;
            border: 1px solid #334155;
            color: #e5e7eb;
            border-radius: 0.5rem;
            padding: 0.55rem 0.75rem;
        }

        .filter-input::placeholder {
            color: #94a3b8;
        }

        .filter-input:focus {
            outline: none;
            border-color: #3b82f6;
        }
    </style>


</x-app-layout>

<!-- FREQUENCY DELETE MODAL -->
<div id="frequencyDeleteModal"
    class="fixed inset-0 z-50 flex items-center justify-center hidden">

    <!-- BACKDROP -->
    <div id="frequencyModalBackdrop"
        class="absolute inset-0 bg-black/60 backdrop-blur-sm opacity-0 transition-opacity duration-300"
        onclick="closeFrequencyDeleteModal()">
    </div>

    <!-- MODAL -->
    <div id="frequencyModalContent"
        class="relative bg-slate-900 border border-slate-700
                rounded-2xl w-full max-w-md p-6 shadow-2xl
                opacity-0 scale-95 transition-all duration-300">

        <div class="flex items-start gap-4">
            <div class="bg-red-600/20 text-red-400 p-3 rounded-xl text-xl">
                ⚠
            </div>

            <div>
                <h2 class="text-lg font-semibold text-white">
                    Delete Frequency Record
                </h2>

                <p class="text-sm text-slate-400 mt-1">
                    Frequency: <span id="frequencyLabel" class="text-slate-200"></span>
                </p>

                <p class="text-sm text-red-400 mt-2">
                    This action permanently removes this record.
                </p>
            </div>
        </div>

        <div class="flex justify-end gap-4 mt-6">

            <button onclick="closeFrequencyDeleteModal()"
                class="w-40 h-11
                           flex items-center justify-center
                           rounded-lg
                           bg-slate-600 hover:bg-slate-500
                           text-white font-medium transition">
                Cancel
            </button>

            <form id="frequencyDeleteForm" method="POST">
                @csrf
                @method('DELETE')

                <button id="frequencyConfirmBtn"
                    type="submit"
                    class="w-40 h-11
                               flex items-center justify-center gap-2
                               rounded-lg
                               bg-red-600 hover:bg-red-500
                               text-white font-medium transition">

                    <span id="frequencyDeleteText">Confirm Delete</span>

                    <svg id="frequencyDeleteSpinner"
                        class="hidden animate-spin h-4 w-4"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25"
                            cx="12"
                            cy="12"
                            r="10"
                            stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75"
                            fill="currentColor"
                            d="M4 12a8 8 0 018-8v4l3-3-3-3v4a12 12 0 00-12 12h4z">
                        </path>
                    </svg>
                </button>
            </form>

        </div>
    </div>
</div>

{{-- SWEETALERT --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmDelete(id) {
        Swal.fire({
            title: "Delete this entry?",
            text: "This action cannot be undone.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>

<script>
    function togglePreview(id, btn) {
        const row = document.getElementById(`preview-${id}`);
        if (!row) return;

        const panel = row.querySelector('.preview-panel');
        const icon = btn.querySelector('svg');

        if (row.classList.contains('hidden')) {
            row.classList.remove('hidden');

            requestAnimationFrame(() => {
                panel.style.maxHeight = panel.scrollHeight + 'px';
                icon.classList.add('rotate-180');
            });
        } else {
            panel.style.maxHeight = '0px';
            icon.classList.remove('rotate-180');

            setTimeout(() => {
                row.classList.add('hidden');
            }, 300);
        }

    }
</script>

<script>
    document.getElementById('importBtn')?.addEventListener('click', function() {
        const fileInput = document.getElementById('importFile');

        if (!fileInput.files.length) {
            Swal.fire({
                icon: 'warning',
                title: 'No file selected',
                text: 'Please choose an Excel file first.'
            });
            return;
        }

        Swal.fire({
            title: 'Import Excel?',
            text: 'This will add new frequency records to the database.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#16a34a',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, import it'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('importForm').submit();
            }
        });
    });
</script>

<script>
    function toggleWatchlist(id) {
        fetch(`/sigint/frequency/${id}/watchlist`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(() => location.reload())
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to update watchlist status.'
                });
            });
    }
</script>

<script>
    const freqForm = document.getElementById('frequencyDeleteForm');
    const freqLabel = document.getElementById('frequencyLabel');

    const freqBtn = document.getElementById('frequencyConfirmBtn');
    const freqText = document.getElementById('frequencyDeleteText');
    const freqSpinner = document.getElementById('frequencyDeleteSpinner');

    let freqModalOpen = false;

    function openFrequencyDeleteModal(id, frequency) {
        const modal = document.getElementById('frequencyDeleteModal');
        const backdrop = document.getElementById('frequencyModalBackdrop');
        const content = document.getElementById('frequencyModalContent');

        // SHOW MODAL
        modal.classList.remove('hidden');

        // ANIMATE
        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            content.classList.remove('opacity-0', 'scale-95');
            content.classList.add('opacity-100', 'scale-100');
        }, 10);

        // SET DATA
        document.getElementById('frequencyLabel').innerText = frequency;
        document.getElementById('frequencyDeleteForm').action = `/sigint/frequency/${id}`;
    }

    function closeFrequencyDeleteModal() {
        const modal = document.getElementById('frequencyDeleteModal');
        const backdrop = document.getElementById('frequencyModalBackdrop');
        const content = document.getElementById('frequencyModalContent');

        backdrop.classList.add('opacity-0');
        content.classList.add('opacity-0', 'scale-95');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // ESC Close
    document.addEventListener('keydown', function(e) {
        if (e.key === "Escape" && freqModalOpen) {
            closeFrequencyDeleteModal();
        }
    });

    // Outside Click
    freqBackdrop.addEventListener('click', function() {
        closeFrequencyDeleteModal();
    });

    // Loading state
    freqForm.addEventListener('submit', function() {
        freqBtn.disabled = true;
        freqText.classList.add('hidden');
        freqSpinner.classList.remove('hidden');
    });
</script>

@if(session('exported'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Export Complete',
        text: 'Your Excel file has been downloaded.',
        timer: 2000,
        showConfirmButton: false
    });
</script>
@endif