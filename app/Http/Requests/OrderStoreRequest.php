<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(){
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(){
        return [
            'address_id' => 'required:exists:user_addresses,id',
            'payment_option_id' => 'required',
        ];
    }
    public function messages(){
        return [
            'address_id.required' => 'Address id is required!',
            'payment_option_id.required' => 'Payment Option id field is required!'
        ];
    }
}
