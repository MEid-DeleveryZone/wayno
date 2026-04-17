<?php
namespace Database\Seeders;
use DB;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->delete();
        $categories = array(
            array(
                'id' => '1',
                'slug' => 'Root',
                'type_id' => 3,
                'is_visible' => 0,
                'status' => 1,
                'position' => 1,
                'is_core' => 1,
                'can_add_products' => 0,
                'display_mode' => 1,
                'parent_id' => NULL
            ),
            array(
                'id' => '2',
                'slug' => 'Delivery',
                'type_id' => 1,
                'is_visible' => 1,
                'status' => 1,
                'position' => 1,
                'is_core' => 1,
                'can_add_products' => 1,
                'display_mode' => 1,
                'parent_id' => 1
            ),
            array(
                'id' => '3',
                'slug' => 'Restaurant',
                'type_id' => 1,
                'is_visible' => 1,
                'status' => 1,
                'position' => 1,
                'is_core' => 1,
                'can_add_products' => 1,
                'display_mode' => 1,
                'parent_id' => 1
            ),
            array(
                'id' => '4',
                'slug' => 'Supermarket',
                'type_id' => 1,
                'is_visible' => 1,
                'status' => 1,
                'position' => 1,
                'is_core' => 1,
                'can_add_products' => 1,
                'display_mode' => 1,
                'parent_id' => 1
            ),
            array(
                'id' => '5',
                'slug' => 'Pharmacy',
                'type_id' => 1,
                'is_visible' => 1,
                'status' => 1,
                'position' => 1,
                'is_core' => 1,
                'can_add_products' => 1,
                'display_mode' => 1,
                'parent_id' => 1
            ),
            array(
                'id' => '6',
                'slug' => 'Send something',
                'type_id' => 1,
                'is_visible' => 1,
                'status' => 1,
                'position' => 1,
                'is_core' => 1,
                'can_add_products' => 1,
                'display_mode' => 1,
                'parent_id' => 2
            ),
            array(
                'id' => '7',
                'slug' => 'Buy something',
                'type_id' => 1,
                'is_visible' => 1,
                'status' => 1,
                'position' => 1,
                'is_core' => 1,
                'can_add_products' => 1,
                'display_mode' => 1,
                'parent_id' => 2
            ),
            array(
                'id' => '8',
                'slug' => 'Vegetables',
                'type_id' => 1,
                'is_visible' => 1,
                'status' => 1,
                'position' => 1,
                'is_core' => 1,
                'can_add_products' => 1,
                'display_mode' => 1,
                'parent_id' => 4
            ),
            array(
                'id' => '9',
                'slug' => 'Fruits',
                'type_id' => 1,
                'is_visible' => 1,
                'status' => 1,
                'position' => 1,
                'is_core' => 1,
                'can_add_products' => 1,
                'display_mode' => 1,
                'parent_id' => 4
            ),
            array(
                'id' => '10',
                'slug' => 'Dairy and Eggs',
                'type_id' => 1,
                'is_visible' => 1,
                'status' => 1,
                'position' => 1,
                'is_core' => 1,
                'can_add_products' => 1,
                'display_mode' => 1,
                'parent_id' => 4
            ),
            array(
                'id' => '11',
                'slug' => 'E-Commerce',
                'type_id' => 1,
                'is_visible' => 1,
                'status' => 1,
                'position' => 1,
                'is_core' => 1,
                'can_add_products' => 1,
                'display_mode' => 1,
                'parent_id' => 1
            ),
            array(
                'id' => '12',
                'slug' => 'Cloth',
                'type_id' => 1,
                'is_visible' => 1,
                'status' => 1,
                'position' => 1,
                'is_core' => 1,
                'can_add_products' => 1,
                'display_mode' => 1,
                'parent_id' => 1
            ),
            array(
                'id' => '13',
                'slug' => 'Dispatcher',
                'type_id' => 1,
                'is_visible' => 1,
                'status' => 1,
                'position' => 1,
                'is_core' => 1,
                'can_add_products' => 1,
                'display_mode' => 1,
                'parent_id' => 1
            ),
        ); 
        DB::table('categories')->insert($categories);
        DB::table('category_translations')->delete();
        $category_translations = array(
            array(
                'id' => 1,
                'name' => 'root',
                'trans-slug' => '',
                'meta_title' => 'root',
                'meta_description' => '',
                'meta_keywords' => '',
                'category_id' => 1,
                'language_id' => 1,
            ),
            array(
                'id' => 2,
                'name' => 'Delivery',
                'trans-slug' => '',
                'meta_title' => 'Delivery',
                'meta_description' => '',
                'meta_keywords' => '',
                'category_id' => 2,
                'language_id' => 1,
            ),
            array(
                'id' => 3,
                'name' => 'Restaurant',
                'trans-slug' => '',
                'meta_title' => 'Restaurant',
                'meta_description' => '',
                'meta_keywords' => '',
                'category_id' => 3,
                'language_id' => 1,
            ),
            array(
                'id' => 4,
                'name' => 'Supermarket',
                'trans-slug' => '',
                'meta_title' => 'Supermarket',
                'meta_description' => '',
                'meta_keywords' => '',
                'category_id' => 4,
                'language_id' => 1,
            ),
            array(
                'id' => 5,
                'name' => 'Pharmacy',
                'trans-slug' => '',
                'meta_title' => 'Pharmacy',
                'meta_description' => '',
                'meta_keywords' => '',
                'category_id' => 5,
                'language_id' => 1,
            ),
            array(
                'id' => 6,
                'name' => 'Send something',
                'trans-slug' => '',
                'meta_title' => 'Send something',
                'meta_description' => '',
                'meta_keywords' => '',
                'category_id' => 6,
                'language_id' => 1,
            ),
            array(
                'id' => 7,
                'name' => 'Buy something',
                'trans-slug' => '',
                'meta_title' => 'Buy something',
                'meta_description' => '',
                'meta_keywords' => '',
                'category_id' => 7,
                'language_id' => 1,
            ),
            array(
                'id' => 8,
                'name' => 'Vegetables',
                'trans-slug' => '',
                'meta_title' => 'Vegetables',
                'meta_description' => '',
                'meta_keywords' => '',
                'category_id' => 8,
                'language_id' => 1,
            ),
            array(
                'id' => 9,
                'name' => 'Fruits',
                'trans-slug' => '',
                'meta_title' => 'Fruits',
                'meta_description' => '',
                'meta_keywords' => '',
                'category_id' => 9,
                'language_id' => 1,
            ),
            array(
                'id' => 10,
                'name' => 'Dairy and Eggs',
                'trans-slug' => '',
                'meta_title' => 'Dairy and Eggs',
                'meta_description' => '',
                'meta_keywords' => '',
                'category_id' => 10,
                'language_id' => 1,
            ),
            array(
                'id' => 11,
                'name' => 'E-Commerce',
                'trans-slug' => '',
                'meta_title' => 'E-Commerce',
                'meta_description' => '',
                'meta_keywords' => '',
                'category_id' => 11,
                'language_id' => 1,
            ),
            array(
                'id' => 12,
                'name' => 'Cloth',
                'trans-slug' => '',
                'meta_title' => 'Cloth',
                'meta_description' => '',
                'meta_keywords' => '',
                'category_id' => 12,
                'language_id' => 1,
            ),
            array(
                'id' => 13,
                'name' => 'Dispatcher',
                'trans-slug' => '',
                'meta_title' => 'Dispatcher',
                'meta_description' => '',
                'meta_keywords' => '',
                'category_id' => 13,
                'language_id' => 1,
            ),
        );
        DB::table('category_translations')->insert($category_translations);
    }
}
