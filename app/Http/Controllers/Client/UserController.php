<?php

namespace App\Http\Controllers\Client;

use Auth;
use Image;
use Password;
use DataTables;
use Carbon\Carbon;
use App\Models\Vendor;
use App\Models\UserVendor;
use App\Models\Permissions;
use Illuminate\Http\Request;
use App\Models\UserPermissions;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Notifications\PasswordReset;
use App\Http\Traits\ToasterResponser;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Client\BaseController;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomerExport;
use App\Models\UserDevice;
use Session;
use App\Models\{Payment, User, Client, Country, Currency, Language, UserVerification, Role, Transaction};

class UserController extends BaseController
{
    use ToasterResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        $countries = Country::all();
        $active_users = User::where('status', 1)->where('is_superadmin', '!=', 1)->count();
        $inactive_users = User::where('status', 3)->count();
        $users = User::withCount(['orders', 'activeOrders'])->where('status', '!=', 3)->where('is_superadmin', '!=', 1)->orderBy('id', 'desc')->paginate(10);
        $social_logins = 0;
        foreach ($users as  $user) {
            if (!empty($user->facebook_auth_id)) {
                $social_logins++;
            } elseif (!empty($user->twitter_auth_id)) {
                $social_logins++;
            } elseif (!empty($user->google_auth_id)) {
                $social_logins++;
            } elseif (!empty($user->apple_auth_id)) {
                $social_logins++;
            }
        }
        return view('backend/users/index')->with(['inactive_users' => $inactive_users, 'social_logins' => $social_logins, 'active_users' => $active_users, 'users' => $users, 'roles' => $roles, 'countries' => $countries]);
    }
    public function getFilterData(Request $request)
    {
        $current_user = Auth::user();
        $users = User::withCount(['orders', 'currentlyWorkingOrders'])->where('status', '!=', 3)->where('is_superadmin', '!=', 1)->where('is_admin', '!=', 1)->orderBy('id', 'desc')->get();
        foreach ($users as  $user) {
            $user->edit_url = route('customer.new.edit', $user->id);
            $user->delete_url = route('customer.account.action', [$user->id, 3]);
            $user->image_url = $user->image['proxy_url'] . '40/40' . $user->image['image_path'];
            $user->login_type = 'Email';
            $user->is_superadmin = $current_user->is_superadmin;
            $user->login_type_value = $user->email;
            $user->balanceFloat = $user->balanceFloat;
            if (!empty($user->facebook_auth_id)) {
                $user->login_type = 'Facebook';
                $user->login_type_value = $user->facebook_auth_id;
            } elseif (!empty($user->twitter_auth_id)) {
                $user->login_type = 'Twitter';
                $user->login_type_value = $user->twitter_auth_id;
            } elseif (!empty($user->google_auth_id)) {
                $user->login_type = 'Google';
                $user->login_type_value = $user->google_auth_id;
            } elseif (!empty($user->apple_auth_id)) {
                $user->login_type = 'Apple';
                $user->login_type_value = $user->apple_auth_id;
            }
        }
        return Datatables::of($users)
            ->addIndexColumn()
            ->filter(function ($instance) use ($request) {
                if (!empty($request->get('search'))) {
                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                        if (Str::contains(Str::lower($row['name']), Str::lower($request->get('search')))) {
                            return true;
                        } elseif (Str::contains(Str::lower($row['email']), Str::lower($request->get('search')))) {
                            return true;
                        } elseif (Str::contains(Str::lower($row['phone_number']), Str::lower($request->get('search')))) {
                            return true;
                        }
                        return false;
                    });
                }
            })->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteCustomer($domain = '', $uid, $action)
    {
        $user = User::where('id', $uid)->firstOrFail();
        $user->status = 3;
        $user->save();
        $msg = 'activated';
        if ($action == 2) {
            $msg = 'blocked';
        }
        if ($action == 3) {
            $msg = 'deleted';
        }
        return redirect()->back()->with('success', 'Customer account ' . $msg . ' successfully!');
    }

    /*      block - activate customer account*/
    public function changeStatus(Request $request, $domain = '')
    {
        $user = User::where('id', $request->userId)->firstOrFail();
        $user->status = ($request->value == 1) ? 1 : 2; // 1 for active 2 for block
        $user->save();
        $msg = 'activated';
        if ($request->value == 0) {
            $msg = 'blocked';
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Customer account ' . $msg . ' successfully!',
        ]);
    }

    /**              Add customer             */
    public function show($domain = '', $uid)
    {
        $user = User::where('id', $uid)->firstOrFail();
        return redirect()->back();
    }
    // public function validator(array $data)
    // {


    //     $full_number = '';
    //     if (isset($data['dial_code']) && !empty($data['dial_code']) && isset($data['phone_number']) && !empty($data['phone_number']))
    //         $full_number = '+' . $data['dial_code'] . $data['phone_number'];

    //     $data['phone_number'] = '+' . $data['dial_code'] . $data['phone_number'];
    //     return Validator::make($data, [
    //         'name' => ['required', 'string', 'min:3', 'max:50'],
    //         'email' => ['required', 'email', 'max:50', Rule::unique('users')],
    //         'phone_number' =>  ['required', 'min:8', 'max:15', Rule::unique('users')->where(function ($query) use ($full_number) {
    //          $query->where('phone_number', $full_number);
    //         })],
    //         'password' => ['required', 'string', 'min:6', 'max:50'],
          

    //     ]);
    // }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $customer = new User();
       $validation  = Validator::make($request->all(), $customer->rules())->validate();
       //$validator = $this->validator($request->all())->validate();
       
        $saveId = $this->save($request, $customer, 'false');
        if ($saveId > 0) {
            return response()->json([
                'status' => 'success',
                'message' => 'Customer created Successfully!',
                'data' => $saveId,
                'aaa' => $request->all()
            ]);
        }
    }

    /**
     * save and update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request, User $user, $update = 'false')
    {
        $request->contact;
        $request->phone_number;
        $phone = ($request->has('contact') && !empty($request->contact)) ? $request->contact : $request->phone_number;
        $user->name = $request->name;
        $user->dial_code = $request->dial_code;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->phone_number = $phone;
        $user->is_email_verified = ($request->has('is_email_verified') && $request->is_email_verified == 'on') ? 1 : 0;
        $user->is_phone_verified = ($request->has('is_phone_verified') && $request->is_phone_verified == 'on') ? 1 : 0;
        if ($request->hasFile('image')) {    /* upload logo file */
            $file = $request->file('image');
            $user->image = Storage::disk('s3')->put('/profile', $file, 'public');
        }
        $user->save();
        $wallet = $user->wallet;
        $userCustomData = $this->userMetaData($user->id, 'web', 'web');
        return $user->id;
    }


    public function edit($domain = '', $id)
    {
        $user = User::where('id', $id)->first();
        return response()->json(array('success' => true, 'user' => $user->toArray()));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function newEdit($domain = '', $id)
    {
        $subadmin = User::find($id);
        $permissions = Permissions::where('status',1)->whereNotin('id',[4,5,6,7,8,9,10,11,14,15,16,22,23,24,25])->get();
        $user_permissions = UserPermissions::where('user_id', $id)->get();
        $vendor_permissions = UserVendor::where('user_id', $id)->pluck('vendor_id')->toArray();
        $vendors = Vendor::where('status', 1)->get();
        return view('backend.users.editUser')->with(['subadmin' => $subadmin, 'vendors' => $vendors, 'permissions' => $permissions, 'user_permissions' => $user_permissions, 'vendor_permissions' => $vendor_permissions]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function newUpdate(Request $request, $domain = '', $id)
    {
        $data = [
            'status' => $request->status,
            'is_admin' => $request->is_admin,
            'is_superadmin' => 0
        ];
        $client = User::where('id', $id)->update($data);
        //for updating permissions
        $removepermissions = UserPermissions::where('user_id', $id)->delete();
        if ($request->permissions) {
            $userpermissions = $request->permissions;
            $addpermission = [];
            for ($i = 0; $i < count($userpermissions); $i++) {
                $addpermission[] =  array('user_id' => $id, 'permission_id' => $userpermissions[$i]);
            }
            UserPermissions::insert($addpermission);
        }
        //for updating vendor permissions
        if ($request->vendor_permissions) {
            $teampermissions = $request->vendor_permissions;
            $addteampermission = [];
            $removeteampermissions = UserVendor::where('user_id', $id)->delete();
            for ($i = 0; $i < count($teampermissions); $i++) {
                $addteampermission[] =  array('user_id' => $id, 'vendor_id' => $teampermissions[$i]);
            }
            UserVendor::insert($addteampermission);
        }
        return redirect()->route('customer.index')->with('success', 'Customer Updated successfully!');
    }

    public function profile()
    {
        $countries = Country::all();
        $client = Client::where('code', Auth::user()->code)->first();
        $tzlist = \DateTimeZone::listIdentifiers(\DateTimeZone::ALL);
        return view('backend/setting/profile')->with(['client' => $client, 'countries' => $countries, 'tzlist' => $tzlist]);
    }

    public function updateProfile(Request $request, $domain = '', $id)
    {
        $user = Auth::user();
        $client = Client::where('code', $user->code)->firstOrFail();
        $rules = array(
            'name' => 'required|string|max:50',
            'phone_number' => 'required|min:8|max:15',
            'company_name' => 'required',
            'company_address' => 'required',
            'country_id' => 'required',
            'timezone' => 'required',
        );
        $validation  = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation);
        }
        $data = array();
        foreach ($request->only('name', 'phone_number', 'company_name', 'company_address', 'country_id', 'timezone') as $key => $value) {
            $data[$key] = $value;
        }
        $client = Client::where('code', $user->code)->first();
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $file_name = 'Clientlogo/' . uniqid() . '.' .  $file->getClientOriginalExtension();
            $path = Storage::disk('s3')->put($file_name, file_get_contents($file), 'public');
            $data['logo'] = $file_name;
        } else {
            $data['logo'] = $client->getRawOriginal('logo');
        }
        $client = Client::where('code', $user->code)->update($data);
        $userdata = array();
        foreach ($request->only('name', 'phone_number', 'timezone') as $key => $value) {
            $userdata[$key] = $value;
        }
        $user = $user->update($userdata);
        return redirect()->back()->with('success', 'Client Updated successfully!');
    }
    public function changePassword(Request $request)
    {
        $client = User::where('id', Auth::id())->first();
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        if (Hash::check($request->old_password, $client->password)) {
            $client->password = Hash::make($request->password);
            $client->save();
            $clientData = 'empty';
            return redirect()->back()->with('success', 'Password Changed successfully!');
        } else {
            $request->session()->flash('error', 'Wrong Old Password');
            return redirect()->back();
        }
    }

    public function filterWalletTransactions(Request $request)
    {
        $pagiNate = 10;
        $user_transactions = Transaction::where('wallet_id', $request->walletId)->orderBy('id', 'desc')->get();
        // dd($user_transactions->toArray());
        foreach ($user_transactions as $key => $trans) {
            // $user = User::find($trans->payable_id);
            $trans->serial = $key + 1;
            $trans->date = Carbon::parse($trans->created_at)->format('M d, Y, H:i A');
            // $trans->date = convertDateTimeInTimeZone($trans->created_at, $user->timezone, 'l, F d, Y, H:i A');
            $trans->description = json_decode($trans->meta)[0];
            $trans->amount = '$' . sprintf("%.2f", ($trans->amount / 100));
            $trans->type = $trans->type;
        }
        return Datatables::of($user_transactions)
            ->addIndexColumn()
            ->rawColumns(['description'])
            ->filter(function ($instance) use ($request) {
                if (!empty($request->get('search'))) {
                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                        if (Str::contains(Str::lower($row['date']), Str::lower($request->get('search')))) {
                            return true;
                        } elseif (Str::contains(Str::lower($row['meta']), Str::lower($request->get('search')))) {
                            return true;
                        } elseif (Str::contains(Str::lower($row['amount']), Str::lower($request->get('search')))) {
                            return true;
                        }
                        return false;
                    });
                }
            })->make(true);
    }

    public function export()
    {
        return Excel::download(new CustomerExport, 'users.xlsx');
    }

    public function save_fcm(Request $request)
    {
        // FIXED: Use composite key to prevent overwriting other users' sessions on same device
        UserDevice::updateOrCreate(
            ['device_token' => $request->fcm_token, 'user_id' => Auth::user()->id],
            ['device_type' => "web", 'fcm_token' => $request->fcm_token]
        );
        Session::put('current_fcm_token', $request->fcm_token);
        return response()->json(['status' => 'success', 'message' => 'Token updated successfully']);
    }
}
