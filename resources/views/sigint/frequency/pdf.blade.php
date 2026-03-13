<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>8MIB – Frequency Intelligence Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 25px;
            font-size: 12px;
        }

        /* Watermark */
        .watermark {
            position: fixed;
            top: 35%;
            left: 12%;
            opacity: 0.07;
            font-size: 120px;
            transform: rotate(-30deg);
            z-index: -1;
        }

        /* Cover page */
        .cover {
            text-align: center;
            padding-top: 120px;
            page-break-after: always;
        }

        .seal {
            width: 160px;
            margin-bottom: 20px;
        }

        h1 {
            margin: 0;
            font-size: 28px;
        }

        .subtitle {
            font-size: 14px;
            margin-top: 8px;
            color: #333;
        }

        .meta {
            margin-top: 30px;
            font-size: 13px;
        }

        .qr {
            margin-top: 30px;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #444;
            padding: 5px;
            font-size: 11px;
        }

        th {
            background: #e6e6e6;
            text-align: left;
        }

        /* Signatures */
        .sign-section {
            margin-top: 40px;
            width: 100%;
            text-align: center;
        }

        .sig-block {
            width: 45%;
            display: inline-block;
            margin-top: 40px;
        }

        .line {
            width: 80%;
            border-top: 1px solid #000;
            margin: 0 auto 5px auto;
            padding-top: 5px;
        }

        footer {
            position: fixed;
            bottom: -5px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>

<body>

    <div class="watermark">CONFIDENTIAL</div>

    {{-- ================= COVER PAGE ================= --}}
    <div class="cover">
        <img src="{{ $logo }}" class="seal">

        <h1>8MIB – Frequency Intelligence Report</h1>
        <p class="subtitle">Signals Intelligence Division · Philippine Army</p>

        <div class="meta">
            <strong>Generated:</strong> {{ $generated_at }}<br>
            <strong>Total Records:</strong> {{ count($frequencies) }}<br>
            <strong>Report ID:</strong> {{ $report_id }}
        </div>

    </div>


    {{-- ================= TABLE SECTION ================= --}}
    <h2 style="margin: 10px 0;">Frequency Database</h2>

    @php
    use Illuminate\Support\Str;
    @endphp

    <table>
        <thead>
            <tr>
                <th>Frequency</th>
                <th>Date & Time</th>
                <th>Conversation</th>
                <th>Clarity</th>
                <th>LOB</th>
                <th>Barangay</th>
                <th>Municipality</th>
                <th>Province</th>
            </tr>
        </thead>

        <tbody>
            @foreach($frequencies as $f)
            <tr>
                <td>{{ $f->frequency }}</td>
                <td>{{ $f->datetime_code }}</td>
                <td>{{ Str::limit($f->conversation, 60) }}</td>
                <td>{{ $f->clarity }}</td>
                <td>{{ $f->lob }}</td>
                <td>{{ $f->barangay }}</td>
                <td>{{ $f->municipality }}</td>
                <td>{{ $f->province }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>


    {{-- ================= SIGNATURES ================= --}}
    <div class="sign-section">
        <div class="sig-block">
            <div class="line"></div>
            <strong>Prepared by</strong><br>
            Intelligence NCO
        </div>

        <div class="sig-block">
            <div class="line"></div>
            <strong>Approved by</strong><br>
            Chief, SIGINT Section
        </div>
    </div>


    <footer>
        Classified Document • SIGINT Division — Page <span class="pagenum"></span>
    </footer>
    <p><strong>Assessment:</strong></p>
    <p>{{ $f->analysis_summary }}</p>

    <p class="text-sm text-gray-600">
        {{ $f->getTimelineSummary() }}
    </p>


    {{-- PAGE NUMBER SCRIPT --}}
    <script type="text/php">
        if (isset($pdf)) {
    $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
    $font = $fontMetrics->get_font("DejaVu Sans", "normal");
    $pdf->page_text(500, 820, $text, $font, 9);
}
</script>

</body>

</html>