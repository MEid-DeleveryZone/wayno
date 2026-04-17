<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DownloadFileController extends Controller
{
    public function index(Request $request, $domain = '', $file_name){
    	$file_path = public_path($file_name);
    	$headers = ['Content-Type: application/csv'];
    	return response()->download($file_path, $file_name, $headers);
    }
}
