<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Client\BaseController;
use Illuminate\Support\Facades\Auth;

class RoutingController extends BaseController
{


    // public function __construct()
    // {
    //     $this->middleware('auth')->except('index');
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($domain= '')
    {
       
        return view('index');
    }

    /**
     * Display a view based on first route param
     *
     * @return \Illuminate\Http\Response
     */
    public function root($domain= '', $first)
    {   
        if ($first != 'assets')
            return view('theme/'.$first);
        return view('index');
    }

    /**
     * second level route
     */
    public function secondLevel($domain= '', $first, $second)
    {        
        if ($first != 'assets')
            return view('theme/'.$first.'.'.$second);
        return view('index');
    }

    /**
     * third level route
     */
    public function thirdLevel($domain= '', $first, $second, $third)
    {
        if ($first != 'assets')
            return view('theme/'.$first.'.'.$second.'.'.$third);
        return view('index');
    }
}