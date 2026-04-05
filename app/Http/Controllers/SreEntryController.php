<?php

namespace App\Http\Controllers;

use App\Models\SreSelector;
use App\Models\SreEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SreEntryController extends Controller
{
    private array $threats = [
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
        'UNKNOWN',
    ];

    public function create()
    {
        return view('sigint.sre.create', [
            'threats' => $this->threats
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'selector'     => ['required', 'regex:/^\+63\d{10}$/'],
            'observed_at'  => ['required', 'date'],
            'imei'         => ['nullable', 'digits:15'],
            'imsi'         => ['nullable', 'digits:15'],
            'lac'          => ['nullable', 'numeric'],
            'cid'          => ['nullable', 'numeric'],
            'threat_group' => ['nullable', 'string', 'max:100'],
            'code_name'    => ['nullable', 'string', 'max:100'],
            'remarks'      => ['nullable', 'string', 'max:500'],
            'bts_location' => ['nullable', 'string', 'max:255'],
            'bts_lat'      => ['nullable', 'numeric', 'between:-90,90', 'required_with:bts_lng'],
            'bts_lng'      => ['nullable', 'numeric', 'between:-180,180', 'required_with:bts_lat'],
        ]);

        $selector = SreSelector::firstOrCreate(
            [
                'selector_value' => $validated['selector'],
            ],
            [
                'selector_type' => 'MSISDN',
                'user_id' => Auth::id(),
                'threat_group' => $validated['threat_group'] ?? null,
                'code_name' => $validated['code_name'] ?? null,
            ]
        );

        SreEvent::create([
            'sre_selector_id' => $selector->id,
            'observed_at' => $validated['observed_at'],
            'imei' => $validated['imei'] ?? null,
            'imsi' => $validated['imsi'] ?? null,
            'lac' => $validated['lac'] ?? null,
            'cid' => $validated['cid'] ?? null,
            'bts_location' => $validated['bts_location'] ?? null,
            'bts_lat'      => $validated['bts_lat'] ?? null,
            'bts_lng'      => $validated['bts_lng'] ?? null,
        ]);

        return redirect()
            ->route('sigint.sre.index')
            ->with('success', 'SRE entry recorded successfully.');
    }

    public function edit(SreEvent $event)
    {
        $event->load('selector');
        return view('sigint.sre.edit', compact('event'));
    }

    public function update(Request $request, SreEvent $event)
    {
        $validated = $request->validate([
            'selector'      => ['required', 'regex:/^\+63\d{10}$/'],
            'observed_at'   => ['required', 'date'],
            'imei'          => ['nullable', 'digits:15'],
            'imsi'          => ['nullable', 'digits:15'],
            'lac'          => ['nullable', 'numeric'],
            'cid'          => ['nullable', 'numeric'],
            'threat_group'  => ['nullable', 'string'],
            'code_name'     => ['nullable', 'string', 'max:100'],
            'bts_location' => ['nullable', 'string'],
            'bts_lat'      => ['nullable', 'numeric'],
            'bts_lng'      => ['nullable', 'numeric'],
        ]);

        // Update selector
        $event->selector->update([
            'selector_value' => $validated['selector'],
            'threat_group'   => $validated['threat_group'] ?? null,
            'code_name'      => $validated['code_name'] ?? null,
            'bts_location' => $validated['bts_location'] ?? null,
            'bts_lat'      => $validated['bts_lat'] ?? null,
            'bts_lng'      => $validated['bts_lng'] ?? null,
        ]);

        // Update event
        $event->update([
            'observed_at' => $validated['observed_at'],
            'imei'        => $validated['imei'] ?? null,
            'imsi'        => $validated['imsi'] ?? null,
            'lac'         => $validated['lac'] ?? null,
            'cid'         => $validated['cid'] ?? null,
        ]);

        return redirect()
            ->route('sigint.sre.index')
            ->with('success', 'SRE record updated successfully.');
    }
}
