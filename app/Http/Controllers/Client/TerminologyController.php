<?php

namespace App\Http\Controllers\Client;

use App\Models\Terminology;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Client\BaseController;

class TerminologyController extends BaseController{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $terminologies = array();
        return view('backend/terminology/index')->with(['terminologies' => $terminologies]);
    }
}
