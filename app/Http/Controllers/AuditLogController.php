<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    //
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->latest();

        //FILTERS
        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('user')) {
            $query->where('user_id', $request->user);
        }

        $logs = $query->paginate(10);;

        return view('audit.index', compact('logs'));
    }
}
