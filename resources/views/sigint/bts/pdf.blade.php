<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #222;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
        }

        .subtitle {
            font-size: 12px;
            margin-top: 4px;
        }

        .generated {
            margin-top: 8px;
            font-size: 10px;
            color: #555;
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
        }

        th {
            background: #222;
            color: white;
            padding: 8px;
            border: 1px solid #000;
            font-size: 10px;
        }

        td {
            border: 1px solid #444;
            padding: 6px;
            font-size: 9px;
            vertical-align: top;
        }

        .footer {
            margin-top: 12px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
    </style>

</head>

<body>


    {{-- HEADER --}}

    <div class="header">

        <div class="title">
            SIGINT BTS INTELLIGENCE REPORT
        </div>

        <div class="subtitle">
            Command Control System
        </div>

        <div class="generated">
            Generated:
            {{ now()->format('d M Y • H:i A') }}
        </div>

    </div>


    {{-- INTELLIGENCE SUMMARY --}}

    <table class="stats">

        <tr>

            <td>
                TOTAL BTS<br>
                {{ $totalBts }}
            </td>

            <td>
                SMART BTS<br>
                {{ $smartBts }}
            </td>

            <td>
                GLOBE BTS<br>
                {{ $globeBts }}
            </td>

            <td>
                5G TOWERS<br>
                {{ $fiveGTowers }}
            </td>

        </tr>

    </table>


    {{-- BTS DATA TABLE --}}

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

                <td>
                    {{ $bts->mgrs_location }}
                </td>

                <td>
                    {{ $bts->network }}
                </td>

                <td>
                    {{ $bts->network_mode }}
                </td>

                <td>
                    {{ $bts->lac }}
                </td>

                <td>
                    {{ $bts->cid }}
                </td>

                <td>
                    {{ $bts->neighbor_cid }}
                </td>

                <td>
                    {{ $bts->barangay }}
                </td>

                <td>
                    {{ $bts->municipality }}
                </td>

                <td>
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