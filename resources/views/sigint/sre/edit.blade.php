<x-app-layout>
    <div class="max-w-4xl mx-auto py-10">

        <!-- HEADER -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-100 flex items-center gap-2">
                ✏️ Edit SRE Record
            </h1>
            <p class="text-sm text-slate-400">
                Update monitoring event details
            </p>
        </div>

        <form method="POST"
            action="{{ route('sigint.sre.update', $event->id) }}"
            class="space-y-8">

            @csrf
            @method('PUT')

            <!-- SELECTOR + DATETIME -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label class="label-dark">Selector</label>
                    <input type="text"
                        name="selector"
                        value="{{ old('selector', $event->selector->selector_value ?? '') }}"
                        class="input-dark"
                        required>
                </div>

                <div>
                    <label class="label-dark">Date & Time</label>
                    <input type="datetime-local"
                        name="observed_at"
                        value="{{ old('observed_at', optional($event->observed_at)->format('Y-m-d\TH:i')) }}"
                        class="input-dark"
                        required>
                </div>

            </div>

            <!-- IMEI / IMSI -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label class="label-dark">IMEI</label>
                    <input type="text"
                        name="imei"
                        value="{{ old('imei', $event->imei) }}"
                        class="input-dark">
                </div>

                <div>
                    <label class="label-dark">IMSI</label>
                    <input type="text"
                        name="imsi"
                        value="{{ old('imsi', $event->imsi) }}"
                        class="input-dark">
                </div>

            </div>

            <!-- LAC / CID -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label class="label-dark">LAC</label>
                    <input type="text"
                        name="lac"
                        value="{{ old('lac', $event->lac) }}"
                        class="input-dark">
                </div>

                <div>
                    <label class="label-dark">CID</label>
                    <input type="text"
                        name="cid"
                        value="{{ old('cid', $event->cid) }}"
                        class="input-dark">
                </div>

            </div>

            <!-- THREAT -->
            <div>
                <label class="label-dark">Threat Confronted</label>

                <select name="threat_group"
                    class="input-dark @error('threat_group') ring-2 ring-red-500 @enderror">

                    <option value="">— Select Threat Group —</option>

                    @php
                    $threats = [
                    'SRC',
                    'SRGU',
                    'SRMA',
                    'SROC',
                    'SRMA EMPORIUM',
                    'SRMA ARCTIC',
                    'SRMA BROWSER',
                    'SRMA SESAME',
                    'SRMA LEVOX',
                    'COMTECH',
                    'EV MRGU',
                    'FUNCTIONAL',
                    'UNKNOWN',
                    ];
                    @endphp

                    @foreach($threats as $threat)
                    <option value="{{ $threat }}"
                        {{ old('threat_group', $event->selector->threat_group) === $threat ? 'selected' : '' }}>
                        {{ $threat }}
                    </option>
                    @endforeach

                </select>

                @error('threat_group')
                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- CODE NAME -->
            <div>
                <label class="label-dark">Code Name</label>
                <input type="text"
                    name="code_name"
                    value="{{ old('code_name', $event->selector->code_name ?? '') }}"
                    placeholder="Enter code name (optional)"
                    class="input-dark @error('code_name') ring-2 ring-red-500 @enderror">

                @error('code_name')
                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>


            <!-- ACTIONS -->
            <div class="flex justify-end gap-4 pt-6 border-t border-slate-800">

                <a href="{{ route('sigint.sre.index') }}"
                    class="px-6 py-2 rounded-lg bg-slate-700 text-slate-200 hover:bg-slate-600">
                    Cancel
                </a>

                <button type="submit"
                    class="px-6 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-500">
                    Update Record
                </button>

            </div>

        </form>
    </div>
</x-app-layout>