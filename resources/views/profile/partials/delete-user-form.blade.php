<section class="space-y-6 border border-red-500/30 rounded-lg p-6 bg-red-950/20">
    <header>
        <h2 class="text-lg font-semibold text-red-400 flex items-center gap-2">
            ⚠️ Delete Account
        </h2>

        <p class="mt-1 text-sm text-slate-400">
            This action is <span class="text-red-400 font-semibold">permanent</span>.
            Once your account is deleted, all associated data will be irreversibly removed.
        </p>
    </header>

    {{-- TRIGGER BUTTON --}}
    <button
        x-data
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-4 py-2 bg-red-600 hover:bg-red-700
               text-white rounded-md transition">
        Delete Account
    </button>

    {{-- CONFIRMATION MODAL --}}
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 space-y-6">
            @csrf
            @method('delete')

            <header>
                <h2 class="text-lg font-semibold text-red-500">
                    Confirm Account Deletion
                </h2>

                <p class="mt-2 text-sm text-slate-400">
                    This will permanently delete your account and all related data.
                    Please enter your password to confirm.
                </p>
            </header>

            {{-- PASSWORD CONFIRMATION --}}
            <div>
                <x-input-label
                    for="password"
                    value="{{ __('Password') }}"
                    class="text-slate-300" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full
                           bg-slate-800 border-red-500/40 text-slate-100
                           focus:border-red-500 focus:ring-red-500"
                    placeholder="Enter your password" />

                <x-input-error
                    :messages="$errors->userDeletion->get('password')"
                    class="mt-2 text-red-400" />
            </div>

            {{-- ACTIONS --}}
            <div class="flex justify-end gap-3">
                <button
                    type="button"
                    x-on:click="$dispatch('close')"
                    class="px-4 py-2 bg-slate-700 hover:bg-slate-600
                           text-white rounded-md transition">
                    Cancel
                </button>

                <button
                    class="px-4 py-2 bg-red-600 hover:bg-red-700
                           text-white rounded-md transition">
                    Permanently Delete
                </button>
            </div>
        </form>
    </x-modal>
</section>