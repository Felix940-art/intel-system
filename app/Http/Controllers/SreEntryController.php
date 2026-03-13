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
            'lac'          => ['nullable', 'digits:5'],
            'cid'          => ['nullable', 'digits:5'],
            'threat_group' => ['nullable', 'string'],
            'code_name'    => ['nullable', 'string', 'max:100'],
            'remarks'      => ['nullable', 'string'],
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
            'lac'           => ['nullable', 'digits:5'],
            'cid'           => ['nullable', 'digits:5'],
            'threat_group'  => ['nullable', 'string'],
            'code_name'     => ['nullable', 'string', 'max:100'],
        ]);

        // Update selector
        $event->selector->update([
            'selector_value' => $validated['selector'],
            'threat_group'   => $validated['threat_group'] ?? null,
            'code_name'      => $validated['code_name'] ?? null,
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
