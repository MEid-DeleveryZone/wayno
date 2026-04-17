<?php
namespace Database\Seeders;
use DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminsTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run(){
        DB::table('admins')->truncate();
        $maps = array(
            array(
                'name' => 'Admin',
		        'email' => 'admin@cbl.com',
		        'email_verified_at' => now(),
		        'password' => Hash::make('password'),
		        'remember_token' => \Str::random(10),
            ),
        ); 
        DB::table('admins')->insert($maps);
    }
}
