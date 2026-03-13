@php
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
@endphp

<x-app-layout>

    <div class="p-6">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-6">

            <div>
                <h1 class="text-2xl font-bold text-white">
                    🧬 D-FORENSICS REPORT
                </h1>

                <p class="text-slate-400 text-sm">
                    Digital Evidence Extraction & Documentation
                </p>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('dforensics.create') }}"
                    class="px-4 py-2 bg-cyan-500 text-white rounded-lg hover:bg-cyan-600">
                    + Create Report
                </a>

                <a href="#"
                    class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                    📊 View Analysis
                </a>
            </div>

        </div>

        <div class="grid grid-cols-4 gap-4 mb-6">

            <!-- TOTAL REPORTS -->
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-4">
                <div class="text-slate-400 text-xs uppercase">
                    Total Reports
                </div>

                <div class="text-2xl font-bold text-white mt-1">
                    {{ $reports->count() }}
                </div>
            </div>

            <!-- EXTRACTED -->
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-4">
                <div class="text-slate-400 text-xs uppercase">
                    Extracted
                </div>

                <div class="text-2xl font-bold text-green-400 mt-1">
                    {{ $reports->where('remarks','Extracted')->count() }}
                </div>
            </div>

            <!-- NOT EXTRACTED -->
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-4">
                <div class="text-slate-400 text-xs uppercase">
                    Not Extracted
                </div>

                <div class="text-2xl font-bold text-red-400 mt-1">
                    {{ $reports->where('remarks','Not Extracted')->count() }}
                </div>
            </div>

            <!-- TOTAL DOCUMENTS -->
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-4">
                <div class="text-slate-400 text-xs uppercase">
                    Total Documents
                </div>

                <div class="text-2xl font-bold text-cyan-400 mt-1">
                    {{ $reports->sum(fn($r) => $r->documents->count()) }}
                </div>
            </div>

        </div>

        <div class="grid grid-cols-12 gap-6">

            <!-- ================= LEFT : TABLE ================= -->
            <div class="col-span-7">

                <div class="bg-slate-900 rounded-xl border border-slate-800 h-[650px] flex flex-col overflow-hidden">

                    <!-- TABLE HEADER -->
                    <table class="w-full text-sm text-left">

                        <thead class="bg-slate-800 text-slate-300">
                            <tr>
                                <th class="p-3 w-[120px]">DATE</th>
                                <th class="p-3">LOCATION</th>
                                <th class="p-3">EQUIPMENT</th>
                                <th class="p-3">STATUS</th>
                                <th class="p-3">DOCUMENT</th>
                                <th class="p-3">EXAMINER</th>
                                <th class="p-3 w-[120px]">ACTION</th>
                            </tr>
                        </thead>

                    </table>


                    <!-- SCROLLABLE BODY -->
                    <div class="overflow-y-auto flex-1">

                        <table class="w-full text-sm text-left">

                            <tbody>

                                @forelse($reports as $report)

                                <tr class="border-t border-slate-800">

                                    <!-- DATE -->
                                    <td class="p-3 w-[120px]">
                                        {{ \Carbon\Carbon::parse($report->extraction_date)->format('d M Y') }}
                                    </td>

                                    <!-- LOCATION -->
                                    <td class="p-3">
                                        {{ $report->location }}
                                    </td>

                                    <!-- EQUIPMENT -->
                                    <td class="p-3">
                                        {{ $report->equipment_type }}
                                    </td>

                                    <!-- STATUS -->
                                    <td class="p-3">

                                        @if($report->remarks == 'Extracted')

                                        <span class="px-2 py-1 text-xs bg-green-600 rounded">
                                            Extracted
                                        </span>

                                        @else

                                        <span class="px-2 py-1 text-xs bg-red-600 rounded">
                                            Not Extracted
                                        </span>

                                        @endif

                                    </td>

                                    <!-- DOCUMENT -->
                                    <td class="p-3">

                                        <div class="flex flex-col gap-1">

                                            @foreach($report->documents as $doc)

                                            @php

                                            $ext = strtolower(pathinfo($doc->file_name, PATHINFO_EXTENSION));

                                            $icons = [
                                            'jpg'=>'🖼','jpeg'=>'🖼','png'=>'🖼','gif'=>'🖼',
                                            'pdf'=>'📄',
                                            'doc'=>'📑','docx'=>'📑',
                                            'xls'=>'📊','xlsx'=>'📊','csv'=>'📊',
                                            'mp4'=>'🎥','mov'=>'🎥','webm'=>'🎥',
                                            'zip'=>'📁','rar'=>'📁'
                                            ];

                                            $icon = $icons[$ext] ?? '📎';

                                            $size = Storage::disk('public')->exists($doc->file_path)
                                            ? number_format(Storage::disk('public')->size($doc->file_path)/1024,2).' KB'
                                            : 'Unknown';

                                            @endphp

                                            <div class="flex items-center gap-2">

                                                <button
                                                    onclick="loadEvidence(
