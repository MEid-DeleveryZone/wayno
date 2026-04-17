<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;
use Illuminate\Http\Request;

class AdminSignUpController extends BaseController
{
    public function index()
    {
        return view('backend.admin_signup');
    }
}
