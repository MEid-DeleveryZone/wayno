<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class VariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    { 
        /*      variants        */
        \DB::table('variants')->delete();

        $vari = array(
            array(
                'id' => 1,
                'title' => 'Size',
                'type' => 1,
                'position' => 1,
                'status' => 1
            ),
            array(
                'id' => 2,
                'title' => 'Color',
                'type' => 2,
                'position' => 2,
                'status' => 1
            ),
            array(
                'id' => 3,
                'title' => 'Phones',
                'type' => 1,
                'position' => 3,
                'status' => 1
            ),
        ); 
        \DB::table('variants')->insert($vari);

        /*      variants  categories      */
        \DB::table('variant_categories')->delete();
 
        $vcl = array(
            array(
                'variant_id' => 1,
                'category_id' => 11
            ),
            array(
                'variant_id' => 2,
                'category_id' => 11
            ),
            array(
                'variant_id' => 3,
                'category_id' => 12
            ),
        ); 
        \DB::table('variant_categories')->insert($vcl);

        /*      variant     translations        */
        \DB::table('variant_translations')->delete();
 
        $variant_trans = array(
            array(
                'id' => 1,
                'title' => 'Size',
                'variant_id' => 1,
                'language_id' => 1
            ),
            array(
                'id' => 2,
                'title' => 'Color',
                'variant_id' => 2,
                'language_id' => 1
            ),
            array(
                'id' => 3,
                'title' => 'Phones',
                'variant_id' => 3,
                'language_id' => 1
            ),
        ); 
        \DB::table('variant_translations')->insert($variant_trans);

        /*      variants  options      */
        \DB::table('variant_options')->delete();
 
        $vc = array(
            array(
                'id' => 1,
                'title' => 'Small',
                'variant_id' => 1,
                'hexacode' => '',
                'position' => 1,
            ),
            array(
                'id' => 2,
                'title' => 'White',
                'variant_id' => 2,
                'hexacode' => '#ffffff',
                'position' => 1,
            ),
            array(
                'id' => 3,
                'title' => 'Black',
                'variant_id' => 2,
                'hexacode' => '#000000',
                'position' => 1,
            ),
            array(
                'id' => 4,
                'title' => 'Grey',
                'variant_id' => 2,
                'hexacode' => '#808080',
                'position' => 1,
            ),
            array(
                'id' => 5,
                'title' => 'Medium',
                'variant_id' => 1,
                'hexacode' => '',
                'position' => 1,
            ),
            array(
                'id' => 6,
                'title' => 'Large',
                'variant_id' => 1,
                'hexacode' => '',
                'position' => 1,
            ),
            array(
                'id' => 7,
                'title' => 'IPhone',
                'variant_id' => 3,
                'hexacode' => '',
                'position' => 1,
            ),
            array(
                'id' => 8,
                'title' => 'Samsung',
                'variant_id' => 3,
                'hexacode' => '',
                'position' => 1,
            ),
            array(
                'id' => 9,
                'title' => 'Xiaomi',
                'variant_id' => 3,
                'hexacode' => '',
                'position' => 1,
            ),
        ); 
        \DB::table('variant_options')->insert($vc);

        /*     variant_option_translations    */
        \DB::table('variant_option_translations')->delete();
 
        $vct = array(
            array(
                'id' => 1,
                'title' => 'Small',
                'variant_option_id' => 1,
                'language_id' => 1,
            ),
            array(
                'id' => 2,
                'title' => 'White',
                'variant_option_id' => 2,
                'language_id' => 1,
            ),
            array(
                'id' => 3,
                'title' => 'Black',
                'variant_option_id' => 3,
                'language_id' => 1,
            ),
            array(
                'id' => 4,
                'title' => 'Grey',
                'variant_option_id' => 4,
                'language_id' => 1,
            ),array(
                'id' => 5,
                'title' => 'Medium',
                'variant_option_id' => 5,
                'language_id' => 1,
            ),
            array(
                'id' => 6,
                'title' => 'Large',
                'variant_option_id' => 6,
                'language_id' => 1,
            ),
            array(
                'id' => 7,
                'title' => 'IPhone',
                'variant_option_id' => 7,
                'language_id' => 1,
            ),
            array(
                'id' => 8,
                'title' => 'Samsung',
                'variant_option_id' => 8,
                'language_id' => 1,
            ),array(
                'id' => 9,
                'title' => 'Xiaomi',
                'variant_option_id' => 9,
                'language_id' => 1,
            ),
        ); 
        \DB::table('variant_option_translations')->insert($vct);
    }
}


