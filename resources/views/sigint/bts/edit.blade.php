<x-app-layout>

    <div class="max-w-6xl mx-auto">

        <h1 class="text-3xl font-bold text-white mb-6">
            📡 Edit BTS Entry
        </h1>

        <form action="{{ route('sigint.bts.update', $bts->id) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Name --}}
                <div>
                    <label class="text-slate-300">Name</label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $bts->name) }}"
                        class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white">
                </div>


                {{-- MGRS --}}
                <div>
                    <label class="text-slate-300">
                        Location (MGRS)
                    </label>

                    <input
                        type="text"
                        name="mgrs_location"
                        value="{{ old('mgrs_location', $bts->mgrs_location) }}"
                        class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white">
                </div>


                {{-- Network --}}
                <div>
                    <label class="text-slate-300">Network</label>

                    <select name="network"
                        class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white">

                        @foreach (['GLOBE','TM','GOMO','SMART','TNT','SUN'] as $network)
                        <option value="{{ $network }}"
                            {{ old('network', $bts->network) == $network ? 'selected' : '' }}>
                            {{ $network }}
                        </option>
                        @endforeach

                    </select>
                </div>


                {{-- Network Mode --}}
                <div>
                    <label class="text-slate-300">Network Mode</label>

                    <select name="network_mode"
                        class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white">

                        @foreach (['2G','3G','4G LTE','5G'] as $mode)
                        <option value="{{ $mode }}"
                            {{ old('network_mode', $bts->network_mode) == $mode ? 'selected' : '' }}>
                            {{ $mode }}
                        </option>
                        @endforeach

                    </select>
                </div>


                {{-- LAC --}}
                <div>
                    <label class="text-slate-300">LAC</label>

                    <input type="text"
                        name="lac"
                        value="{{ old('lac', $bts->lac) }}"
                        class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white">
                </div>


                {{-- CID --}}
                <div>
                    <label class="text-slate-300">CID</label>

                    <input type="text"
                        name="cid"
                        value="{{ old('cid', $bts->cid) }}"
                        class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white">
                </div>


                {{-- Neighbor CID --}}
                <div>
                    <label class="text-slate-300">Neighbor CID</label>

                    <input type="text"
                        name="neighbor_cid"
                        value="{{ old('neighbor_cid', $bts->neighbor_cid) }}"
                        class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white">
                </div>


                {{-- Barangay --}}
                <div>
                    <label class="text-slate-300">Barangay</label>

                    <input type="text"
                        name="barangay"
                        value="{{ old('barangay', $bts->barangay) }}"
                        class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white">
                </div>


                {{-- Municipality --}}
                <div>
                    <label class="text-slate-300">Municipality</label>

                    <input type="text"
                        name="municipality"
                        value="{{ old('municipality', $bts->municipality) }}"
                        class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white">
                </div>


                {{-- Province --}}
                <div>
                    <label class="text-slate-300">Province</label>

                    <input type="text"
                        name="province"
                        value="{{ old('province', $bts->province) }}"
                        class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white">
                </div>

            </div>


            <button
                type="submit"
                class="mt-8 px-8 py-4 rounded-2xl
                       bg-indigo-600 hover:bg-indigo-500
                       text-white font-bold">

                💾 Update BTS

            </button>

        </form>

    </div>

</x-app-layout>