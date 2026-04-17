<?php

namespace App\Http\Controllers\Client;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Client\BaseController;

class ReportController extends BaseController{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reports = array();
        return view('backend/report/index')->with(['reports' => $reports]);
    }
}
