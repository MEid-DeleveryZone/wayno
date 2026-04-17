<?php

namespace App\Jobs;

use App\Models\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Config;
use Exception;
use Illuminate\Support\Facades\Artisan;

class ProcessClientDatabase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $client_id; 
    protected $languId;
    protected $business_type;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($client_id, $languId,$business_type)
    {
        $this->client_id = $client_id;
        $this->languId = $languId;
        $this->business_type = $business_type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = Client::where('id', $this->client_id)->first(['name', 'email', 'password', 'phone_number', 'database_path', 'database_name', 'database_username', 'database_password', 'logo', 'company_name', 'company_address', 'custom_domain', 'status', 'code', 'country_id', 'sub_domain'])->toarray();
        $clientData = array();

        foreach ($client as $key => $value) {
            if($key == 'logo'){
                $clientData[$key] = $value['original'];
            }else{
                $clientData[$key] = $value;
            }
        }
        $userData = array();
        foreach ($client as $key => $value) {
            if ($key == 'name' || $key == 'email' || $key == 'password' || $key == 'phone_number') {
                $userData[$key] = $value;
                }
            }
            $userData['status'] = 1;
            $userData['is_superadmin'] = 1;
           
        try {
           
            $schemaName = 'royo_' . $client['database_name'] ?: config("database.connections.mysql.database");
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

            $settings = [
                'client_code'           => $client['code'],
                'business_type'           => $this->business_type??null,
                'theme_admin'           => 'light',
                'distance_unit'         => 'metric',
                'date_format'           => 'YYYY-MM-DD',
                'time_format'           => '24',
                'fb_login'              => 0,
                'twitter_login'         => 0,
                'google_login'          => 0,
                'apple_login'           => 0,
                'is_hyperlocal'         => 0,
                'Default_location_name' => 'Chandigarh, Punjab, India',
                'Default_latitude'      =>'30.53899440',
                'Default_longitude'     =>'75.95503290',
                'map_provider'          => 1,
                'sms_provider'          => 1,
                'verify_email'          => 0,
                'verify_phone'          => 0,
                'web_template_id'       => 1,
                'app_template_id'       => 2,
                'need_delivery_service' => 0,
                'primary_color'         => '#32B5FC',
                'secondary_color'       => '#41A2E6'
            ];
            $cli_currs = [
                'client_code' => $client['code'],
                'currency_id' => '147',
                'is_primary' => '1',
                'doller_compare' => 1.00
            ];            

            $query = "CREATE DATABASE $schemaName;";

            DB::statement($query);

            $cli_langs = [
                'client_code' => $client['code'],
                'language_id' => $this->languId,
                'is_primary' => 1,
                'is_active' => 1
            ];

            Config::set("database.connections.$schemaName", $default);
            config(["database.connections.mysql.database" => $schemaName]);
            Artisan::call('migrate', ['--database' => $schemaName, '--force' => true]);
            Artisan::call('db:seed', ['--class' => 'DatabaseSeeder', '--database' => $schemaName, '--force' => true]);

            DB::connection($schemaName)->table('clients')->insert($clientData);
            DB::connection($schemaName)->table('client_preferences')->insert($settings);
            DB::connection($schemaName)->table('client_languages')->insert($cli_langs);
            DB::connection($schemaName)->table('client_currencies')->insert($cli_currs);
            DB::connection($schemaName)->table('users')->insert($userData);
            if($this->languId == 1){
                Artisan::call('db:seed', ['--class' => 'TypeSeeder', '--database' => $schemaName,  '--force' => true]);
                Artisan::call('db:seed', ['--class' => 'CategorySeeder', '--database' => $schemaName,  '--force' => true]);
                Artisan::call('db:seed',['--class' => 'CatalogSeeder', '--database' => $schemaName,  '--force' => true]);
                Artisan::call('db:seed', ['--class' => 'AddonsetDataSeeder', '--database' => $schemaName,  '--force' => true]);
                Artisan::call('db:seed', ['--class' => 'VariantSeeder', '--database' => $schemaName,  '--force' => true]);
                Artisan::call('db:seed', ['--class' => 'ProductSeeder', '--database' => $schemaName,  '--force' => true]);
            }else{
                $main_category = [
                    'id' => '1',
                    'slug' => 'root',
                    'type_id' => 1,
                    'is_visible' => 0,
                    'status' => 1,
                    'position' => 1,
                    'is_core' => 1,
                    'can_add_products' => 1,
                    'display_mode' => 1,
                    'parent_id' => NULL
                ];
                $main_trans = [
                    'id' => 1,
                    'name' => 'root',
                    'trans-slug' => '',
                    'meta_title' => 'root',
                    'meta_description' => '',
                    'meta_keywords' => '',
                    'category_id' => 1,
                    'language_id' => $this->languId,
                ];
                DB::connection($schemaName)->table('categories')->insert($main_category);
                DB::connection($schemaName)->table('category_translations')->insert($main_category);
            }
            DB::disconnect($schemaName);
        } catch (Exception $ex) {
            print_r($ex->getMessage());die;
           return $ex->getMessage();

        }
    }
}
