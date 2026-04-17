<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EmailSuccessFailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (is_null(DB::table('email_templates')->where('label', '=', 'SuccessEmail')->first())) {
            $create_array = 
                [
                    'label' => 'SuccessEmail',
                    'subject' => 'Success Email Notification',
                    'tags' => '{name}',
                    'content' => '<table style="width: 100%; background-color:#fff;"> <thead> <tr> <th colspan="2" style="text-align: center;"> <a style="display: block;margin-bottom: 10px;" href="#"><img src="images/logo.png" alt=""> </a> <h1 style="margin: 0 0 10px;font-weight:400;">Thanks for your order</h1> <p style="margin: 0 0 20px;font-weight:300;">Hi {name}, <br> Payment done successfully. </p> <a style="display: inline-block; padding: 6.7px 29px;border-radius: 4px;background:#8142ff;line-height: 20px; text-transform: uppercase;font-size: 14px;font-weight: 700;text-decoration: none;color: #fff;" href="#">View your order</a> </th> </tr> </thead> <tbody> <tr> <td colspan="2"> <table style="width:100%;border: 1px solid rgb(221 221 221 / 41%);"> <thead> <tr> <th colspan="2" style="border-bottom: 1px solid rgb(221 221 221 / 41%);"> <h3 style="font-weight: 700;">Items Ordered</h3> </th> </tr> </thead> <tbody> <tr style="vertical-align: top;"> <td style="border-bottom: 1px solid rgb(221 221 221 / 41%);border-right: 1px solid rgb(221 221 221 / 41%);width: 50%;"> <p style="margin-bottom: 5px;"><b></b></p> <p></p> </td> </tr> <tr> <td colspan="2" style="padding: 0;"> <table style="width:100%;"> <tbody>  </tbody> <tfoot> <tr> <td colspan="2" style="background-color: #8142ff;color: #fff; border-top: 1px solid rgb(221 221 221 / 41%);text-align: center;"> <b>Powered By Royo</b> </td> </tr> </tfoot> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody></table>'
            

            ];
         
                EmailTemplate::create(['label' =>  $create_array['label'], 'slug' => Str::slug( $create_array['label'], "-"), 'content' =>  $create_array['content'], 'subject' =>  $create_array['subject'], 'tags' =>  $create_array['tags']]);
            
        }

        if (is_null(DB::table('email_templates')->where('label', '=', 'FailEmail')->first())) {
            $create_array = 
                [
                    'label' => 'FailEmail',
                    'subject' => 'Failure Email Notification',
                    'tags' => '{name}',
                    'content' => '<table style="width: 100%; background-color:#fff;"> <thead> <tr> <th colspan="2" style="text-align: center;"> <a style="display: block;margin-bottom: 10px;" href="#"><img src="images/logo.png" alt=""> </a> <h1 style="margin: 0 0 10px;font-weight:400;"></h1> <p style="margin: 0 0 20px;font-weight:300;">Hi {name}, <br> Payment failed. </p> <a style="display: inline-block; padding: 6.7px 29px;border-radius: 4px;background:#8142ff;line-height: 20px; text-transform: uppercase;font-size: 14px;font-weight: 700;text-decoration: none;color: #fff;" href="#">View your order</a> </th> </tr> </thead> <tbody> <tr> <td colspan="2"> <table style="width:100%;border: 1px solid rgb(221 221 221 / 41%);"> <thead> <tr> <th colspan="2" style="border-bottom: 1px solid rgb(221 221 221 / 41%);"> <h3 style="font-weight: 700;">Items Ordered</h3> </th> </tr> </thead> <tbody> <tr style="vertical-align: top;"> <td style="border-bottom: 1px solid rgb(221 221 221 / 41%);border-right: 1px solid rgb(221 221 221 / 41%);width: 50%;"> <p style="margin-bottom: 5px;"><b></b></p> <p></p> </td> </tr> <tr> <td colspan="2" style="padding: 0;"> <table style="width:100%;"> <tbody>  </tbody> <tfoot> <tr> <td colspan="2" style="background-color: #8142ff;color: #fff; border-top: 1px solid rgb(221 221 221 / 41%);text-align: center;"> <b>Powered By Royo</b> </td> </tr> </tfoot> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody></table>'
                
            ];
                EmailTemplate::create(['label' => $create_array['label'], 'slug' => Str::slug($create_array['label'], "-"), 'content' => $create_array['content'], 'subject' => $create_array['subject'], 'tags' => $create_array['tags']]);
            
        }
    }
}
