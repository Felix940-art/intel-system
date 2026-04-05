<?php

namespace App\Http\Controllers;

use App\Models\SreSelector;
use App\Models\SreEvent;
use Illuminate\Http\Request;

class SreDashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = SreEvent::with('selector');

        // SEARCH
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('imei', 'like', "%$search%")
                    ->orWhere('imsi', 'like', "%$search%")
                    ->orWhereHas('selector', function ($sq) use ($search) {
                        $sq->where('selector_value', 'like', "%$search%")
                            ->orWhere('code_name', 'like', "%$search%");
                    });
            });
        }

        // THREAT FILTER
        if ($request->filled('threat')) {
            $query->whereHas('selector', function ($q) use ($request) {
                $q->where('threat_group', $request->threat);
            });
        }

        // DATE FILTER
        if ($request->filled('date')) {
            $query->whereDate('observed_at', $request->date);
        }

        $events = $query
            ->orderBy('observed_at', 'desc')
            ->paginate(10);

        return view('sigint.sre.dashboard', [
            'events' => $events,
            'threats' => [
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
                'FUNCTIONAL',
                'UNKNOWN'
            ],
        ]);
    }
}
