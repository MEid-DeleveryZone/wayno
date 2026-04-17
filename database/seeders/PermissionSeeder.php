<?php
namespace Database\Seeders;
use DB;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        DB::table('permissions')->truncate();
        $permissions_array = [];
        $permissions = ['DASHBOARD', 'ORDERS', 'VENDORS', 'CUSTOMERS', 'Profile', 'CUSTOMIZE', 'CONFIGURATIONS', 'BANNER','CATALOG', 'TAX', 'PAYMENT','PROMOCODE', 'LOYALTY CARDS', 'CELEBRITY', 'WEB STYLING', 'APP STYLING', 'Accounting Orders', 'Accounting Loyality', 'Accounting Promo Codes', 'Accounting Taxes', 'Accounting Vendors','Subscriptions Customers', 'Subscriptions Vendors', 'CMS Pages', 'CMS Emails', 'Inquiries'];
        foreach ($permissions as $permission) {
           $permissions_array[]=array(
            'name' => $permission,
            'slug' => Str::slug($permission, '_'),
           );
        }
        DB::table('permissions')->insert($permissions_array);
    }
}
