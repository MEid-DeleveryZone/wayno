<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceArea extends Model
{
    protected $appends = ['geo_coordinates'];

    protected $fillable = ['name','description','geo_array','zoom_level','polygon','vendor_id'];

    public function getGeoCoordinatesAttribute(){
        $data = [];  
        $temp = $this->geo_array;
        $temp = str_replace('(','[',$temp);
        $temp = str_replace(')',']',$temp);
        $temp = '['.$temp.']';
        $temp_array =  json_decode($temp,true);

        foreach($temp_array as $k=>$v){
            $data[] = [
                'lat' => $v[0],
                'lng' => $v[1]
            ];
        }
        return $data;
    }
}
