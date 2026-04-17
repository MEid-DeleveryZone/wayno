<?php
namespace Database\Seeders;
use DB;
use Illuminate\Database\Seeder;
use App\Models\PaymentOption;
class PaymentOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){ 

      $option_count = DB::table('payment_options')->count();

      $payment_options = array(
        array('id' => '1','code' => 'cod','path' => '','title' => __('Cash On Delivery'), 'off_site' => '0', 'status' => '0'),
        // array('id' => '2','code' => 'loyalty-points','path' => '','title' => 'loyalty Points', 'offsite' => '0', 'status' => '1'),
        array('id' => '3', 'path' => 'omnipay/paypal', 'code' => 'paypal',  'title' => 'PayPal', 'off_site' => '1', 'status' => '0'),
        array('id' => '4', 'path' => 'omnipay/stripe', 'code' => 'stripe', 'title' => 'Stripe', 'off_site' => '0', 'status' => '0'),
        array('id' => '5', 'path' => 'paystackhq/omnipay-paystack', 'code' => 'paystack', 'title' => 'Paystack', 'off_site' => '1', 'status' => '0'),
        array('id' => '6', 'path' => 'omnipay/payfast', 'code' => 'payfast', 'title' => 'Payfast', 'off_site' => '1', 'status' => '0'),
        array('id' => '7', 'path' => 'mobbex/sdk', 'code' => 'mobbex', 'title' => 'Mobbex', 'off_site' => '1', 'status' => '0'),
        array('id' => '8', 'path' => 'yoco/yoco-php-laravel', 'code' => 'yoco', 'title' => 'Yoco', 'off_site' => '1', 'status' => '0'),
        array('id' => '9', 'path' => 'paylink/paylink', 'code' => 'paylink', 'title' => 'Paylink', 'off_site' => '1', 'status' => '0')
      ); 

      if($option_count == 0)
      {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('payment_options')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('payment_options')->insert($payment_options);
      }
      else{
          foreach ($payment_options as $option) {
              $payop = PaymentOption::where('code', $option['code'])->first();
 
              if ($payop !== null) {
                  $payop->update(['title' => $option['title'],'off_site' => $option['off_site']]);
              } else {
                  $payop = PaymentOption::create([
                    'title' => $option['title'],
                    'code' => $option['code'],
                    'path' => $option['path'],
                    'off_site' => $option['off_site'],
                    'status' => $option['status'],
                  ]);
              }
          }
      }
     
     
      
    }
}