<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FrequenciesExport implements FromCollection, WithHeadings
{
    protected $records;

    public function __construct($records)
    {
        $this->records = $records;
    }

    public function collection()
    {
        return $this->records->map(function ($f) {

            return [

                'Frequency' => $f->frequency,

                'Date & Time' => $f->datetime_code,

                'Site Location' => collect([
                    $f->barangay,
                    $f->municipality,
                    $f->province
                ])->filter()->implode(', '),

                'LOB' => $f->lob
                    ? $f->lob . '°'
                    : 'N/A',

                'Possible Origin' =>
                $f->site_location,

                'Clarity' =>
                $f->clarity,

                'Threat Group' =>
                $f->threat_confronted,

                'Watchlisted' =>
                $f->is_watchlisted
                    ? 'YES'
                    : 'NO',

                'Conversation' =>
                strip_tags($f->conversation),
            ];
        });
    }

    public function headings(): array
    {
        return [

            'Frequency',
            'Date & Time',
            'Site Location',
            'LOB',
            'Possible Origin',
            'Clarity',
            'Threat Group',
            'Watchlisted',
            'Conversation',

        ];
    }
}
