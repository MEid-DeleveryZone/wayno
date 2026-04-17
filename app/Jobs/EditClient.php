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

class EditClient implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $password;
    protected $client_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($client_id, $password = '')
    {
        $this->password    = $password;
        $this->client_id = $client_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = Client::where('id', $this->client_id)->first();

        $cli_code = $client->code;
        $saveData = array(
            'name'              =>  $client->name,
            'email'             =>  $client->email,
            'phone_number'      =>  $client->phone_number,
            'password'          =>  $client->password,
            'encpass'           =>  $client->encpass,
            'custom_domain'     =>  $client->custom_domain,
            'logo'              =>  $client->logo,
            'company_name'      =>  $client->company_name,
            'company_address'   =>  $client->company_address,

        );

        $schemaName = 'royo_' . $client->database_name;
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

        if(!empty($cli_code)){
            DB::connection($schemaName)->table('clients')->where('code', $cli_code)->update($saveData);
            DB::connection($schemaName)->table('client_preferences')->where('id', 1)->update(['business_type' => $client->business_type]);
        } 

        DB::disconnect($schemaName);
    }
}
