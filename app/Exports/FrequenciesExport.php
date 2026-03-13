<?php

namespace App\Exports;

use App\Models\Frequency;
use Maatwebsite\Excel\Concerns\FromCollection;

class FrequenciesExport implements FromCollection
{
    public function collection()
    {
        return Frequency::all();
    }
}
