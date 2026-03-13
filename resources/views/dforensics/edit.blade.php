<x-app-layout>

    <div class="p-6 max-w-5xl mx-auto">

        <!-- HEADER -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-white">
                Edit Forensic Report
            </h1>

            <p class="text-slate-400 text-sm">
                Update digital evidence extraction details
            </p>
        </div>


        <!-- UPDATE REPORT FORM -->
        <form method="POST"
            action="{{ route('dforensics.update',$report->id) }}"
            enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="bg-slate-900 border border-slate-800 rounded-xl p-6 space-y-6">

                <!-- DATE + LOCATION -->
                <div class="grid md:grid-cols-2 gap-4">

                    <div>
                        <label class="text-sm text-slate-300">
                            Date of Extraction
                        </label>

                        <input type="date"
                            name="extraction_date"
                            value="{{ $report->extraction_date }}"
                            class="mt-1 w-full bg-slate-800 border border-slate-700 rounded-lg p-2 text-white">
                    </div>


                    <div>
                        <label class="text-sm text-slate-300">
                            Location
                        </label>

                        <input type="text"
                            name="location"
                            value="{{ $report->location }}"
                            class="mt-1 w-full bg-slate-800 border border-slate-700 rounded-lg p-2 text-white">
                    </div>

                </div>



                <!-- EQUIPMENT + EXAMINER -->
                <div class="grid md:grid-cols-2 gap-4">

                    <div>
                        <label class="text-sm text-slate-300">
                            Equipment Type
                        </label>

                        <input type="text"
                            name="equipment_type"
                            value="{{ $report->equipment_type }}"
                            class="mt-1 w-full bg-slate-800 border border-slate-700 rounded-lg p-2 text-white">
                    </div>


                    <div>
                        <label class="text-sm text-slate-300">
                            Examiner
                        </label>

                        <input type="text"
                            name="examiner_name"
                            value="{{ $report->examiner_name }}"
                            class="mt-1 w-full bg-slate-800 border border-slate-700 rounded-lg p-2 text-white">
                    </div>

                </div>



                <!-- STATUS -->
                <div>
                    <label class="text-sm text-slate-300">
                        Remarks
                    </label>

                    <select name="remarks"
                        id="remarks"
                        class="mt-1 w-full bg-slate-800 border border-slate-700 rounded-lg p-2 text-white">

                        <option value="Extracted"
                            {{ $report->remarks == 'Extracted' ? 'selected' : '' }}>
                            Extracted
                        </option>

                        <option value="Not Extracted"
                            {{ $report->remarks == 'Not Extracted' ? 'selected' : '' }}>
                            Not Extracted
                        </option>

                    </select>
                </div>



                <!-- REASON -->
                <div id="reasonBox"
                    class="{{ $report->remarks == 'Not Extracted' ? '' : 'hidden' }}">

                    <label class="text-sm text-slate-300">
                        Reason why not extracted
                    </label>

                    <textarea name="reason_not_extracted"
                        rows="3"
                        class="mt-1 w-full bg-slate-800 border border-slate-700 rounded-lg p-2 text-white">{{ $report->reason_not_extracted }}</textarea>

                </div>



                <!-- ADD NEW FILES -->
                <div>
                    <label class="text-sm text-slate-300">
                        Upload Additional Documents
                    </label>

                    <input type="file"
                        name="documents[]"
                        multiple
                        accept=".pdf,.doc,.docx,.xls,.xlsx,.csv,.jpg,.jpeg,.png,.zip"
                        class="mt-1 w-full bg-slate-800 border border-slate-700 rounded-lg p-2 text-white">

                    <p class="text-xs text-slate-400 mt-1">
                        Maximum file size: 1GB. Allowed types: pdf, doc, docx, xls, xlsx, csv, jpg, jpeg, png, zip.
                    </p>
                </div>



                <!-- BUTTONS -->
                <div class="flex justify-end gap-3 pt-4">

                    <a href="{{ route('dforensics.index') }}"
                        class="px-4 py-2 bg-slate-700 text-white rounded-lg">
                        Cancel
                    </a>

                    <button type="submit"
                        class="px-5 py-2 bg-cyan-500 hover:bg-cyan-600 text-white rounded-lg">
                        Update Report
                    </button>

                </div>

            </div>

        </form>



        <!-- EXISTING DOCUMENTS SECTION (OUTSIDE UPDATE FORM) -->

        <div class="mt-6 bg-slate-900 border border-slate-800 rounded-xl p-6">

            <label class="text-sm text-slate-300">
                Uploaded Documents
            </label>

            <div class="mt-3 space-y-2">

                @foreach($report->documents as $doc)

                <div class="flex items-center gap-4">

                    <a href="{{ asset('storage/'.$doc->file_path) }}"
                        target="_blank"
                        class="text-cyan-400 hover:underline">
                        Preview
                    </a>

                    <a href="{{ asset('storage/'.$doc->file_path) }}"
                        download
                        class="text-blue-400 hover:underline">
                        Download
                    </a>

                    <form method="POST"
                        action="{{ route('dforensics.document.delete',$doc->id) }}"
                        onsubmit="return confirm('Delete this document?')">

                        @csrf
                        @method('DELETE')

                        <button class="text-red-400 hover:underline">
                            Delete
                        </button>

                    </form>

                </div>

                @endforeach

            </div>

        </div>

    </div>


    <script>
        const remarks = document.getElementById("remarks");
        const reasonBox = document.getElementById("reasonBox");

        remarks.addEventListener("change", function() {

            if (this.value === "Not Extracted") {
                reasonBox.classList.remove("hidden");
            } else {
                reasonBox.classList.add("hidden");
            }

        });
    </script>

</x-app-layout>