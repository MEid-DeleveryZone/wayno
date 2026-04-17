<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Front\FrontController;
use Illuminate\Http\Request;
use App\Models\ProductInquiry;
use Illuminate\Support\Facades\Validator;

class ProductInquiryController extends FrontController
{

    /**
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $domain = '')
    {
        try {
            $request->validate([
                'agree' =>'accepted',
                'name' => 'required',
                'email' => 'required',
                'number1' => 'required',
                'message' => 'required',
            ], [
                'name.required' => __('The name field is required.'),
                'agree.accepted' => __('The agree must be accepted.'),
                'email.required' => __('The email field is required.'),
                'number1.required' => __('The number field is required.'),
                'message.required' => __('The message field is required.'),
            ]);
            ProductInquiry::create(['name' => $request->name, 'email' => $request->email, 'phone_number' => $request->number1, 'company_name' => $request->company_name, 'message' => $request->message, 'product_id' => $request->product_id, 'vendor_id' => $request->vendor_id, 'product_variant_id' => $request->variant_id]);
            return response()->json(['success', 'Inquiry Submitted Successfully.']);
        } catch (Exception $e) {
            return response()->json(['error', $e->getMessage()]);
        }
    }
}
