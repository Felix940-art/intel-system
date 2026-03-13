<x-app-layout>

    <div class="p-6 max-w-5xl mx-auto">

        <div class="mb-6">

            <h1 class="text-2xl font-bold text-white">
                + Create Forensic Report
            </h1>

            <p class="text-slate-400 text-sm">
                Digital Evidence Extraction Documentation
            </p>

        </div>


        <form method="POST"
            action="{{ route('dforensics.store') }}"
            enctype="multipart/form-data">

            @csrf


            <div class="bg-slate-900 border border-slate-800 rounded-xl p-6 space-y-6">

                <!-- DATE + LOCATION -->

                <div class="grid md:grid-cols-2 gap-4">

                    <div>

                        <label class="text-sm text-slate-300">
                            Date of Extraction *
                        </label>

                        <input type="date"
                            name="extraction_date"
                            required
                            class="mt-1 w-full bg-slate-800 border border-slate-700 rounded-lg p-2 text-white">

                    </div>


                    <div>

                        <label class="text-sm text-slate-300">
                            Location *
                        </label>

                        <input type="text"
                            name="location"
                            placeholder="Example: Camp Lukban"
                            required
                            class="mt-1 w-full bg-slate-800 border border-slate-700 rounded-lg p-2 text-white">

                    </div>

                </div>



                <!-- EQUIPMENT + EXAMINER -->

                <div class="grid md:grid-cols-2 gap-4">

                    <div>

                        <label class="text-sm text-slate-300">
                            Type of Digital Equipment *
                        </label>

                        <select name="equipment_type"
                            required
                            class="mt-1 w-full bg-slate-800 border border-slate-700 rounded-lg p-2 text-white">

                            <option value="">Select Equipment</option>
                            <option>Mobile Phone</option>
                            <option>Laptop</option>
                            <option>Hard Drive</option>
                            <option>USB Storage</option>
                            <option>Memory Card</option>
                            <option>SIM Card</option>
                            <option>Drone Storage</option>
                            <option>Network Device</option>
                            <option>Other</option>

                        </select>

                    </div>


                    <div>

                        <label class="text-sm text-slate-300">
                            Examiner Name *
                        </label>

                        <input type="text"
                            name="examiner_name"
                            required
                            class="mt-1 w-full bg-slate-800 border border-slate-700 rounded-lg p-2 text-white">

                    </div>

                </div>



                <!-- REMARKS -->

                <div>

                    <label class="text-sm text-slate-300">
                        Remarks *
                    </label>

                    <select name="remarks"
                        id="remarks"
                        required
                        class="mt-1 w-full bg-slate-800 border border-slate-700 rounded-lg p-2 text-white">

                        <option value="">Select Status</option>
                        <option value="Extracted">Extracted</option>
                        <option value="Not Extracted">Not Extracted</option>

                    </select>

                </div>



                <!-- REASON (CONDITIONAL FIELD) -->

                <div id="reasonBox" class="hidden">

                    <label class="text-sm text-slate-300">
                        Reason why not extracted
                    </label>

                    <textarea name="reason_not_extracted"
                        rows="3"
                        class="mt-1 w-full bg-slate-800 border border-slate-700 rounded-lg p-2 text-white"></textarea>

                </div>



                <!-- DOCUMENT UPLOAD -->

                <div>

                    <label class="text-sm text-slate-300">
                        Upload Documents
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
                        Save Report
                    </button>

                </div>

                @if(session('success'))
                <div class="bg-green-600 text-white p-3 rounded mb-4">
                    {{ session('success') }}
                </div>
                @endif

            </div>

        </form>

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