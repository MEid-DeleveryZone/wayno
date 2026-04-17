<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['middleware' => 'languageSwitch'], function () {

    Auth::routes();

    include_once "images.php";
    include_once "godpanel.php";

    Route::domain('{domain}')->middleware(['subdomain'])->group(function() {
        include_once "frontend.php";
        include_once "backend.php";
    });

    Route::get('showImg/{folder}/{img}',function($folder, $img){
        $image  = \Storage::disk('s3')->url($folder . '/' . $img);
        return \Image::make($image)->fit(460, 120)->response('jpg');
    });

    Route::get('/prods/{img}',function($img){
        $image  = \Storage::disk('s3')->url('prods/' . $img);
        return \Image::make($image)->fit(460, 320)->response('jpg');
    });


});

// languageSwitch 
Route::get('/switch/language',function(Request $request){
    if($request->lang){
        session()->put("applocale",$request->lang);
    }
    return redirect()->back();
});

// ADMIN languageSwitch 
Route::get('/switch/admin/language',function(Request $request){
    if($request->lang){
        session()->put("applocale_admin",$request->lang);
        session()->put("adminLanguage",$request->langid);
    }
    return redirect()->back();
});
