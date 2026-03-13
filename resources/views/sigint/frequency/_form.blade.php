<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- Frequency --}}
    <div>
        <label class="block font-semibold text-slate-300 mb-1">
            Frequency (MHz) <span class="text-red-500">*</span>
        </label>
        <input type="text" name="frequency"
            class="w-full rounded-lg p-2
                      bg-slate-800 text-slate-100
                      border border-slate-700
                      focus:border-indigo-500 focus:ring-indigo-500
                      placeholder-slate-400
                      @error('frequency') border-red-500 @enderror"
            value="{{ old('frequency', $frequency->frequency ?? '') }}"
            required>
        @error('frequency')
        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Date & Time --}}
    <div>
        <label class="block font-semibold text-slate-300 mb-1">
            Date & Time (e.g. 102400 December 2025) <span class="text-red-500">*</span>
        </label>
        <input type="text" name="datetime_code"
            class="w-full rounded-lg p-2
                      bg-slate-800 text-slate-100
                      border border-slate-700
                      focus:border-indigo-500 focus:ring-indigo-500
                      placeholder-slate-400
                      @error('datetime_code') border-red-500 @enderror"
            value="{{ old('datetime_code', $frequency->datetime_code ?? '') }}"
            required>
        @error('datetime_code')
        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- SITE LOCATION --}}
    <div>
        <label class="text-sm text-slate-400">Site Location</label>
        <input
            type="text"
            name="site_location"
            value="{{ old('site_location', $frequency->site_location ?? '') }}"
            class="w-full rounded-lg bg-slate-800 border border-slate-700 text-white px-3 py-2 focus:ring focus:ring-indigo-500"
            placeholder="e.g. Catbalogan City">
    </div>

    {{-- Conversation --}}
    <div>
        <label class="block text-sm text-slate-300 mb-1">
            Conversation
        </label>

        <textarea
            name="conversation"
            rows="6"
            class="w-full rounded-lg bg-slate-800 border border-slate-700
               text-slate-100 text-sm p-3 focus:ring-2 focus:ring-indigo-500
               focus:border-indigo-500 resize-none"
            placeholder="Enter intercepted conversation or notes here...">{{ old('conversation', $frequency->conversation ?? '') }}</textarea>
    </div>


    {{-- Clarity --}}
    <div>
        <label class="block font-semibold text-slate-300 mb-1">Clarity</label>
        <input type="text" name="clarity"
            class="w-full rounded-lg p-2
                      bg-slate-800 text-slate-100
                      border border-slate-700
                      focus:border-indigo-500 focus:ring-indigo-500
                      placeholder-slate-400
                      @error('clarity') border-red-500 @enderror"
            value="{{ old('clarity', $frequency->clarity ?? '') }}">
        @error('clarity')
        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- LOB --}}
    <div>
        <label class="block font-semibold text-slate-300 mb-1">Line of Bearing (LOB)</label>
        <input type="text" name="lob"
            class="w-full rounded-lg p-2
                      bg-slate-800 text-slate-100
                      border border-slate-700
                      focus:border-indigo-500 focus:ring-indigo-500
                      placeholder-slate-400
                      @error('lob') border-red-500 @enderror"
            value="{{ old('lob', $frequency->lob ?? '') }}">
        @error('lob')
        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Barangay --}}
    <div>
        <label class="block font-semibold text-slate-300 mb-1">Barangay</label>
        <input type="text" name="barangay"
            class="w-full rounded-lg p-2
                      bg-slate-800 text-slate-100
                      border border-slate-700
                      focus:border-indigo-500 focus:ring-indigo-500
                      placeholder-slate-400
                      @error('barangay') border-red-500 @enderror"
            value="{{ old('barangay', $frequency->barangay ?? '') }}">
        @error('barangay')
        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Municipality --}}
    <div>
        <label class="block font-semibold text-slate-300 mb-1">Municipality</label>
        <input type="text" name="municipality"
            class="w-full rounded-lg p-2
                      bg-slate-800 text-slate-100
                      border border-slate-700
                      focus:border-indigo-500 focus:ring-indigo-500
                      placeholder-slate-400
                      @error('municipality') border-red-500 @enderror"
            value="{{ old('municipality', $frequency->municipality ?? '') }}">
        @error('municipality')
        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Province --}}
    <div class="md:col-span-2">
        <label class="block font-semibold text-slate-300 mb-1">Province</label>
        <input type="text" name="province"
            class="w-full rounded-lg p-2
                      bg-slate-800 text-slate-100
                      border border-slate-700
                      focus:border-indigo-500 focus:ring-indigo-500
                      placeholder-slate-400
                      @error('province') border-red-500 @enderror"
            value="{{ old('province', $frequency->province ?? '') }}">
        @error('province')
        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

</div>