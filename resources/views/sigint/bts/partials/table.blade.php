<table class="w-full">

    <thead class="bg-slate-800/90 backdrop-blur-xl text-slate-400 uppercase text-sm">

        <tr>

            <th class="px-6 py-5 text-left text-xs uppercase tracking-wide text-slate-400">Name</th>
            <th class="px-6 py-5 text-left text-xs uppercase tracking-wide text-slate-400">Location (MGRS)</th>
            <th class="px-6 py-5 text-left text-xs uppercase tracking-wide text-slate-400">Network</th>
            <th class="px-6 py-5 text-left text-xs uppercase tracking-wide text-slate-400">Mode</th>
            <th class="px-6 py-5 text-left text-xs uppercase tracking-wide text-slate-400">LAC</th>
            <th class="px-6 py-5 text-left text-xs uppercase tracking-wide text-slate-400">CID</th>
            <th class="px-6 py-5 text-left text-xs uppercase tracking-wide text-slate-400">Neighbor CID</th>
            <th class="px-6 py-5 text-left text-xs uppercase tracking-wide text-slate-400">Barangay</th>
            <th class="px-6 py-5 text-left text-xs uppercase tracking-wide text-slate-400">Municipality</th>
            <th class="px-6 py-5 text-left text-xs uppercase tracking-wide text-slate-400">Province</th>
            <th class="px-6 py-5 text-center text-xs uppercase tracking-wide text-slate-400">Action</th>

        </tr>

    </thead>

    <tbody>

        @if($btsRecords->count())

        @foreach($btsRecords as $bts)
        <tr class="border-t border-slate-700">

            <td class="px-6 py-4">
                {{ $bts->name }}
            </td>

            <td class="px-6 py-4">
                {{ $bts->mgrs_location }}
            </td>

            <td class="px-6 py-4">
                {{ $bts->network }}
            </td>

            <td class="px-6 py-4">
                {{ $bts->network_mode }}
            </td>

            <td class="px-6 py-4">
                {{ $bts->lac }}
            </td>

            <td class="px-6 py-4">
                {{ $bts->cid }}
            </td>

            <td class="px-6 py-4">
                {{ $bts->neighbor_cid }}
            </td>

            <td class="px-6 py-4">
                {{ $bts->barangay }}
            </td>

            <td class="px-6 py-4">
                {{ $bts->municipality }}
            </td>

            <td class="px-6 py-4">
                {{ $bts->province }}
            </td>

            <!-- THIS MUST BE THE LAST TD -->
            <td class="px-6 py-4">

                <div class="flex items-center gap-2">

                    <!-- EDIT BUTTON -->
                    <a href="{{ route('sigint.bts.edit', $bts->id) }}"
                        class="
            inline-flex items-center gap-2
            px-4 py-2 rounded-xl
            bg-blue-500/20
            border border-blue-500/40
            text-blue-300
            hover:bg-blue-500
            hover:text-white
            hover:shadow-lg hover:shadow-blue-500/30
            transition-all duration-300">

                        📝 Edit

                    </a>


                    <!-- DELETE BUTTON -->
                    <form
                        id="delete-form-{{ $bts->id }}"
                        action="{{ route('sigint.bts.destroy', $bts->id) }}"
                        method="POST">

                        @csrf
                        @method('DELETE')

                        <button
                            type="button"
                            onclick="openDeleteModal({{ $bts->id }}, '{{ addslashes($bts->name) }}')"
                            class="
        inline-flex items-center gap-2
        px-4 py-2 rounded-xl
        bg-red-500/20
        border border-red-500/40
        text-red-300
        hover:bg-red-500
        hover:text-white
        hover:shadow-lg hover:shadow-red-500/30
        transition-all duration-300">

                            🗑 Delete

                        </button>

                    </form>

                </div>

            </td>

        </tr>
        @endforeach

        @else

        <tr>
            <td colspan="11">

                <div class="h-[180px] flex flex-col items-center justify-center">

                    <div class="text-5xl mb-5 opacity-70">
                        📡
                    </div>

                    <div class="text-2xl mb-4">
                        No BTS stations registered.
                    </div>

                    <p class="mt-4 text-slate-500 text-sm">
                        Use the
                        <span class="text-indigo-400 font-semibold">
                            + Add BTS
                        </span>
                        button above to create your first Base Transceiver Station entry.
                    </p>

                </div>

            </td>
        </tr>

        @endif

    </tbody>

</table>

@if ($btsRecords->hasPages())

<div class="mt-6 flex justify-center">

    {{ $btsRecords->links() }}

</div>

@endif