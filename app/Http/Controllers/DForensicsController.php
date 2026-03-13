<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ForensicReport;
use App\Models\ForensicDocument;
use Illuminate\Support\Facades\Storage;
use App\Helpers\ActivityLogger;

class DForensicsController extends Controller
{

    public function index()
    {
        $reports = ForensicReport::with('documents')
            ->latest()
            ->get();

        return view('dforensics.index', compact('reports'));
    }


    public function create()
    {
        return view('dforensics.create');
    }


    public function store(Request $request)
    {

        $request->validate([
            'extraction_date' => 'required|date',
            'location' => 'required|string|max:255',
            'equipment_type' => 'required|string|max:255',
            'remarks' => 'required|string',
            'examiner_name' => 'required|string|max:255',
            'documents.*' => 'nullable|file|max:1048576'
        ]);

        if ($request->remarks == "Extracted") {
            $request->merge([
                'reason_not_extracted' => null
            ]);
        }

        $report = ForensicReport::create([
            'extraction_date' => $request->extraction_date,
            'location' => $request->location,
            'equipment_type' => $request->equipment_type,
            'remarks' => $request->remarks,
            'reason_not_extracted' => $request->reason_not_extracted,
            'examiner_name' => $request->examiner_name
        ]);

        if ($request->hasFile('documents')) {

            foreach ($request->file('documents') as $file) {

                $path = $file->store('forensics', 'public');

                ForensicDocument::create([
                    'report_id' => $report->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path
                ]);
            }
        }

        return redirect()
            ->route('dforensics.index')
            ->with('success', 'Forensic report created successfully.');
    }


    public function edit($id)
    {
        $report = ForensicReport::with('documents')->findOrFail($id);

        return view('dforensics.edit', compact('report'));
    }


    public function update(Request $request, $id)
    {

        $request->validate([
            'extraction_date' => 'required|date',
            'location' => 'required|string|max:255',
            'equipment_type' => 'required|string|max:255',
            'remarks' => 'required|string',
            'examiner_name' => 'required|string|max:255',
            'documents.*' => 'nullable|file|max:1048576'
        ]);

        $report = ForensicReport::findOrFail($id);

        if ($request->remarks == "Extracted") {
            $request->merge([
                'reason_not_extracted' => null
            ]);
        }

        $report->update([
            'extraction_date' => $request->extraction_date,
            'location' => $request->location,
            'equipment_type' => $request->equipment_type,
            'remarks' => $request->remarks,
            'reason_not_extracted' => $request->reason_not_extracted,
            'examiner_name' => $request->examiner_name
        ]);


        /* Upload additional files */

        if ($request->hasFile('documents')) {

            foreach ($request->file('documents') as $file) {

                $path = $file->store('forensics', 'public');

                ForensicDocument::create([
                    'report_id' => $report->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path
                ]);
            }
        }

        return redirect()
            ->route('dforensics.index')
            ->with('success', 'Forensic report updated successfully.');
    }


    public function deleteDocument($id)
    {
        $doc = ForensicDocument::findOrFail($id);

        Storage::disk('public')->delete($doc->file_path);

        $doc->delete();

        return back()->with('success', 'Document removed.');
    }


    public function destroy($id)
    {
        $report = ForensicReport::with('documents')->findOrFail($id);

        foreach ($report->documents as $doc) {

            Storage::disk('public')->delete($doc->file_path);

            $doc->delete();
        }

        $report->delete();

        return back()->with('success', 'Report deleted.');
    }
}
