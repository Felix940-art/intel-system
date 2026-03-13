<section>
    <header class="mb-6">
        <h2 class="text-lg font-semibold text-slate-100">
            Update Password
        </h2>

        <p class="mt-1 text-sm text-slate-400">
            Use a strong, unique password to keep your account secure.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        {{-- CURRENT PASSWORD --}}
        <div>
            <x-input-label
                for="update_password_current_password"
                :value="__('Current Password')"
                class="text-slate-300" />

            <x-text-input
                id="update_password_current_password"
                name="current_password"
                type="password"
                class="mt-1 block w-full
                       bg-slate-800 border-slate-700 text-slate-100
                       focus:border-blue-500 focus:ring-blue-500"
                autocomplete="current-password" />

            <x-input-error
                :messages="$errors->updatePassword->get('current_password')"
                class="mt-2 text-red-400" />
        </div>

        {{-- NEW PASSWORD --}}
        <div>
            <x-input-label
                for="update_password_password"
                :value="__('New Password')"
                class="text-slate-300" />

            <x-text-input
                id="update_password_password"
                name="password"
                type="password"
                class="mt-1 block w-full
                       bg-slate-800 border-slate-700 text-slate-100
                       focus:border-blue-500 focus:ring-blue-500"
                autocomplete="new-password" />

            <x-input-error
                :messages="$errors->updatePassword->get('password')"
                class="mt-2 text-red-400" />
        </div>

        {{-- CONFIRM PASSWORD --}}
        <div>
            <x-input-label
                for="update_password_password_confirmation"
                :value="__('Confirm Password')"
                class="text-slate-300" />

            <x-text-input
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                class="mt-1 block w-full
                       bg-slate-800 border-slate-700 text-slate-100
                       focus:border-blue-500 focus:ring-blue-500"
                autocomplete="new-password" />

            <x-input-error
                :messages="$errors->updatePassword->get('password_confirmation')"
                class="mt-2 text-red-400" />
        </div>

        {{-- ACTIONS --}}
        <div class="flex items-center gap-4">
            <button
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700
                       text-white rounded-md transition">
                Update Password
            </button>

            @if (session('status') === 'password-updated')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-green-400">
                Password updated successfully.
            </p>
            @endif
        </div>
    </form>
</section>