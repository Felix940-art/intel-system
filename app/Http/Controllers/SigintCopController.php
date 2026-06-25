<?php

namespace App\Http\Controllers;

use App\Models\Frequency;
use App\Models\Bts;
use App\Models\SreEvent;
use App\Models\GeoInt;
use App\Models\ForensicReport;
use Illuminate\Http\Request;


class SigintCopController extends Controller
{
    public function index()
    {

        $btsMarkers = Bts::all()->map(function ($site) {

            $targets = SreEvent::where('lac', $site->lac)
                ->where('cid', $site->cid)
                ->get();

            /*
            |--------------------------------------------------------------------------
            | RADIO FREQUENCY FUSION
            |--------------------------------------------------------------------------
            */

            $frequencyHits = Frequency::where(
                'municipality',
                $site->municipality
            )->get();

            /*
            |--------------------------------------------------------------------------
            | GEOINT FUSION
            |--------------------------------------------------------------------------
            */

            $geoHits = GeoInt::where(
                'threat_confronted',
                'LIKE',
                '%' . $site->municipality . '%'
            )->get();

            /*
            |--------------------------------------------------------------------------
            | DIGITAL FORENSICS
            |--------------------------------------------------------------------------
            */

            $forensicHits = ForensicReport::where(
                'location',
                'LIKE',
                '%' . $site->municipality . '%'
            )->get();


            /*
            |--------------------------------------------------------------------------
            | INTELLIGENCE FUSION SCORE
            |--------------------------------------------------------------------------
            */

            $fusionScore = 0;

            /* SRE */

            $fusionScore += $targets->count() * 20;

            /* Watchlisted Frequencies */

            $fusionScore += $frequencyHits
                ->where('is_watchlisted', true)
                ->count() * 10;

            /* GEOINT */

            $fusionScore += $geoHits->count() * 15;

            /* DIGITAL FORENSICS */

            $fusionScore += $forensicHits->count() * 10;


            /*
            |--------------------------------------------------------------------------
            | FUSION THREAT LEVEL
            |--------------------------------------------------------------------------
            */

            if ($fusionScore >= 120) {

                $fusionThreat = 'CRITICAL';
            } elseif ($fusionScore >= 80) {

                $fusionThreat = 'HIGH';
            } elseif ($fusionScore >= 40) {

                $fusionThreat = 'MEDIUM';
            } else {

                $fusionThreat = 'LOW';
            }

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

                'frequencies' => $frequencyHits->values(),
                'geoint' => $geoHits->values(),
                'forensics' => $forensicHits->values(),
                'fusion_score' => $fusionScore,
                'fusion_threat' => $fusionThreat,

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
