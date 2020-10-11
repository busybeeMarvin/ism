<?php

namespace App\Http\Controllers;

use Yajra\DataTables\DataTables;
use DB;

class AuditLogController extends Controller
{
    public function index()
    {
        return view('audit');
    }

    public function table(DataTables $dataTables)
    {
        return $dataTables->queryBuilder(DB::table('audit_logs'))->make(true);
    }
}
