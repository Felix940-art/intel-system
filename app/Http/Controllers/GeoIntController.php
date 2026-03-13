<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GeoInt;
use Illuminate\Support\Facades\Http;
use SimpleSoftwareIO\QrCode\DataTypes\Geo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;

class GeoIntController extends Controller
{
    public function index(Request $request)
    {
        $query = GeoInt::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('home_point_mgrs', 'like', '%' . $request->search . '%')
                    ->orWhere('uav', 'like', '%' . $request->search . '%')
                    ->orWhere('threat_confronted', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('mission_datetime', $request->date);
        }

        $geointRecords = $query
            ->orderByDesc('mission_datetime')
            ->paginate(10);

        $stats = [
            'total' => GeoInt::count(),
            'today' => GeoInt::whereDate('mission_datetime', today())->count(),
            'this_week' => GeoInt::whereBetween('mission_datetime', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'classified_secret' => GeoInt::where('classification', 'SECRET')->count(),
        ];

        $uavUsage = GeoInt::selectRaw('uav, COUNT(*) as total')
            ->groupBy('uav')
            ->orderByDesc('total')
            ->get();

        $totalMissions = GeoInt::count();

        $todayMissions = GeoInt::whereDate('mission_datetime', today())->count();

        $weekMissions = GeoInt::whereBetween('mission_datetime', [now()->startOfWeek(), now()->endOfWeek()])->count();


        $classified = GeoInt::where('classification', 'SECRET')->count();

        return view('geoint.index', compact(
            'geointRecords',
            'stats',
            'uavUsage',

            'totalMissions',
            'todayMissions',
            'weekMissions',

            'classified'
        ));
    }

    public function create()
    {
        return view('geoint.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'mission_datetime' => 'required|date',
            'uav' => 'required|string',
            'home_point_mgrs' => 'required|string',
            'threat_confronted' => 'required|string',
            'classification' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'document' => 'nullable|file'
        ]);

        $path = null;

        if ($request->hasFile('document')) {
            $path = $request->file('document')
                ->store('geoint_documents', 'public');
        }

        GeoInt::create([
            'mission_datetime' => $request->mission_datetime,
            'uav' => $request->uav,
            'home_point_mgrs' => $request->home_point_mgrs,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'threat_confronted' => $request->threat_confronted,
            'classification' => $request->classification ?? 'UNCLASSIFIED',
            'document_path' => $path,
        ]);

        return redirect()->route('geoint.index')
            ->with('success', 'GeoInt record stored.');
    }

    public function uavIntel(Request $request)
    {
        $query = GeoInt::query();

        if ($request->filled('uav')) {
            $query->where('uav', $request->uav);
        }

        $records = $query->get();

        // =========================
        // Strategic Metrics
        // =========================

        $totalMissions = $records->count();
        $lastDeployment = $records->max('mission_datetime');

        $mostFrequentThreat = $records
            ->groupBy('threat_confronted')
            ->map->count()
            ->sortDesc()
            ->keys()
            ->first();

        $uavDistribution = $records
            ->groupBy('uav')
            ->map->count();

        $missionsPerUav = $uavDistribution;

        $threatExposure = $records
            ->groupBy('uav')
            ->map(function ($group) {
                return $group
                    ->groupBy('threat_confronted')
                    ->map->count();
            });

        // =========================
        // Timeline (filtered)
        // =========================

        $timeline = $records
            ->groupBy(function ($item) {
                return \Carbon\Carbon::parse($item->mission_datetime)
                    ->format('Y-m-d');
            })
            ->map(function ($group, $date) {
                return [
                    'date' => $date,
                    'total' => $group->count()
                ];
            })
            ->values();

        // =========================
        // Heat Data (filtered)
        // =========================

        $heatData = GeoInt::query()
            ->when(request('uav'), fn($q) => $q->where('uav', request('uav')))
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(function ($g) {
                return [
                    'mgrs' => $g->home_point_mgrs,
                    'lat' => (float) $g->latitude,
                    'lng' => (float) $g->longitude,
                    'uav' => $g->uav,
                    'classification' => $g->classification,
                    'threat' => $g->threat_confronted,
                ];
            });

        return view('geoint.uav-intel', compact(
            'totalMissions',
            'lastDeployment',
            'mostFrequentThreat',
            'uavDistribution',
            'missionsPerUav',
            'threatExposure',
            'heatData',
            'timeline'
        ));
    }

    public function analytics()
    {
        $heatmapData = GeoInt::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        $uavStats = GeoInt::select('uav')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('uav')
            ->get();

        return view('geoint.analytics', compact('heatmapData', 'uavStats'));
    }

    public function edit(GeoInt $record)
    {
        return view('geoint.edit', compact('record'));
    }

    public function update(Request $request, GeoInt $record)
    {
        $validated = $request->validate([
            'mission_datetime' => 'required|date',
            'uav' => 'required|string|max:100',
            'home_point_mgrs' => 'required|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'threat_confronted' => 'nullable|string|max:100',
            'classification' => 'nullable|string|max:50',
            'document' => 'nullable|file|mimes:pdf|max:10240'
        ]);

        if ($request->hasFile('document')) {

            // Delete old file (if exists)
            if ($record->document_path) {
                Storage::disk('public')->delete($record->document_path);
            }

            // Store new file in PUBLIC disk
            $validated['document_path'] =
                $request->file('document')
                ->store('geoint_docs', 'public');
        }

        $record->update($validated);

        return redirect()
            ->route('geoint.index')
            ->with('success', 'Mission updated successfully.');
    }
}
