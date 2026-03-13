<?php

namespace App\Http\Controllers;

use App\Models\Frequency;
use App\Exports\FrequenciesExport;
use App\Helpers\Audit;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;



class FrequencyController extends Controller
{
    /******************************************
     * LIST PAGE
     ******************************************/
    public function index(Request $request)
    {
        $query = Frequency::query();
        $user = Auth::user();

        // Mark alerts as read (ADMIN ONLY)
        if (auth()->user()->isAdmin() && Schema::hasTable('alerts')) {
            DB::table('alerts')
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        // DATA VISIBILITY
        if ($user && $user->isOperator()) {
            $query->where('user_id', $user->id);
        }

        if ($request->boolean('watchlisted')) {
            $query->where('is_watchlisted', 1);
        }

        // SEARCH
        if ($request->filled('q')) {
            $q = trim($request->q);
            $qUpper = strtoupper($q);

            $threatGroups = ['SRC', 'SRGU', 'SRMA', 'SROC', 'SRMA EMPORIUM', 'SRMA ARCTIC', 'SRMA BROWSER', 'SRMA SESAME', 'SRMA LEVOX', 'COMTECH', 'EV MRGU', 'FUNCTIONAL'];

            if (in_array($qUpper, $threatGroups)) {

                // ✅ EXACT threat group search ONLY
                $query->where('threat_confronted', $qUpper);
            } else {

                // 🔍 Global fuzzy search
                $query->where(function ($sub) use ($q) {
                    $sub->where('frequency', 'LIKE', "%{$q}%")
                        ->orWhere('datetime_code', 'LIKE', "%{$q}%")
                        ->orWhere('site_location', 'LIKE', "%{$q}%")
                        ->orWhere('lob', 'LIKE', "%{$q}%")
                        ->orWhere('barangay', 'LIKE', "%{$q}%")
                        ->orWhere('municipality', 'LIKE', "%{$q}%")
                        ->orWhere('province', 'LIKE', "%{$q}%")
                        ->orWhere('clarity', 'LIKE', "%{$q}%")
                        ->orWhere('conversation', 'LIKE', "%{$q}%");
                });
            }
        }

        if ($request->filled('month')) {
            $query->where('datetime_code', 'LIKE', "{$request->month}%");
        }

        if ($request->filled('year')) {
            $query->where('datetime_code', 'LIKE', "%{$request->year}%");
        }

        $frequencies = $query->latest()->paginate(10)->withQueryString();

        $distinctMonths = (clone $query)
            ->selectRaw("SUBSTRING_INDEX(SUBSTRING_INDEX(datetime_code,' ',3),' ',-1) AS m")
            ->groupBy('m')
            ->pluck('m');

        $distinctYears = (clone $query)
            ->selectRaw("SUBSTRING_INDEX(datetime_code,' ',-1) AS y")
            ->groupBy('y')
            ->pluck('y');

        return view('sigint.frequency.index', compact(
            'frequencies',
            'distinctMonths',
            'distinctYears'
        ));
    }


    /******************************************
     * CREATE
     ******************************************/
    public function create()
    {
        $knownFrequencies = Frequency::query()
            ->whereNotNull('frequency')
            ->pluck('frequency')
            ->unique()
            ->values();

        $knownOrigins = Frequency::query()
            ->whereNotNull('barangay')
            ->pluck('barangay')
            ->map(fn($o) => strtolower($o))
            ->unique()
            ->values();

        return view('sigint.frequency.create', [
            'knownFrequencies' => $knownFrequencies,
            'knownOrigins'     => $knownOrigins,
        ]);
    }


    /******************************************
     * STORE
     ******************************************/
    public function store(Request $request)
    {
        $validated = $request->validate([
            'frequency'     => 'required|string|max:255',
            'datetime_code' => 'required|string|max:255',
            'site_location' => 'nullable|string|max:255', // ✅ ADD
            'conversation'  => 'nullable|string',
            'clarity'       => 'nullable|string|max:255',
            'lob'           => 'nullable|string|max:255',
            'barangay'      => 'nullable|string|max:255',
            'municipality'  => 'nullable|string|max:255',
            'province'      => 'nullable|string|max:255',
        ]);


        $frequency = Frequency::create([
            ...$validated,
            'user_id' => auth()->id(),
            'is_watchlisted' => $request->boolean('is_watchlisted'),
            'threat_confronted' => $request->input('threat_confronted'),
        ]);

        Audit::log(
            module: 'SIGINT',
            action: 'CREATE',
            model: 'Frequency',
            recordId: $frequency->id,
            description: 'Frequency entry created'
        );

        DB::table('encoder_metrics')->insert([
            'user_id' => auth()->id(),
            'entry_time_seconds' => $request->entry_time_seconds ?? 0,
            'is_watchlisted' => $request->boolean('is_watchlisted'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('sigint.frequency.index')
            ->with('success', 'Frequency entry saved successfully!');
    }

    /******************************************
     * ANALYTICS
     ******************************************/
    public function analytics()
    {
        $query = Frequency::query();
        $user = Auth::user();

        if ($user && $user->isOperator()) {
            $query->where('user_id', $user->id);
        }

        $monthly = (clone $query)
            ->select(
                DB::raw("SUBSTRING_INDEX(SUBSTRING_INDEX(datetime_code, ' ', 3), ' ', -1) AS label"),
                DB::raw("COUNT(*) as value")
            )
            ->groupBy('label')
            ->pluck('value', 'label');

        $clarityDist = (clone $query)
            ->select('clarity', DB::raw('COUNT(*) as count'))
            ->groupBy('clarity')
            ->pluck('count', 'clarity');

        $origin = (clone $query)
            ->select('barangay', DB::raw('COUNT(*) as count'))
            ->groupBy('barangay')
            ->pluck('count', 'barangay');

        $daily = (clone $query)
            ->select(
                DB::raw("SUBSTRING_INDEX(datetime_code, ' ', 1) AS label"),
                DB::raw("COUNT(*) as value")
            )
            ->groupBy('label')
            ->pluck('value', 'label');

        return view('sigint.frequency.analytics', compact(
            'monthly',
            'clarityDist',
            'origin',
            'daily'
        ));
    }

    /******************************************
     * IMPORT
     ******************************************/
    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);

        $rows = Excel::toArray([], $request->file('file'))[0] ?? [];
        $imported = 0;

        foreach ($rows as $index => $row) {
            if ($index === 0) continue;
            if (empty($row[0]) || empty($row[1])) continue;

            $frequency = Frequency::firstOrCreate([
                'frequency' => $row[0],
                'datetime_code' => $row[1],
            ], [
                'conversation' => $row[2] ?? null,
                'clarity' => $row[3] ?? null,
                'lob' => $row[4] ?? null,
                'barangay' => $row[5] ?? null,
                'municipality' => $row[6] ?? null,
                'province' => $row[7] ?? null,
                'user_id' => auth()->id(),
            ]);

            if ($frequency->wasRecentlyCreated) {
                $imported++;
            }
        }

        Audit::log('SIGINT', 'IMPORT', 'Frequency', null, "Imported {$imported} records");

        return back()->with('success', "Import complete: {$imported} records added.");
    }

    /******************************************
     * EXPORT EXCEL
     ******************************************/
    public function export()
    {
        Audit::log('SIGINT', 'EXPORT', 'Frequency', null, 'Exported Excel');
        return Excel::download(new FrequenciesExport, 'frequencies.xlsx');
    }

    /******************************************
     * DELETE
     ******************************************/
    public function destroy($id)
    {
        $frequency = Frequency::findOrFail($id);
        $frequency->delete();

        Audit::log('SIGINT', 'DELETE', 'Frequency', $id, 'Frequency deleted');

        return redirect()
            ->route('sigint.frequency.index')
            ->with('success', 'Frequency entry deleted.');
    }

    /******************************************
     * PDF EXPORT
     ******************************************/
    public function exportPdf(Request $request)
    {
        $query = Frequency::query();
        $user = Auth::user();

        if ($user && $user->isOperator()) {
            $query->where('user_id', $user->id);
        }

        $frequencies = $query->latest()->get();

        $reportId = strtoupper(Str::random(10));

        Audit::log('SIGINT', 'EXPORT_PDF', 'Frequency', null, "PDF {$reportId}");

        return Pdf::loadView('sigint.frequency.pdf', compact('frequencies', 'reportId'))
            ->setPaper('a4', 'landscape')
            ->download("Frequency_Report_{$reportId}.pdf");
    }

    /******************************************
     * EDIT
     ******************************************/
    public function edit($id)
    {
        $frequency = Frequency::findOrFail($id);
        return view('sigint.frequency.edit', compact('frequency'));
    }


    public function toggleWatchlist(Frequency $frequency)
    {
        $frequency->update([
            'is_watchlisted' => ! $frequency->is_watchlisted
        ]);

        return response()->json([
            'status' => 'ok',
            'is_watchlisted' => $frequency->is_watchlisted
        ]);
    }

    public function checkDuplicate(Request $request)
    {
        $frequency = $request->query('frequency');

        if (!$frequency) {
            return response()->json(['exists' => false]);
        }

        $record = Frequency::where('frequency', $frequency)->first();

        return response()->json([
            'exists' => (bool) $record,
            'watchlisted' => $record?->is_watchlisted ?? false,
            'id' => $record?->id
        ]);
    }

    public function update(Request $request, Frequency $frequency)
    {
        $validated = $request->validate([
            'frequency' => 'required|string|max:255',
            'datetime_code' => 'required|string|max:255',
            'conversation' => 'nullable|string',
            'clarity' => 'nullable|string|max:255',
            'lob' => 'nullable|numeric|min:0|max:360',
            'barangay' => 'nullable|string|max:255',
            'municipality' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'site_location' => 'nullable|string|max:255',
            'threat_confronted' => 'nullable|string|max:50',
        ]);

        $frequency->update([
            ...$validated,

            // preserve owner
            'user_id' => $frequency->user_id ?? auth()->id(),

            // boolean-safe
            'is_watchlisted' => $request->boolean('is_watchlisted'),
        ]);

        return redirect()
            ->route('sigint.frequency.index')
            ->with('success', 'Frequency updated successfully.');
    }
}
