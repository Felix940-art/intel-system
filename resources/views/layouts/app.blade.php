<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-slate-950 text-slate-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'My System') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ================================
           SIDEBAR BASE
        ================================= */
        #sidebar {
            width: 250px;
            transition: width 0.3s ease;
        }

        #sidebar.sidebar-collapsed {
            width: 80px;
        }

        /* ================================
           SIDEBAR ITEM
        ================================= */
        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 8px;
            transition: all 0.2s ease;
            white-space: nowrap;
            position: relative;
        }

        .sidebar-item:hover {
            background: rgba(59, 130, 246, 0.15);
        }

        /* ICON */
        .icon {
            width: 26px;
            min-width: 26px;
            height: 26px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 18px;
        }

        /* TEXT */
        .label {
            transition: opacity 0.2s ease;
        }

        /* ================================
           COLLAPSED STATE
        ================================= */

        /* HIDE TEXT CLEANLY */
        #sidebar.sidebar-collapsed .label {
            display: none;
        }

        /* CENTER ICONS */
        #sidebar.sidebar-collapsed .sidebar-item {
            justify-content: center;
            padding-left: 10px 0;
            gap: 0;
        }

        /* HIDE SUBMENU */
        #sidebar.sidebar-collapsed .sidebar-sub {
            display: none !important;
        }

        /* TOOLTIP */
        #sidebar.sidebar-collapsed .sidebar-item:hover::after {
            content: attr(data-label);
            position: absolute;
            left: 70px;
            background: #1e293b;
            color: white;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            white-space: nowrap;
        }
    </style>
</head>

@stack('scripts')

<body class="bg-slate-950 text-slate-100">

    <div class="flex min-h-screen relative">

        <!-- SIDEBAR -->
        <aside id="sidebar"
            class="fixed lg:relative z-50
               bg-slate-900 text-slate-100
               min-h-screen py-6 border-r border-slate-800 flex flex-col
               transition-all duration-300 ease-in-out
               w-64 -translate-x-full lg:translate-x-0">

            <!-- BACKDROP -->
            <div id="sidebarBackdrop"
                onclick="toggleMobileSidebar()"
                class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden">
            </div>

            <!-- HEADER -->
            <div class="flex flex-col items-center mb-8 relative">

                <button onclick="toggleSidebar()"
                    class="absolute right-3 top-0 text-slate-400 hover:text-white text-lg">
                    ☰
                </button>

                <div class="text-3xl mb-2">👤</div>

                <div class="font-semibold text-center label">
                    {{ auth()->user()->name }}
                </div>

                <div class="text-xs text-slate-400 uppercase mt-1 label">
                    {{ auth()->user()->role }}
                </div>
            </div>

            <!-- NAV -->
            <nav class="space-y-2 px-2 flex-1">

                <a href="{{ route('dashboard') }}"
                    class="sidebar-item text-sm pl-6"
                    data-label="Dashboard">
                    <span class="icon">🏠</span>
                    <span class="label">Dashboard</span>
                </a>

                <!-- SIGINT -->
                <div x-data="{ open: {{ request()->is('sigint*') ? 'true' : 'false' }} }">

                    <button
                        @click="open = !open"
                        class="sidebar-item w-full text-sm pl-6"
                        data-label="SIGINT">

                        <span class="icon">💻</span>
                        <span class="label flex-1 text-left">SIGINT</span>
                        <span class="label" :class="open ? 'rotate-90' : ''">▶</span>
                    </button>

                    <div
                        x-show="open"
                        x-transition
                        class="mt-1 space-y-2 text-slate-400 sidebar-sub"
                        style="display: none;">

                        <a href="{{ route('sigint.frequency.index') }}"
                            class="sidebar-item text-sm pl-6"
                            data-label="Radio Frequency">
                            <span class="icon">📡</span>
                            <span class="label">Radio Frequency</span>
                        </a>

                        <a href="{{ route('sigint.sre.index') }}"
                            class="sidebar-item text-sm pl-6"
                            data-label="SRE System">
                            <span class="icon">🧭</span>
                            <span class="label">SRE System</span>
                        </a>
                    </div>
                </div>

                <a href="{{ route('geoint.index') }}"
                    class="sidebar-item text-sm pl-6"
                    data-label="GEOINT">
                    <span class="icon">✈️</span>
                    <span class="label">GEOINT</span>
                </a>

                <a href="{{ route('dforensics.index') }}"
                    class="sidebar-item text-sm pl-6"
                    data-label="D-FORENSICS">
                    <span class="icon">🧬</span>
                    <span class="label">D-FORENSICS</span>
                </a>

            </nav>

            <!-- FOOTER -->
            <div class="pt-4 border-t border-slate-700">

                <a href="{{ route('profile.edit') }}"
                    class="sidebar-item text-sm pl-6"
                    data-label="Profile">
                    <span class="icon">👤</span>
                    <span class="label">Profile</span>
                </a>

                <a href="{{ route('profile.edit') }}#password"
                    class="sidebar-item text-sm pl-6"
                    data-label="Change Password">
                    <span class="icon">🔑</span>
                    <span class="label">Change Password</span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="sidebar-item text-sm pl-6"
                        data-label="Logout">
                        <span class="icon">🚪</span>
                        <span class="label">Logout</span>
                    </button>
                </form>

            </div>

        </aside>

        <!-- MAIN -->
        <main id="mainContent"
            class="flex-1 p-6 lg:p-8 bg-slate-950 transition-all duration-300 ml-[250px]">
            {{ $slot }}

            <button onclick="toggleMobileSidebar()"
                class="lg:hidden mb-4 px-3 py-2 bg-slate-800 rounded text-white">
                ☰ Menu
            </button>
        </main>

    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const main = document.getElementById('mainContent');

            sidebar.classList.toggle('sidebar-collapsed');

            if (sidebar.classList.contains('sidebar-collapsed')) {
                main.style.marginLeft = "5px";
                localStorage.setItem('sidebar', 'collapsed');
            } else {
                main.style.marginLeft = "5px";
                localStorage.setItem('sidebar', 'expanded');
            }
        }

        window.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.getElementById('sidebar');
            const main = document.getElementById('mainContent');

            if (localStorage.getItem('sidebar') === 'collapsed') {
                sidebar.classList.add('sidebar-collapsed');
                main.style.marginLeft = "5px";
            } else {
                main.style.marginLeft = "5px";
            }
        });
    </script>

</body>

</html>