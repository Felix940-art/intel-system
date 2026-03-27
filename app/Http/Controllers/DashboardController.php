<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //

    public function index()
    {
        //TOTALS
        $sigint = DB::table('report_db')->where('module', 'SIGINT')->count();
        $geoint = DB::table('report_db')->where('module', 'GEOINT')->count();
        $dforensics = DB::table('report_db')->where('module', 'D-FORENSICS')->count();

        $totalRecords = $sigint + $geoint + $dforensics;

        //TIME SERIES (LAST 30 DAYS)
        $trend = DB::table('report_db')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupby('date')
            ->orderby('date')
            ->limit(30)
            ->get();

        //MAP DATA (SIGINT ONLY)
        $mapData = DB::table('report_db')
            ->where('module', 'SIGINT')
            ->select('latitude', 'longitude', 'frequency', 'created_at')
            ->get();

        //FORENSICS PREVIEW
        $forensics = DB::table('report_db')
            ->where('module', 'D-FORENSICS')
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard', 'compact'(
            'sigint',
            'geoint',
            'dforensics',
            'totalRecords',
            'trend',
            'mapData',
            'forensics'
        ));
    }
}
