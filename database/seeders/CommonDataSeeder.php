<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class CommonDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* Add on sets tranlation*/
        \DB::table('tax_categories')->delete();
 
        $tax_cat = array(
            array(
                'id' => 1,
                'title' => 'VAT',
                'code' => 'vat',
                'description' => NULL,
                'is_core' => 1,
                'vendor_id' => NULL
            ),
        ); 
        \DB::table('tax_categories')->insert($tax_cat);


        /*  tax_categories*/
        \DB::table('tax_rates')->delete();
 
        $tax_r = array(
            array(
                'id' => 1,
                'identifier' => 'VAT',
                'is_zip' => 0,
                'zip_code' => '',
                'zip_from' => '',
                'zip_to' => '',
                'state' => 'Dubai',
                'country' => 'United Arab Emirates',
                'tax_rate' => '5.00',
                'tax_amount' => NULL
            ),
        ); 
        \DB::table('tax_rates')->insert($tax_r);

        /*  tax_rate_categories*/
        \DB::table('tax_rate_categories')->delete();
 
        $rate_cat = array(
            array(
                'id' => '1',
                'tax_cate_id' => '1',
                'tax_rate_id' => '1',
            ),
        ); 
        \DB::table('tax_rate_categories')->insert($rate_cat);

    }
}