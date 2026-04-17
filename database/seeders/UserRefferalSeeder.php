<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserRefferal;
use Illuminate\Database\Seeder;

class UserRefferalSeeder extends Seeder{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        $users = User::all();
        foreach($users as $user){
            $userRefferal = UserRefferal::where('user_id', $user->id)->first();
            if($userRefferal){
                if($userRefferal->refferal_code == null){
                    $userRefferal->refferal_code = $this->randomData("user_refferals", 8, 'refferal_code');
                    $userRefferal->save();
                }
            }else{
                $userRef = new UserRefferal();
                $userRef->refferal_code = $this->randomData("user_refferals", 8, 'refferal_code');
                $userRef->user_id = $user->id;
                $userRef->save();
            }
        }
    }

    /* Create random and unique client code*/
    public function randomData($table, $digit, $where){
        $random_string = substr(md5(microtime()), 0, $digit);
        while(\DB::table($table)->where($where, $random_string)->exists()){
            $random_string = substr(md5(microtime()), 0, $digit);
        }
        return $random_string;
    }
}
