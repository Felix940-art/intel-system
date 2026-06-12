<x-app-layout>

    {{-- HEADER --}}

    <div class="flex flex-wrap items-center justify-between gap-4">

        {{-- LEFT --}}
        <div>
            <h1 class="text-2xl font-bold text-slate-100 flex items-center gap-3">
                📡 BTS Database
            </h1>

            <p class="text-sm text-slate-400 mt-1">
                Base Transceiver Station Registry
            </p>

            <div class="flex items-center gap-1 mt-1 text-xs text-slate-400">
                <span class="relative px-3 py-1 rounded-md bg-slate-800 border border-slate-700 text-xs">
                    <span class="absolute -left-1 -top-1 h-2 w-2 bg-emerald-400 rounded-full animate-ping"></span>
                    <span class="absolute -left-1 -top-1 h-2 w-2 bg-emerald-400 rounded-full"></span>
                    Live BTS Database
                </span>

                <span>
                    {{ now()->format('d M Y • H:i') }}
                </span>
            </div>
        </div>

        {{-- RIGHT --}}
        <div class="flex gap-3">

            <a href="{{ route('sigint.bts.export.excel') }}"
                class="px-5 py-3 rounded-2xl
    border border-emerald-500/30
    bg-emerald-500/10
    hover:bg-emerald-500/20
    text-emerald-300 font-semibold">

                📗 Export Excel

            </a>

            <a href="{{ route('sigint.bts.export.pdf') }}"
                class="px-5 py-3 rounded-2xl
    border border-red-500/30
    bg-red-500/10
    hover:bg-red-500/20
    text-red-300 font-semibold">

                📕 Generate PDF

            </a>

            <a href="{{ route('sigint.bts.create') }}"
                class="h-16 px-8 rounded-2xl bg-gradient-to-r from-indigo-500 to-violet-500
    hover:from-indigo-400 hover:to-violet-400
    text-white font-bold shadow-lg shadow-indigo-500/20
    flex items-center">

                ＋ Add BTS

            </a>

        </div>

    </div>

    {{-- BTS INTELLIGENCE STATISTICS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mt-6">


        {{-- 2G --}}
        <div class="
        rounded-3xl p-6
        bg-slate-900/70
        border border-amber-500/30
        shadow-lg shadow-amber-500/10
        hover:scale-105 transition">

            <div class="text-3xl mb-2">
                📡
            </div>

            <p class="text-slate-400 text-sm">
                2G Towers
            </p>

            <h2 class="text-3xl font-bold text-amber-300 mt-2">
                {{ $twoGTowers }}
            </h2>

        </div>


        {{-- 4G LTE --}}
        <div class="
        rounded-3xl p-6
        bg-slate-900/70
        border border-green-500/30
        shadow-lg shadow-green-500/10
        hover:scale-105 transition">

            <div class="text-3xl mb-2">
                📶
            </div>

            <p class="text-slate-400 text-sm">
                4G LTE Towers
            </p>

            <h2 class="text-3xl font-bold text-green-300 mt-2">
                {{ $fourGLTETowers }}
            </h2>

        </div>


        {{-- GLOBE BTS --}}
        <div class="
        rounded-3xl p-6
        bg-slate-900/70
        border border-orange-500/30
        shadow-lg shadow-orange-500/10
        hover:scale-105 transition">

            <div class="text-3xl mb-2">
                🌐
            </div>

            <p class="text-slate-400 text-sm">
                2G Towers Share
            </p>

            <h2 class="text-3xl font-bold text-orange-300 mt-2">
                {{ $twoGShare }}%
            </h2>

        </div>


        {{-- 4G BTS --}}
        <div class="
        rounded-3xl p-6
        bg-slate-900/70
        border border-cyan-500/30
        shadow-lg shadow-cyan-500/10
        hover:scale-105 transition">

            <div class="text-3xl mb-2">
                ⚡
            </div>

            <p class="text-slate-400 text-sm">
                4G LTE Towers Share
            </p>

            <h2 class="text-3xl font-bold text-cyan-300 mt-2">
                {{ $fourGLTEShare }}%
            </h2>

        </div>

    </div>

    {{-- TECHNOLOGY EVOLUTION INTELLIGENCE --}}
    <div class="
    mt-6
    w-full
    rounded-[30px]
    bg-slate-950/60
    border border-slate-700/40
    p-8
    shadow-lg shadow-cyan-500/10
">

        {{-- Header --}}
        <div class="mb-8">

            <h2 class="text-xl font-bold text-white flex items-center gap-3">
                📡 Technology Evolution
            </h2>

            <p class="text-sm text-slate-400 mt-1">
                Telecommunications Infrastructure Assessment
            </p>

        </div>

        <!-- Technology Progress -->
        <div class="mt-8 space-y-6">

            <!-- 2G -->
            <div>
                <div class="flex justify-between mb-2">
                    <span class="text-yellow-400 font-semibold">
                        2G Legacy Network
                    </span>

                    <span class="text-yellow-300">
                        {{ $twoGTowers }} Towers
                    </span>
                </div>

                <div class="w-full bg-slate-800 rounded-full h-3 overflow-hidden">
                    <div
                        class="h-full bg-yellow-400 rounded-full transition-all duration-1000"
                        style="width: {{ $twoGShare }}%">
                    </div>
                </div>

                <p class="text-right text-yellow-300 text-sm mt-1">
                    {{ $twoGShare }}%
                </p>
            </div>


            <!-- 3G -->
            <div>
                <div class="flex justify-between mb-2">
                    <span class="text-purple-400 font-semibold">
                        3G Network
                    </span>

                    <span class="text-purple-300">
                        {{ $threeGTowers }} Towers
                    </span>
                </div>

                <div class="w-full bg-slate-800 rounded-full h-3 overflow-hidden">
                    <div
                        class="h-full bg-purple-500 rounded-full transition-all duration-1000"
                        style="width: {{ $threeGShare }}%">
                    </div>
                </div>

                <p class="text-right text-purple-300 text-sm mt-1">
                    {{ $threeGShare }}%
                </p>
            </div>


            <!-- 4G LTE -->
            <div>
                <div class="flex justify-between mb-2">
                    <span class="text-green-400 font-semibold">
                        4G LTE Broadband
                    </span>

                    <span class="text-green-300">
                        {{ $fourGLTETowers }} Towers
                    </span>
                </div>

                <div class="w-full bg-slate-800 rounded-full h-3 overflow-hidden">
                    <div
                        class="h-full bg-green-500 rounded-full transition-all duration-1000"
                        style="width: {{ $fourGLTEShare }}%">
                    </div>
                </div>

                <p class="text-right text-green-300 text-sm mt-1">
                    {{ $fourGLTEShare }}%
                </p>
            </div>


            <!-- 5G -->
            <div>
                <div class="flex justify-between mb-2">
                    <span class="text-cyan-400 font-semibold">
                        5G Advanced Network
                    </span>

                    <span class="text-cyan-300">
                        {{ $fiveGTowers }} Towers
                    </span>
                </div>

                <div class="w-full bg-slate-800 rounded-full h-3 overflow-hidden">
                    <div
                        class="h-full bg-cyan-500 rounded-full transition-all duration-1000"
                        style="width: {{ $fiveGShare }}%">
                    </div>
                </div>

                <p class="text-right text-cyan-300 text-sm mt-1">
                    {{ $fiveGShare }}%
                </p>
            </div>

        </div>

    </div>

    <!-- NETWORK DISTRIBUTION INTELLIGENCE -->
    <div class="
    mt-6
    rounded-3xl
    bg-slate-900/60
    border border-cyan-500/20
    shadow-xl shadow-cyan-500/10
    p-8">

        <!-- Header -->
        <div class="mb-6">
            <h3 class="text-3xl font-bold text-white">
                📡 Network Distribution
            </h3>

            <p class="text-slate-400">
                Telecommunications Provider Analysis
            </p>
        </div>


        <!-- Chart Container -->
        <div class="h-80">
            <canvas id="networkChart"></canvas>
        </div>

    </div>

    {{-- FILTER PANEL --}}
    <form method="GET"
        action="{{ route('sigint.bts.index') }}">

        <div class="mt-5 p-6 rounded-[30px] bg-slate-950/60 border border-slate-700/40">

            <div class="flex flex-wrap gap-4 items-center">


                {{-- SEARCH --}}
                <div class="flex-1 min-w-[320px]">
                    <input
                        id="searchInput"
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search Name, MGRS, LAC, CID..."
                        class="w-full h-16 px-6 rounded-2xl bg-slate-900/70 border border-slate-700 text-slate-200
                placeholder:text-slate-500
                focus:ring-2 focus:ring-blue-500">
                </div>


                {{-- NETWORK --}}
                <div class="w-44">
                    <select
                        id="networkFilter"
                        name="network"
                        class="w-full h-16 px-6 rounded-2xl bg-slate-900/70 border border-slate-700 text-white">

                        <option value="">All Networks</option>

                        @foreach(['GLOBE','TM','GOMO','SMART','TNT','SUN'] as $network)
                        <option value="{{ $network }}"
                            {{ request('network') == $network ? 'selected' : '' }}>
                            {{ $network }}
                        </option>
                        @endforeach

                    </select>
                </div>


                {{-- MODE --}}
                <div class="w-44">
                    <select
                        id="modeFilter"
                        name="mode"
                        class="w-full h-16 px-6 rounded-2xl bg-slate-900/70 border border-slate-700 text-white">

                        <option value="">All Modes</option>

                        @foreach(['2G','3G','4G LTE','5G'] as $mode)
                        <option value="{{ $mode }}"
                            {{ request('mode') == $mode ? 'selected' : '' }}>
                            {{ $mode }}
                        </option>
                        @endforeach

                    </select>
                </div>


                {{-- PROVINCE --}}
                <div class="w-44">
                    <input
                        id="provinceFilter"
                        type="text"
                        name="province"
                        value="{{ request('province') }}"
                        placeholder="Province"
                        class="w-full h-16 px-6 rounded-2xl bg-slate-900/70 border border-slate-700 text-white">
                </div>


                {{-- RESULT COUNT --}}
                <div class="px-4 py-2 rounded-xl
            bg-indigo-500/20
            text-indigo-300
            text-sm
            border border-indigo-500/20">

                    Showing
                    {{ $btsRecords->firstItem() ?? 0 }}
                    –
                    {{ $btsRecords->lastItem() ?? 0 }}
                    of
                    {{ $btsRecords->total() }}
                    BTS Records

                </div>


                {{-- BUTTONS --}}
                <button
                    type="submit"
                    class="h-16 px-8 rounded-2xl
            bg-blue-600 hover:bg-blue-500
            text-white font-bold
            shadow-lg shadow-blue-500/20">

                    🔎 Filter

                </button>


                <a href="{{ route('sigint.bts.index') }}"
                    class="h-16 px-8 rounded-2xl
            bg-slate-700 hover:bg-slate-600
            text-white font-bold
            flex items-center justify-center">

                    ✖ Clear

                </a>


            </div>

        </div>

    </form>

    {{-- TABLE --}}
    <div id="btsTableContainer">

        @include('sigint.bts.partials.table')

    </div>

    {{-- DELETE CONFIRMATION MODAL --}}

    <!-- DELETE CONFIRMATION MODAL -->
    <div id="deleteModal"
        onclick="closeDeleteModal()"
        class="hidden fixed inset-0 bg-black/0 backdrop-blur-none
            items-center justify-center transition-all duration-300">

        <div
            id="modalCard"
            onclick="event.stopPropagation()"
            class="
    w-full
    max-w-md
    p-8
    rounded-3xl
    bg-slate-900
    border border-red-500/30
    shadow-2xl shadow-red-500/20
    scale-90
    opacity-0
    transition-all duration-300
">

            <!-- Icon -->
            <div class="text-5xl text-center mb-4">
                ⚠️
            </div>

            <!-- Title -->
            <h2
                id="deleteTitle"
                class="text-2xl font-bold text-white text-center">
                Delete BTS Entry?
            </h2>

            <!-- Message -->
            <p class="text-slate-400 text-center mt-3 leading-relaxed">
                This action cannot be undone.
                The BTS record will be permanently removed.
            </p>

            <!-- Buttons -->
            <div class="flex justify-center gap-4 mt-8">

                <!-- Cancel -->
                <button
                    onclick="closeDeleteModal()"
                    class="
                px-6 py-3 rounded-xl
                bg-slate-700 text-white font-semibold
                hover:bg-slate-600 transition">

                    Cancel

                </button>


                <!-- Confirm Delete -->
                <button
                    onclick="confirmDelete()"
                    class="
                px-6 py-3 rounded-xl
                bg-red-600 text-white font-semibold
                hover:bg-red-500
                shadow-lg shadow-red-500/30
                transition">

                    Delete

                </button>

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @php
    $networkLabels = $networkStats->keys()->toArray();
    $networkValues = $networkStats->values()->toArray();
    @endphp

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const ctx = document
                .getElementById("networkChart")
                .getContext("2d");


            const networkChart = new Chart(ctx, {

                type: "doughnut",

                data: {

                    labels: @json($networkLabels),

                    datasets: [{
                        data: @json($networkValues),

                        backgroundColor: [
                            "#00E5FF", // SMART
                            "#00FF88", // TM
                            "#FFD700", // GLOBE
                            "#FF4D6D", // TNT
                            "#9B5DE5", // GOMO
                            "#FFFFFF" // SUN
                        ],

                        borderWidth: 2,
                        borderColor: "#020617"

                    }]
                },


                options: {

                    responsive: true,

                    maintainAspectRatio: false,


                    plugins: {

                        legend: {

                            position: "right",

                            labels: {

                                color: "#E2E8F0",

                                padding: 25,

                                font: {

                                    size: 14
                                }
                            }
                        },


                        tooltip: {

                            backgroundColor: "#0F172A",

                            titleColor: "#ffffff",

                            bodyColor: "#94A3B8"

                        }

                    },


                    cutout: "65%"

                }

            });

        });
    </script>

    <script>
        let deleteId = null;

        // Open Modal
        function openDeleteModal(id, name) {

            deleteId = id;

            document.getElementById("deleteTitle").innerText =
                "Delete " + name + "?";


            const modal = document.getElementById("deleteModal");
            const card = document.getElementById("modalCard");


            modal.classList.remove("hidden");
            modal.classList.add("flex");

            setTimeout(() => {

                modal.classList.remove("bg-black/0", "backdrop-blur-none");
                modal.classList.add("bg-black/70", "backdrop-blur-sm");


                card.classList.remove("scale-90", "opacity-0");
                card.classList.add("scale-100", "opacity-100");

            }, 10);
        }


        // Close Modal
        function closeDeleteModal() {

            const modal = document.getElementById("deleteModal");
            const card = document.getElementById("modalCard");


            modal.classList.add("bg-black/0", "backdrop-blur-none");

            card.classList.add("scale-90", "opacity-0");


            setTimeout(() => {

                modal.classList.add("hidden");
                modal.classList.remove("flex");

            }, 300);

        }


        // Delete Record
        function confirmDelete() {

            document
                .getElementById('delete-form-' + deleteId)
                .submit();

        }

        document.addEventListener('keydown', function(event) {

            if (event.key === "Escape") {
                closeDeleteModal();
            }

        });
    </script>

    <script>
        setTimeout(() => {

            const toast = document.getElementById("toast");

            if (toast) {

                toast.style.transition = "0.5s";
                toast.style.opacity = "0";

                setTimeout(() => {
                    toast.remove();
                }, 500);
            }

        }, 3000);
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const search = document.getElementById("searchInput");
            const network = document.getElementById("networkFilter");
            const mode = document.getElementById("modeFilter");
            const province = document.getElementById("provinceFilter");


            function loadBtsData(url) {

                fetch(url, {
                        headers: {
                            "X-Requested-With": "XMLHttpRequest"
                        }
                    })
                    .then(response => response.text())
                    .then(html => {

                        document.getElementById("btsTableContainer").innerHTML = html;

                    })
                    .catch(error => {

                        console.error("BTS AJAX Error:", error);

                    });
            }


            function liveFilter() {

                let params = new URLSearchParams({
                    search: search.value,
                    network: network.value,
                    mode: mode.value,
                    province: province.value
                });

                loadBtsData(
                    "{{ route('sigint.bts.index') }}?" + params.toString()
                );
            }


            search.addEventListener("keyup", liveFilter);
            network.addEventListener("change", liveFilter);
            mode.addEventListener("change", liveFilter);
            province.addEventListener("change", liveFilter);


            // =====================
            // AJAX PAGINATION
            // =====================
            document.addEventListener("click", function(e) {

                let link = e.target.closest("#pagination-links a");

                if (!link) return;

                e.preventDefault();

                loadBtsData(link.href);

            });

        });
    </script>

</x-app-layout>