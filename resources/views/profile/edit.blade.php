<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-slate-100">
                👤 Account Settings
            </h2>

            <span class="px-3 py-1 text-xs rounded-full
                {{ auth()->user()->role === 'admin'
                    ? 'bg-red-600/20 text-red-400'
                    : 'bg-blue-600/20 text-blue-400' }}">
                {{ ucfirst(auth()->user()->role) }}
            </span>
        </div>
    </x-slot>

    <div class="py-10 max-w-6xl mx-auto space-y-8">

        {{-- PROFILE INFORMATION --}}
        <section class="bg-slate-900 border border-slate-800 rounded-xl p-6">
            <h3 class="text-lg font-medium text-slate-100 mb-1">
                Profile Information
            </h3>
            <p class="text-sm text-slate-400 mb-6">
                Update your account’s basic information.
            </p>

            @include('profile.partials.update-profile-information-form')
        </section>

        {{-- SECURITY --}}
        <section class="bg-slate-900 border border-slate-800 rounded-xl p-6">
            <h3 class="text-lg font-medium text-slate-100 mb-1">
                Security
            </h3>
            <p class="text-sm text-slate-400 mb-6">
                Change your password regularly to keep your account secure.
            </p>

            @include('profile.partials.update-password-form')
        </section>

        {{-- DANGER ZONE --}}
        <section class="bg-red-950/40 border border-red-800 rounded-xl p-6">
            <h3 class="text-lg font-medium text-red-400 mb-1">
                Danger Zone
            </h3>
            <p class="text-sm text-red-300 mb-6">
                Deleting your account is irreversible.
            </p>

            @include('profile.partials.delete-user-form')
        </section>

    </div>
</x-app-layout>