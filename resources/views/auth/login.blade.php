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

            <!-- 1. Background Image -->
            <img src="{{ asset('images/security-bg.jpg') }}"
                class="absolute inset-0 w-full h-full object-cover opacity-40">

            <!-- 2. DARK BASE (IMPORTANT) -->
            <div class="absolute inset-0 bg-[#020617]"></div>

            <!-- 3. GRADIENT OVERLAY (YOUR CURRENT ONE, BUT KEEP IT HERE) -->
            <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-black/40 to-transparent"></div>

            <!-- 4. OPTIONAL: GRID EFFECT -->
            <div class="absolute inset-0 grid-overlay"></div>

            <!-- 4.1 OPTIONAL: SCANNING SYSTEM LINE -->
            <div class="absolute inset-0 pointer-events-none">
                <div class="scan-line"></div>
            </div>

            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-[500px] h-[500px]
bg-cyan-500/10 blur-3xl"></div>

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

        </div>

        <!-- RIGHT PANEL (LOGIN) -->
        <div class="w-full md:w-1/3 flex">

            <div id="loginCard"
                class="w-full bg-[#020617]/90 backdrop-blur-xl
            border border-cyan-900/30 rounded-2xl p-8 space-y-6
            shadow-[0_0_30px_rgba(0,255,255,0.05)]
            transition-all duration-300">

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
                    <button id="loginButton" class="btn-clean w-full relative overflow-hidden">
                        <span id="loginText">Authorize Access</span>
                        <span id="loginLoader" class="hidden absolute inset-0 flex items-center justify-center">
                            <span class="loader"></span>
                        </span>
                    </button>

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
        .input-clean {
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
            background: linear-gradient(90deg, #0891b2, #2563eb);
            padding: 0.8rem;
            border-radius: 0.9rem;
            font-weight: 600;
            transition: all 0.25s ease;
        }

        .btn-clean:hover {
            transform: translateY(-1px);
            box-shadow: 0 0 18px rgba(6, 182, 212, 0.35);
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
            box-shadow: 0 0 10px #06b6d4;
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
            text.textContent = "AUTHENTICATING...";
            loader.classList.remove('hidden');

            setTimeout(() => {
                text.textContent = "VERIFYING CLEARANCE...";
            }, 500);

        });

        /* FAILED LOGIN */
        if (window.loginFailed) {
            loginCard.classList.add('animate-pulse', 'border-red-500');
        }
    </script>

</body>

</html>