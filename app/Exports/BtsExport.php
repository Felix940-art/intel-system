<?php

namespace App\Exports;

use App\Models\Bts;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class BtsExport implements FromView, ShouldAutoSize, WithStyles
{
    public function view(): View
    {
        $btsRecords = Bts::orderBy('name')->get();

        // Intelligence Summary
        $totalBts = Bts::count();

        $smart = Bts::where('network', 'SMART')->count();
        $globe = Bts::where('network', 'GLOBE')->count();
        $tm = Bts::where('network', 'TM')->count();

        $twoG = Bts::where('network_mode', '2G')->count();
        $threeG = Bts::where('network_mode', '3G')->count();
        $fourG = Bts::where('network_mode', '4G LTE')->count();
        $fiveG = Bts::where('network_mode', '5G')->count();

        return view('exports.bts_report', [
            'btsRecords' => $btsRecords,

            'totalBts' => $totalBts,

            'smart' => $smart,
            'globe' => $globe,
            'tm' => $tm,

            'twoG' => $twoG,
            'threeG' => $threeG,
            'fourG' => $fourG,
            'fiveG' => $fiveG,
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();

        /*
    |--------------------------------------------------------------------------
    | COMMAND HEADER
    |--------------------------------------------------------------------------
    */

        $sheet->mergeCells('A1:J1');
        $sheet->mergeCells('A2:J2');
        $sheet->mergeCells('A3:J3');
        $sheet->mergeCells('A4:J4');

        $sheet->getStyle('A1:J4')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => [
                    'rgb' => 'FFFFFF'
                ]
            ],

            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],

            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => [
                    'rgb' => '081426'
                ]
            ]
        ]);


        // Better header height
        foreach ([1, 2, 3, 4] as $row) {
            $sheet->getRowDimension($row)->setRowHeight(25);
        }


        /*
    |--------------------------------------------------------------------------
    | REPORT IDENTIFICATION
    |--------------------------------------------------------------------------
    */

        $sheet->mergeCells('A5:J5');

        $sheet->getStyle('A5:J5')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
                'color' => [
                    'rgb' => 'FFFFFF'
                ]
            ],

            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT
            ],

            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => [
                    'rgb' => '1E293B'
                ]
            ]
        ]);


        /*
    |--------------------------------------------------------------------------
    | INTELLIGENCE SECTION TITLES
    |--------------------------------------------------------------------------
    */

        foreach (['A11:B11', 'A17:B17', 'A24:J24'] as $section) {

            $sheet->getStyle($section)->applyFromArray([

                'font' => [
                    'bold' => true,
                    'color' => [
                        'rgb' => 'FFFFFF'
                    ]
                ],

                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => [
                        'rgb' => '1E293B'
                    ]
                ]
            ]);
        }


        /*
    |--------------------------------------------------------------------------
    | DATABASE TABLE HEADER
    |--------------------------------------------------------------------------
    */

        $sheet->getStyle('A25:J25')->applyFromArray([

            'font' => [
                'bold' => true,
                'color' => [
                    'rgb' => 'FFFFFF'
                ]
            ],

            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ],

            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => [
                    'rgb' => '0F172A'
                ]
            ]
        ]);


        /*
    |--------------------------------------------------------------------------
    | DATABASE DATA ALIGNMENT
    |--------------------------------------------------------------------------
    */

        $sheet->getStyle("A26:J{$lastRow}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);


        /*
    |--------------------------------------------------------------------------
    | PROFESSIONAL BORDERS
    |--------------------------------------------------------------------------
    */

        $sheet->getStyle("A25:J{$lastRow}")
            ->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => [
                            'rgb' => '334155'
                        ]
                    ]
                ]
            ]);


        /*
    |--------------------------------------------------------------------------
    | GLOBAL ALIGNMENT
    |--------------------------------------------------------------------------
    */

        $sheet->getStyle("A1:J{$lastRow}")
            ->getAlignment()
            ->setVertical(Alignment::VERTICAL_CENTER);


        /*
    |--------------------------------------------------------------------------
    | Freeze Intelligence Header
    |--------------------------------------------------------------------------
    */

        $sheet->freezePane('A6');


        /*
|--------------------------------------------------------------------------
| INTELLIGENCE FOOTER
|--------------------------------------------------------------------------
*/

        $footerStart = $lastRow + 3;

        $sheet->mergeCells("A{$footerStart}:J{$footerStart}");

        $sheet->getStyle("A{$footerStart}:J{$footerStart}")
            ->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => [
                        'rgb' => 'FFFFFF'
                    ]
                ],

                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ],

                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => [
                        'rgb' => '7F1D1D'
                    ]
                ]
            ]);
    }
}
