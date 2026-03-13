<section>
    <header class="mb-6">
        <h2 class="text-lg font-semibold text-slate-100">
            Profile Information
        </h2>

        <p class="mt-1 text-sm text-slate-400">
            Update your account’s name and email address.
        </p>
    </header>

    {{-- EMAIL VERIFICATION --}}
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        {{-- NAME --}}
        <div>
            <x-input-label
                for="name"
                :value="__('Name')"
                class="text-slate-300" />

            <x-text-input
                id="name"
                name="name"
                type="text"
                class="mt-1 block w-full
                       bg-slate-800 border-slate-700 text-slate-100
                       focus:border-blue-500 focus:ring-blue-500"
                :value="old('name', $user->name)"
                required
                autofocus
                autocomplete="name" />

            <x-input-error class="mt-2 text-red-400" :messages="$errors->get('name')" />
        </div>

        {{-- EMAIL --}}
        <div>
            <x-input-label
                for="email"
                :value="__('Email')"
                class="text-slate-300" />

            <x-text-input
                id="email"
                name="email"
                type="email"
                class="mt-1 block w-full
                       bg-slate-800 border-slate-700 text-slate-100
                       focus:border-blue-500 focus:ring-blue-500"
                :value="old('email', $user->email)"
                required
                autocomplete="username" />

            <x-input-error class="mt-2 text-red-400" :messages="$errors->get('email')" />

            {{-- EMAIL NOT VERIFIED --}}
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="mt-3 p-3 rounded-lg bg-yellow-500/10 border border-yellow-500/30">
                <p class="text-sm text-yellow-300">
                    Your email address is unverified.
                </p>

                <button
                    form="send-verification"
                    class="mt-1 text-sm text-yellow-400 underline hover:text-yellow-300">
                    Click here to re-send the verification email.
                </button>

                @if (session('status') === 'verification-link-sent')
                <p class="mt-2 text-sm text-green-400">
                    A new verification link has been sent.
                </p>
                @endif
            </div>
            @endif
        </div>

        {{-- ACTIONS --}}
        <div class="flex items-center gap-4">
            <button
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700
                       text-white rounded-md transition">
                Save Changes
            </button>

            @if (session('status') === 'profile-updated')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-green-400">
                Saved successfully.
            </p>
            @endif
        </div>
    </form>
</section>