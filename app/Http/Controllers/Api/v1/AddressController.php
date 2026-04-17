<?php

namespace App\Http\Controllers\Api\v1;

use DB;
use Config;
use Validation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\v1\BaseController;
use App\Models\{Cart, User, Client, UserAddress};
use Exception;

class AddressController extends BaseController
{
    use ApiResponser;

    public function getAddressList(Request $request, $id = '')
    {
        $address = UserAddress::where('user_id', Auth::user()->id);
        
        if ($id > 0) {
            $address = $address->where('id', $id);
        }
        
        // Filter by category_id if provided
        if ($request->has('category_id') && $request->category_id) {
            $address = $address->where('category_id', $request->category_id);
        }
        
        $address = $address->orderBy('is_primary', 'desc')->orderBy('id', 'desc')->limit(5)->get();
        
        if ($address->isEmpty()) {
            // Collection is empty
            return $this->successResponse($address, __('Address not found.'));
        }
        return $this->successResponse($address, __('Address found.'));
    }

    public function postSaveAddress(Request $request, $addressId = 0)
    {
        try {
            $validator = Validator::make($request->all(), [
                'address' => 'required',
                'category_id' => 'nullable|exists:categories,id',
                //'country' => 'required',
            ]);
            $user = Auth::user();
            if ($validator->fails()) {
                foreach ($validator->errors()->toArray() as $error_key => $error_value) {
                    $errors['error'] = __($error_value[0]);
                    return response()->json($errors, 422);
                }
            }
            if ($request->has('is_primary') && $request->is_primary == 1) {
                $add = UserAddress::where('user_id', $user->id)->update(['is_primary' => 0]);
            }
            $address = UserAddress::where('id', $addressId)->where('user_id', $user->id)->first();
            $message = __("Address updated successfully.");
            if (!$address) {
                $message = __("Address added successfully.");
                $address = new UserAddress();
                $address->user_id = $user->id;
                $address->is_primary = $request->has('is_primary') ? 1 : 0;
            }
            foreach ($request->only('address', 'house_number', 'street', 'city', 'state', 'latitude', 'longitude', 'pincode', 'phonecode', 'country_code', 'country', 'tag', 'building_villa_flat_no', 'name', 'phone_number_type','phone_number', 'category_id') as $key => $value) {
                $address[$key] = $value;
            }
            $address->type = ($request->has('address_type') && $request->address_type < 4) ? $request->address_type : 4;
            $address->save();
            return $this->successResponse($address, $message);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function postUpdatePrimaryAddress($addressId = 0)
    {
        try {
            $user = Auth::user();
            $address = UserAddress::where('id', $addressId)->where('user_id', $user->id)->first();
            if (!$address) {
                return $this->errorResponse(__('Address not found.'), 404);
            }
            $add = UserAddress::where('user_id', $user->id)->update(['is_primary' => 0]);
            $add = UserAddress::where('user_id', $user->id)->where('id', $addressId)->update(['is_primary' => 1]);
            $this->updateCheckLocInCart(0);
            return $this->successResponse('', __('Address is set as primary address successfully.'));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function postDeleteAddress($addressId = 0)
    {
        try {
            $address = UserAddress::where('id', $addressId)->where('user_id', Auth::user()->id)->first();
            if (!$address) {
                return $this->errorResponse(__('Address not found.'), 404);
            }
            $address->delete();
            return $this->successResponse('', __('Address deleted successfully.'));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function updateCheckLocInCart($status = 0)
    {
        $user = Auth::user();
        Cart::where('user_id', $user->id)->update(['check_location' => $status]);
    }
}
