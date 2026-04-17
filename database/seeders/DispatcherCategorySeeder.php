<?php

namespace Database\Seeders;
use Carbon\Carbon;
use App\Models\Category;
use Illuminate\Database\Seeder;
use App\Models\Category_translation;

class DispatcherCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        $category_id = Category::insertGetId([
            'status' => 1,
            'is_core' => 1,
            'type_id' => 1,
            'position' => 1,
            'parent_id' => 1,
            'is_visible' => 1,
            'display_mode' => 1,
            'slug' => 'Dispatcher',
            'can_add_products' => 1,
            'updated_at' => Carbon::now(),
            'created_at' =>  Carbon::now(),
        ]);
        Category_translation::create([
            'language_id' => 1,
            'trans-slug' => '',
            'meta_keywords' => '',
            'name' => 'Dispatcher',
            'meta_description' => '',
            'meta_title' => 'Dispatcher',
            'category_id' => $category_id
        ]);
    }
}
