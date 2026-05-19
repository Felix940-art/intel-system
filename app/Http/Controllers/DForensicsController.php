<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ForensicReport;
use App\Models\ForensicDocument;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Helpers\ActivityLogger;

class DForensicsController extends Controller
{

    /* =========================================================
     | INDEX
     ========================================================= */

    public function index()
    {
        $reports = ForensicReport::with('documents')
            ->latest()
            ->get();

        return view('dforensics.index', compact('reports'));
    }



    /* =========================================================
     | CREATE PAGE
     ========================================================= */

    public function create()
    {
        return view('dforensics.create');
    }



    /* =========================================================
     | STORE REPORT
     ========================================================= */

    public function store(Request $request)
    {

        $request->validate([

            'extraction_date'      => 'required|date',
            'location'             => 'required|string|max:255',
            'equipment_type'       => 'required|string|max:255',
            'remarks'              => 'required|string',
            'examiner_name'        => 'required|string|max:255',
            'reason_not_extracted' => 'nullable|string',

            'documents.*' => 'nullable|file|max:1048576'

        ]);


        /* AUTO CLEAR REASON IF EXTRACTED */

        if ($request->remarks === "Extracted") {

            $request->merge([
                'reason_not_extracted' => null
            ]);
        }


        /* CREATE REPORT */

        $report = ForensicReport::create([

            'extraction_date'      => $request->extraction_date,
            'location'             => $request->location,
            'equipment_type'       => $request->equipment_type,
            'remarks'              => $request->remarks,
            'reason_not_extracted' => $request->reason_not_extracted,
            'examiner_name'        => $request->examiner_name

        ]);


        /* STORE DOCUMENTS */

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


        /* ACTIVITY LOG */

        ActivityLogger::log(
            'D-Forensics',
            'Created',
            'Forensic Report ID: ' . $report->id
        );


        return Redirect::route('dforensics.index')
            ->with('success', 'Forensic report created successfully.');
    }



    /* =========================================================
     | EDIT PAGE
     ========================================================= */

    public function edit($id)
    {
        $report = ForensicReport::with('documents')
            ->findOrFail($id);

        return view('dforensics.edit', compact('report'));
    }



    /* =========================================================
     | UPDATE REPORT
     ========================================================= */

    public function update(Request $request, $id)
    {

        $request->validate([

            'extraction_date'      => 'required|date',
            'location'             => 'required|string|max:255',
            'equipment_type'       => 'required|string|max:255',
            'remarks'              => 'required|string',
            'examiner_name'        => 'required|string|max:255',
            'reason_not_extracted' => 'nullable|string',

            'documents.*' => 'nullable|file|max:1048576'

        ]);


        $report = ForensicReport::findOrFail($id);


        /* AUTO CLEAR REASON */

        if ($request->remarks === "Extracted") {

            $request->merge([
                'reason_not_extracted' => null
            ]);
        }


        /* UPDATE REPORT */

        $report->update([

            'extraction_date'      => $request->extraction_date,
            'location'             => $request->location,
            'equipment_type'       => $request->equipment_type,
            'remarks'              => $request->remarks,
            'reason_not_extracted' => $request->reason_not_extracted,
            'examiner_name'        => $request->examiner_name

        ]);


        /* APPEND NEW DOCUMENTS */

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


        /* ACTIVITY LOG */

        ActivityLogger::log(
            'D-Forensics',
            'Updated',
            'Forensic Report ID: ' . $report->id
        );


        return Redirect::route('dforensics.index')
            ->with('success', 'Forensic report updated successfully.');
    }



    /* =========================================================
     | DELETE SINGLE DOCUMENT
     ========================================================= */

    public function deleteDocument($id)
    {

        $doc = ForensicDocument::findOrFail($id);


        /* DELETE FILE FROM STORAGE */

        if (Storage::disk('public')->exists($doc->file_path)) {

            Storage::disk('public')->delete($doc->file_path);
        }


        /* DELETE DB RECORD */

        $doc->delete();


        /* ACTIVITY LOG */

        ActivityLogger::log(
            'D-Forensics',
            'Deleted Document',
            'Forensic Document ID: ' . $doc->id
        );


        return Redirect::back()
            ->with('success', 'Document removed successfully.');
    }



    /* =========================================================
     | DELETE REPORT
     ========================================================= */

    public function destroy($id)
    {

        $report = ForensicReport::with('documents')
            ->findOrFail($id);


        /* DELETE ALL DOCUMENTS */

        foreach ($report->documents as $doc) {

            if (Storage::disk('public')->exists($doc->file_path)) {

                Storage::disk('public')->delete($doc->file_path);
            }

            $doc->delete();
        }


        /* DELETE REPORT */

        $report->delete();


        /* ACTIVITY LOG */

        ActivityLogger::log(
            'D-Forensics',
            'Deleted',
            'Forensic Report ID: ' . $report->id
        );


        return Redirect::back()
            ->with('success', 'Forensic report deleted successfully.');
    }
}
