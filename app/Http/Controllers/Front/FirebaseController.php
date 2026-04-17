<?php

namespace App\Http\Controllers\Front;

use Auth;
use Session;
use Timezonelist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Front\FrontController;
use App\Models\ClientPreference;

class FirebaseController extends FrontController
{

    public function service_worker()
    {
        $preference = ClientPreference::first();
        $view = response()->view('frontend.firebase.service_worker', compact('preference'));
        $view->header('Content-Type', 'application/javascript');
        return $view;
    }
}
