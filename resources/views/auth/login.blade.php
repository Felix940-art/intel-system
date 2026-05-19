<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#020617]">

    <div class="noise"></div>

    <div class="w-full h-screen flex">

        <!-- LEFT PANEL (IMMERSIVE VISUAL) -->
        <div class="hidden md:flex w-2/3 relative overflow-hidden">

            <div class="absolute right-0 top-0 h-full w-px
bg-gradient-to-b from-transparent
via-cyan-500/30
to-transparent">
            </div>

            <div class="absolute top-10 right-10 flex items-center gap-3 z-10">

                <div class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></div>

                <div class="text-[11px] tracking-widest text-green-400">
                    SYSTEM ONLINE
                </div>

            </div>

            <div class="absolute top-10 left-10 z-10">
                <div class="text-[10px] tracking-[0.4em] text-red-500 uppercase">
                    Restricted Intelligence Network
                </div>
            </div>

            <!-- 1. Background Image -->
            <img src="{{ asset('images/security-bg.jpg') }}"
                class="absolute inset-0 w-full h-full object-cover opacity-40">

            <!-- 2. DARK BASE (IMPORTANT) -->
            <div class="absolute inset-0 bg-[#020617]"></div>

            <!-- 3. GRADIENT OVERLAY (YOUR CURRENT ONE, BUT KEEP IT HERE) -->
            <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-black/40 to-transparent"></div>

            <!-- 4. OPTIONAL: GRID EFFECT -->
            <div class="absolute inset-0 grid-overlay"></div>

            <div class="absolute inset-0 opacity-[0.03]
bg-[url('/images/world-map-grid.png')]
bg-cover bg-center mix-blend-screen">
            </div>

            <div class="absolute inset-0 opacity-[0.03]"
                style="background-image:
radial-gradient(circle at 20% 20%, cyan 0%, transparent 25%),
radial-gradient(circle at 80% 70%, blue 0%, transparent 25%);">
            </div>

            <!-- 4.1 OPTIONAL: SCANNING SYSTEM LINE -->
            <div class="absolute inset-0 pointer-events-none">
                <div class="scan-line"></div>
            </div>

            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-[500px] h-[500px]
bg-cyan-500/10 blur-3xl"></div>

            <div class="absolute top-6 left-6 w-20 h-20 border-l border-t border-cyan-500/20"></div>

            <div class="absolute bottom-6 left-6 w-20 h-20 border-l border-b border-cyan-500/20"></div>

            <div class="absolute top-6 right-6 w-20 h-20 border-r border-t border-cyan-500/20"></div>

            <!-- 5. CONTENT -->
            <div class="relative z-10 flex flex-col justify-center px-20 space-y-6">

                <h1 class="text-5xl font-bold tracking-wide leading-tight text-white drop-shadow-[0_0_20px_rgba(0,0,0,0.8)]">
                    YOU ARE<br>SECURE.
                </h1>

                <p class="text-slate-300 text-lg leading-relaxed max-w-lg">
                    Advanced Intelligence Monitoring System with encrypted command access,
                    mission logging, and real-time surveillance integration.
                </p>

                <div class="text-xs text-cyan-400 tracking-widest">
                    Powered by TICO Command System • Secure Access Layer
                </div>

            </div>

            <div class="absolute inset-0 pointer-events-none">
                <span class="node" style="top:30%; left:20%"></span>
                <span class="node" style="top:60%; left:40%"></span>
                <span class="node" style="top:50%; left:70%"></span>
            </div>

            <div id="systemLogs"
                class="absolute bottom-10 left-20 text-[11px]
    font-mono text-cyan-400/45 space-y-1 z-10">
            </div>

            <div class="absolute bottom-10 right-20
text-[10px] font-mono text-cyan-400/35
space-y-1 text-right z-10">

                <div>ISR NODE :: CENTRAL-04</div>
                <div>SATCOM :: ACTIVE</div>
                <div>UPLINK :: STABLE</div>
                <div>AUTH CH :: ENCRYPTED</div>

            </div>

            <div class="absolute inset-0
bg-[radial-gradient(circle_at_left,rgba(6,182,212,0.12),transparent_45%)]">
            </div>
        </div>

        <!-- RIGHT PANEL (LOGIN) -->
        <div class="w-full md:w-1/3 flex items-center justify-center p-6 md:p-10">

            <div id="loginCard"
                class="relative w-full max-w-lg bg-[#020617]/90 backdrop-blur-xl
            border border-cyan-900/30 rounded-2xl p-8 space-y-6
            shadow-inner
shadow-[0_0_40px_rgba(0,255,255,0.08),0_0_120px_rgba(0,0,0,0.8)]
            transition-all duration-300">

                <div id="deniedFlash"
                    class="absolute inset-0 bg-red-500/5 opacity-0 pointer-events-none rounded-2xl transition-opacity duration-300">
                </div>

                <div class="absolute inset-0 rounded-2xl
