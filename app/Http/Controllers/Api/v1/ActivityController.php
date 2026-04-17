<?php

namespace App\Http\Controllers\Api\v1;

use DB;
use Config;
use Validation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Api\v1\BaseController;
use App\Models\{User, Agent, AgentLog, Client, ClientPreference, Cms, Order, Task, TaskProof};

class ActivityController extends BaseController{
	/**
     * update driver availability status if 0 than 1 if 1 than 0

     */
    public function updateDriverStatus(Request $request){
        $agent = Agent::findOrFail(Auth::user()->id); 
        $agent->is_available = ($agent->is_available == 1) ? 0 : 1;
        $agent->update();
        return response()->json(['message' => 'Status updated Successfully','data' => array('is_available' => $agent->is_available)]);
    }

    /**
     * Login user and create token
     */

    public function tasks(Request $request){
        $id    = Auth::user()->id;
        $all = $request->all; 
        $tasks = Task::where('task_status',1)->orWhere('task_status',2)->with([
                'location','tasktype','order'=> function($o) use ($id,$all){
                    if($all == 0){
                        $o->where('driver_id',$id)->where('order_time',Carbon::today())->with('customer');
                    }else{
                        $o->where('driver_id',$id)->with('customer');
                    }
                }])->get(['id','order_id','dependent_task_id','task_type_id','location_id','appointment_duration','task_status','allocation_type','created_at']);
        return response()->json(['data' => $tasks],200);
    }

    /**
     * Login user and create token
     */

    public function profile(Request $request){
       $agent = Agent::where('id',Auth::user()->id)->first();
       return response()->json(['data' => $agent],200);
    }

    public function updateProfile(Request $request){
        $getFileName = '';
        if(isset($request->profile_picture)){
        $saved = Agent::where('id',Auth::user()->id)->first();
        $header = $request->header();
        $client_code = Client::where('database_name',$header['client'][0])->first('code');
            if ($request->hasFile('profile_picture')) {
                $folder = str_pad($client_code->code, 8, '0', STR_PAD_LEFT);
                $folder = 'client_'.$folder;
                $file = $request->file('profile_picture');
                $file_name = uniqid() .'.'.  $file->getClientOriginalExtension();
                $s3filePath = '/assets/'.$folder.'/agents' . $file_name;
                $path = Storage::disk('s3')->put($s3filePath, $file,'public');
                $getFileName = $path;
            }
        }else{
            $getFileName = $saved->profile_picture;
        }
        $agent                   = Agent::find(Auth::user()->id);
        $agent->name             = isset($request->name)?$request->name:$saved->name;
        $agent->profile_picture  = $getFileName;
        $agent->vehicle_type_id  = $request->vehicle_type_id;
        $agent->make_model       = isset($request->make_model)?$request->make_model:$saved->make_model;
        $agent->plate_number     = isset($request->plate_number)?$request->plate_number:$saved->plate_number;
        $agent->phone_number     = isset($request->phone_number)?$request->phone_number:$saved->phone_number;
        $agent->color            = isset($request->color)?$request->color:$saved->color;
        if($agent->save()){
            return response()->json(['message' => 'Profile Updated Successfully'],200);
        } else {
            return response()->json(['message' => 'Sorry Something Went Wrong'],404);
        }
    }

    public function agentLog(Request $request){
        $agent = AgentLog::where('agent_id',Auth::user()->id)->first();
        $data =  [
            'lat'               => $request->lat,
            'long'              => $request->long,
            'agent_id'          => Auth::user()->id,
            'on_route'          => $request->on_route,
            'os_version'        => $request->os_version,
            'device_type'       => $request->device_type,
            'app_version'       => $request->app_version,
            'battery_level'     => $request->battery_level,
            'current_speed'     => $request->current_speed,
        ];
        AgentLog::create($data);
        $id    = Auth::user()->id;
        $all   = $request->all; 
        $tasks = Task::where('task_status',1)->orWhere('task_status',2)->with([
                'location','tasktype','order'=> function($o) use ($id,$all){
                    if($all == 0){
                        $o->where('driver_id',$id)->where('order_time',Carbon::today())->with('customer');
                    }else{
                        $o->where('driver_id',$id)->with('customer');
                    }
                }])->get(['id','order_id','dependent_task_id','task_type_id','location_id','appointment_duration','task_status','allocation_type','created_at']);
        $agents     = Agent::where('id',$id)->with('team')->first();
        $taskProof = TaskProof::where('id',1)->first();
        $prefer    = ClientPreference::select('theme', 'distance_unit', 'currency_id', 'language_id', 'agent_name', 'date_format', 'time_format', 'map_type','map_key_1')->first();
        $agents['client_preference'] = $prefer;
        $agents['task_proof']        = $taskProof;
        $datas['user']                = $agents;
        $datas['tasks']               = $tasks;
        return response()->json([
            'data' => $datas,
        ],200);
    }

    public function cmsData(Request $request){
        $data = Cms::where('id',$request->cms_id)->first();
        return response()->json(['data' => $data],200);
    }

    public function getAppForceUpdateStatus(){
        $data = ClientPreference::select('android_app_force_update_status','ios_app_force_update_status','android_maintenance_mode','ios_maintenance_mode')->get();
        return response()->json(['data' => $data],200);
    }
  
}
