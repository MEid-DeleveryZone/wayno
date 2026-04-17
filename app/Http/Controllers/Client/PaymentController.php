<?php

namespace App\Http\Controllers\Client;

use Omnipay\Omnipay;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Client\BaseController;

class PaymentController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = array();
        return view('backend/payment/index')->with(['payments' => $payments]);
    }

    public function showForm()
    {
        return view('backend/stripe/form');
    }

    public function showFormApp($domain='',$token)
    {
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
        return view('frontend/payment')->with(['navCategories' => $navCategories]);
    }

}
