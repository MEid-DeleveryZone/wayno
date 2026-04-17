<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SmsnotasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sms_providers')->truncate();

        $config = array(
            array(
                'id' => 1,
                'provider' => 'Twilio Service',
                'keyword' => 'twilio',
                'status' => '0'
            ),
            array(
                'id' => 1,
                'provider' => 'Mobishastra Service',
                'keyword' => 'mobishastra',
                'status' => '1'
            ),
        );
        DB::table('sms_providers')->insert($config);
    }
}
