<?php

namespace App\Http\Controllers\Client;

use App\Models\Cms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Client\BaseController;

class CmsController extends BaseController{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $cms = array();
        return view('backend/cms/index')->with(['cmslist' => $cms]);
    }
}
