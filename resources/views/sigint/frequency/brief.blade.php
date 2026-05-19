<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <style>
        body {
            font-family: DejaVu Sans;
            background: #071122;
            color: white;
            padding: 18px;
            font-size: 12px;
        }

        .header {

            border-left: 4px solid #00d2ff;

            background: linear-gradient(135deg,
                    #071a2f 0%,
                    #081f3d 50%,
                    #071122 100%);

            border: 1px solid #12345c;

            padding: 22px 28px;

            border-radius: 14px;

            margin-bottom: 14px;

            box-shadow:
                0 0 30px rgba(0, 210, 255, 0.08);

            position: relative;

            overflow: hidden;

            box-sizing: border-box;
        }

        .header table td {

            vertical-align: top;
        }

        .header::after {

            content: '';

            position: absolute;

            top: -80px;
            right: -80px;

            width: 220px;
            height: 220px;

            background: radial-gradient(rgba(0, 210, 255, 0.15),
                    transparent 70%);
        }

        .title {
            font-size: 26px;
            font-weight: bold;
            color: #00d2ff;
        }

        .subtitle {
            color: #94a3b8;
            margin-top: 4px;
        }

        .meta {
            margin-top: 12px;
            line-height: 1.7;
        }

        .summary-box {

            background: #0f172a;

            border: 1px solid #1e293b;

            padding: 14px;

            margin-bottom: 10px;

            border-radius: 10px;
        }

        .seal-wrapper {

            width: 100px;
            height: 100px;

            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            background:
                radial-gradient(circle,
                    rgba(0, 210, 255, 0.12),
                    rgba(0, 0, 0, 0));

            border: 1px solid rgba(0, 210, 255, 0.12);

            box-shadow:
                0 0 12px rgba(0, 210, 255, 0.08);

            padding: 10px;
        }

        .seal {

            width: 88px;
            height: 88px;

            object-fit: contain;
        }

        .title {

            font-size: 30px;

            text-transform: uppercase;

            font-weight: bold;

            letter-spacing: 1px;

            color: #16d9ff;

            margin-bottom: 8px;
        }

        .subtitle {

            color: #9fb3c8;

            font-size: 15px;

            margin-bottom: 22px;
        }

        .meta {

            color: #d7e3f1;

            font-size: 13px;

            line-height: 1.4;
        }

        .meta-item {

            margin-bottom: 12px;
        }

        .summary-title {
            color: #38bdf8;
            font-size: 16px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #0f172a;
            color: #38bdf8;
            padding: 10px;
            border: 1px solid #1e293b;
            text-align: left;
        }

        td {
            padding: 10px;
            border: 1px solid #1e293b;
            vertical-align: top;
        }

        .watch {
            color: #facc15;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            font-size: 10px;
            color: #64748b;
            text-align: right;
        }
    </style>
</head>

<body>

    <div class="header">

        <table width="100%" cellspacing="0">

            <div class="title">
                SIGINT INTELLIGENCE BRIEF
            </div>

            <div class="subtitle">
                Radio Frequency Operational Summary
            </div>

            <div class="meta-item">
                <strong>Report ID:</strong>
                {{ $reportId }}
            </div>

            <div class="meta-item">
                <strong>Generated:</strong>
                {{ now()->format('F d, Y H:i:s') }}
            </div>

            <div class="meta-item">
                <strong>Total Signals:</strong>
                {{ $frequencies->count() }}
            </div>

        </table>

    </div>

    <div class="summary-box">

        <div class="summary-title">
            Operational Assessment
        </div>

        <p>
            Intelligence extraction detected
            <strong>{{ $watchlisted }}</strong>
            watchlisted transmissions.

            Most active monitored threat entity:
            <strong>{{ $topThreat ?? 'N/A' }}</strong>.
        </p>

    </div>

    <div class="summary-box">

        <div class="summary-title">
            Executive Summary
        </div>

        <p style="line-height: 1.8;">
            {{ $executiveSummary }}
        </p>

    </div>

    <table>

        <thead>

            <tr>
                <th>Frequency</th>
                <th>Date & Time</th>
                <th>Location</th>
                <th>Threat</th>
                <th>Clarity</th>
                <th>Status</th>
            </tr>

        </thead>

        <tbody>

            @foreach($frequencies as $signal)

            <tr>

                <td>
                    {{ $signal->frequency }}
                </td>

                <td>
                    {{ $signal->datetime_code }}
                </td>

                <td>
                    {{ $signal->barangay }},
                    {{ $signal->municipality }},
                    {{ $signal->province }}
                </td>

                <td>
                    {{ $signal->threat_confronted ?? 'UNKNOWN' }}
                </td>

                <td>
                    {{ $signal->clarity }}
                </td>

                <td>

                    @if($signal->is_watchlisted)

                    <span class="watch">
                        WATCHLISTED
                    </span>

                    @else

                    NORMAL

                    @endif

                </td>

            </tr>

            @endforeach

        </tbody>

    </table>

    <div style="margin-top:25px; width:100%;">

        <table width="100%">

            <tr>

                <td width="50%">

                    <div style="
                    border-top:1px solid #64748b;
                    width:260px;
                    padding-top:8px;
                    color:#cbd5e1;
                ">

                        Prepared By:

                        <br><br>

                        <strong>
                            {{ auth()->user()->name ?? 'System Analyst' }}
                        </strong>

                        <br>

                        Intelligence Analyst

                    </div>

                </td>

                <td align="right">

                    <div style="
                    border-top:1px solid #64748b;
                    width:260px;
                    padding-top:8px;
                    color:#cbd5e1;
                ">

                        Approved By:

                        <br><br>

                        ____________________

                        <br>

                        Commanding Officer

                    </div>

                </td>

            </tr>

        </table>

    </div>

    <div class="footer">

        Generated by SIGINT Intelligence System

    </div>

</body>

</html>