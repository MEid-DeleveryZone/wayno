<?php
namespace App\Http\Controllers\Front;

use Auth;
use Session;
use Timezonelist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail; 
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Front\FrontController;
use App\Models\UserDevice;
use App\Models\{UserWishlist, User, Product, UserAddress, UserRefferal, ClientPreference, Client, Order, Transaction};

class ProfileController extends FrontController
{
    private $folderName = '/profile/image';
    
    /**
     * Display send refferal page
     *
     * @return \Illuminate\Http\Response
     */
    public function showRefferal(){
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
        return view('frontend/account/sendRefferal')->with(['navCategories' => $navCategories]);
    }

     /**
     * Send Refferal Code
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendRefferalCode(Request $request){
        $rae = UserRefferal::where('user_id', Auth::user()->id)->first()->toArray();
        $otp = $rae['refferal_code'];
        $client = Client::select('id', 'name', 'email', 'phone_number', 'logo')->where('id', '>', 0)->first();
        $data = ClientPreference::select('sms_key', 'sms_secret', 'sms_from', 'mail_type', 'mail_driver', 'mail_host', 'mail_port', 'mail_username', 'sms_provider', 'mail_password', 'mail_encryption', 'mail_from')->where('id', '>', 0)->first();
        if (!empty($data->mail_driver) && !empty($data->mail_host) && !empty($data->mail_port) && !empty($data->mail_port) && !empty($data->mail_password) && !empty($data->mail_encryption)) {
            $confirured = $this->setMailDetail($data->mail_driver, $data->mail_host, $data->mail_port, $data->mail_username, $data->mail_password, $data->mail_encryption);
            $client_name = $client->name;
            $mail_from = $data->mail_from;
            $sendto = $request->email;
            try{
                Mail::send('email.verify',[
                        'customer_name' => "Link from ".Auth::user()->name,
                        'code_text' => 'Register yourself using this refferal code below to get bonus offer',
                        'code' => $otp,
                        'logo' => $client->logo['original'],
                        'link'=> "http://local.myorder.com/user/register?refferal_code=".$otp,
                ],
                function ($message) use($sendto, $client_name, $mail_from) {
                    $message->from($mail_from, $client_name);
                    $message->to($sendto)->subject('OTP to verify account');
                });
                $response['send_email'] = 1;
            }
            catch(\Exception $e){
                return response()->json(['data' => $e->getMessage()]);
            }
        }
        return response()->json(array('success' => true, 'message' => 'Send Successfully'));
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile(Request $request, $domain = ''){
        $curId = Session::get('customerCurrency');
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
        $user = User::with('country', 'address')->select('id', 'name', 'email', 'description', 'phone_number', 'image', 'type', 'country_id', 'timezone')->where('id', Auth::user()->id)->first();
        $user_addresses = UserAddress::where('user_id', Auth::user()->id)->get();
        $refferal_code = UserRefferal::where('user_id', Auth::user()->id)->first();
        $timezone_list = Timezonelist::create('timezone', $user->timezone, [
            'id'    => 'timezone',
            'class' => 'styled form-control',
        ]);
        return view('frontend.account.profile')->with(['user' => $user, 'navCategories' => $navCategories, 'userAddresses'=>$user_addresses, 'userRefferal' => $refferal_code,'timezone_list' => $timezone_list]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateAccount(Request $request, $domain = '')
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:80',
            'phone_number' => 'required'
        ]);
        $messages = array(
            'name.required' => __('The name field is required'),
            'phone_number.required' => __('Phone number field is required')
        );
        if ($validator->fails()) {
            foreach ($validator->errors()->toArray() as $error_key => $error_value) {
                $errors['error'] = $error_value[0];
                return redirect()->back()->withInput()->withErrors($errors);
            }
        }
        $user = User::where('id', Auth::user()->id)->first();
        if ($user){
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $user->image = Storage::disk('s3')->put($this->folderName, $file,'public');
            }
            $user->name = $request->name;
            $user->timezone = $request->timezone;
            $user->dial_code = $request->dialCode;
            $user->description = $request->description;
            $user->phone_number = str_replace('-', '', $request->phone_number);
            $user->save();
            return redirect()->back()->with('success', 'Profile has been updated');
        }
        return redirect()->back()->with('errors', 'Profile updation failed');
    }

    /**
     * Update user timezone.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateTimezone(Request $request, $domain = ''){
        $timezone = $request->timezone ? $request->timezone : NULL;
        $user = User::where('id', Auth::user()->id)->first();
        if ($user){
            $user->timezone = $timezone;
            $user->save();
            return redirect()->back()->with('success', 'Timezone has been updated');
        }
        return redirect()->back()->with('error', 'Timezone cannot be updated');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function editAccount(Request $request){
        $user = User::select('id', 'name', 'email', 'description', 'phone_number', 'dial_code', 'image', 'type', 'country_id')->where('id', Auth::user()->id)->first();
        $user_addresses = UserAddress::where('user_id', Auth::user()->id)->get();
        $timezone_list = Timezonelist::create('timezone', $user->timezone, [
            'id'    => 'timezone',
            'class' => 'styled form-control',
        ]);
        $returnHTML = view('frontend.account.edit-profile')->with(['user' => $user, 'userAddresses' => $user_addresses, 'timezone_list' => $timezone_list])->render();
        return response()->json(array('success' => true, 'html'=>$returnHTML));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request, $domain = ''){
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
       return view('frontend/account/changePassword')->with(['navCategories' => $navCategories]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function submitChangePassword(Request $request, $domain = ''){
        $request->validate([
            'new_password' => 'required|string|min:6',
            'confirm_password' => 'required|same:new_password',
        ],[
            'new_password.required' => __('The new password field is required.'),
            'new_password.min' => __('The new password must be at least 6 characters.'),
            'confirm_password.required' => __('The confirm password field is required.'),
            'confirm_password.same' => __('The confirm password and new password must match.'),
        ]);
        $user = User::where('id', Auth::user()->id)->first();
        if ($user){
            $user->password = Hash::make($request['new_password']);
            $user->save();
        }
        return redirect()->route('user.profile')->with('success', __('Your Password has been changed successfully'));
    }

    public function save_fcm(Request $request){
        // FIXED: Use composite key to prevent overwriting other users' sessions on same device
        UserDevice::updateOrCreate(
            ['device_token' => $request->fcm_token, 'user_id' => Auth::user()->id],
            ['device_type' => "web", 'fcm_token' => $request->fcm_token]
        );
        Session::put('current_fcm_token', $request->fcm_token);
        return response()->json([ 'status'=>'success', 'message' => 'Token updated successfully']);
    }

}
