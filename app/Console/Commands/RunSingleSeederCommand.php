<?php

namespace App\Console\Commands;
use DB;
use Config;
use Exception;
use App\Models\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RunSingleSeederCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seeder:run {--seedername=}';

    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command for re-run the particular seeder in this command we have to pass seeder name for target that seeder';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $seeder_name = $this->option('seedername');
        $clients = Client::all();
        foreach ($clients as $key => $client) {
            $database_name = 'royo_' . $client->database_name;
            $this->info("select database start: {$database_name}!");
            $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME =  ?";
            $db = DB::select($query, [$database_name]);
            if ($db) {
                $default = [
                    'driver' => env('DB_CONNECTION', 'mysql'),
                    'host' => env('DB_HOST'),
                    'port' => env('DB_PORT'),
                    'database' => $database_name,
                    'username' => env('DB_USERNAME'),
                    'password' => env('DB_PASSWORD'),
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                    'prefix' => '',
                    'prefix_indexes' => true,
                    'strict' => false,
                    'engine' => null
                ];
                Config::set("database.connections.$database_name", $default);
                Artisan::call('db:seed', ['--class'=>$seeder_name,'--database' => $database_name]);
                DB::disconnect($database_name);
            }else{
                DB::disconnect($database_name);
                $this->info("migrate database end: {$database_name}!");
            }
        }
    }
}
