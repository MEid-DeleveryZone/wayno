<?php

namespace App\Imports;
use Illuminate\Support\Collection;
use Spatie\Geocoder\Facades\Geocoder;
use App\Models\{Vendor, CsvVendorImport};
use Maatwebsite\Excel\Concerns\ToCollection;

class VendorImport implements ToCollection
{
    public function  __construct($csv_vendor_import_id){
        $this->csv_vendor_import_id= $csv_vendor_import_id;
    }
    public function collection(Collection $rows){
        try {
            $data = array();
            $error = array();
            $i = 0;
            foreach ($rows as $row) {
                $row = $row->toArray();
                $checker = 0;
                if ($row[0] != "Logo") {
                    if ($row[2] != "") {
                        $vendor_check = Vendor::where('name', $row[2])->first();
                        if ($vendor_check) { //if not empty, then is it already exists
                            $error[] = "Row " . $i . " : Vendor name already Exist";
                            $checker = 1;
                        }
                    } else {
                        $error[] = "Row " . $i . " : Name cannot be empty";
                        $checker = 1;
                    }
                    if($row[8] != ""){
                        if(!is_numeric($row[8])) {
                            $error[] = "Row " . $i . " : Invalid input for order prepare time";
                            $checker = 1;
                        }
                    }
                    if($row[9] != ""){
                        if(!is_numeric($row[9])) {
                            $error[] = "Row " . $i . " : Invalid input for Auto Reject Time";
                            $checker = 1;
                        }
                    }
                    if($row[10] != ""){
                        if(!is_numeric($row[10])) {
                            $error[] = "Row " . $i . " : Invalid input for Order Minimum Amount";
                            $checker = 1;
                        }
                    }
                    if($row[12] != ""){
                        if(!is_numeric($row[12])) {
                            $error[] = "Row " . $i . " : Invalid input for Commission Percent";
                            $checker = 1;
                        }
                    }
                    if($row[13] != ""){
                        if(!is_numeric($row[13])) {
                            $error[] = "Row " . $i . " : Invalid input for Commission Fixed Per Order";
                            $checker = 1;
                        }
                    }
                    if($row[14] != ""){
                        if(!is_numeric($row[14])) {
                            $error[] = "Row " . $i . " : Invalid input for Commission Monthly";
                            $checker = 1;
                        }
                    }
                    if($checker == 0) { 
                        $data[] = $row;
                    }
                }
                $i++;
            }
            if (!empty($data)) {
                $insert_vendor_details = [];
                foreach ($data as $da) {
                    if (!Vendor::where('name', $da[2])->exists()) {
                        $latitude = 0;
                        $longitude = 0;
                        if($da[4]){
                           $geoInfo = Geocoder::getCoordinatesForAddress($da[4]);
                           $latitude = $geoInfo['lat'];
                           $longitude = $geoInfo['lng'];
                        }
                        $insert_vendor_details[] = array(
                            'name' => $da[2],
                            'latitude' => $latitude,
                            'longitude' => $longitude,
                            'dine_in' => ($da[5] == 'TRUE') ? 1 : 0,
                            'takeaway' => ($da[6] == 'TRUE') ? 1 : 0,
                            'delivery' => ($da[7] == 'TRUE') ? 1 : 0,
                            'desc' => ($da[3] == "") ? NULL : $da[3],
                            'show_slot' => ($da[15] == "TRUE") ? 1 : 0,
                            'logo' => ($da[0] == "") ? NULL : $da[0],
                            'banner' => ($da[1] == "") ? NULL : $da[1],
                            'address' => ($da[4] == "") ? NULL : $da[4],
                            'order_pre_time' => ($da[8] == "") ? NULL : $da[8],
                            'auto_reject_time' => ($da[9] == "") ? NULL : $da[9],
                            'order_min_amount' => ($da[10] == "") ? NULL : $da[10],
                            'commission_monthly' => ($da[14] == "") ? NULL : $da[14],
                            'commission_percent' => ($da[12] == "") ? NULL : $da[12],
                            'commission_fixed_per_order' => ($da[13] == "") ? NULL : $da[13],
                            'phone_no' => ($da[16] == "") ? NULL : $da[16],
                            'email' => ($da[17] == "") ? NULL : $da[17],
                        );
                    }
                }
                Vendor::insert($insert_vendor_details);
            }
            $csv_vendor_import = CsvVendorImport::where('id', $this->csv_vendor_import_id)->first();
            if (!empty($error)) {
                $csv_vendor_import->status = 3;
                $csv_vendor_import->error = json_encode($error, true);
            }else{
                $csv_vendor_import->status = 2;
            }
            $csv_vendor_import->save();
        } catch (Exception $e) {
            pr($e->getCode());
        }
        
    }
}
