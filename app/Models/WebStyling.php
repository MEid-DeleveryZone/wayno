<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebStyling extends Model{

    use HasFactory;

    public function styleOption(){
        return $this->hasOne('App\Models\WebStylingOption')->where('is_selected', 1);
    }

    public static function getSelectedData(){
        $app_styles_array = [];
        $app_styles = WebStyling::select('id','name')->with('styleOption')->get();
        foreach ($app_styles as $app_style) {
            $key_name = str_replace(" ","_",strtolower($app_style->name));;
            if($app_style->name == "Tab Bar Style" || $app_style->name == "Home Page Style"){
                $template_id = $app_style->styleOption->template_id;
            }else {
                $template_id = $app_style->styleOption->name;
            }
            $app_styles_array[]=array(
                'key' => $key_name,
                'value' => $template_id,
            );
        }
        return $app_styles_array;
    }
}
