<table>
    <!-- ============================= -->
    <!-- COMMAND HEADER -->
    <!-- ============================= -->

    <tr>
        <td colspan="10">
            TICO COMMAND CONTROL SYSTEM
        </td>
    </tr>

    <tr>
        <td colspan="10">
            SIGNAL INTELLIGENCE PLATOON
        </td>
    </tr>

    <tr>
        <td colspan="10">
            BTS TELECOMMUNICATION INTELLIGENCE REPORT
        </td>
    </tr>

    <tr>
        <td colspan="10">
            RESTRICTED - INTERNAL USE ONLY
        </td>
    </tr>

    <tr>
        <td colspan="10">
            <strong>REPORT IDENTIFICATION</strong>
        </td>
    </tr>


    <tr>
        <td>
            Report ID
        </td>

        <td colspan="3">
            SIGINT-BTS-{{ now()->format('Ymd-His') }}
        </td>
    </tr>


    <tr>
        <td>
            Generated Date
        </td>

        <td colspan="3">
            {{ now()->format('F d, Y') }}
        </td>
    </tr>


    <tr>
        <td>
            Generated Time
        </td>

        <td colspan="3">
            {{ now()->format('H:i:s') }}
        </td>
    </tr>


    <tr>
        <td>
            Source System
        </td>

        <td colspan="3">
            BTS Intelligence Database
        </td>
    </tr>


    <tr></tr>


    <!-- ============================= -->
    <!-- EXECUTIVE SUMMARY -->
    <!-- ============================= -->

    <tr>
        <td colspan="2"><strong>EXECUTIVE SUMMARY</strong></td>
    </tr>


    <tr>
        <td>Total BTS Assets</td>
        <td>{{ $totalBts }}</td>
    </tr>


    <tr>
        <td>SMART Towers</td>
        <td>{{ $smart }}</td>
    </tr>


    <tr>
        <td>GLOBE Towers</td>
        <td>{{ $globe }}</td>
    </tr>


    <tr>
        <td>TM Towers</td>
        <td>{{ $tm }}</td>
    </tr>


    <tr></tr>


    <!-- ============================= -->
    <!-- TECHNOLOGY EVOLUTION -->
    <!-- ============================= -->


    <tr>
        <td colspan="2">
            <strong>TECHNOLOGY EVOLUTION</strong>
        </td>
    </tr>


    <tr>
        <td>2G Legacy Network</td>
        <td>{{ $twoG }}</td>
    </tr>


    <tr>
        <td>3G Network</td>
        <td>{{ $threeG }}</td>
    </tr>


    <tr>
        <td>4G LTE Broadband</td>
        <td>{{ $fourG }}</td>
    </tr>


    <tr>
        <td>5G Advanced Network</td>
        <td>{{ $fiveG }}</td>
    </tr>


    <tr></tr>
    <tr></tr>


    <!-- ============================= -->
    <!-- DETAILED BTS DATABASE -->
    <!-- ============================= -->

    <tr>
        <td colspan="10">
            <strong>
                BTS DATABASE RECORDS
            </strong>
        </td>
    </tr>


    <!-- Column Headers -->

    <tr>
        <th>BTS Name</th>
        <th>MGRS Location</th>
        <th>Network</th>
        <th>Network Mode</th>
        <th>LAC</th>
        <th>CID</th>
        <th>Neighbor CID</th>
        <th>Barangay</th>
        <th>Municipality</th>
        <th>Province</th>
    </tr>


    <!-- Database Records -->

    @foreach($btsRecords as $bts)

    <tr>

        <td>{{ $bts->name }}</td>

        <td>{{ $bts->mgrs_location }}</td>

        <td>{{ $bts->network }}</td>

        <td>{{ $bts->network_mode }}</td>

        <td>{{ $bts->lac }}</td>

        <td>{{ $bts->cid }}</td>

        <td>{{ $bts->neighbor_cid }}</td>

        <td>{{ $bts->barangay }}</td>

        <td>{{ $bts->municipality }}</td>

        <td>{{ $bts->province }}</td>

    </tr>

    @endforeach

</table>

<br>

<table>
    <tr>
        <td colspan="10">
            <strong>
                INTELLIGENCE CLASSIFICATION
            </strong>
        </td>
    </tr>

    <tr>
        <td colspan="10">
            RESTRICTED - INTERNAL USE ONLY
        </td>
    </tr>

    <tr>
        <td colspan="10">
            This document contains telecommunications intelligence data
            intended solely for authorized Command Control personnel.
        </td>
    </tr>

    <tr>
        <td colspan="5">
            <br><br>
            Prepared by:
            <br>
            SIGINT Analysis Unit
            <br>
            Command Control System
        </td>

        <td colspan="5">
            <br><br>
            Approved by:
            <br>
            _______________________
            <br>
            Authorized Officer
        </td>
    </tr>

    <tr>
        <td colspan="10">
            <br>
            Generated:
            {{ now()->format('F d, Y h:i A') }}
        </td>
    </tr>
</table>