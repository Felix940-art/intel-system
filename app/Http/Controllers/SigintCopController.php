<?php

namespace App\Http\Controllers;

use App\Models\Frequency;
use App\Models\Bts;
use Illuminate\Http\Request;
use App\Models\SreEvent;

class SigintCopController extends Controller
{
    public function index()
    {

        $btsMarkers = Bts::all()->map(function ($site) {

            $targets = SreEvent::where('lac', $site->lac)
                ->where('cid', $site->cid)
                ->get();

            return [

                'name' => $site->name,
                'network' => $site->network,
                'mode' => $site->network_mode,

                'lac' => $site->lac,
                'cid' => $site->cid,
                'neighbor_cid' => $site->neighbor_cid,

                'target_count' => $targets->count(),

                'threat_level' => $targets->contains(function ($t) {

                    return in_array($t->threat_group, [
                        'SRMA LEVOX',
                        'SRMA ARCTIC',
                        'SRMA',
                        'SROC',
                    ]);
                }) ? 'HIGH' : ($targets->count() ? 'MEDIUM' : 'LOW'),

                'mgrs' => $site->mgrs_location,

                'targets' => $targets->map(function ($target) {

                    return [
                        'code_name' => $target->code_name,
                        'threat_group' => $target->threat_group,
                        'imei' => $target->imei,
                        'imsi' => $target->imsi,
                    ];
                })->values(),


            ];
        });

        $frequencies = Frequency::all();

        $timelineEvents = SreEvent::latest('observed_at')
            ->take(20)
            ->get();

        $frequencyLobs = [];

        foreach ($frequencies as $freq) {

            if (!isset($municipalityCoordinates[$freq->municipality])) {
                continue;
                \Log::info($freq->municipality);
            }

            $frequencyLobs[] = [
                'frequency'    => $freq->frequency,
                'lob'          => $freq->lob,
                'municipality' => $freq->municipality,
                'barangay'     => $freq->barangay,
                'watchlisted'  => $freq->is_watchlisted,
                'conversation' => $freq->conversation,
                'clarity'      => $freq->clarity,
                'datetime'     => $freq->datetime_code,

                'lat' => $municipalityCoordinates[$freq->municipality]['lat'],
                'lng' => $municipalityCoordinates[$freq->municipality]['lng'],
            ];
        }

        return view('sigint.cop.index', [
            'btsMarkers' => $btsMarkers,
            'frequencyLobs' => $frequencyLobs,
            'timelineEvents' => $timelineEvents,
        ]);
    }
}
