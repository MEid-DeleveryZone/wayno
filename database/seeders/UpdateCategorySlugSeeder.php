<?php

namespace Database\Seeders;

use App\Models\Vendor;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class UpdateCategorySlugSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        $categories = Category::all();
        foreach ($categories as  $category) {
            $category->slug = Str::slug($category->slug, "-");
            $category->save();
        }
        $vendor_list = Vendor::all();
        foreach ($vendor_list as  $vendor) {
            $vendor->slug = Str::slug($vendor->name, "-");
            $vendor->save();
        }
    }
}