'{{ asset('storage/'.$doc->file_path) }}',
'{{ basename($doc->file_name) }}',
'{{ $ext }}',
'{{ $size }}',
'{{ $doc->created_at ?? 'Unknown' }}'
)"
                                                    class="text-blue-400 hover:underline text-left">

                                                    {{ $icon }} {{ Str::limit(basename($doc->file_name),25) }}

                                                </button>

                                                <a href="{{ asset('storage/'.$doc->file_path) }}"
                                                    download
                                                    class="text-cyan-400 hover:underline text-xs">

                                                    Download

                                                </a>

                                            </div>

                                            @endforeach

                                        </div>

                                    </td>

                                    <!-- EXAMINER -->
                                    <td class="p-3">
                                        {{ $report->examiner_name }}
                                    </td>

                                    <!-- ACTION -->
                                    <td class="p-3 flex gap-3 w-[120px]">

                                        <a href="{{ route('dforensics.edit',$report->id) }}"
                                            class="text-blue-400 hover:underline">
                                            Edit
                                        </a>

                                        <form action="{{ route('dforensics.destroy',$report->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Delete this report?')">

                                            @csrf
                                            @method('DELETE')

                                            <button class="text-red-400 hover:underline">
                                                Delete
                                            </button>

                                        </form>

                                    </td>

                                </tr>

                                @empty

                                <tr>

                                    <td colspan="7" class="text-center p-6 text-slate-400">
                                        No forensic reports available.
                                    </td>

                                </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            <!-- ================= RIGHT : EVIDENCE VIEWER ================= -->
            <div class="col-span-5">

                <div class="bg-slate-900 border border-slate-800 rounded-xl p-4">

                    <h2 class="text-white font-semibold mb-3">
                        Evidence Viewer
                    </h2>

                    <div class="flex gap-4 h-[600px]">

                        <!-- PREVIEW -->
                        <div id="evidenceViewer"
                            class="w-3/4 bg-slate-800 rounded-lg flex items-center justify-center text-slate-400 overflow-hidden">

                            Select a document to preview

                        </div>

                        <!-- METADATA -->
                        <div id="evidenceMeta"
                            class="w-1/4 bg-slate-800 rounded-lg p-4 text-sm text-slate-300 overflow-y-auto">

                            <h3 class="text-white font-semibold mb-3">
                                File Metadata
                            </h3>

                            <div class="space-y-2">
                                <div><b>Name:</b> -</div>
                                <div><b>Type:</b> -</div>
                                <div><b>Size:</b> -</div>
                                <div><b>Uploaded:</b> -</div>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- ================= JS ================= -->

        <script>
            function loadEvidence(path, name, type, size, uploaded) {

                const viewer = document.getElementById("evidenceViewer");
                const meta = document.getElementById("evidenceMeta");

                const ext = type.toLowerCase();

                viewer.innerHTML = `

<div class="w-full h-full flex items-center justify-center">

${(['jpg','jpeg','png','gif','webp'].includes(ext)) ?

`<img src="${path}" class="max-h-full max-w-full rounded">` :

(['mp4','mov','webm'].includes(ext)) ?

`<video controls class="max-h-full max-w-full rounded">
<source src="${path}">
</video>` :

(ext==='pdf') ?

`<iframe src="${path}" class="w-full h-full rounded"></iframe>` :

(['doc','docx','xls','xlsx','ppt','pptx'].includes(ext)) ?

`<iframe src="https://view.officeapps.live.com/op/embed.aspx?src=${encodeURIComponent(path)}"
class="w-full h-full"></iframe>` :

`<div class="text-center">
Preview not available.<br><br>
<a href="${path}" class="text-cyan-400 underline">Download File</a>
</div>`

}

</div>
`;

                meta.innerHTML = `

<h3 class="text-white font-semibold mb-3">File Metadata</h3>

<div class="space-y-2">
<div><b>Name:</b> ${name}</div>
<div><b>Type:</b> ${type}</div>
<div><b>Size:</b> ${size}</div>
<div><b>Uploaded:</b> ${uploaded}</div>
</div>

`;

            }
        </script>

</x-app-layout>