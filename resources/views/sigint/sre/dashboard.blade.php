<!-- DELETE MODAL -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden">

    <div id="modalBackdrop"
        class="absolute inset-0 bg-black/60 backdrop-blur-sm opacity-0 transition-opacity duration-300">
    </div>

    <div class="absolute inset-0 flex items-center justify-center p-4">

        <div id="modalContent"
            class="bg-slate-900 border border-slate-700
                   rounded-2xl w-full max-w-md p-6 shadow-2xl
                   transform scale-95 opacity-0
                   transition-all duration-300">

            <div class="flex items-start gap-4">
                <div class="bg-red-600/20 text-red-400 p-3 rounded-xl">⚠</div>

                <div>
                    <h2 class="text-lg font-semibold text-white">
                        Delete SRE Record
                    </h2>
                    <p class="text-sm text-slate-400 mt-1">
                        This action permanently removes this event.
                    </p>
                </div>
            </div>

            <div class="flex justify-center gap-4 mt-6">

                <button type="button"
                    onclick="closeDeleteModal()"
                    class="w-40 h-11 flex items-center justify-center
                           rounded-lg bg-slate-700 hover:bg-slate-600
                           text-white font-medium transition">
                    Cancel
                </button>

                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')

                    <button id="confirmDeleteBtn"
                        type="submit"
                        class="w-40 h-11 flex items-center justify-center gap-2
                               rounded-lg bg-red-600 hover:bg-red-500
                               text-white font-medium transition">

                        <span id="deleteBtnText">Confirm Delete</span>

                        <svg id="deleteSpinner"
                            class="hidden animate-spin h-4 w-4"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25"
                                cx="12" cy="12" r="10"
                                stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8v4l3-3-3-3v4a12 12 0 00-12 12h4z" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<x-app-layout>

    {{-- HEADER --}}

    <div class="flex flex-wrap items-center justify-between gap-4">

        {{-- LEFT --}}
        <div>
            <h1 class="text-2xl font-bold text-slate-100 flex items-center gap-3">
                🛰️ SRE System
            </h1>
            <p class="text-sm text-slate-400 mt-1">
                GSM Networks Monitoring Database
            </p>

            <div class="flex items-center gap-1 mt-1 text-xs text-slate-400">
                <span class="relative px-3 py-1 rounded-md bg-slate-800 border border-slate-700 text-xs">
                    <span class="absolute -left-1 -top-1 h-2 w-2 bg-emerald-400 rounded-full animate-ping"></span>
                    <span class="absolute -left-1 -top-1 h-2 w-2 bg-emerald-400 rounded-full"></span>
                    Live Operational Feed
                </span>

                <span>
                    {{ now()->format('d M Y • H:i') }}
                </span>
            </div>
        </div>


        {{-- RIGHT ACTIONS --}}
        <div class="flex flex-wrap items-center gap-3">

            {{-- IMPORT --}}
            <form id="sreImportForm"
                action="{{ route('sigint.sre.import') }}"
                method="POST"
                enctype="multipart/form-data"
                class="flex items-center gap-2">
                @csrf

                <input type="file"
                    name="file"
                    id="sreImportFile"
                    class="hidden"
                    accept=".xlsx,.xls,.csv"
                    required>

                <button type="button"
                    onclick="document.getElementById('sreImportFile').click()"
                    class="h-11 px-5 inline-flex items-center justify-center gap-2
                                rounded-xl
                                bg-slate-700 hover:bg-slate-600
                                text-slate-200 text-sm font-medium
                                shadow-md shadow-slate-800/40
                                hover:shadow-slate-600/40
                                transition-all duration-200">
                    Choose File
                </button>


                <button type="submit"
                    id="sreImportBtn"
                    class="h-11 px-5 inline-flex items-center justify-center gap-2
                                rounded-xl
                                bg-emerald-600 hover:bg-emerald-500
                                text-white text-sm font-medium
                                shadow-lg shadow-emerald-600/20
                                hover:shadow-emerald-500/40
                                transition-all duration-200">

                    <span id="sreImportText">Import Excel</span>

                    <svg id="sreImportSpinner"
                        class="hidden animate-spin w-4 h-4"
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


                {{-- EXPORT --}}
                <a href="{{ route('sigint.sre.export') }}"
                    class="h-11 px-5 inline-flex items-center justify-center gap-2
                                rounded-xl
                                bg-green-600 hover:bg-green-500
                                text-white text-sm font-medium
                                shadow-lg shadow-green-600/20
                                hover:shadow-green-500/40
                                transition-all duration-200">
                    Export Excel
                </a>


                <a href="{{ route('sigint.sre.export.pdf') }}"
                    class="h-11 px-5 inline-flex items-center justify-center
                                rounded-xl
                                bg-red-600 hover:bg-red-700
                                text-white text-sm font-medium transition">
                    Export PDF
                </a>
            </form>
        </div>
    </div>

    <!-- STICKY FILTER BAR -->
    <div id="filterBarWrapper" class="sticky top-0 z-50 w-full">

        <div id="sreFilterBar"
            class="relative
               bg-[#020617]/95
               backdrop-blur-2xl
               border border-slate-800/60
               rounded-2xl
               px-6 py-4
               shadow-xl shadow-black/60
               transition-all duration-300">

            <!-- Subtle Tactical Glow Line -->
            <div class="absolute inset-x-0 -top-px h-px
                    bg-gradient-to-r
                    from-transparent
                    via-blue-500/40
                    to-transparent">
            </div>

            <form method="GET"
                action="{{ route('sigint.sre.index') }}"
                class="flex flex-wrap items-center gap-4">

                {{-- SEARCH --}}
                <input type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search selector, IMEI, IMSI, code name..."
                    class="filter-input w-80">

                {{-- THREAT --}}
                <select name="threat"
                    class="filter-input w-44">
                    <option value="">All Threats</option>
                    @foreach($threats as $t)
                    <option value="{{ $t }}"
                        {{ request('threat') == $t ? 'selected' : '' }}>
                        {{ $t }}
                    </option>
                    @endforeach
                </select>

                {{-- DATE --}}
                <input type="date"
                    name="date"
                    value="{{ request('date') }}"
                    class="filter-input w-44">

                {{-- RIGHT SIDE ACTIONS --}}
                <div class="flex items-center gap-3 ml-auto">

                    {{-- Result Counter --}}
                    <span class="px-3 py-1 text-xs rounded-full
                             bg-indigo-500/10
                             text-indigo-300
                             border border-indigo-500/30
                             backdrop-blur-md">
                        {{ collect($groupedEvents)->flatten()->count() }} Results
                    </span>

                    {{-- FILTER --}}
                    <button type="submit"
                        class="h-10 px-4 inline-flex items-center justify-center gap-2
               rounded-xl
               bg-blue-600 hover:bg-blue-500
               text-white text-sm font-semibold
               shadow-md shadow-blue-600/20
               hover:shadow-blue-500/40
               transition-all duration-200">
                        🔎 Filter
                    </button>

                    {{-- CLEAR --}}
                    <a href="{{ route('sigint.sre.index') }}"
                        class="h-10 px-4 inline-flex items-center justify-center gap-2
              rounded-xl
              bg-slate-700 hover:bg-slate-600
              text-slate-200 text-sm font-semibold
              border border-slate-600
              hover:border-slate-500
              transition-all duration-200">
                        ✖ Clear
                    </a>

                    {{-- ADD ENTRY --}}
                    <a href="{{ route('sigint.sre.create') }}"
                        class="h-10 px-4 inline-flex items-center justify-center gap-2
              rounded-xl
              bg-indigo-600 hover:bg-indigo-500
              text-white text-sm font-semibold
              shadow-md shadow-indigo-600/20
              hover:shadow-indigo-500/40
              transition-all duration-200">
                        ➕ Add Entry
                    </a>

                </div>

            </form>

            {{-- ACTIVE FILTER CHIPS --}}
            <div class="mt-3 flex flex-wrap gap-2">
                @if(request('search'))
                <span class="filter-chip">
                    Search: "{{ request('search') }}"
                </span>
                @endif

                @if(request('threat'))
                <span class="filter-chip">
                    Threat: {{ request('threat') }}
                </span>
                @endif

                @if(request('date'))
                <span class="filter-chip">
                    Date: {{ request('date') }}
                </span>
                @endif
            </div>

        </div>
    </div>

    <div class="h-px w-full bg-gradient-to-r
            from-transparent
            via-blue-500/30
            to-transparent
            mb-4">
    </div>

    <!-- TABLE -->
    <div class="relative bg-slate-900 border border-slate-800 rounded-xl overflow-hidden">

        <!-- Top gradient fade -->
        <div class="pointer-events-none absolute top-0 left-0 right-0 h-6
                bg-gradient-to-b from-slate-900 to-transparent z-10">
        </div>

        <table class="w-full text-sm border-separate border-spacing-y-1">

            <thead class="bg-slate-800 text-slate-400 text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-3 text-left">Date / Time</th>
                    <th class="px-5 py-3 text-left">Code Name</th>
                    <th class="px-5 py-3 text-left">Selector</th>
                    <th class="px-5 py-3 text-left">IMEI</th>
                    <th class="px-5 py-3 text-left">IMSI</th>
                    <th class="px-5 py-3 text-left">LAC</th>
                    <th class="px-5 py-3 text-left">CID</th>
                    <th class="px-5 py-3 text-left">Threat</th>
                    <th class="px-5 py-3 text-right">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($groupedEvents as $group => $items)

                @foreach($items as $e)

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

                $threatStyle = $threatColors[$e->threat_group]
                ?? 'bg-slate-600/20 text-slate-300 border-slate-500/30';
                @endphp

                <tr class="animate-rowFade
                                bg-slate-900/60 hover:bg-slate-800/60
                                transition duration-300
                                hover:scale-[1.01]
                                hover:shadow-lg
                                {{ $e->description === 'UNKNOWN' ? '' : 'hover:shadow-red-500/20' }}">

                    <td class="px-5 py-4 text-slate-300">
                        {{ optional($e->created_at)->format('d M Y H:i') ?? '—' }}
                    </td>

                    <td class="px-5 py-4 text-center">
                        @if($e->selector->code_name)
                        <span class="px-3 py-1 text-xs rounded-full
                                bg-cyan-500/10 text-cyan-300 border border-cyan-500/30">
                            {{ $e->selector->code_name ?? '—' }}
                        </span>
                        @else
                        —
                        @endif
                    </td>

                    <td class="px-5 py-4 text-slate-300 font-medium">
                        {{ $e->selector->selector_value ?? '—' }}
                    </td>

                    <td class="px-5 py-4 text-slate-300">{{ $e->imei ?? '—' }}</td>
                    <td class="px-5 py-4 text-slate-300">{{ $e->imsi ?? '—' }}</td>
                    <td class="px-5 py-4 text-slate-300">{{ $e->lac ?? '—' }}</td>
                    <td class="px-5 py-4 text-slate-300">{{ $e->cid ?? '—' }}</td>

                    <td class="px-5 py-4 text-center">
                        @if($e->selector && $e->selector->threat_group)
                        <span class="px-3 py-1 text-xs rounded-full border {{ $threatStyle }}">
                            {{ $e->selector->threat_group }}
                        </span>
                        @else
                        —
                        @endif
                    </td>

                    <td class="px-5 py-4 text-right">
                        <div class="flex justify-end gap-4">
                            <a href="{{ route('sigint.sre.edit', $e->id) }}"
                                class="text-blue-400 hover:text-blue-300 transition">
                                Edit
                            </a>

                            <button
                                onclick="openDeleteModal({{ $e->id }})"
                                class="text-red-400 hover:text-red-300 transition">
                                Delete
                            </button>
                        </div>
                    </td>

                </tr>

                @endforeach

                @empty

                <tr>
                    <td colspan="9" class="p-12 text-center">

                        <div class="flex flex-col items-center gap-3">

                            <div class="text-4xl opacity-30">🛰️</div>

                            <p class="text-slate-400 text-sm">
                                No SRE events found.
                            </p>

                            <a href="{{ route('sigint.sre.create') }}"
                                class="mt-2 px-4 py-2 rounded-lg
                                      bg-blue-600 hover:bg-blue-500
                                      text-white text-sm transition">
                                Add First Entry
                            </a>

                        </div>

                    </td>
                </tr>

                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>

@if(session('success'))
<div id="successToast"
    class="fixed bottom-6 right-6
            bg-emerald-600 text-white
            px-6 py-3 rounded-xl shadow-xl
            flex items-center gap-2
            animate-fade-in">
    ✔ {{ session('success') }}
</div>

<style>
    @keyframes rowFade {
        from {
            opacity: 0;
            transform: translateY(8px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-rowFade {
        animation: rowFade .45s ease forwards;
    }
</style>

<style>
    /* ============================= */
    /* DARK INTEL FILTER INPUT STYLE */
    /* ============================= */

    .filter-input {
        background: linear-gradient(145deg,
                rgba(15, 23, 42, 0.95),
                rgba(2, 6, 23, 0.95));

        border: 1px solid rgba(51, 65, 85, 0.8);
        color: #e2e8f0;

        border-radius: 12px;
        padding: 10px 14px;
        font-size: 0.875rem;

        transition: all .25s ease;
    }

    .filter-input::placeholder {
        color: #64748b;
    }

    .filter-input:focus {
        outline: none;
        border-color: rgba(59, 130, 246, 0.7);
        box-shadow:
            0 0 0 2px rgba(59, 130, 246, .25),
            0 0 20px rgba(59, 130, 246, .15);
    }

    /* Fix Date Icon Color */
    .filter-input[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(1);
        opacity: .7;
    }

    /* Custom Select Arrow */
    select.filter-input {
        appearance: none;
        background-image:
            linear-gradient(45deg, transparent 50%, #64748b 50%),
            linear-gradient(135deg, #64748b 50%, transparent 50%);
        background-position:
            calc(100% - 18px) calc(50% - 3px),
            calc(100% - 12px) calc(50% - 3px);
        background-size: 6px 6px, 6px 6px;
        background-repeat: no-repeat;
    }

    /* Buttons */
    .btn-primary {
        padding: .55rem 1rem;
        background: #2563eb;
        border-radius: .75rem;
        font-size: .875rem;
        color: white;
        transition: all .2s ease;
    }

    .btn-primary:hover {
        background: #3b82f6;
        box-shadow: 0 0 15px rgba(59, 130, 246, .4);
    }

    .btn-secondary {
        padding: .55rem 1rem;
        background: #334155;
        border-radius: .75rem;
        font-size: .875rem;
        color: white;
    }

    .btn-accent {
        padding: .55rem 1rem;
        background: #6366f1;
        border-radius: .75rem;
        font-size: .875rem;
        color: white;
        box-shadow: 0 0 15px rgba(99, 102, 241, .4);
    }

    /* Filter Chips */
    .filter-chip {
        padding: .35rem .75rem;
        background: rgba(99, 102, 241, .15);
        border: 1px solid rgba(99, 102, 241, .35);
        border-radius: 999px;
        font-size: .75rem;
        color: #c7d2fe;
    }
</style>

<script>
    setTimeout(() => {
        const toast = document.getElementById('successToast');
        if (toast) {
            toast.style.transition = "opacity 0.5s";
            toast.style.opacity = "0";
            setTimeout(() => toast.remove(), 500);
        }
    }, 2500);
</script>
@endif

<script>
    const modal = document.getElementById('deleteModal');
    const backdrop = document.getElementById('modalBackdrop');
    const content = document.getElementById('modalContent');
    const form = document.getElementById('deleteForm');

    const confirmBtn = document.getElementById('confirmDeleteBtn');
    const deleteText = document.getElementById('deleteBtnText');
    const spinner = document.getElementById('deleteSpinner');

    let modalOpen = false;

    function openDeleteModal(id) {
        if (modalOpen) return;

        form.action = "{{ url('sigint/sre') }}/" + id;
        modal.classList.remove('hidden');

        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            content.classList.remove('opacity-0', 'scale-95');
        }, 10);

        modalOpen = true;

        // Auto-focus cancel button
        setTimeout(() => {
            content.querySelector('button').focus();
        }, 200);
    }

    function closeDeleteModal() {
        backdrop.classList.add('opacity-0');
        content.classList.add('opacity-0', 'scale-95');

        setTimeout(() => {
            modal.classList.add('hidden');
            modalOpen = false;
        }, 300);
    }

    // ESC close
    document.addEventListener('keydown', function(e) {
        if (e.key === "Escape" && modalOpen) {
            closeDeleteModal();
        }
    });

    // Outside click
    backdrop.addEventListener('click', function() {
        closeDeleteModal();
    });

    // Loading state
    form.addEventListener('submit', function() {
        confirmBtn.disabled = true;
        deleteText.classList.add('hidden');
        spinner.classList.remove('hidden');
        confirmBtn.classList.add('opacity-80');
    });
</script>



<script>
    document.getElementById('sreImportBtn')?.addEventListener('click', function() {

        const fileInput = document.getElementById('sreImportFile');

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
            text: 'This will insert new SRE records.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#16a34a',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, import it'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('sreImportForm').submit();
            }
        });

    });
</script>

<script>
    const sreImportForm = document.getElementById('sreImportForm');
    const sreImportBtn = document.getElementById('sreImportBtn');
    const sreImportText = document.getElementById('sreImportText');
    const sreImportSpinner = document.getElementById('sreImportSpinner');

    sreImportForm.addEventListener('submit', function() {
        sreImportBtn.disabled = true;
        sreImportText.classList.add('hidden');
        sreImportSpinner.classList.remove('hidden');
        sreImportBtn.classList.add('opacity-80');
    });
</script>

<script>
    const sreFilterBar = document.getElementById('sreFilterBar');

    window.addEventListener('scroll', () => {

        if (window.scrollY > 60) {

            sreFilterBar.classList.add(
                'shadow-2xl',
                'shadow-black/60'
            );

            sreFilterBar.style.paddingTop = "10px";
            sreFilterBar.style.paddingBottom = "10px";
            sreFilterBar.style.background = "rgba(2,6,23,0.95)";

        } else {

            sreFilterBar.classList.remove(
                'shadow-2xl',
                'shadow-black/60'
            );

            sreFilterBar.style.paddingTop = "16px";
            sreFilterBar.style.paddingBottom = "16px";
            sreFilterBar.style.background = "rgba(2,6,23,0.8)";
        }
    });
</script>

<script>
    document.addEventListener('keydown', function(e) {
        if (e.key === "/" && document.activeElement.tagName !== "INPUT") {
            e.preventDefault();
            document.querySelector('input[name="search"]')?.focus();
        }
    });
</script>

<script>
    const searchInput = document.querySelector('input[name="search"]');
    const threatSelect = document.querySelector('select[name="threat"]');
    const dateInput = document.querySelector('input[name="date"]');

    if (searchInput?.value || threatSelect?.value || dateInput?.value) {
        document.getElementById('sreFilterBar')
            .classList.add('filter-active');
    }
</script>

<script>
    const sreFilterBar = document.getElementById('sreFilterBar');

    let lastScrollY = 0;

    window.addEventListener('scroll', () => {
        const current = window.scrollY;

        if (current > 60) {

            sreFilterBar.style.transform = "scale(0.98)";
            sreFilterBar.style.paddingTop = "8px";
            sreFilterBar.style.paddingBottom = "8px";

            sreFilterBar.style.background = "rgba(2,6,23,0.97)";
            sreFilterBar.style.backdropFilter = "blur(22px)";

            sreFilterBar.style.boxShadow =
                "0 10px 40px rgba(0,0,0,.6), 0 0 25px rgba(59,130,246,.08)";

            sreFilterBar.style.borderColor = "rgba(59,130,246,.3)";

        } else {

            sreFilterBar.style.transform = "scale(1)";
            sreFilterBar.style.paddingTop = "16px";
            sreFilterBar.style.paddingBottom = "16px";

            sreFilterBar.style.background = "rgba(2,6,23,0.92)";
            sreFilterBar.style.backdropFilter = "blur(18px)";

            sreFilterBar.style.boxShadow =
                "0 6px 20px rgba(0,0,0,.4)";

            sreFilterBar.style.borderColor = "rgba(51,65,85,.6)";
        }

        lastScrollY = current;
    });
</script>


<style>
    /* ===== FORCE DARK INPUTS ===== */

    #sreFilterBar input,
    #sreFilterBar select {
        background-color: #0f172a !important;
        /* slate-900 */
        color: #e2e8f0 !important;
        border: 1px solid #334155 !important;
        border-radius: 12px !important;
        padding: 10px 14px !important;
        font-size: 0.875rem !important;
        transition: all .25s ease;
    }

    /* Remove white autofill */
    #sreFilterBar input:-webkit-autofill {
        -webkit-box-shadow: 0 0 0 1000px #0f172a inset !important;
        -webkit-text-fill-color: #e2e8f0 !important;
    }

    /* Placeholder color */
    #sreFilterBar input::placeholder {
        color: #64748b !important;
    }

    /* Focus glow */
    #sreFilterBar input:focus,
    #sreFilterBar select:focus {
        outline: none !important;
        border-color: #3b82f6 !important;
        box-shadow:
            0 0 0 2px rgba(59, 130, 246, .25),
            0 0 18px rgba(59, 130, 246, .15) !important;
    }

    /* Date icon fix */
    #sreFilterBar input[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(1);
        opacity: .7;
    }

    /* Remove default select white arrow background */
    #sreFilterBar select {
        appearance: none;
        background-image:
            linear-gradient(45deg, transparent 50%, #64748b 50%),
            linear-gradient(135deg, #64748b 50%, transparent 50%);
        background-position:
            calc(100% - 18px) calc(50% - 3px),
            calc(100% - 12px) calc(50% - 3px);
        background-size: 6px 6px, 6px 6px;
        background-repeat: no-repeat;
    }

    /* Filter container background */
    #sreFilterBar {
        background: rgba(2, 6, 23, 0.92) !important;
        backdrop-filter: blur(18px);
        border: 1px solid rgba(51, 65, 85, .6);
    }
</style>