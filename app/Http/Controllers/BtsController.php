<?php

namespace App\Http\Controllers;

use App\Models\Bts;
use Illuminate\Http\Request;
use App\Exports\BtsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class BtsController extends Controller
{
    public function index(Request $request)
    {
        $query = Bts::query();

        /*
        |--------------------------------------------------------------------------
        | Global Intelligence Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('mgrs_location', 'LIKE', "%{$search}%")
                    ->orWhere('network', 'LIKE', "%{$search}%")
                    ->orWhere('network_mode', 'LIKE', "%{$search}%")
                    ->orWhere('lac', 'LIKE', "%{$search}%")
                    ->orWhere('cid', 'LIKE', "%{$search}%")
                    ->orWhere('neighbor_cid', 'LIKE', "%{$search}%")
                    ->orWhere('barangay', 'LIKE', "%{$search}%")
                    ->orWhere('municipality', 'LIKE', "%{$search}%")
                    ->orWhere('province', 'LIKE', "%{$search}%");
            });
        }

        /*
    |--------------------------------------------------------------------------
    | Network Filter
    |--------------------------------------------------------------------------
    */

        if ($request->network) {

            $query->where('network', $request->network);
        }


        /*
    |--------------------------------------------------------------------------
    | Network Mode Filter
    |--------------------------------------------------------------------------
    */

        if ($request->mode) {

            $query->where('network_mode', $request->mode);
        }


        /*
    |--------------------------------------------------------------------------
    | Province Filter
    |--------------------------------------------------------------------------
    */

        if ($request->province) {

            $query->where('province', $request->province);
        }


        // =========================
        // BTS Technology Intelligence Statistics
        // =========================

        // Total BTS Assets
        $totalBts = Bts::count();


        // Technology Counts
        $twoGTowers = Bts::where('network_mode', '2G')->count();

        $fourGLTETowers = Bts::where('network_mode', '4G LTE')->count();

        $threeGTowers = Bts::where('network_mode', '3G')->count();

        $fiveGTowers = Bts::where('network_mode', '5G')->count();

        // =========================
        // Network Distribution Intelligence
        // =========================

        // Network Tower Counts
        $smartTowers = Bts::where('network', 'SMART')->count();

        $globeTowers = Bts::where('network', 'GLOBE')->count();

        $tmTowers = Bts::where('network', 'TM')->count();

        $gomoTowers = Bts::where('network', 'GOMO')->count();

        $tntTowers = Bts::where('network', 'TNT')->count();

        $sunTowers = Bts::where('network', 'SUN')->count();

        $networkStats = collect([
            'SMART' => $smartTowers,
            'TM'    => $tmTowers,
            'GLOBE' => $globeTowers,
            'TNT'   => $tntTowers,
            'GOMO'  => $gomoTowers,
            'SUN'   => $sunTowers,
        ]);

        // Technology Share (% of all BTS)
        $twoGShare = $totalBts > 0
            ? round(($twoGTowers / $totalBts) * 100, 1)
            : 0;


        $fourGLTEShare = $totalBts > 0
            ? round(($fourGLTETowers / $totalBts) * 100, 1)
            : 0;

        $threeGShare = $totalBts > 0
            ? round(($threeGTowers / $totalBts) * 100, 1)
            : 0;


        $fiveGShare = $totalBts > 0
            ? round(($fiveGTowers / $totalBts) * 100, 1)
            : 0;
        /*
    |--------------------------------------------------------------------------
    | Get Records
    |--------------------------------------------------------------------------
    */

        $btsRecords = $query
            ->latest()
            ->paginate(20)
            ->withQueryString();

        // Return only the table during live filtering
        if ($request->ajax()) {

            return view(
                'sigint.bts.partials.table',
                compact('btsRecords')
            );
        }


        // Normal page load
        return view(
            'sigint.bts.index',
            compact(
                'btsRecords',
                'totalBts',
                'twoGTowers',
                'fourGLTETowers',
                'threeGTowers',
                'fiveGTowers',
                'twoGShare',
                'fourGLTEShare',
                'threeGShare',
                'fiveGShare',
                'smartTowers',
                'globeTowers',
                'tmTowers',
                'gomoTowers',
                'tntTowers',
                'sunTowers',
                'networkStats'
            )
        );
    }

    public function create()
    {
        return view('sigint.bts.create');
    }

    public function store(Request $request)
    {
        $request->validate([

            'name' => 'required',
            'mgrs_location' => 'required',
            'network' => 'required',
            'network_mode' => 'required'

        ]);

        Bts::create($request->all());

        return redirect()
            ->route('sigint.bts.index')
            ->with('success', 'BTS station added successfully.');
    }

    public function edit(Bts $bts)
    {
        return view('sigint.bts.edit', compact('bts'));
    }

    public function update(Request $request, Bts $bts)
    {
        $request->validate([
            'name' => 'required',
            'mgrs_location' => 'required',
            'network' => 'required',
            'network_mode' => 'required',
            'lac' => 'required',
            'cid' => 'required',
        ]);

        $bts->update([
            'name' => $request->name,
            'mgrs_location' => $request->mgrs_location,
            'network' => $request->network,
            'network_mode' => $request->network_mode,
            'lac' => $request->lac,
            'cid' => $request->cid,
            'neighbor_cid' => $request->neighbor_cid,
            'barangay' => $request->barangay,
            'municipality' => $request->municipality,
            'province' => $request->province,
        ]);

        return redirect()
            ->route('sigint.bts.index')
            ->with('success', 'BTS station updated successfully.');
    }

    public function destroy(Bts $bts)
    {
        $name = $bts->name;

        $bts->delete();

        return redirect()
            ->route('sigint.bts.index')
            ->with('success', 'BTS station deleted successfully.');
    }

    public function export()
    {
        $filename = 'BTS_INTELLIGENCE_REPORT_' . now()->format('Y_m_d_His') . '.xlsx';

        return Excel::download(
            new BtsExport,
            $filename
        );
    }

    public function pdf()
    {
        $btsRecords = Bts::orderBy('name')->get();


        /*
    |--------------------------------------------------------------------------
    | Executive Intelligence Summary
    |--------------------------------------------------------------------------
    */

        $totalBts = Bts::count();

        // Network Distribution
        $smartBts = Bts::where('network', 'SMART')->count();
        $globeBts = Bts::where('network', 'GLOBE')->count();
        $tmBts = Bts::where('network', 'TM')->count();


        // Technology Evolution
        $twoGTowers = Bts::where('network_mode', '2G')->count();
        $threeGTowers = Bts::where('network_mode', '3G')->count();
        $fourGLTETowers = Bts::where('network_mode', '4G LTE')->count();
        $fiveGTowers = Bts::where('network_mode', '5G')->count();


        $pdf = Pdf::loadView(
            'sigint.bts.pdf',
            compact(
                'btsRecords',

                'totalBts',

                'smartBts',
                'globeBts',
                'tmBts',

                'twoGTowers',
                'threeGTowers',
                'fourGLTETowers',
                'fiveGTowers'
            )
        )
            ->setPaper('a4', 'landscape');


        return $pdf->download(
            'BTS_INTELLIGENCE_REPORT_' .
                now()->format('Y_m_d_His') .
                '.pdf'
        );
    }
}
