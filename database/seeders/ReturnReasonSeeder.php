<?php
namespace Database\Seeders;
use DB;
use Illuminate\Database\Seeder;

class ReturnReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('return_reasons')->delete();
        $return_reason_array = array(
            ['id' => 1,
                'title' => 'The merchant shipped the wrong item',
                'order' =>'1'
            ],
            ['id' => 2,
                'title' => 'Purchase arrived too late',
                'order' =>'2'
            ],
            ['id' => 3,
                'title' => "Customer doesn't need it anymore",
                'order' =>'3'
            ],
            ['id' => 4,
                'title' => 'The product was damaged or defective',
                'order' =>'4'
            ],
            ['id' => 5,
                'title' => 'Other',
                'order' =>'5'
            ]
        ); 
        DB::table('return_reasons')->insert($return_reason_array);
    }
}