bg-gradient-to-br from-cyan-500/5 via-transparent to-blue-500/5
pointer-events-none">
                </div>

                <div class="text-[10px] tracking-widest text-cyan-400 flex justify-between mb-4">
                    <span>NODE STATUS: ACTIVE</span>
                    <span>ENCRYPTION: AES-256</span>
                </div>

                <div class="absolute inset-0 border border-cyan-500/10 rounded-xl pointer-events-none"></div>

                <!-- TOP BAR -->
                <div class="h-1 w-16 bg-gradient-to-r from-cyan-500 to-blue-600 rounded-full"></div>

                <!-- HEADER -->
                <div class="text-center space-y-2">

                    <x-application-logo class="h-10 text-cyan-400 opacity-90" />

                    <p class="text-xs tracking-[0.3em] text-cyan-400 uppercase">
                        Secure Intelligence Node
                    </p>

                    <h1 class="text-xl font-semibold text-white">
                        Authentication Portal
                    </h1>

                    <p class="text-xs text-slate-500">
                        Clearance Required
                    </p>

                </div>

                <!-- FORM -->
                <form id="loginForm" method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    @if ($errors->any())
                    <script>
                        window.loginFailed = true;
                    </script>
                    @endif

                    <!-- EMAIL -->
                    <div class="space-y-1">
                        <label class="text-xs text-slate-400 tracking-widest uppercase">
                            Email Identifier
                        </label>

                        <input type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required autofocus
                            placeholder="operator@agency.gov"
                            class="input-clean w-full">
                    </div>

                    <!-- PASSWORD -->
                    <div class="space-y-1">
                        <label class="text-xs text-slate-400 tracking-widest uppercase">
                            Access Key
                        </label>

                        <input type="password"
                            name="password"
                            required
                            placeholder="••••••••"
                            class="input-clean w-full">
                    </div>

                    @if ($errors->any())
                    <div class="text-red-400 text-xs tracking-wider">
                        ACCESS DENIED — INVALID CREDENTIALS
                    </div>
                    @endif

                    <!-- CLEARANCE -->
                    <div class="space-y-1">
                        <label class="text-xs text-slate-400 tracking-widest uppercase">
                            Clearance Level
                        </label>

                        <select name="clearance" class="input-clean w-full">
                            <option>Level I - Restricted</option>
                            <option>Level II - Confidential</option>
                            <option>Level III - Secret</option>
                            <option>Level IV - Top Secret</option>
                        </select>
                    </div>

                    <!-- SUBMIT -->
                    <button id="loginButton" data-state="idle" class="btn-clean w-full relative overflow-hidden">
                        <span id="loginText">Authorize Access</span>
                        <span id="loginLoader" class="hidden absolute inset-0 flex items-center justify-center">
                            <span class="loader"></span>
                        </span>
                    </button>

                    <div id="systemStatus"
                        class="text-[11px] tracking-widest text-cyan-400 h-4 transition-all duration-300 animate-pulse">
                    </div>

                </form>

                <!-- FOOTER -->
                <div class="text-center text-[11px] text-slate-600 pt-4 border-t border-slate-800 tracking-widest">
                    © {{ date('Y') }} TICO Command System • Secure Access Layer
                </div>

            </div>

        </div>

    </div>

    <!-- STYLES -->
    <style>
        #systemLogs div {
            transition: all 0.4s ease;
        }

        .input-clean {
            box-shadow:
                inset 0 0 12px rgba(0, 0, 0, 0.35);
            background: rgba(15, 23, 42, 0.7);
            border: 1px solid rgba(59, 130, 246, 0.15);
            border-radius: 0.75rem;
            padding: 0.7rem 1rem;
            font-size: 0.875rem;
            color: #e2e8f0;
            transition: all 0.25s ease;
        }

        .input-clean:focus {
            outline: none;
            border-color: #06b6d4;
            box-shadow: 0 0 12px rgba(6, 182, 212, 0.25);
            background: #0b1220;
        }

        .btn-clean {
            color: #ffffff;
            background: linear-gradient(90deg, #0891b2, #2563eb);
            padding: 0.8rem;
            border-radius: 0.9rem;
            font-weight: 600;
            transition: all 0.25s ease;
        }

        .btn-clean span {
            color: #ffffff;
        }

        .btn-clean:hover {
            transform: translateY(-1px) scale(1.01);
            box-shadow:
                0 0 18px rgba(6, 182, 212, 0.35),
                0 0 40px rgba(37, 99, 235, 0.15);
        }

        .loader {
            width: 18px;
            height: 18px;
            border: 2px solid transparent;
            border-top: 2px solid #06b6d4;
            border-right: 2px solid #2563eb;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .grid-overlay {
            background-image:
                linear-gradient(rgba(0, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
        }

        .noise {
            position: fixed;
            inset: 0;
            background-image: url('https://www.transparenttextures.com/patterns/noise.png');
            opacity: 0.02;
            pointer-events: none;
        }

        .node {
            position: absolute;
            width: 6px;
            height: 6px;
            background: #06b6d4;
            border-radius: 9999px;

            box-shadow:
                0 0 10px #06b6d4,
                0 0 25px rgba(6, 182, 212, 0.5),
                0 0 40px rgba(6, 182, 212, 0.25);

            animation: pulse 2s infinite ease-in-out;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 0.3;
                transform: scale(1);
            }

            50% {
                opacity: 1;
                transform: scale(1.8);
            }
        }

        .sweep-line {
            position: absolute;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(0, 255, 255, 0.4), transparent);
            animation: sweepMove 6s linear infinite;
        }

        .scan-line {
            position: absolute;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg,
                    transparent,
                    rgba(0, 255, 255, 0.5),
                    transparent);
            animation: scanMove 5s linear infinite;
            opacity: 0.6;
        }

        @keyframes scanMove {
            0% {
                top: -5%;
            }

            100% {
                top: 105%;
            }
        }

        @keyframes sweepMove {
            0% {
                top: -10%;
            }

            100% {
                top: 110%;
            }
        }

        @keyframes tacticalShake {

            0%,
            100% {
                transform: translateX(0);
            }

            20% {
                transform: translateX(-4px);
            }

            40% {
                transform: translateX(4px);
            }

            60% {
                transform: translateX(-3px);
            }

            80% {
                transform: translateX(3px);
            }
        }

        .access-denied {
            animation: tacticalShake 0.35s ease;
            border-color: rgba(239, 68, 68, 0.5) !important;

            box-shadow:
                inset 0 0 20px rgba(255, 0, 0, 0.04),
                0 0 25px rgba(239, 68, 68, 0.18),
                0 0 120px rgba(0, 0, 0, 0.8);
        }

        .grid-overlay {
            animation: gridShift 20s linear infinite;
        }

        @keyframes gridShift {
            0% {
                transform: translateY(0px);
            }

            100% {
                transform: translateY(50px);
            }
        }
    </style>

    <!-- SCRIPT -->
    <script>
        const loginForm = document.getElementById('loginForm');
        const loginButton = document.getElementById('loginButton');
        const loginText = document.getElementById('loginText');
        const loginCard = document.getElementById('loginCard');
        const form = document.querySelector('form');
        const btn = document.getElementById('loginButton');
        const text = document.getElementById('loginText');
        const loader = document.getElementById('loginLoader');

        form.addEventListener('submit', function() {

            btn.disabled = true;

            btn.dataset.state = "authenticating";

            loader.classList.remove('hidden');

            const status = document.getElementById('systemStatus');

            text.textContent = "AUTHENTICATING...";
            status.textContent = "Establishing encrypted tunnel...";

            setTimeout(() => {
                text.textContent = "VERIFYING CLEARANCE...";
                status.textContent = "Cross-checking operator credentials...";
            }, 700);

            setTimeout(() => {
                status.textContent = "Synchronizing intelligence node...";
                loginCard.style.boxShadow =
                    "inset 0 0 25px rgba(6,182,212,0.05), \
                        0 0 50px rgba(6,182,212,0.18), \
                        0 0 120px rgba(0,0,0,0.8)";
            }, 1400);

            setTimeout(() => {

                loginCard.style.boxShadow =
                    "inset 0 0 20px rgba(255,255,255,0.02), \
                        0 0 40px rgba(0,255,255,0.08), \
                        0 0 120px rgba(0,0,0,0.8)";

            }, 2200);

        });

        /* FAILED LOGIN */
        if (window.loginFailed) {

            loginCard.classList.add('access-denied');

            const deniedFlash = document.getElementById('deniedFlash');

            deniedFlash.classList.remove('opacity-0');
            deniedFlash.classList.add('opacity-100');

            setTimeout(() => {
                deniedFlash.classList.remove('opacity-100');
                deniedFlash.classList.add('opacity-0');

                loginCard.classList.remove('access-denied');
            }, 300);

        }

        const logs = [
            "[NODE] Secure uplink established",
            "[SYS] AES-256 encryption active",
            "[SATCOM] Signal synchronized",
            "[INTEL] Monitoring ISR channels",
            "[AUTH] Clearance verification ready",
            "[GRID] Tactical overlay active",
            "[COMMS] Secure relay online"
        ];

        const logContainer = document.getElementById('systemLogs');

        function addLog() {

            const line = document.createElement('div');

            line.textContent =
                logs[Math.floor(Math.random() * logs.length)];

            line.classList.add('opacity-0');

            logContainer.prepend(line);

            setTimeout(() => {
                line.classList.remove('opacity-0');
            }, 50);

            if (logContainer.children.length > 6) {
                logContainer.removeChild(logContainer.lastChild);
            }

        }

        setInterval(addLog, 2200);

        addLog();
    </script>

</body>

</html>