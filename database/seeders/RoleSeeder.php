<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('roles')->delete();
 
        $maps = array(
            array(
                'id' => 1,
                'role' => 'Buyer',
                'status' => '1'
            ),
            array(
                'id' => 2,
                'role' => 'Seller',
                'status' => '1'
            ),
        ); 
        \DB::table('roles')->insert($maps);
    }
}
