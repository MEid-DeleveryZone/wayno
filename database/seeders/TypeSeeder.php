<?php
namespace Database\Seeders;
use DB;
use App\Models\Type;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('types')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $types = array(
            [
                'id' => 1,
                'sequence' => 2,
                'title' => 'Product',
                'image' => 'product.png',
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
            ],
            [
                'id' => 2,
                'sequence' => 7,
                'title' => 'Pickup/Parent',
                'image' => 'pickup_delivery.png',
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.'
            ],
            [
                'id' => 3,
                'sequence' => 3,
                'title' => 'Vendor',
                'image' => 'vendor.png',
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
            ],
            [
                'id' => 4,
                'sequence' => 4,
                'title' => 'Brand',
                'image' => 'brand.png',
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
            ],
            [
                'id' => 5,
                'sequence' => 6,
                'title' => 'Celebrity',
                'image' => 'celebrity.png',
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
            ],
            [
                'id' => 6,
                'sequence' => 1,
                'title' => 'Subcategory',
                'image' => 'subcategory.png',
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
            ],
            [
                'id' => 7,
                'sequence' => 6,
                'title' => 'Pickup/Delivery',
                'image' => 'dispatcher.png',
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
            ],
            [
                'id' => 8,
                'sequence' => 7,
                'title' => 'On Demand Service',
                'image' => 'ondemand.png',
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
            ]
        );
        foreach ($types as $type) {
           Type::upsert($type, ['id', 'title','description', 'sequence','image']);
        }
    }
}
