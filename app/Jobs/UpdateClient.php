<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Client;
use Illuminate\Support\Facades\DB;
use Config;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class UpdateClient implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $password;
    protected $client_data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($client_data, $password = '')
    {
        $this->password    = $password;
        $this->client_data = $client_data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(){
        $schemaName = config("database.connections.mysql.database") ? config("database.connections.mysql.database") : 'royo_orders';
        $default = [
            'driver' => env('DB_CONNECTION', 'mysql'),
            'host' => env('DB_HOST'),
            'port' => env('DB_PORT'),
            'database' => $schemaName,
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
            'engine' => null
        ];
        config(["database.connections.mysql.database" => null]);
        Config::set("database.connections.$schemaName", $default);
        config(["database.connections.mysql.database" => $schemaName]);
        if($this->client_data != 'empty'){
            $data = array(
                'name'                  =>  $this->client_data['name'],
                'logo'                  =>  $this->client_data['logo'],
                'timezone'              =>  $this->client_data['timezone'],
                'country_id'            =>  $this->client_data['country_id'],
                'company_name'          =>  $this->client_data['company_name'],
                'phone_number'          =>  $this->client_data['phone_number'],
                'company_address'       =>  $this->client_data['company_address'],
            );
            DB::connection($schemaName)->table('clients')->where('code',Auth::user()->code)->update($data);
        } else {
            DB::connection($schemaName)->table('clients')->where('code',Auth::user()->code)->update(['password'=>$this->password]);
        }
        
        DB::disconnect($schemaName);
    }
}
