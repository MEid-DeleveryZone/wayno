<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Permissions;
use App\Models\{Currency, Client, Category, Brand, Cart, ReferAndEarn, ClientPreference, Vendor, ClientCurrency, User, Country, UserRefferal, Wallet, WalletHistory,CartProduct};
use App\Models\UserPermissions;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Crypt;
use Illuminate\Support\Facades\DB;

class AclController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subadmins = User::where('is_superadmin', 0)->where('id', '!=', Auth::user()->id)->orderBy('id', 'DESC')->paginate(10);
        return view('backend.acl.index')->with(['subadmins' => $subadmins]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permissions::all();
       
        return view('backend.acl.form')->with(['permissions'=>$permissions]);
    }

    /**
     * Validation method for clients data
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone_number' => ['required'],
            'password' => ['required']
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $domain = '')
    {
        $validator = $this->validator($request->all())->validate();
        DB::beginTransaction();

        try {
         $subdmin = $this->register($request);
        
        

        if ($request->permissions) {
            $userpermissions = $request->permissions;
            $addpermission = [];
            $removepermissions = UserPermissions::where('user_id', $subdmin->id)->delete();
            for ($i=0;$i<count($userpermissions);$i++) {
                $addpermission[] =  array('user_id' => $subdmin->id,'permission_id' => $userpermissions[$i]);
            }
            UserPermissions::insert($addpermission);
        }

        
        
        DB::commit();
        return redirect()->route('acl.index')->with('success', 'Manager Added successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('acl.index')->with('error', $e->getMessage());
        }
    }



    /**     * Display register Form     */
    public function register($req){
        try {
            $user = new User();
            $county = Country::where('code', strtoupper($req->countryData))->first();
            $phoneCode = mt_rand(100000, 999999);
            $emailCode = mt_rand(100000, 999999);
            $sendTime = \Carbon\Carbon::now()->addMinutes(10)->toDateTimeString();
            $user->type = 1;
            $user->status = 1;
            $user->role_id = 1;
            $user->name = $req->name;
            $user->email = $req->email;
            $user->is_email_verified = 0;
            $user->is_phone_verified = 0;
            $user->is_superadmin = 0;
            $user->is_admin = 1;
            $user->country_id = $county->id;
            $user->phone_token = $phoneCode;
            $user->email_token = $emailCode;
            $user->phone_number = $req->countryCode.$req->phone_number;
            $user->phone_token_valid_till = $sendTime;
            $user->email_token_valid_till = $sendTime;
            $user->password = Hash::make($req->password);
            $user->save();
            $userRefferal = new UserRefferal();
            $userRefferal->refferal_code = $this->randomData("user_refferals", 8, 'refferal_code');
            if($req->refferal_code != null){
                $userRefferal->reffered_by = $req->refferal_code;
            }
            $userRefferal->user_id = $user->id;
            $userRefferal->save();
            if ($user->id > 0) {
                $userCustomData = $this->userMetaData($user->id, 'web', 'web');
                $rae = ReferAndEarn::first();
                if($rae){
                    $userReff_by = UserRefferal::where('refferal_code', $req->refferal_code)->first();
                    $wallet_by = Wallet::where('user_id' , $userReff_by->user_id)->first();
                    $wallet_to = Wallet::where('user_id' , $user->id)->first();
                    if($rae->reffered_by_amount != null){
                        $wallet_history = new WalletHistory();
                        $wallet_history->user_id = $userReff_by->user_id;
                        $wallet_history->wallet_id = $wallet_by->id;
                        $wallet_history->amount = $rae->reffered_by_amount;
                        $wallet_history->save();
                    }
                    if($rae->reffered_to_amount != null){
                        $wallet_history = new WalletHistory();
                        $wallet_history->user_id = $user->id;
                        $wallet_history->wallet_id = $wallet_to->id;
                        $wallet_history->amount = $rae->reffered_to_amount;
                        $wallet_history->save();
                    }
                }
                $this->checkCookies($user->id);
               return $user;
            }
        } catch (Exception $e) {
            die();
        }  
    }

    /**     * check if cookie already exist     */
    public function checkCookies($userid){
        if (\Cookie::has('uuid')) {
            $existCookie = \Cookie::get('uuid');
            $userFind = User::where('system_id', $existCookie)->first();
            if($userFind){
                $cart = Cart::where('user_id', $userFind->id)->first();
                if($cart){
                    $cart->user_id = $userid;
                    $cart->save();
                }
                $userFind->delete();
            }
            \Cookie::queue(\Cookie::forget('uuid'));
            return redirect()->route('user.checkout');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($domain = '', $id)
    {
        $subadmin = User::find($id);
        $permissions = Permissions::all();
        $user_permissions = UserPermissions::where('user_id', $id)->get();
        
        return view('backend.acl.form')->with(['subadmin'=> $subadmin,'permissions'=>$permissions,'user_permissions'=>$user_permissions]);
    }

    protected function updateValidator(array $data, $id)
    {
        return Validator::make($data, [

            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255',\Illuminate\Validation\Rule::unique('users')->ignore($id)],
            'phone_number' => ['required'],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $domain = '', $id)
    {
        $validator = $this->updateValidator($request->all(), $id)->validate();
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'status' => $request->status,
            'is_superadmin' => 0
            
        ];
        if ($request->password!="") {
            $data['password'] = Hash::make($request->password);
        }
        $client = User::where('id', $id)->update($data);
        //for updating permissions
        if ($request->permissions) {
            $userpermissions = $request->permissions;
            $addpermission = [];
            $removepermissions = UserPermissions::where('user_id', $id)->delete();
            for ($i=0;$i<count($userpermissions);$i++) {
                $addpermission[] =  array('user_id' => $id,'permission_id' => $userpermissions[$i]);
            }
            UserPermissions::insert($addpermission);
        }
        return redirect()->route('acl.index')->with('success', 'Manager Updated successfully!');
    }
}
