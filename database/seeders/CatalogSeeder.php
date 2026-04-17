<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* seeding data of brands*/
        \DB::table('brands')->delete();
 
        $brand = array(
            array(
                'id' => '1',
                'title' => 'J.Crew',
                'image' => 'default/default_image.png',
                'position' => 1,
                'status' => 1
            ),
            array(
                'id' => '2',
                'title' => 'Allform',
                'image' => 'default/default_image.png',
                'position' => 2,
                'status' => 1
            ),
            array(
                'id' => '3',
                'title' => 'EyeBuyDirect',
                'image' => 'default/default_image.png',
                'position' => 3,
                'status' => 1
            ),
            array(
                'id' => '4',
                'title' => 'In Pictures',
                'image' => 'default/default_image.png',
                'position' => 4,
                'status' => 1
            ),
        ); 
        \DB::table('brands')->insert($brand);

        /* Add on sets tranlation*/
        \DB::table('brand_categories')->delete();
 
        $brand_cate = array(
            array(
                'brand_id' => '1',
                'category_id' => '11',
            ),
            array(
                'brand_id' => '2',
                'category_id' => '11',
            ),
            array(
                'brand_id' => '3',
                'category_id' => '11',
            ),
            array(
                'brand_id' => '4',
                'category_id' => '11',
            )
        ); 
        \DB::table('brand_categories')->insert($brand_cate);

        /* brand_translations*/
        \DB::table('brand_translations')->delete();
 
        $translation = array(
            array(
                'id' => '1',
                'title' => 'J.Crew',
                'brand_id' => 1,
                'language_id' => 1
            ),
            array(
                'id' => '2',
                'title' => 'Allform',
                'brand_id' => 2,
                'language_id' => 1
            ),
            array(
                'id' => '3',
                'title' => 'EyeBuyDirect',
                'brand_id' => 3,
                'language_id' => 1
            ),
            array(
                'id' => '4',
                'title' => 'In Pictures',
                'brand_id' => 4,
                'language_id' => 1
            )
        ); 
        \DB::table('brand_translations')->insert($translation);


    }
}