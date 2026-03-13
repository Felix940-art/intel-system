<x-guest-layout>
    <div class="relative min-h-screen flex items-center justify-center bg-[#020617] text-slate-200 overflow-hidden px-4">

        <!-- Tactical Grid Background -->
        <div class="absolute inset-0 bg-[linear-gradient(rgba(59,130,246,0.04)_1px,transparent_1px),linear-gradient(90deg,rgba(59,130,246,0.04)_1px,transparent_1px)] bg-[size:40px_40px]"></div>

        <!-- Animated Radar Sweep -->
        <div class="absolute insert-0 pointer-events-none overflow-hidden">
            <div class="radar-sweep"></div>
        </div>

        <!-- Soft Radial Glow -->
        <div class="absolute inset-0 bg-gradient-to-br from-blue-900/20 via-transparent to-cyan-900/20"></div>

        <!-- Classification Badge -->
        <div class="absolute top-6 right-6 text-xs tracking-widest
                    bg-red-600/20 border border-red-600/40
                    text-red-400 px-3 py-1 rounded-md">
            RESTRICTED ACCESS
        </div>

        <!-- Login Panel -->
        <div class="relative w-full max-w-md">

            <div class="relative overflow-hidden">

                <!-- Scan Line -->
                <div class="scan-line"></div>

                <!-- Login Card -->
                <div id="loginCard"
                    class="relative bg-[#020617]/95 backdrop-blur-xl
            border border-cyan-900/40
            rounded-2xl
            shadow-[0_0_40px_rgba(0,255,255,0.08)]
            p-8 space-y-6
            transition-all duration-300">

                    <!-- Top Classification Strip -->
                    <div class="absolute top-0 left-0 right-0 h-1
                bg-gradient-to-r from-red-600 via-yellow-500 to-red-600
                opacity-80 rounded-t-2xl"></div>

                    <!-- Logo -->
                    <div class="flex justify-center">
                        <x-application-logo class="h-10 w-auto text-cyan-400 opacity-90" />
                    </div>

                    <!-- Header -->
                    <div class="text-center space-y-1">
                        <p class="text-xs tracking-[0.25em] text-cyan-400 uppercase">
                            Secure Intelligence Node
                        </p>

                        <h1 class="text-2xl font-semibold text-white tracking-wide">
                            Command Authentication Portal
                        </h1>

                        <p class="text-xs text-slate-500">
                            Encrypted Access • Clearance Required
                        </p>

                        <div class="text-[11px] text-slate-600 mt-1 tracking-widest">
                            SESSION :: {{ now()->format('d.m.Y H:i:s') }}
                        </div>
                    </div>

                    <!-- Login Form -->
                    <form id="loginForm"
                        method="POST"
                        action="{{ route('login') }}"
                        class="space-y-5">
                        @csrf

                        @if ($errors->any())
                        <script>
                            window.loginFailed = true;
                        </script>
                        @endif

                        <!-- Email -->
                        <div class="space-y-1">
                            <label class="text-xs text-slate-400 tracking-widest uppercase">
                                Email Identifier
                            </label>

                            <input type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required autofocus
                                class="command-input w-full"
                                placeholder="operator@agency.gov">
                        </div>

                        <!-- Password -->
                        <div class="space-y-1">
                            <label class="text-xs text-slate-400 tracking-widest uppercase">
                                Access Key
                            </label>

                            <input type="password"
                                name="password"
                                required
                                class="command-input w-full"
                                placeholder="••••••••">
                        </div>

                        <!-- Clearance Dropdown -->
                        <div class="space-y-1">
                            <label class="text-xs text-slate-400 tracking-widest uppercase">
                                Clearance Level
                            </label>

                            <select name="clearance"
                                class="command-input w-full">
                                <option>Level I - Restricted</option>
                                <option>Level II - Confidential</option>
                                <option>Level III - Secret</option>
                                <option>Level IV - Top Secret</option>
                            </select>
                        </div>

                        <!-- Submit -->
                        <button type="submit"
                            id="loginButton"
                            class="command-button w-full">

                            <span id="loginText">Authorize Access</span>

                            <svg id="loginSpinner"
                                class="hidden animate-spin h-5 w-5"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8v4l3-3-3-3v4a12 12 0 00-12 12h4z" />
                            </svg>
                        </button>
                    </form>

                    <!-- Footer -->
                    <div class="text-center text-[11px] text-slate-600 pt-4 border-t border-slate-800 tracking-widest">
                        © {{ date('Y') }} TICO Command System • Military Grade Encryption
                    </div>
                </div>

            </div>
        </div>

        <!-- Styles -->
        <style>
            .intel-input {
                width: 100%;
                background: #0f172a;
                border: 1px solid #334155;
                color: #e2e8f0;
                padding: .75rem 1rem;
                border-radius: .75rem;
                font-size: .875rem;
                transition: all .25s ease;
            }

            .intel-input:focus {
                outline: none;
                border-color: #3b82f6;
                box-shadow:
                    0 0 0 2px rgba(59, 130, 246, .25),
                    0 0 20px rgba(59, 130, 246, .15);
            }

            .intel-btn {
                width: 100%;
                background: linear-gradient(90deg, #1d4ed8, #2563eb);
                padding: .75rem;
                border-radius: .75rem;
                font-weight: 600;
                font-size: .875rem;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: .5rem;
                transition: all .2s ease;
            }

            .intel-btn:hover {
                box-shadow: 0 0 20px rgba(59, 130, 246, .35);
            }

            .intel-btn:active {
                transform: scale(.98);
            }
        </style>

        <style>
            .scan-line {
                position: absolute;
                top: -100%;
                left: 0;
                width: 100%;
                height: 120%;
                background: linear-gradient(to bottom,
                        transparent 0%,
                        rgba(59, 130, 246, 0.08) 45%,
                        rgba(59, 130, 246, 0.25) 50%,
                        rgba(59, 130, 246, 0.08) 55%,
                        transparent 100%);
                animation: scanSweep 2.5s ease-out forwards;
                pointer-events: none;
            }

            @keyframes scanSweep {
                0% {
                    top: -120%;
                }

                100% {
                    top: 120%;
                }
            }
        </style>

        <style>
            .biometric-pulse {
                position: relative;
            }

            .biometric-pulse::before {
                content: "";
                position: absolute;
                inset: 0;
                border-radius: 0.75rem;
                background: radial-gradient(circle at center,
                        rgba(59, 130, 246, 0.35),
                        transparent 70%);
                opacity: 0;
                animation: biometricPulse 3s infinite ease-in-out;
            }

            @keyframes biometricPulse {
                0% {
                    opacity: 0;
                    transform: scale(0.95);
                }

                40% {
                    opacity: 0.35;
                    transform: scale(1.02);
                }

                70% {
                    opacity: 0.15;
                    transform: scale(1.01);
                }

                100% {
                    opacity: 0;
                    transform: scale(0.95);
                }
            }
        </style>

        <style>
            select option {
                background-color: #0f172a;
                color: #e2e8f0;
            }
        </style>

        <style>
            .auth-input {
                width: 100%;
                border-radius: 0.75rem;
                background: rgba(15, 23, 42, 0.9);
                border: 1px solid rgba(59, 130, 246, 0.2);
                padding: 0.6rem 1rem;
                color: #e2e8f0;
                transition: all 0.3s ease;
            }

            .auth-input:focus {
                outline: none;
                border-color: #06b6d4;
                box-shadow: 0 0 0 2px rgba(6, 182, 212, 0.3);
            }

            .auth-button {
                background: linear-gradient(90deg, #2563eb, #06b6d4);
                border-radius: 0.75rem;
                padding: 0.6rem;
                font-weight: 600;
                color: white;
                transition: all 0.3s ease;
            }

            .auth-button:hover {
                box-shadow: 0 0 20px rgba(6, 182, 212, 0.5);
            }
        </style>

        <script>
            function startAuthScan() {

                const overlay = document.getElementById('authScanOverlay');
                const scanText = document.getElementById('scanText');

                overlay.classList.remove('opacity-0', 'pointer-events-none');

                const steps = [
                    "Encrypting Credentials...",
                    "Validating Identity Signature...",
                    "Checking Clearance Level...",
                    "Establishing Secure Channel...",
                    "Clearance Verified."
                ];

                let index = 0;

                const interval = setInterval(() => {

                    scanText.innerText = steps[index];
                    index++;

                    if (index >= steps.length) {
                        clearInterval(interval);

                        setTimeout(() => {
                            overlay.classList.add('opacity-0');
                            overlay.classList.add('pointer-events-none');
                            goToPhase2();
                        }, 600);
                    }

                }, 700);
            }
        </script>

        <style>
            .scan-line {
                position: absolute;
                top: -100%;
                left: 0;
                width: 100%;
                height: 4px;
                background: linear-gradient(to bottom,
                        transparent,
                        rgba(6, 182, 212, 0.6),
                        transparent);
                animation: scanMove 3s linear infinite;
            }

            @keyframes scanMove {
                0% {
                    top: -10%;
                }

                100% {
                    top: 110%;
                }
            }
        </style>

        <style>
            /* Tactical Input */
            .command-input {
                background: #0f172a;
                border: 1px solid rgba(0, 255, 255, 0.15);
                border-radius: 0.75rem;
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
                color: #e2e8f0;
                transition: all .25s ease;
            }

            .command-input:focus {
                outline: none;
                border-color: #06b6d4;
                box-shadow: 0 0 15px rgba(6, 182, 212, .4);
                background: #0b1220;
            }

            /* Tactical Button */
            .command-button {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: .5rem;
                padding: .8rem 1rem;
                border-radius: .9rem;
                font-weight: 600;
                background: linear-gradient(90deg, #0891b2, #2563eb);
                color: white;
                box-shadow: 0 0 20px rgba(37, 99, 235, .3);
                transition: all .25s ease;
            }

            .command-button:hover {
                transform: translateY(-2px);
                box-shadow: 0 0 25px rgba(6, 182, 212, .6);
            }

            /* Radar Sweep */
            .radar-sweep {
                position: absolute;
                width: 200%;
                height: 2px;
                background: linear-gradient(90deg, transparent, rgba(0, 255, 255, .4), transparent);
                animation: sweep 5s linear infinite;
            }

            @keyframes sweep {
                0% {
                    top: -10%;
                }

                100% {
                    top: 110%;
                }
            }

            /* FAILED LOGIN SHAKE */
            @keyframes tacticalShake {

                0%,
                100% {
                    transform: translateX(0);
                }

                20% {
                    transform: translateX(-6px);
                }

                40% {
                    transform: translateX(6px);
                }

                60% {
                    transform: translateX(-4px);
                }

                80% {
                    transform: translateX(4px);
                }
            }

            .login-shake {
                animation: tacticalShake 0.4s ease;
            }

            /* RED FLASH */
            @keyframes redFlash {
                0% {
                    box-shadow: 0 0 0 rgba(255, 0, 0, 0);
                }

                50% {
                    box-shadow: 0 0 40px rgba(255, 0, 0, 0.6);
                }

                100% {
                    box-shadow: 0 0 0 rgba(255, 0, 0, 0);
                }
            }

            .login-fail {
                border-color: rgba(255, 0, 0, 0.6) !important;
                animation: redFlash 0.6s ease;
            }

            /* ACCESS GRANTED */
            @keyframes accessGranted {
                0% {
                    box-shadow: 0 0 0 rgba(0, 255, 120, 0);
                }

                50% {
                    box-shadow: 0 0 50px rgba(0, 255, 120, 0.8);
                }

                100% {
                    box-shadow: 0 0 0 rgba(0, 255, 120, 0);
                }
            }

            .login-success {
                border-color: rgba(0, 255, 120, 0.7) !important;
                animation: accessGranted 0.8s ease;
            }
        </style>

        <script>
            const loginForm = document.getElementById('loginForm');
            const loginButton = document.getElementById('loginButton');
            const loginText = document.getElementById('loginText');
            const loginSpinner = document.getElementById('loginSpinner');
            const loginCard = document.getElementById('loginCard');

            /* FAILED LOGIN EFFECT */
            if (window.loginFailed) {
                loginCard.classList.add('login-shake', 'login-fail');

                setTimeout(() => {
                    loginCard.classList.remove('login-shake', 'login-fail');
                }, 800);
            }

            /* SUBMIT BEHAVIOR */
            loginForm.addEventListener('submit', function() {

                loginButton.disabled = true;
                loginText.textContent = "Verifying Clearance...";
                loginSpinner.classList.remove('hidden');
                loginButton.classList.add('opacity-80');

                // ACCESS GRANTED effect (visual before redirect)
                setTimeout(() => {
                    loginCard.classList.add('login-success');
                    loginText.textContent = "ACCESS GRANTED";

                    document.body.style.background = "#001a12";
                }, 600);
            });
        </script>


</x-guest-layout>