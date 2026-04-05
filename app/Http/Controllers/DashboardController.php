<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Frequency;
use App\Models\GeoInt;
use App\Models\ForensicReport;
use App\Models\SreEvent;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // =========================
        // TOTAL COUNTS (REAL DATA)
        // =========================
        $rfCount = Frequency::count();
        $sreCount = SreEvent::count();

        $sigint = $rfCount + $sreCount;

        $geoint = GeoInt::count();
        $dforensics = ForensicReport::count();

        $totalRecords = $sigint + $geoint + $dforensics;

        // =========================
        // HEATMAP DATA (GEOINT)
        // =========================
        $heatData = GeoInt::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(function ($g) {
                return [
                    'lat' => (float) $g->latitude,
                    'lng' => (float) $g->longitude,
                    'uav' => $g->uav,
                    'mgrs' => $g->home_point_mgrs,
                    'threat' => $g->threat_confronted,
                ];
            });

        // =========================
        // TREND (SIGINT BASED)
        // =========================
        $sigintTrend = DB::table(function ($query) {
            $query->selectRaw('created_at')
                ->from('frequencies')
                ->unionAll(
                    DB::table('sre_events')
                        ->selectRaw('created_at')
                );
        }, 'combined_sigint')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $geoTrend = DB::table('geo_ints')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as geoint')
            ->groupBy('month')
            ->get();

        $forensicTrend = DB::table('forensic_reports')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as dforensics')
            ->groupBy('month')
            ->get();

        $sigintData = array_fill(1, 12, 0);
        foreach ($sigintTrend as $row) {
            $sigintData[$row->month] = $row->total;
        }
        $geoIntData = array_fill(1, 12, 0);
        foreach ($geoTrend as $row) {
            $geoIntData[$row->month] = $row->geoint;
        }
        $forensicsData = array_fill(1, 12, 0);
        foreach ($forensicTrend as $row) {
            $forensicsData[$row->month] = $row->dforensics;
        }

        // =========================
        // MAP DATA (SIGINT)
        // =========================
        $mapData = Frequency::whereNotNull('barangay')
            ->get()
            ->map(function ($f) {
                return [
                    'lat' => null, // (you don’t store lat yet)
                    'lng' => null,
                    'frequency' => $f->frequency,
                    'location' => $f->barangay . ', ' . $f->municipality,
                ];
            });

        // =========================
        // FORENSICS PREVIEW
        // =========================
        $forensics = ForensicReport::latest()
            ->limit(5)
            ->get();


        // =========================
        // RECENT ACTIVITY (REAL DATA)
        // =========================
        $recentActivities = AuditLog::latest()
            ->limit(10)
            ->get()
            ->map(function ($log) {
                return [
                    'time' => $log->created_at->format('H:i'),
                    'module' => $log->module,
                    'action' => $log->action,
                    'user' => $log->user->name ?? 'System',
                ];
            });


        $alerts = collect();

        // SIGINT ALERT (example: watchlist)
        $watchSignals = Frequency::count();

        if ($watchSignals > 0) {
            $alerts->push([
                'type' => 'high',
                'title' => 'SIGINT Threat Detected',
                'message' => "$watchSignals watchlisted signals detected"
            ]);
        }

        // GEOINT ALERT (example: pending)
        $pendingGeo = ForensicReport::whereDate('created_at', today())->count();

        if ($pendingGeo > 0) {
            $alerts->push([
                'type' => 'warning',
                'title' => 'GEOINT Records',
                'message' => "$pendingGeo GEOINT records added today"
            ]);
        }

        // FORENSIC ALERT (example: new report)
        $recentForensics = ForensicReport::whereDate('created_at', today())->count();

        if ($recentForensics > 0) {
            $alerts->push([
                'type' => 'info',
                'title' => 'New Forensic Evidence',
                'message' => "$recentForensics new forensic reports added today"
            ]);
        }

        // =========================
        // RECENT ACTIVITY FEED
        // =========================

        // SIGINT (Radio Frequency)
        $sigintLogs = \App\Models\Frequency::select(
            DB::raw("'SIGINT' as module"),
            DB::raw("'New frequency logged' as action"),
            'created_at',
            DB::raw("'Specialist' as user")
        )
            ->latest()
            ->take(5);

        // GEOINT
        $geointLogs = \App\Models\GeoInt::select(
            DB::raw("'GEOINT' as module"),
            DB::raw("'UAV mission recorded' as action"),
            'created_at',
            DB::raw("'Analyst' as user")
        )
            ->latest()
            ->take(5);

        // D-FORENSICS
        $forensicLogs = \App\Models\ForensicReport::select(
            DB::raw("'D_FORENSICS' as module"),
            DB::raw("'Evidence uploaded' as action"),
            'created_at',
            DB::raw("'Forensic Examiner' as user")
        )
            ->latest()
            ->take(5);

        // MERGE ALL
        $activities = $sigintLogs
            ->unionAll($geointLogs)
            ->unionAll($forensicLogs);

        // FINAL SORT
        $activities = DB::table(DB::raw("({$activities->toSql()}) as activity"))
            ->mergeBindings($activities->getQuery())
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();


        return view('dashboard', compact(
            'sigint',
            'geoint',
            'dforensics',
            'rfCount',
            'sreCount',
            'totalRecords',
            'sigintTrend',
            'geoTrend',
            'forensicTrend',
            'mapData',
            'forensics',
            'recentActivities',
            'heatData',
            'geoTrend',
            'forensicTrend',
            'sigintData',
            'geoIntData',
            'forensicsData',
            'forensics',
            'alerts',
            'activities'

        ));
    }
}
