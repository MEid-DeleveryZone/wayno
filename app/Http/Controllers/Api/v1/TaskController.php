<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\v1\BaseController;
use App\Model\Agent;
use Client;
use App\Model\Customer;
use App\Model\Geo;
use App\Model\Location;
use Order;
use App\Model\Roster;
use App\Model\Task;
use App\Model\TaskReject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PHPUnit\Framework\Constraint\Count;
use Validation;
use Illuminate\Support\Facades\Storage;
use App\Jobs\RosterCreate;
use App\Model\AllocationRule;
use App\Model\DriverGeo;

class TaskController extends BaseController
{

    public function updateTaskStatus(Request $request)
    {
        $note = '';
        if (isset($request->note)) {
            $note = $request->note;
        } else {
            $note = '';
        }


        $orderAll   = Task::where('id', $request->task_id)->get();
        $orderId    = Task::where('id', $request->task_id)->first('order_id');
        $allCount   = Count($orderAll);
        $inProgress = $orderAll->where('task_status', 2);
        $lasttask   = count($orderAll->where('task_status', 3));
        $check      = $allCount - $lasttask;
        if ($request->task_id == 3) {
            if ($check == 1) {
                $Order  = Order::where('id', $orderId)->update(['status' => $request->task_status, 'note' => $note]);
            }
        } else {
            $Order  = Order::where('id', $orderId)->update(['status' => $request->task_status, 'note' => $note]);
        }
        $task = Task::where('id', $request->task_id)->update(['task_status' => $request->task_status]);
        $newDetails = Task::where('id', $request->task_id)->with(['location','tasktype','pricing','order.customer'])->first();
        return response()->json([
            'data' => $newDetails,
        ]);
    }

    public function TaskUpdateReject(Request $request){
        $check = Order::where('id', $request->order_id)->first();
        if (!isset($check)) {
            return response()->json([
                'message' => 'Order Not Found With This Id',
            ], 404);
        }
        if (isset($check) && $check->driver_id != null) {
            return response()->json([
                'message' => 'Order Already Assigned',
            ], 404);
        }
        if ($request->status == 1) {
            Order::where('id', $request->order_id)->update(['driver_id' => $request->driver_id, 'status' => 'assigned']);
            Task::where('order_id',$request->order_id)->update(['task_status' => 1]);
            return response()->json([
                'data' => 'Task Accecpted Successfully',
            ], 200);
        } else {
            $data = [
                'order_id'          => $request->order_id,
                'driver_id'         => $request->driver_id,
                'status'            => $request->status,
                'created_at'        => Carbon::now()->toDateTimeString(),
                'updated_at'        => Carbon::now()->toDateTimeString(),
            ];
            TaskReject::create($data);
            return response()->json([
                'data' => 'Task Rejected Successfully',
            ], 200);
        }
    }

