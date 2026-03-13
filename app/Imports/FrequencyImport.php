<?php

namespace App\Imports;

use App\Models\Frequency;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FrequencyImport implements ToModel, WithHeadingRow
{
    use Importable;

    public function model(array $row)
    {
        // adjust headings to your Excel column names (lowercase)
        return new Frequency([
            'frequency' => $row['frequency'] ?? $row['frequency_mhz'] ?? null,
            'datetime_code' => $row['datetime_code'] ?? $row['datetime'] ?? null,
            'conversation' => $row['conversation'] ?? null,
            'clarity' => $row['clarity'] ?? null,
            'lob' => $row['lob'] ?? null,
            'origin' => $row['origin'] ?? null,
        ]);
    }
}
