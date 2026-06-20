<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">

    <style>
        .database-title {
            background: #081426;
            color: white;
            font-size: 12px;
            font-weight: bold;
            padding: 7px;
            margin-top: 10px;
            text-align: center;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #222;
        }

        .header {
            background: #081426;
            color: white;
            text-align: center;
            padding: 15px;
            margin-bottom: 15px;
        }

        .header .title {
            font-size: 20px;
            font-weight: bold;
        }

        .header .division {
            font-size: 12px;
            margin-top: 4px;
        }

        .header .classification {
            margin-top: 8px;
            font-size: 11px;
            font-weight: bold;
            color: #ff5555;
        }

        .report-info {
            width: 100%;
            margin-bottom: 15px;
            border: 1px solid #222;
            border-collapse: collapse;
        }

        .report-info th {
            background: #1E293B;
            color: white;
            text-align: left;
            padding: 6px;
        }

        .report-info td {
            padding: 5px;
            border: 1px solid #444;
            font-size: 10px;
        }

        /* Intelligence Cards */

        .stats {
            width: 100%;
            margin-bottom: 15px;
        }

        .stats td {
            width: 25%;
            border: 1px solid #444;
            text-align: center;
            padding: 8px;
            font-weight: bold;
        }


        /* Data Table */

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }


        thead {
            display: table-header-group;
        }


        th {
            background: #081426;
            color: #FFFFFF;
            padding: 8px;
            border: 1px solid #000;
            font-size: 9px;
            text-align: center;
        }


        td {
            border: 1px solid #555;
            padding: 6px;
            font-size: 8.5px;
            vertical-align: middle;
        }


        /* Alternate row shading */
        tbody tr:nth-child(even) {
            background-color: #F2F5F8;
        }


        /* Keep rows from splitting between pages */
        tr {
            page-break-inside: avoid;
        }

        .footer {
            margin-top: 12px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }

        .section-title {
            background: #1E293B;
            color: white;
            padding: 6px;
            font-size: 12px;
            font-weight: bold;
            margin-top: 10px;
        }


        .intelligence-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }


        .intelligence-table td {
            width: 25%;
            border: 1px solid #444;
            padding: 8px;
            text-align: center;
            font-size: 10px;
            font-weight: bold;
        }
    </style>

</head>

<body>


    {{-- HEADER --}}

    <div class="header">

        <div class="title">
            TICO COMMAND CONTROL SYSTEM
        </div>

        <div class="division">
            SIGNAL INTELLIGENCE PLATOON
        </div>

        <br>

        <div class="title">
            BTS TELECOMMUNICATION INTELLIGENCE REPORT
        </div>

        <div class="classification">
            RESTRICTED - INTERNAL USE ONLY
        </div>

    </div>


    <table class="report-info">

        <tr>
            <th colspan="2">
                REPORT IDENTIFICATION
            </th>
        </tr>

        <tr>
            <td width="35%">
                Report ID
            </td>

            <td>
                SIGINT-BTS-{{ now()->format('Ymd-His') }}
            </td>
        </tr>


        <tr>
            <td>
                Generated Date
            </td>

            <td>
                {{ now()->format('F d, Y') }}
            </td>
        </tr>


        <tr>
            <td>
                Generated Time
            </td>

            <td>
                {{ now()->format('h:i A') }}
            </td>
        </tr>


        <tr>
            <td>
                Source System
            </td>

            <td>
                BTS Intelligence Database
            </td>
        </tr>

    </table>


    {{-- EXECUTIVE INTELLIGENCE SUMMARY --}}

    <div class="section-title">
        EXECUTIVE INTELLIGENCE SUMMARY
    </div>

    <table class="intelligence-table">

        <tr>
            <td>
                TOTAL BTS ASSETS<br>
                {{ $totalBts }}
            </td>

            <td>
                SMART TOWERS<br>
                {{ $smartBts }}
            </td>

            <td>
                GLOBE TOWERS<br>
                {{ $globeBts }}
            </td>

            <td>
                TM TOWERS<br>
                {{ $tmBts }}
            </td>
        </tr>

    </table>


    {{-- TECHNOLOGY EVOLUTION --}}

    <div class="section-title">
        TECHNOLOGY EVOLUTION
    </div>


    <table class="intelligence-table">

        <tr>

            <td>
                2G LEGACY<br>
                {{ $twoGTowers }}
            </td>

            <td>
                3G NETWORK<br>
                {{ $threeGTowers }}
            </td>

            <td>
                4G LTE<br>
                {{ $fourGLTETowers }}
            </td>

            <td>
                5G ADVANCED<br>
                {{ $fiveGTowers }}
            </td>

        </tr>

    </table>


    {{-- BTS TELECOMMUNICATION DATABASE --}}

    <div class="database-title">
        BTS TELECOMMUNICATION DATABASE RECORDS
    </div>

    <table>

        <thead>

            <tr>

                <th>Name</th>
                <th>MGRS</th>
                <th>Network</th>
                <th>Mode</th>
                <th>LAC</th>
                <th>CID</th>
                <th>Neighbor CID</th>
                <th>Barangay</th>
                <th>Municipality</th>
                <th>Province</th>

            </tr>

        </thead>


        <tbody>

            @foreach($btsRecords as $bts)

            <tr>

                <td>
                    {{ $bts->name }}
                </td>

                <td align="center">
                    {{ $bts->mgrs_location }}
                </td>

                <td align="center">
                    {{ $bts->network }}
                </td>

                <td align="center">
                    {{ $bts->network_mode }}
                </td>

                <td align="center">
                    {{ $bts->lac }}
                </td>

                <td align="center">
                    {{ $bts->cid }}
                </td>

                <td align="center">
                    {{ $bts->neighbor_cid }}
                </td>

                <td align="center">
                    {{ $bts->barangay }}
                </td>

                <td align="center">
                    {{ $bts->municipality }}
                </td>

                <td align="center">
                    {{ $bts->province }}
                </td>

            </tr>

            @endforeach

        </tbody>

    </table>


    {{-- FOOTER --}}

    <div class="footer">

        End of BTS Intelligence Report

    </div>


</body>

</html>