    public function CreateTask(Request $request)
    {
        $loc_id = $cus_id = $send_loc_id = 0;
        $images = [];
        $last = '';
        $customer = [];
        $finalLocation = [];
        $taskcount = 0;
        $latitude  = [];
        $longitude = [];

        // dd($request->all());

        if (isset($request->file) && count($request->file) > 0) {
            $folder = str_pad(Auth::user()->id, 8, '0', STR_PAD_LEFT);
            $folder = 'client_' . $folder;
            $files = $request->file('file');
            foreach ($files as $key => $value) {
                $file = $value;
                $file_name = uniqid() . '.' .  $file->getClientOriginalExtension();

                $s3filePath = '/assets/' . $folder . '/' . $file_name;
                $path = Storage::disk('s3')->put($s3filePath, $file, 'public');
                array_push($images, $path);
                // $can = Storage::disk('s3')->url('image.png');
                // $last = str_replace('image.png', '', $can);

            }
            $last = implode(",", $images);
        }
        if (isset($request->customer_email)) {
            $customer = Customer::where('email', '=', $request->customer_email)->first();
            if (isset($customer->id)) {
                $cus_id = $customer->id;
            } else {
                $cus = [
                    'name' => $request->customer_name,
                    'email' => $request->customer_email,
                    'phone_number' => $request->customer_phone_number,
                ];
                $customer = Customer::create($cus);
                $cus_id = $customer->id;
            }
        } else {
            // $cus_id = $request->ids;
            // $customer = Customer::where('id',$request->ids)->first();
        }

        $notification_time = isset($request->schedule_time) ? $request->schedule_time : Carbon::now()->toDateTimeString();

        $agent_id        = $request->allocation_type === 'm' ? $request->agent : null;
        $order = [
            'customer_id'                => $cus_id,
            'recipient_phone'            => $request->recipient_phone,
            'Recipient_email'            => $request->recipient_email,
            'task_description'           => $request->task_description,
            'driver_id'                  => $agent_id,
            'auto_alloction'             => $request->allocation_type,
            'images_array'               => $last,
            'order_type'                 => $request->task_dispatch,
            'order_time'                 => $notification_time,
            'status'                     => $agent_id != null ? 'assigned' : 'unassigned',
            'cash_to_be_collected'       => $request->cash_to_be_collected
        ];
        $orders = Order::create($order);

        $dep_id = null;

        foreach ($request->task as $key => $value) {
            $taskcount++;
            if (isset($value)) {

                $loc = [
                    'latitude'    => $value['latitude'],
                    'longitude'   => $value['longitude'],
                    'short_name'  => $value['short_name'],
                    'address'     => $value['address'],
                    'post_code'   => $value['post_code'],
                    'customer_id' => $cus_id,
                ];
                $Loction = Location::create($loc);
                $loc_id = $Loction->id;
            }
            // else {
            //     if($key == 0){
            //         $loc_id = $request->old_address_id;

            //     }else{
            //         $loc_id = $request->input('old_address_id'.$key);

            //     }

            // }

            if ($key == 0) {
                $send_loc_id = $loc_id;
                $finalLocation = Location::where('id', $loc_id)->first();
            }
            $task_allo_type = isset($request->appointment_duration) ? $request->appointment_duration : null;

            $data = [
                'order_id'                   => $orders->id,
                'task_type_id'               => $value['task_type_id'],
                'location_id'                => $loc_id,
                'appointment_duration'       => $request['appointment_duration'],
                'dependent_task_id'          => $dep_id,
                'task_status'                => $agent_id != null ? 1 : 0,
                'allocation_type'            => $request->allocation_type
            ];
            // if(!empty($request->pricing_rule_id)){
            //     $data['pricing_rule_id'] = $request->pricing_rule_id;
            // }
            $task = Task::create($data);
            $dep_id = $task->id;
        }


        if (isset($request->allocation_type) && $request->allocation_type === 'a') {
            // if (isset($request->team_tag)) {
            //     $orders->teamtags()->sync($request->team_tag);
            // }
            // if (isset($request->agent_tag)) {
            //     $orders->drivertags()->sync($request->agent_tag);
            // }
        }
        $geo = null;
        if ($request->allocation_type === 'a') {
            $geo = $this->createRoster($send_loc_id);

            $agent_id = null;
        }
       
        $header = $request->header();
        if ($request->allocation_type === 'a' || $request->allocation_type === 'm') {
            $allocation = AllocationRule::where('id', 1)->first();
            switch ($allocation->auto_assign_logic) {
                case 'one_by_one':
                    $this->finalRoster($geo, $notification_time, $agent_id, $orders->id, $customer, $finalLocation, $taskcount, $header, $allocation);
                    break;
                case 'send_to_all':
                    $this->SendToAll($geo, $notification_time, $agent_id, $orders->id, $customer, $finalLocation, $taskcount, $header, $allocation);
                    break;
                case 'round_robin':
                    $this->finalRoster($geo, $notification_time, $agent_id, $orders->id, $customer, $finalLocation, $taskcount, $header, $allocation);
                    break;
                default:
                    $this->finalRoster($geo, $notification_time, $agent_id, $orders->id, $customer, $finalLocation, $taskcount, $header, $allocation);
            }
        }

        return response()->json([
            'message' => 'Task Created Successfully',
            'task_id' => $orders->id,
            'status'  => $orders->status,
        ], 200);

        // return redirect()->route('tasks.index')->with('success', 'Task Added Successfully!');
    }
    public function createRoster($location_id)
    {

        $getletlong = Location::where('id', $location_id)->first();
        $lat = $getletlong->latitude;
        $long = $getletlong->longitude;
        //$allgeo     = Geo::all();

        return $check = $this->findLocalityByLatLng($lat, $long);
    }


