<x-app-layout>
    <div class="max-w-4xl mx-auto py-10">

        <!-- HEADER -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-100 flex items-center gap-2">
                ➕ SRE Data Entry
            </h1>
            <p class="text-sm text-slate-400">
                Register a selector for monitoring (IMEI, IMSI, MSISDN, etc.)
            </p>
        </div>

        <form method="POST" action="{{ route('sigint.sre.store') }}" class="space-y-8">
            @csrf

            <!-- SELECTOR + DATETIME -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="label-dark">Selector (MSISDN +63)</label>
                    <input
                        type="text"
                        name="selector"
                        value="{{ old('selector') }}"
                        placeholder="+639XXXXXXXXX"
                        class="input-dark @error('selector') ring-2 ring-red-500 @enderror"
                        required>
                    @error('selector')
                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="label-dark">Date & Time</label>
                    <input
                        type="datetime-local"
                        name="observed_at"
                        value="{{ old('observed_at') }}"
                        class="input-dark @error('observed_at') ring-2 ring-red-500 @enderror"
                        required>
                    @error('observed_at')
                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- IMEI / IMSI -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="label-dark">TF_IMEI (Mobile Device)</label>
                    <input
                        type="text"
                        name="imei"
                        value="{{ old('imei') }}"
                        placeholder="15-digit IMEI"
                        class="input-dark @error('imei') ring-2 ring-red-500 @enderror">
                    @error('imei')
                    <p class="mt-1 text-xs text-red-400">IMEI must be exactly 15 digits.</p>
                    @enderror
                </div>

                <div>
                    <label class="label-dark">IMSI (SIM Card)</label>
                    <input
                        type="text"
                        name="imsi"
                        value="{{ old('imsi') }}"
                        placeholder="15-digit IMSI"
                        class="input-dark @error('imsi') ring-2 ring-red-500 @enderror">
                    @error('imsi')
                    <p class="mt-1 text-xs text-red-400">IMSI must be exactly 15 digits.</p>
                    @enderror
                </div>
            </div>

            <!-- LAC / CID -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="label-dark">LAC (Location Area Code)</label>
                    <input
                        type="text"
                        name="lac"
                        value="{{ old('lac') }}"
                        placeholder="enter LAC"
                        class="input-dark @error('lac') ring-2 ring-red-500 @enderror">
                    @error('lac')
                    <p class="mt-1 text-xs text-red-400">LAC must be a valid number.</p>
                    @enderror
                </div>

                <div>
                    <label class="label-dark">CID (Cell ID)</label>
                    <input
                        type="text"
                        name="cid"
                        value="{{ old('cid') }}"
                        placeholder="enter CID"
                        class="input-dark @error('cid') ring-2 ring-red-500 @enderror">
                    @error('cid')
                    <p class="mt-1 text-xs text-red-400">CID must be a valid number.</p>
                    @enderror
                </div>
            </div>

            <!-- BTS LOCATION -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div>
                    <label class="label-dark">BTS Location (Name)</label>
                    <input type="text"
                        name="bts_location"
                        value="{{ old('bts_location') }}"
                        placeholder="e.g. Tacloban Tower A"
                        class="input-dark">
                </div>

                <div>
                    <label class="label-dark">Latitude</label>
                    <input type="number"
                        name="bts_lat"
                        value="{{ old('bts_lat') }}"
                        placeholder="11.2434"
                        class="input-dark"
                        step="0.000001"
                        min="-90"
                        max="90">
                </div>

                <div>
                    <label class="label-dark">Longitude</label>
                    <input type="number"
                        name="bts_lng"
                        value="{{ old('bts_lng') }}"
                        placeholder="125.0045"
                        class="input-dark"
                        step="0.0001"
                        min="-180"
                        max="180">
                </div>

            </div>

            <!-- THREAT CONFRONTED -->
            <div>
                <label class="label-dark">Threat Confronted</label>
                <select name="threat_group" class="input-dark">
                    <option value="">— Select Threat Group —</option>
                    @foreach([
                    'SRC','SRGU','SRMA','SROC','SRMA EMPORIUM','SRMA ARCTIC',
                    'SRMA BROWSER','SRMA SESAME','SRMA LEVOX','COMTECH',
                    'EV MRGU','FUNCTIONAL','UNKNOWN'
                    ] as $group)
                    <option value="{{ $group }}" @selected(old('threat_group')===$group)>
                        {{ $group }}
                    </option>
                    @endforeach
                </select>
                @error('threat_group')
                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- CODE NAME -->
            <div>
                <label class="label-dark">
                    CN (Code Name)
                </label>

                <input
                    type="text"
                    name="code_name"
                    value="{{ old('code_name') }}"
                    placeholder="Enter code name (optional)"
                    class="input-dark @error('code_name') ring-2 ring-red-500 @enderror">

                @error('code_name')
                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>


            <!-- ACTIONS -->
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-800">
                <a href="{{ route('sigint.sre.index') }}"
                    class="px-4 py-2 rounded-lg bg-slate-700 text-slate-200 hover:bg-slate-600">
                    Cancel
                </a>

                <button
                    type="submit"
                    id="saveBtn"
                    class="px-5 py-2 rounded-lg bg-blue-600 text-white font-medium
                           hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-400
                           disabled:opacity-50 disabled:cursor-not-allowed transition">
                    Save Entry
                </button>
            </div>

            <!-- Save button UX -->
            <script>
                document.querySelector('form').addEventListener('submit', () => {
                    const btn = document.getElementById('saveBtn');
                    btn.disabled = true;
                    btn.innerText = 'Saving...';
                });
            </script>
        </form>
    </div>
</x-app-layout>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const msisdn = document.querySelector('[name="selector"]');
        if (!msisdn) return;

        msisdn.addEventListener('input', () => {
            let v = msisdn.value.replace(/\D/g, '');

            if (v.startsWith('09')) v = '63' + v.substring(1);
            if (v.startsWith('9') && v.length === 10) v = '63' + v;
            if (v.startsWith('63')) v = '+' + v;

            msisdn.value = v.substring(0, 13);
        });
    });
</script>
@endpush