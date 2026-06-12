<x-app-layout>

    <div class="max-w-6xl mx-auto">

        <h1 class="text-3xl font-bold text-white mb-6">
            📡 Add BTS Entry
        </h1>

        <form method="POST"
            action="{{ route('sigint.bts.store') }}">

            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label class="text-slate-300">Name</label>

                    <input type="text"
                        name="name"
                        class="w-full mt-2 rounded-xl bg-slate-900 border-slate-700 text-white">
                </div>

                <div>
                    <label class="text-slate-300">
                        Location (MGRS)
                    </label>

                    <input type="text"
                        name="mgrs_location"
                        class="w-full mt-2 rounded-xl bg-slate-900 border-slate-700 text-white">
                </div>

                <div>
                    <label class="text-slate-300">Network</label>

                    <select name="network"
                        class="w-full mt-2 rounded-xl bg-slate-900 border-slate-700 text-white">

                        <option>GLOBE</option>
                        <option>TM</option>
                        <option>GOMO</option>
                        <option>SMART</option>
                        <option>TNT</option>
                        <option>SUN</option>

                    </select>
                </div>

                <div>
                    <label class="text-slate-300">Network Mode</label>

                    <select name="network_mode"
                        class="w-full mt-2 rounded-xl bg-slate-900 border-slate-700 text-white">

                        <option>2G</option>
                        <option>3G</option>
                        <option>4G LTE</option>
                        <option>5G</option>

                    </select>
                </div>

                <div>
                    <label class="text-slate-300">LAC</label>

                    <input type="text"
                        name="lac"
                        class="w-full mt-2 rounded-xl bg-slate-900 border-slate-700 text-white">
                </div>

                <div>
                    <label class="text-slate-300">CID</label>

                    <input type="text"
                        name="cid"
                        class="w-full mt-2 rounded-xl bg-slate-900 border-slate-700 text-white">
                </div>

                <div>
                    <label class="text-slate-300">Neighbor CID</label>

                    <input type="text"
                        name="neighbor_cid"
                        class="w-full mt-2 rounded-xl bg-slate-900 border-slate-700 text-white">
                </div>

                <div>
                    <label class="text-slate-300">Barangay</label>

                    <input type="text"
                        name="barangay"
                        class="w-full mt-2 rounded-xl bg-slate-900 border-slate-700 text-white">
                </div>

                <div>
                    <label class="text-slate-300">Municipality</label>

                    <input type="text"
                        name="municipality"
                        class="w-full mt-2 rounded-xl bg-slate-900 border-slate-700 text-white">
                </div>

                <div>
                    <label class="text-slate-300">Province</label>

                    <input type="text"
                        name="province"
                        class="w-full mt-2 rounded-xl bg-slate-900 border-slate-700 text-white">
                </div>

            </div>

            <button
                class="mt-8 px-8 py-4 rounded-2xl
                   bg-indigo-600 hover:bg-indigo-500
                   text-white font-bold">

                Save BTS

            </button>

        </form>

    </div>

</x-app-layout>