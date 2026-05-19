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
        if (auth()->check() && auth()->user()->isAdmin() && Schema::hasTable('alerts')) {
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
            $query->where('datetime_code', 'LIKE', "%{$request->month}%");
        }

        if ($request->filled('year')) {
            $query->where('datetime_code', 'LIKE', "%{$request->year}%");
        }

        $frequencies = $query->latest()->paginate(10)->withQueryString();

        $distinctMonths = (clone $query)
            ->reorder() // reset order for groupBy
            ->selectRaw("SUBSTRING_INDEX(SUBSTRING_INDEX(datetime_code,' ',3),' ',-1) AS m")
            ->groupBy('m')
            ->pluck('m');

        $distinctYears = (clone $query)
            ->reorder() // reset order for groupBy
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
    public function analytics(Request $request)
    {
        $query = Frequency::query();

        if ($request->boolean('watchlisted')) {
            $query->where('is_watchlisted', 1);
        }

        if ($request->filled('q')) {

            $q = trim($request->q);
            $qUpper = strtoupper($q);

            $threatGroups = [
                'SRC',
                'SRGU',
                'SRMA',
                'SROC',
                'SRMA EMPORIUM',
                'SRMA ARCTIC',
                'SRMA BROWSER',
                'SRMA SESAME',
                'SRMA LEVOX',
                'COMTECH',
                'EV MRGU',
                'FUNCTIONAL'
            ];

            if (in_array($qUpper, $threatGroups)) {

                $query->where('threat_confronted', $qUpper);
            } else {

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
            $query->where('datetime_code', 'LIKE', "%{$request->month}%");
        }

        if ($request->filled('year')) {
            $query->where('datetime_code', 'LIKE', "%{$request->year}%");
        }

        $user = Auth::user();

        /*
    |--------------------------------------------------------------------------
    | OPERATOR VISIBILITY
    |--------------------------------------------------------------------------
    */

        if ($user && $user->isOperator()) {

            $query->where('user_id', $user->id);
        }

        /*
    |--------------------------------------------------------------------------
    | MULTI-SELECTION ANALYTICS
    |--------------------------------------------------------------------------
    */

        if ($request->filled('ids')) {

            $ids = explode(',', $request->ids);

            $query->whereIn('id', $ids);
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

        // ================================
        // THREAT PRIORITY INDEX
        // ================================

        $threatPriority = (clone $query)->get()
            ->whereNotNull('threat_confronted')
            ->groupBy('threat_confronted')
            ->map(function ($records, $threat) {

                $occurrence = $records->count();

                $severityScores = [];
                $detectionScores = [];

                foreach ($records as $record) {

                    $clarity = $record->clarity ?? '3x3';

                    // Split clarity
                    [$signalStrength, $readability] = explode('x', strtolower($clarity));

                    $signalStrength = (int) $signalStrength;
                    $readability = (int) $readability;

                    /*
            |--------------------------------------------------------------------------
            | DETECTION SCORE
            |--------------------------------------------------------------------------
            | Derived from FIRST NUMBER
            | 1 = fading
            | 5 = loud
            */

                    $detectionScores[] = $signalStrength;

                    /*
            |--------------------------------------------------------------------------
            | BASE SEVERITY
            |--------------------------------------------------------------------------
            */

                    $severity = $record->is_watchlisted ? 5 : 2;

                    /*
            |--------------------------------------------------------------------------
            | READABILITY MODIFIER
            |--------------------------------------------------------------------------
            */

                    switch ($readability) {

                        case 1:
                            $severity += 3;
                            break;

                        case 2:
                            $severity += 2;
                            break;

                        case 3:
                            $severity += 1;
                            break;

                        case 4:
                            $severity += 0;
                            break;

                        case 5:
                            $severity -= 1;
                            break;
                    }

                    $severityScores[] = $severity;
                }

                /*
        |--------------------------------------------------------------------------
        | AVERAGES
        |--------------------------------------------------------------------------
        */

                $averageSeverity = collect($severityScores)->avg();

                $averageDetection = collect($detectionScores)->avg();

                /*
        |--------------------------------------------------------------------------
        | FINAL TPI
        |--------------------------------------------------------------------------
        */

                $tpi = round(
                    $averageSeverity *
                        $occurrence *
                        $averageDetection
                );

                /*
        |--------------------------------------------------------------------------
        | THREAT LEVEL
        |--------------------------------------------------------------------------
        */

                if ($tpi >= 41) {

                    $level = 'CRITICAL';
                    $color = 'red';
                } elseif ($tpi >= 26) {

                    $level = 'HIGH';
                    $color = 'orange';
                } elseif ($tpi >= 11) {

                    $level = 'MODERATE';
                    $color = 'yellow';
                } else {

                    $level = 'LOW';
                    $color = 'green';
                }

                return [

                    'name' => $threat,
                    'score' => $tpi,
                    'level' => $level,
                    'color' => $color,
                    'occurrence' => $occurrence,
                    'severity' => round($averageSeverity, 2),
                    'detection' => round($averageDetection, 2),
                ];
            })
            ->sortByDesc('score')
            ->values();

        $signalHeat = (clone $query)->select('frequency')

            ->selectRaw('COUNT(*) as hits')

            ->whereNotNull('frequency')

            ->groupBy('frequency')

            ->get()

            ->map(function ($signal) {

                $score = $signal->hits * 4;

                if ($score >= 20) {

                    $level = 'CRITICAL';
                    $color = 'red';
                } elseif ($score >= 12) {

                    $level = 'HIGH';
                    $color = 'orange';
                } elseif ($score >= 6) {

                    $level = 'MODERATE';
                    $color = 'yellow';
                } else {

                    $level = 'LOW';
                    $color = 'green';
                }

                return (object)[

                    'frequency' => $signal->frequency,

                    'hits' => $signal->hits,

                    'score' => $score,

                    'heat_level' => $level,

                    'color' => $color,
                ];
            })

            ->sortByDesc('score')

            ->take(6)

            ->values();

        $watchlistActivity = (clone $query)->where('is_watchlisted', 1)

            ->select(
                'threat_confronted'
            )

            ->selectRaw('
    COUNT(*) as hits,
    MAX(created_at) as latest_activity
')

            ->groupBy('threat_confronted')

            ->get()

            ->map(function ($watch) {

                if ($watch->hits >= 5) {

                    $status = 'CRITICAL';
                    $color = 'red';
                } elseif ($watch->hits >= 3) {

                    $status = 'ELEVATED';
                    $color = 'yellow';
                } else {

                    $status = 'STABLE';
                    $color = 'green';
                }

                return (object)[

                    'threat_group' => $watch->threat_confronted,

                    'hits' => $watch->hits,

                    'status' => $status,

                    'color' => $color,

                    'latest_activity' => \Carbon\Carbon::parse(
                        $watch->latest_activity
                    )->diffForHumans(),
                ];
            })

            ->sortByDesc('hits')

            ->values();

        $originIntel = (clone $query)->selectRaw('
    site_location,
    COUNT(*) as total_signals
')

            ->whereNotNull('site_location')

            ->groupBy('site_location')

            ->orderByDesc('total_signals')

            ->get()

            ->map(function ($origin) {

                $score = $origin->total_signals;

                if ($score >= 5) {

                    $level = 'DOMINANT';
                    $color = 'red';
                } elseif ($score >= 3) {

                    $level = 'ACTIVE';
                    $color = 'yellow';
                } else {

                    $level = 'LOW';
                    $color = 'green';
                }

                return (object)[

                    'origin' => $origin->site_location,

                    'signals' => $score,

                    'level' => $level,

                    'color' => $color,
                ];
            });

        // DAILY SIGNAL ACTIVITY
        $dailySignals = (clone $query)->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        // AVERAGE SIGNALS
        $averageSignals = max(1, round($dailySignals->avg('total')));

        // TODAY SIGNALS
        $todaySignals = (clone $query)->whereDate('created_at', today())->count();

        // SURGE STATUS
        $surgeLevel = 'NORMAL';
        $surgeColor = 'bg-cyan-500/20 text-cyan-400 border-cyan-500/30';

        if ($todaySignals >= ($averageSignals * 3)) {

            $surgeLevel = 'CRITICAL SURGE';
            $surgeColor = 'bg-red-500/20 text-red-400 border-red-500/30';
        } elseif ($todaySignals >= ($averageSignals * 2)) {

            $surgeLevel = 'HIGH SURGE';
            $surgeColor = 'bg-orange-500/20 text-orange-400 border-orange-500/30';
        } elseif ($todaySignals > $averageSignals) {

            $surgeLevel = 'ELEVATED';
            $surgeColor = 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30';
        }

        // CURRENT MONTH THREAT ACTIVITY
        $currentThreats = (clone $query)->selectRaw('        threat_confronted,        COUNT(*) as total    ')
            ->whereMonth('created_at', now()->month)
            ->whereNotNull('threat_confronted')
            ->groupBy('threat_confronted')
            ->pluck('total', 'threat_confronted');

        // PREVIOUS MONTH THREAT ACTIVITY
        $previousThreats = (clone $query)->selectRaw('        threat_confronted,        COUNT(*) as total    ')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereNotNull('threat_confronted')
            ->groupBy('threat_confronted')
            ->pluck('total', 'threat_confronted');

        // ESCALATION ANALYSIS
        $threatEscalation = collect($currentThreats)->map(function ($count, $threat) use ($previousThreats) {

            $previous = $previousThreats[$threat] ?? 0;

            // PERCENT CHANGE
            if ($previous == 0) {
                $change = 100;
            } else {
                $change = round((($count - $previous) / $previous) * 100);
            }

            // STATUS
            if ($change >= 100) {

                $status = 'CRITICAL RISE';
                $color = 'bg-red-500/20 text-red-400 border-red-500/30';
                $icon = '↑';
            } elseif ($change >= 50) {

                $status = 'ESCALATING';
                $color = 'bg-orange-500/20 text-orange-400 border-orange-500/30';
                $icon = '↑';
            } elseif ($change > 0) {

                $status = 'ACTIVE';
                $color = 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30';
                $icon = '↑';
            } elseif ($change < 0) {

                $status = 'DECLINING';
                $color = 'bg-cyan-500/20 text-cyan-400 border-cyan-500/30';
                $icon = '↓';
            } else {

                $status = 'STABLE';
                $color = 'bg-green-500/20 text-green-400 border-green-500/30';
                $icon = '•';
            }

            return [
                'threat' => $threat,
                'count' => $count,
                'change' => $change,
                'status' => $status,
                'color' => $color,
                'icon' => $icon,
            ];
        })->sortByDesc('change');

        // REAL-TIME INTELLIGENCE FEED
        $intelFeed = collect();

        $recentSignals = (clone $query)->latest()
            ->take(12)
            ->get();

        foreach ($recentSignals as $signal) {

            $messages = [];

            // WATCHLIST
            if ($signal->is_watchlisted) {
                $messages[] = [
                    'type' => 'WATCHLIST',
                    'message' => 'Watchlisted signal detected',
                    'color' => 'text-red-400'
                ];
            }

            // LOW CLARITY
            if (
                str_contains($signal->clarity ?? '', '1x') ||
                str_contains($signal->clarity ?? '', '2x')
            ) {
                $messages[] = [
                    'type' => 'CLARITY',
                    'message' => 'Low clarity transmission intercepted',
                    'color' => 'text-orange-400'
                ];
            }

            // THREAT
            if ($signal->threat_confronted) {
                $messages[] = [
                    'type' => 'THREAT',
                    'message' => 'Threat group activity analyzed',
                    'color' => 'text-cyan-400'
                ];
            }

            // LOCATION
            if ($signal->municipality) {
                $messages[] = [
                    'type' => 'ORIGIN',
                    'message' => 'Origin cluster updated: ' . $signal->municipality,
                    'color' => 'text-green-400'
                ];
            }

            foreach ($messages as $msg) {

                $intelFeed->push([
                    'time' => strtoupper(
                        $signal->datetime_code
                            ?? $signal->created_at->format('dHis F Y')
                    ),
                    'type' => $msg['type'],
                    'message' => $msg['message'],
                    'color' => $msg['color'],
                ]);
            }
        }

        $intelFeed = $intelFeed->take(15);

        // THREAT FORECAST ENGINE
        $forecastThreats = (clone $query)->selectRaw("
        threat_confronted,
        COUNT(*) as total_signals,

        SUM(
            CASE
                WHEN is_watchlisted = 1 THEN 3
                ELSE 0
            END
        ) as watchlist_score,

        SUM(
            CASE
                WHEN clarity LIKE '1x%'
                  OR clarity LIKE '2x%'
                THEN 2
                ELSE 0
            END
        ) as low_clarity_score
    ")
            ->whereNotNull('threat_confronted')
            ->groupBy('threat_confronted')
            ->get()
            ->map(function ($item) {

                // PREDICTIVE SCORE
                $predictionScore =
                    ($item->total_signals * 2) +
                    $item->watchlist_score +
                    $item->low_clarity_score;

                // FORECAST STATUS
                if ($predictionScore >= 20) {

                    $forecast = 'HIGH ESCALATION RISK';
                    $color = 'bg-red-500/20 text-red-400 border-red-500/30';
                    $threatLevel = 'CRITICAL';
                } elseif ($predictionScore >= 10) {

                    $forecast = 'ELEVATED ACTIVITY';
                    $color = 'bg-orange-500/20 text-orange-400 border-orange-500/30';
                    $threatLevel = 'HIGH';
                } elseif ($predictionScore >= 5) {

                    $forecast = 'MONITOR CLOSELY';
                    $color = 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30';
                    $threatLevel = 'MODERATE';
                } else {

                    $forecast = 'LOW OPERATIONAL RISK';
                    $color = 'bg-green-500/20 text-green-400 border-green-500/30';
                    $threatLevel = 'LOW';
                }

                return [
                    'threat' => $item->threat_confronted,
                    'signals' => $item->total_signals,
                    'score' => $predictionScore,
                    'forecast' => $forecast,
                    'color' => $color,
                    'threatLevel' => $threatLevel,
                ];
            })
            ->sortByDesc('score')
            ->take(6);

        // HOT ZONE PREDICTION ENGINE
        $hotZones = (clone $query)->selectRaw("
        municipality,

        COUNT(*) as total_signals,

        SUM(
            CASE
                WHEN is_watchlisted = 1 THEN 3
                ELSE 0
            END
        ) as watchlist_score,

        SUM(
            CASE
                WHEN clarity LIKE '1x%'
                  OR clarity LIKE '2x%'
                THEN 2
                ELSE 0
            END
        ) as low_clarity_score,

        SUM(
            CASE
                WHEN threat_confronted IS NOT NULL
                THEN 2
                ELSE 0
            END
        ) as threat_score
    ")
            ->whereNotNull('municipality')
            ->groupBy('municipality')
            ->get()
            ->map(function ($zone) {

                // TOTAL RISK SCORE
                $riskScore =
                    ($zone->total_signals * 2) +
                    $zone->watchlist_score +
                    $zone->low_clarity_score +
                    $zone->threat_score;

                // RISK LEVEL
                if ($riskScore >= 20) {

                    $status = 'CRITICAL HOT ZONE';
                    $color = 'bg-red-500/20 text-red-400 border-red-500/30';
                    $level = 'CRITICAL';
                } elseif ($riskScore >= 12) {

                    $status = 'HIGH ACTIVITY';
                    $color = 'bg-orange-500/20 text-orange-400 border-orange-500/30';
                    $level = 'HIGH';
                } elseif ($riskScore >= 6) {

                    $status = 'ELEVATED MONITORING';
                    $color = 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30';
                    $level = 'MODERATE';
                } else {

                    $status = 'LOW ACTIVITY';
                    $color = 'bg-green-500/20 text-green-400 border-green-500/30';
                    $level = 'LOW';
                }

                return [
                    'municipality' => $zone->municipality,
                    'signals' => $zone->total_signals,
                    'riskScore' => $riskScore,
                    'status' => $status,
                    'color' => $color,
                    'level' => $level,
                ];
            })
            ->sortByDesc('riskScore')
            ->take(8);

        // BEHAVIORAL PATTERN ANALYSIS

        // MOST ACTIVE HOURS
        $peakHours = (clone $query)->selectRaw("
        HOUR(created_at) as hour,
        COUNT(*) as total
    ")
            ->groupBy('hour')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        // MOST ACTIVE DAYS
        $activeDays = (clone $query)->selectRaw("
        DAYNAME(created_at) as day,
        COUNT(*) as total
    ")
            ->groupBy('day')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        // WATCHLIST PATTERN
        $watchlistPattern = (clone $query)->where('is_watchlisted', 1)
            ->selectRaw("
        HOUR(created_at) as hour,
        COUNT(*) as total
    ")
            ->groupBy('hour')
            ->orderByDesc('total')
            ->first();

        // LOW CLARITY PATTERN
        $lowClarityPattern = (clone $query)->where(function ($query) {

            $query->where('clarity', 'LIKE', '1x%')
                ->orWhere('clarity', 'LIKE', '2x%');
        })
            ->selectRaw("
        HOUR(created_at) as hour,
        COUNT(*) as total
    ")
            ->groupBy('hour')
            ->orderByDesc('total')
            ->first();

        // AI THREAT SCORING ENGINE

        $aiThreatScores = (clone $query)->selectRaw("
        threat_confronted,

        COUNT(*) as total_signals,

        SUM(
            CASE
                WHEN is_watchlisted = 1 THEN 5
                ELSE 0
            END
        ) as watchlist_score,

        SUM(
            CASE
                WHEN clarity LIKE '1x%'
                  OR clarity LIKE '2x%'
                THEN 4
                ELSE 0
            END
        ) as low_clarity_score,

        COUNT(DISTINCT municipality) as geo_spread
    ")
            ->whereNotNull('threat_confronted')
            ->groupBy('threat_confronted')
            ->get()
            ->map(function ($item) {

                // CORE AI SCORE
                $score =
                    ($item->total_signals * 3) +
                    $item->watchlist_score +
                    $item->low_clarity_score +
                    ($item->geo_spread * 2);

                // NORMALIZE
                $score = min(100, $score);

                // RISK LEVEL
                if ($score >= 80) {

                    $level = 'CRITICAL';
                    $color = 'bg-red-500/20 text-red-400 border-red-500/30';
                    $bar = 'bg-red-500';
                } elseif ($score >= 60) {

                    $level = 'HIGH';
                    $color = 'bg-orange-500/20 text-orange-400 border-orange-500/30';
                    $bar = 'bg-orange-400';
                } elseif ($score >= 40) {

                    $level = 'ELEVATED';
                    $color = 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30';
                    $bar = 'bg-yellow-400';
                } elseif ($score >= 20) {

                    $level = 'MODERATE';
                    $color = 'bg-cyan-500/20 text-cyan-400 border-cyan-500/30';
                    $bar = 'bg-cyan-400';
                } else {

                    $level = 'LOW';
                    $color = 'bg-green-500/20 text-green-400 border-green-500/30';
                    $bar = 'bg-green-400';
                }

                return [
                    'threat' => $item->threat_confronted,
                    'score' => $score,
                    'level' => $level,
                    'color' => $color,
                    'bar' => $bar,
                    'signals' => $item->total_signals,
                    'spread' => $item->geo_spread,
                ];
            })
            ->sortByDesc('score')
            ->take(10);

        // AUTOMATED INTELLIGENCE SUMMARY ENGINE

        $summaryLines = collect();

        // TOP THREAT
        $topThreat = $aiThreatScores->first();

        if ($topThreat) {

            $summaryLines->push(
                "{$topThreat['threat']} remains the highest-risk monitored entity with an AI threat score of {$topThreat['score']}."
            );
        }

        // HOT ZONE
        $topZone = $hotZones->first();

        if ($topZone) {

            $summaryLines->push(
                "Operational activity concentration detected in {$topZone['municipality']} with a regional risk score of {$topZone['riskScore']}."
            );
        }

        // WATCHLIST
        if ($watchlistPattern) {

            $hour = str_pad($watchlistPattern->hour, 2, '0', STR_PAD_LEFT);

            $summaryLines->push(
                "Watchlisted transmissions peaked around {$hour}:00H operational window."
            );
        }

        // LOW CLARITY
        if ($lowClarityPattern) {

            $hour = str_pad($lowClarityPattern->hour, 2, '0', STR_PAD_LEFT);

            $summaryLines->push(
                "Repeated degraded or low-clarity transmissions detected near {$hour}:00H."
            );
        }

        // SURGE STATUS
        $summaryLines->push(
            "Current operational tempo classified as {$surgeLevel} based on live signal activity analysis."
        );

        // ACTIVE DAY
        $topDay = $activeDays->first();

        if ($topDay) {

            $summaryLines->push(
                "{$topDay->day} shows the highest observed transmission density across monitored channels."
            );
        }

        // FINAL SUMMARY
        $automatedSummary = $summaryLines;

        // TACTICAL TIMELINE ENGINE

        $tacticalTimeline = (clone $query)->latest()

            ->take(12)

            ->get()

            ->map(function ($signal) {

                $eventType = 'NORMAL';
                $eventColor = 'cyan';

                if ($signal->is_watchlisted) {

                    $eventType = 'WATCHLIST';
                    $eventColor = 'yellow';
                }

                if (
                    str_contains($signal->clarity ?? '', '1x') ||
                    str_contains($signal->clarity ?? '', '2x')
                ) {

                    $eventType = 'LOW CLARITY';
                    $eventColor = 'red';
                }

                return [

                    'time' => $signal->datetime_code
                        ? strtoupper($signal->datetime_code)
                        : strtoupper($signal->created_at->format('dHis F Y')),

                    'date' => $signal->datetime_code
                        ? strtoupper($signal->datetime_code)
                        : strtoupper($signal->created_at->format('dHis F Y')),

                    'frequency' => $signal->frequency,

                    'location' => $signal->municipality ?? 'Unknown Zone',

                    'eventType' => $eventType,

                    'eventColor' => $eventColor,

                    'threat' => $signal->threat_confronted ?? 'UNIDENTIFIED',

                    'clarity' => $signal->clarity ?? 'N/A',
                ];
            });

        // PREDICTIVE THREAT MODELING ENGINE

        $predictiveThreats = (clone $query)->select(
            'threat_confronted'
        )

            ->selectRaw('
        COUNT(*) as total_signals,

        SUM(
            CASE
                WHEN is_watchlisted = 1 THEN 1
                ELSE 0
            END
        ) as watchlisted_hits,

        SUM(
            CASE
                WHEN clarity LIKE "1x%"
                  OR clarity LIKE "2x%"
                THEN 1
                ELSE 0
            END
        ) as degraded_hits
    ')

            ->whereNotNull('threat_confronted')

            ->groupBy('threat_confronted')

            ->get()

            ->map(function ($threat) {

                $predictionScore = (
                    ($threat->total_signals * 2) +
                    ($threat->watchlisted_hits * 4) +
                    ($threat->degraded_hits * 3)
                );

                // NORMALIZE TO %
                $probability = min(100, $predictionScore * 6);

                $riskLevel = 'LOW';
                $riskColor = 'green';

                if ($probability >= 75) {

                    $riskLevel = 'CRITICAL';
                    $riskColor = 'red';
                } elseif ($probability >= 50) {

                    $riskLevel = 'HIGH';
                    $riskColor = 'orange';
                } elseif ($probability >= 30) {

                    $riskLevel = 'MODERATE';
                    $riskColor = 'yellow';
                }

                return [

                    'threat' => $threat->threat_confronted,

                    'probability' => $probability,

                    'riskLevel' => $riskLevel,

                    'riskColor' => $riskColor,

                    'signals' => $threat->total_signals,
                ];
            })

            ->sortByDesc('probability')

            ->values();

        $monthlyActivity = DB::table('frequencies')
            ->selectRaw("
        DATE_FORMAT(created_at, '%b') as month,
        COUNT(*) as total
    ")
            ->groupBy('month')
            ->orderByRaw('MIN(created_at)')
            ->get();

        $monthlyLabels = $monthlyActivity->pluck('month');

        $monthlyCounts = $monthlyActivity->pluck('total');

        // TOTAL WATCHLISTED SIGNALS
        $watchlistedCount = (clone $query)->where('is_watchlisted', 1)->count();

        $threatEntityList = (clone $query)->whereNotNull('threat_confronted')
            ->distinct()
            ->pluck('threat_confronted');

        $activeNodeList = (clone $query)->whereNotNull('municipality')
            ->distinct()
            ->pluck('municipality');

        $watchlistSignals = (clone $query)->where('is_watchlisted', 1)
            ->latest()
            ->take(10)
            ->get([
                'frequency',
                'municipality',
                'threat_confronted'
            ]);

        $hourlyActivity = (clone $query)->selectRaw("
    HOUR(created_at) as hour,
    COUNT(*) as total
")
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()

            ->map(function ($item) {

                $count = $item->total;

                if ($count >= 10) {

                    $level = 'CRITICAL';
                    $color = 'bg-red-500/20 text-red-400 border-red-500/30';
                } elseif ($count >= 6) {

                    $level = 'HIGH';
                    $color = 'bg-orange-500/20 text-orange-400 border-orange-500/30';
                } elseif ($count >= 3) {

                    $level = 'MODERATE';
                    $color = 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30';
                } else {

                    $level = 'LOW';
                    $color = 'bg-cyan-500/20 text-cyan-400 border-cyan-500/30';
                }

                return [

                    'hour' => str_pad($item->hour, 2, '0', STR_PAD_LEFT) . ':00H',

                    'count' => $count,

                    'level' => $level,

                    'color' => $color,
                ];
            });

        return view('sigint.frequency.analytics', compact(
            'monthly',
            'clarityDist',
            'origin',
            'daily',
            'threatPriority',
            'signalHeat',
            'watchlistActivity',
            'originIntel',
            'dailySignals',
            'averageSignals',
            'todaySignals',
            'surgeLevel',
            'surgeColor',
            'threatEscalation',
            'intelFeed',
            'forecastThreats',
            'hotZones',
            'peakHours',
            'activeDays',
            'watchlistPattern',
            'lowClarityPattern',
            'aiThreatScores',
            'automatedSummary',
            'tacticalTimeline',
            'predictiveThreats',
            'monthlyLabels',
            'monthlyCounts',
            'watchlistedCount',
            'threatEntityList',
            'activeNodeList',
            'watchlistSignals',
            'hourlyActivity'
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
    public function export(Request $request)
    {
        $query = Frequency::query();

        /*
    |--------------------------------------------------------------------------
    | EXPORT SELECTED IDS
    |--------------------------------------------------------------------------
    */

        if ($request->filled('ids')) {

            $ids = explode(',', $request->ids);

            $query->whereIn('id', $ids);
        }

        /*
    |--------------------------------------------------------------------------
    | OPERATOR VISIBILITY
    |--------------------------------------------------------------------------
    */

        $user = Auth::user();

        if ($user && $user->isOperator()) {

            $query->where('user_id', $user->id);
        }

        /*
    |--------------------------------------------------------------------------
    | FILTERS
    |--------------------------------------------------------------------------
    */

        if ($request->boolean('watchlisted')) {

            $query->where('is_watchlisted', 1);
        }

        if ($request->filled('q')) {

            $q = trim($request->q);

            $query->where(function ($sub) use ($q) {

                $sub->where('frequency', 'LIKE', "%{$q}%")
                    ->orWhere('datetime_code', 'LIKE', "%{$q}%")
                    ->orWhere('site_location', 'LIKE', "%{$q}%")
                    ->orWhere('barangay', 'LIKE', "%{$q}%")
                    ->orWhere('municipality', 'LIKE', "%{$q}%")
                    ->orWhere('province', 'LIKE', "%{$q}%")
                    ->orWhere('clarity', 'LIKE', "%{$q}%")
                    ->orWhere('conversation', 'LIKE', "%{$q}%")
                    ->orWhere('threat_confronted', 'LIKE', "%{$q}%");
            });
        }

        if ($request->filled('month')) {

            $query->where(
                'datetime_code',
                'LIKE',
                "%{$request->month}%"
            );
        }

        if ($request->filled('year')) {

            $query->where(
                'datetime_code',
                'LIKE',
                "%{$request->year}%"
            );
        }

        /*
    |--------------------------------------------------------------------------
    | FINAL RECORDS
    |--------------------------------------------------------------------------
    */

        $records = $query->latest()->get();

        Audit::log(
            'SIGINT',
            'EXPORT',
            'Frequency',
            null,
            'Exported Intelligence Excel'
        );

        return Excel::download(
            new FrequenciesExport($records),
            'SIGINT_Intelligence_Report.xlsx'
        );
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

        /*
    |--------------------------------------------------------------------------
    | EXPORT SELECTED IDS
    |--------------------------------------------------------------------------
    */

        if ($request->filled('ids')) {

            $ids = explode(',', $request->ids);

            $query->whereIn('id', $ids);
        }

        /*
    |--------------------------------------------------------------------------
    | OPERATOR VISIBILITY
    |--------------------------------------------------------------------------
    */

        $user = Auth::user();

        if ($user && $user->isOperator()) {

            $query->where('user_id', $user->id);
        }

        /*
    |--------------------------------------------------------------------------
    | FILTERS
    |--------------------------------------------------------------------------
    */

        if ($request->boolean('watchlisted')) {

            $query->where('is_watchlisted', 1);
        }

        if ($request->filled('q')) {

            $q = trim($request->q);

            $query->where(function ($sub) use ($q) {

                $sub->where('frequency', 'LIKE', "%{$q}%")
                    ->orWhere('datetime_code', 'LIKE', "%{$q}%")
                    ->orWhere('site_location', 'LIKE', "%{$q}%")
                    ->orWhere('barangay', 'LIKE', "%{$q}%")
                    ->orWhere('municipality', 'LIKE', "%{$q}%")
                    ->orWhere('province', 'LIKE', "%{$q}%")
                    ->orWhere('clarity', 'LIKE', "%{$q}%")
                    ->orWhere('conversation', 'LIKE', "%{$q}%")
                    ->orWhere('threat_confronted', 'LIKE', "%{$q}%");
            });
        }

        if ($request->filled('month')) {

            $query->where(
                'datetime_code',
                'LIKE',
                "%{$request->month}%"
            );
        }

        if ($request->filled('year')) {

            $query->where(
                'datetime_code',
                'LIKE',
                "%{$request->year}%"
            );
        }

        /*
    |--------------------------------------------------------------------------
    | FINAL RECORDS
    |--------------------------------------------------------------------------
    */

        $frequencies = $query->latest()->get();

        /*
    |--------------------------------------------------------------------------
    | REPORT ID
    |--------------------------------------------------------------------------
    */

        $reportId = 'SIGINT-' . strtoupper(Str::random(8));

        /*
    |--------------------------------------------------------------------------
    | THREAT SUMMARY
    |--------------------------------------------------------------------------
    */

        $watchlisted = $frequencies->where('is_watchlisted', 1)->count();

        $topThreat = $frequencies
            ->groupBy('threat_confronted')
            ->map->count()
            ->sortDesc()
            ->keys()
            ->first();

        /*
    |--------------------------------------------------------------------------
    | ACTIVITY LOG
    |--------------------------------------------------------------------------
    */

        Audit::log(
            'SIGINT',
            'EXPORT_PDF',
            'Frequency',
            null,
            "Generated Intelligence Brief {$reportId}"
        );

        /*
    |--------------------------------------------------------------------------
    | PDF
    |--------------------------------------------------------------------------
    */
        $criticalCount = $frequencies
            ->where('clarity', 'LIKE', '%5%')
            ->count();

        $watchlistCount = $frequencies
            ->where('is_watchlisted', 1)
            ->count();

        $topLocation = $frequencies
            ->groupBy('municipality')
            ->map->count()
            ->sortDesc()
            ->keys()
            ->first();

        $executiveSummary = "

Recent SIGINT monitoring detected {$frequencies->count()} active signal intercepts.

Operational analysis identified {$watchlistCount} watchlisted transmissions requiring enhanced monitoring.

Highest activity concentration was observed near {$topLocation}.

Signal clarity assessment detected {$criticalCount} high-confidence intercepts suitable for tactical exploitation.

Threat activity remains associated primarily with {$topThreat} operational patterns.

Continued surveillance and frequency monitoring is strongly recommended.

";

        $pdf = Pdf::loadView(
            'sigint.frequency.brief',
            compact(
                'frequencies',
                'reportId',
                'watchlisted',
                'topThreat',
                'executiveSummary'
            )
        );

        return $pdf
            ->setPaper('a4', 'landscape')
            ->download("Intel_Brief_{$reportId}.pdf");
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
