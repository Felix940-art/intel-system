<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-slate-950 text-slate-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'My System') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

@stack('scripts')

<body class="bg-slate-950 text-slate-100">

    <div class="flex min-h-screen">

        <!-- SIDEBAR -->
        <aside class="w-64 bg-slate-900 text-slate-100 min-h-screen py-6 border-r border-slate-800 flex flex-col">

            <!-- USER -->
            <div class="flex flex-col items-center mb-8">
                <div class="text-3xl mb-2">👤</div>
                <div class="font-semibold">{{ auth()->user()->name }}</div>
                <div class="text-xs text-slate-400 uppercase mt-1">
                    {{ auth()->user()->role }}
                </div>
            </div>

            <!-- NAV LINKS -->
            <nav class="space-y-2 px-4 flex-1">

                {{-- DASHBOARD (ALL ROLES) --}}
                <a href="{{ route('dashboard') }}"
                    class="flex items-center px-3 py-2 hover:bg-slate-800 rounded transition">
                    🏠 <span class="ml-3">Dashboard</span>
                </a>

                {{-- SIGINT COLLAPSIBLE --}}
                <div x-data="{ open: {{ request()->is('sigint*') ? 'true' : 'false' }} }">

                    <button
                        @click="open = !open"
                        class="flex items-center w-full px-3 py-2 hover:bg-slate-800 rounded transition">

                        💻
                        <span class="ml-3 flex-1 text-left">SIGINT</span>
                        <span class="transition-transform" :class="open ? 'rotate-90' : ''">▶</span>
                    </button>

                    <div
                        x-show="open"
                        x-transition
                        class="ml-10 mt-1 space-y-2 text-slate-400"
                        style="display: none;">

                        {{-- FREQUENCY DATABASE (ALL ROLES) --}}
                        <a href="{{ route('sigint.frequency.index') }}"
                            class="flex items-center hover:text-white">
                            📡 <span class="ml-2">Radio Frequency</span>
                        </a>

                        {{-- SRE (ALL ROLES) --}}
                        <a href="{{ route('sigint.sre.index') }}"
                            class="flex items-center hover:text-white">
                            🧭 <span class="ml-2">SRE System</span>
                        </a>
                    </div>
                </div>

                {{-- GEOINT (ALL ROLES) --}}
                <a href="{{ route('geoint.index') }}"
                    class="flex items-center px-3 py-2 hover:bg-slate-800 rounded transition">
                    ✈ <span class="ml-3">GEOINT</span>
                </a>

                {{-- D-FORENSICS (ADMIN ONLY placeholder) --}}
                <a href="{{ route('dforensics.index') }}"
                    class="flex items-center px-3 py-2 hover:bg-slate-800 rounded transition">
                    🧬 <span class="ml-3">D-FORENSICS</span>
                </a>


            </nav>

            {{-- ACCOUNT SECTION --}}
            <div class="pt-4 border-t border-slate-700">

                <a href="{{ route('profile.edit') }}"
                    class="flex items-center gap-3 px-4 py-3 text-slate-300 hover:bg-slate-700/50 transition">
                    👤 <span>Profile</span>
                </a>

                <a href="{{ route('profile.edit') }}#password"
                    class="flex items-center gap-3 px-4 py-3 text-slate-300 hover:bg-slate-700/50 transition">
                    🔑 <span>Change Password</span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="w-full flex items-center gap-3 px-4 py-3
                           text-red-400 hover:bg-red-500/10 hover:text-red-300 transition">
                        🚪 <span>Logout</span>
                    </button>
                </form>

            </div>

        </aside>

        <!-- MAIN CONTENT -->
        <main class="flex-1 p-6 lg:p-8 bg-slate-950">
            {{ $slot }}
        </main>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- SUCCESS ALERT --}}
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session("success") }}',
            timer: 2000,
            showConfirmButton: false,
        });
    </script>
    @endif

    {{-- ERROR ALERT --}}
    @if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            html: `{!! implode('<br>', $errors->all()) !!}`,
        });
    </script>
    @endif

    @if(session('ctg_alert') && auth()->user()?->role === 'admin')
    <script>
        Swal.fire({
            icon: 'warning',
            title: '⚠️ CTG WATCHLIST ALERT',
            html: `
        <strong>Critical Frequency Detected</strong><br>
        This entry is marked as <b>CTG</b> and is on the <b>Watchlist</b>.
    `,
            confirmButtonText: 'View Frequency',
            confirmButtonColor: '#dc2626'
        }).then(() => {
            window.location.href = "{{ route('sigint.frequency.index') }}";
        });
    </script>
    @endif

</body>

</html>