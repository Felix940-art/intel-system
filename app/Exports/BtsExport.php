<?php

namespace App\Exports;

use App\Models\Bts;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BtsExport implements FromCollection, WithHeadings
{
    /**
     * Export all BTS records.
     */
    public function collection()
    {
        return Bts::select(
            'name',
            'mgrs_location',
            'network',
            'network_mode',
            'lac',
            'cid',
            'neighbor_cid',
            'barangay',
            'municipality',
            'province'
        )->get();
    }


    /**
     * Excel column headings.
     */
    public function headings(): array
    {
        return [
            'BTS Name',
            'MGRS Location',
            'Network',
            'Network Mode',
            'LAC',
            'CID',
            'Neighbor CID',
            'Barangay',
            'Municipality',
            'Province',
        ];
    }
}
