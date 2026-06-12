<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SreEvent;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class SreExportController extends Controller
{
    public function excel(Request $request)
    {
        $events = $this->buildQuery($request)->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        /*
    |--------------------------------------------------------------------------
    | Format columns as TEXT
    |--------------------------------------------------------------------------
    */
        $sheet->getStyle('C:C')
            ->getNumberFormat()
            ->setFormatCode(NumberFormat::FORMAT_TEXT);

        $sheet->getStyle('D:D')
            ->getNumberFormat()
            ->setFormatCode(NumberFormat::FORMAT_TEXT);

        $sheet->getStyle('E:E')
            ->getNumberFormat()
            ->setFormatCode(NumberFormat::FORMAT_TEXT);

        /*
    |--------------------------------------------------------------------------
    | Header Row
    |--------------------------------------------------------------------------
    */
        $sheet->fromArray([
            [
                'Date / Time',
                'Code Name',
                'Selector',
                'IMEI',
                'IMSI',
                'LAC',
                'CID',
                'BTS',
                'Threat'
            ]
        ]);

        // Make header bold
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);

        $sheet->getStyle('A1:I1')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'D9D9D9',
                ],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' =>
                    \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);

        // Center header
        $sheet->getStyle('A1:I1')->getAlignment()->setHorizontal(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
        );

        $row = 2;

        foreach ($events as $event) {

            $sheet->fromArray([
                [
                    optional($event->observed_at)->format('M d, Y H:i'),

                    // Code Name
                    $event->code_name ?? '',

                    // Selector
                    $event->selector->selector_value ?? '',

                    // IMEI
                    $event->imei ?? '',

                    // IMSI
                    $event->imsi ?? '',

                    // LAC
                    $event->lac,

                    // CID
                    $event->cid,

                    // BTS
                    $event->bts_location,

                    // Threat
                    $event->threat_group ?? '',
                ]
            ], null, "A{$row}");

            $row++;
        }

        $lastRow = $row - 1;

        $sheet->getStyle("A1:I{$lastRow}")
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(
                \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
            );

        $sheet->getStyle("A1:I{$lastRow}")
            ->getAlignment()
            ->setVertical(
                \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            );

        foreach (['A', 'F', 'G', 'I'] as $col) {
            $sheet->getStyle("{$col}1:{$col}{$lastRow}")
                ->getAlignment()
                ->setHorizontal(
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
                );
        }
        /*
    |--------------------------------------------------------------------------
    | Auto-size columns
    |--------------------------------------------------------------------------
    */
        foreach (range('A', 'I') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        /*
    |--------------------------------------------------------------------------
    | Freeze header row
    |--------------------------------------------------------------------------
    */
        $sheet->freezePane('A2');

        $writer = new Xlsx($spreadsheet);

        $filename = 'SRE_Report_' . now()->format('Ymd_His') . '.xlsx';

        return response()->streamDownload(
            function () use ($writer) {
                $writer->save('php://output');
            },
            $filename,
            [
                'Content-Type' =>
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ]
        );
    }

    public function pdf(Request $request)
    {
        $events = $this->buildQuery($request)->get();

        $pdf = Pdf::loadView(
            'sigint.sre.pdf',
            compact('events')
        )->setPaper('a4', 'landscape');

        return $pdf->download(
            'SRE_Report_' . now()->format('Ymd_His') . '.pdf'
        );
    }

    private function buildQuery(Request $request)
    {
        $query = SreEvent::with('selector');

        // SEARCH
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('imei', 'like', "%{$search}%")
                    ->orWhere('imsi', 'like', "%{$search}%")
                    ->orWhere('lac', 'like', "%{$search}%")
                    ->orWhere('cid', 'like', "%{$search}%")
                    ->orWhereHas('selector', function ($sq) use ($search) {
                        $sq->where('selector_value', 'like', "%{$search}%")
                            ->orWhere('code_name', 'like', "%{$search}%");
                    });
            });
        }

        // THREAT
        if ($request->filled('threat')) {
            $query->whereHas('selector', function ($q) use ($request) {
                $q->where('threat_group', $request->threat);
            });
        }

        // DATE
        if ($request->filled('date')) {
            $query->whereDate('observed_at', $request->date);
        }

        return $query->orderBy('updated_at', 'desc');
    }
}