    public function findLocalityByLatLng($lat, $lng)
    {
        // get the locality_id by the coordinate //
        $latitude_y = $lat;
        $longitude_x = $lng;

        $localities = Geo::all();

        if (empty($localities))
            return false;


        foreach ($localities as $k => $locality) {

            $all_points = $locality->geo_array;
            $temp = $all_points;
            $temp = str_replace('(', '[', $temp);
            $temp = str_replace(')', ']', $temp);
            $temp = '[' . $temp . ']';
            $temp_array =  json_decode($temp, true);

            foreach ($temp_array as $k => $v) {
                $data[] = [
                    'lat' => $v[0],
                    'lng' => $v[1]
                ];
            }


            // $all_points[]= $all_points[0]; // push the first point in end to complete
            $vertices_x = $vertices_y = array();

            foreach ($data as $key => $value) {

                $vertices_y[] = $value['lat'];
                $vertices_x[] = $value['lng'];
            }


            $points_polygon = count($vertices_x) - 1;  // number vertices - zero-based array
            $points_polygon;

            if ($this->is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)) {

                return $locality->id;
            }
        }

        return false;
    }

    public function is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)
    {
        $i = $j = $c = 0;
        for ($i = 0, $j = $points_polygon; $i < $points_polygon; $j = $i++) {
            if ((($vertices_y[$i]  >  $latitude_y != ($vertices_y[$j] > $latitude_y)) &&
                ($longitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($latitude_y - $vertices_y[$i]) / ($vertices_y[$j] - $vertices_y[$i]) + $vertices_x[$i])))
                $c = !$c;
        }
        return $c;
    }

    public function finalRoster($geo, $notification_time, $agent_id, $orders_id, $customer, $finalLocation, $taskcount, $header, $allocation)
    {
        $allcation_type = 'N';
        $date = \Carbon\Carbon::today();
        $auth = Client::where('database_name', $header['client'][0])->with('getAllocation')->first();
        $expriedate = (int)$auth->getAllocation->request_expiry;
        $beforetime = (int)$auth->getAllocation->start_before_task_time;
        $maxsize    = (int)$auth->getAllocation->maximum_batch_size;
        $type       = $auth->getAllocation->acknowledgement_type;
        $try        = $auth->getAllocation->number_of_retries;
        $time       = $this->checkTimeDiffrence($notification_time, $beforetime);
        $randem     = rand(11111111, 99999999);
        $extraData  = [];
        if ($type == 'acceptreject') {
            $allcation_type = 'AR';
        }
        $extraData = [
            'customer_name'            => $customer->name,
            'customer_phone_number'    => $customer->phone_number,
            'sort_name'                => $finalLocation->short_name,
            'address'                  => $finalLocation->address,
            'lat'                      => $finalLocation->latitude,
            'long'                     => $finalLocation->longitude,
            'task_count'               => $taskcount,
            'unique_id'                => $randem,
            'created_at'               => Carbon::now()->toDateTimeString(),
            'updated_at'               => Carbon::now()->toDateTimeString(),
        ];
        if (!isset($geo)) {
            $oneagent = Agent::where('id', $agent_id)->first();
            $data = [
                'order_id'            => $orders_id,
                'driver_id'           => $agent_id,
                'notification_time'   => $time,
                'type'                => $allcation_type,
                'client_code'         => $auth->code,
                'created_at'          => Carbon::now()->toDateTimeString(),
                'updated_at'          => Carbon::now()->toDateTimeString(),
                'device_type'         => $oneagent->device_type,
                'device_token'        => $oneagent->device_token,
                'detail_id'           => $randem,
            ];

            $this->dispatchNow(new RosterCreate($data, $extraData));
            return $task = Roster::create($data);
        } else {
            $dummyentry = [];
            $all        = [];
            $extra      = [];
            $getgeo = DriverGeo::where('geo_id', $geo)->with('agent')->get('driver_id');

            $totalcount = $getgeo->count();
            $orders = order::where('driver_id', '!=', null)->whereDate('created_at', $date)->groupBy('driver_id')->get('driver_id');

            $allreadytaken = [];
            foreach ($orders as $ids) {
                array_push($allreadytaken, $ids->driver_id);
            }
            $counter = 0;
            $data = [];
            for ($i = 1; $i <= $try; $i++) {
                foreach ($getgeo as $key =>  $geoitem) {


                    if (in_array($geoitem->driver_id, $allreadytaken)) {
                        $extra = [
                            'id' => $geoitem->driver_id,
                            'device_type' => $geoitem->agent->device_type, 'device_token' => $geoitem->agent->device_token
                        ];
                        array_push($remening, $extra);
                    } else {


                        $data = [
                            'order_id'            => $orders_id,
                            'driver_id'           => $geoitem->driver_id,
                            'notification_time'   => $time,
                            'type'                => $allcation_type,
                            'client_code'         => Auth::user()->code,
                            'created_at'          => Carbon::now()->toDateTimeString(),
                            'updated_at'          => Carbon::now()->toDateTimeString(),
                            'device_type'         => $geoitem->agent->device_type,
                            'device_token'        => $geoitem->agent->device_token,
                            'detail_id'           => $randem,

                        ];
                        if (count($dummyentry) < 1) {
                            array_push($dummyentry, $data);
                            array_push($dummyentry, $data);
                            array_push($dummyentry, $data);
                        }
                        $time = Carbon::parse($time)
                            ->addSeconds($expriedate)
                            ->format('Y-m-d H:i:s');
                        array_push($all, $data);
                        $counter++;
                    }

                    if ($allcation_type == 'N' && count($all) > 0) {

                        break;
                    }
                }
                if ($allcation_type == 'N' && count($all) > 0) {

                    break;
                }
            }

            if ($totalcount > $counter) {
                $loopcount =  $totalcount - $counter;

                for ($i = 0; $i < $loopcount; $i++) {
                    if ($allcation_type == 'N' && count($all) > 0) {

                        break;
                    }

                    $data = [
                        'order_id'            => $orders_id,
                        'driver_id'           => $remening[$i]['id'],
                        'notification_time'   => $time,
                        'type'                => $allcation_type,
                        'client_code'         => Auth::user()->code,
                        'created_at'          => Carbon::now()->toDateTimeString(),
                        'updated_at'          => Carbon::now()->toDateTimeString(),
                        'device_type'         => $remening[$i]['device_type'],
                        'device_token'        => $remening[$i]['device_token'],
                        'detail_id'           => $randem,
                    ];
                    if (count($dummyentry) < 1) {
                        array_push($dummyentry, $data);
                        array_push($dummyentry, $data);
                        array_push($dummyentry, $data);
                    }
                    $time = Carbon::parse($time)
                        ->addSeconds($expriedate)
                        ->format('Y-m-d H:i:s');
                    array_push($all, $data);
                }
            }

            $this->dispatchNow(new RosterCreate($all, $extraData));
            return Roster::create($dummyentry);
        }
    }
    public function checkTimeDiffrence($notification_time, $beforetime){
        $to   = Carbon::createFromFormat('Y-m-d H:s:i', Carbon::now()->toDateTimeString());
        $from = Carbon::createFromFormat('Y-m-d H:s:i', Carbon::parse($notification_time)->format('Y-m-d H:i:s'));
        $diff_in_minutes = $to->diffInMinutes($from);
        if ($diff_in_minutes < $beforetime) {
            return  Carbon::now()->toDateTimeString();
        } else {
            return  $notification_time;
        }
    }

    public function SendToAll($geo, $notification_time, $agent_id, $orders_id, $customer, $finalLocation, $taskcount, $header, $allocation){
        $allcation_type = 'N';
        $date       = \Carbon\Carbon::today();
        $auth       = Client::where('database_name', $header['client'][0])->with(['getAllocation', 'getPreference'])->first();
        $try        = $auth->getAllocation->number_of_retries;
        $expriedate = (int)$auth->getAllocation->request_expiry;
        $type       = $auth->getPreference->acknowledgement_type;
        $maxsize    = (int)$auth->getAllocation->maximum_batch_size;
        $beforetime = (int)$auth->getAllocation->start_before_task_time;
        $time       = $this->checkTimeDiffrence($notification_time, $beforetime);
        $randem     = rand(11111111, 99999999);
        $data = [];
        if ($type == 'acceptreject') {
            $allcation_type = 'AR';
        }
        $extraData = [
            'customer_name'            => $customer->name,
            'customer_phone_number'    => $customer->phone_number,
            'sort_name'                => $finalLocation->short_name,
            'address'                  => $finalLocation->address,
            'lat'                      => $finalLocation->latitude,
            'long'                     => $finalLocation->longitude,
            'task_count'               => $taskcount,
            'unique_id'                => $randem,
            'created_at'               => Carbon::now()->toDateTimeString(),
            'updated_at'               => Carbon::now()->toDateTimeString(),
        ];
        if (!isset($geo)) {
            $oneagent = Agent::where('id', $agent_id)->first();
            $data = [
                'order_id'            => $orders_id,
                'driver_id'           => $agent_id,
                'notification_time'   => $time,
                'type'                => $allcation_type,
                'client_code'         => Auth::user()->code,
                'created_at'          => Carbon::now()->toDateTimeString(),
                'updated_at'          => Carbon::now()->toDateTimeString(),
                'device_type'         => $oneagent->device_type,
                'device_token'        => $oneagent->device_token,
                'detail_id'           => $randem,
            ];
            $this->dispatchNow(new RosterCreate($data, $extraData));
            return $task = Roster::create($data);
        } else {
            $getgeo = DriverGeo::where('geo_id', $geo)->with('agent')->get('driver_id');
            if(count($getgeo) >0){
                for ($i = 1; $i <= $try; $i++) {
                    foreach ($getgeo as $key =>  $geoitem) {
                        $datas = [
                            'order_id'            => $orders_id,
                            'driver_id'           => $geoitem->driver_id,
                            'notification_time'   => $time,
                            'type'                => $allcation_type,
                            'client_code'         => Auth::user()->code,
                            'created_at'          => Carbon::now()->toDateTimeString(),
                            'updated_at'          => Carbon::now()->toDateTimeString(),
                            'device_type'         => $geoitem->agent->device_type,
                            'device_token'        => $geoitem->agent->device_token,
                            'detail_id'           => $randem,
    
                        ];
                        array_push($data, $datas);
                        if ($allcation_type == 'N') {
                            break;
                        }
                    }
    
                    if ($allcation_type == 'N') {
                        break;
                    }
                }
                $this->dispatchNow(new RosterCreate($data, $extraData));
                print_r($data);
                die;
                return $task = Roster::create($data[0]);
            }else{
                return;
            }
        }
    }

    public function currentstatus(Request $request){
        $status = Order::where('id', $request->task_id)->first();
        return response()->json([
            'task_id' => $status->id,
            'status'  => $status->status,
        ], 200);
    }
}
