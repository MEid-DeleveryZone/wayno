<?php
namespace Database\Seeders;
use DB;
use Illuminate\Database\Seeder;

class VendorProductTempleteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('vendor_templetes')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $vendor_templete_array = array(
            ['id' => 1,
                'title' => 'Only Product',
                'type' => 'Grid',
                'status' =>'1'
            ],
            ['id' => 2,
                'title' => 'Only Category',
                'type' => 'Grid',
                'status' =>'1'
            ],
            ['id' => 3,
                'title' => 'Only Product',
                'type' => 'List',
                'status' =>'0'
            ],
            ['id' => 4,
                'title' => 'Only Category',
                'type' => 'List',
                'status' =>'0'
            ],
			['id' => 5,
                'title' => 'Product with Category',
                'type' => 'Grid',
                'status' =>'1'
            ],
            ['id' => 6,
                'title' => 'Product with Category',
                'type' => 'List',
                'status' =>'0'
            ]
        ); 
        DB::table('vendor_templetes')->insert($vendor_templete_array);
    }
}
