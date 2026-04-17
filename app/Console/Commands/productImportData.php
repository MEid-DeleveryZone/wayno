<?php

namespace App\Console\Commands;
use DB;
use Log;
use Config;
use App\Models\Product;
use App\Models\Category;
use App\Models\CategoryHistory;
use App\Models\CsvProductImport;
use Illuminate\Support\Str;
use App\Models\VendorMedia;
use App\Models\Client;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\ClientLanguage;
use App\Models\ProductCategory;
use Illuminate\Console\Command;
use App\Models\ProductTranslation;
use App\Models\Category_translation;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class productImportData extends Command{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    private $folderName = 'prods';
    protected $signature = 'command:productImportData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
    public function handle(){
        try {
            \Log::info("Chat Cron Starting...");
            $this->info('Chat Cron Starting...');
            $clients = Client::orderBy('id','ASC')->get();
            foreach ($clients as $client) {
                try{
                    $database_name = 'royo_'.$client->database_name;
                    $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME =  ?";
                    $db = DB::select($query, [$database_name]);
                    if($db){
                        $default = [
                        'driver' => env('DB_CONNECTION','mysql'),
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
                        Config::set("client_id", $client->id);
                        Config::set("client_connected",true);
                        Config::set("client_data",$client);
                        DB::setDefaultConnection($database_name);
                        DB::purge($database_name);
                        $this->info("client requests $client->domain_name");
                        \App\Models\CsvProductImport::where('type', 1)->chunk(10, function($requests) use($client){
                            foreach ($requests as  $request_data) {
                                try{
                                    $temp_products = json_decode($request_data->raw_data,false);
                                    foreach ($temp_products->products as $product) {
                                        $category_id = 0;
                                        $sku = Str::slug($product->title, '');
                                        $url_slug = Str::slug($product->title, '_');
                                        $category_slug = $product->categories[0];
                                        $category_detail = Category::where('slug', $category_slug)->first();
                                        if($category_detail){
                                            $category_id = $category_detail->id;
                                        }else{
                                            $new_category = Category::create([
                                                'status' => 1,
                                                'type_id' =>1,
                                                'is_core' => 1,
                                                'position' => 1,
                                                'parent_id' => 1,
                                                'is_visible' => 1,
                                                'can_add_products' => 1,
                                                'slug' => $category_slug,
                                            ]);
                                            Category_translation::create(['language_id' => 1, 'name' => $category_slug, 'category_id' => $new_category->id]);
                                            CategoryHistory::create(['action' => 'Add', 'update_id' => 1, 'updater_role' => 'Admin', 'category_id'=>$new_category->id]);
                                            $category_id = $new_category->id;
                                        }
                                        $product_detail = Product::where('sku', $sku)->first();
                                        if(!$product_detail){
                                            $filePath = $this->folderName.'/' . Str::random(40);
                                            $path = Storage::disk('s3')->put($filePath, file_get_contents($product->featured_src), 'public');
                                            $new_product = new Product();
                                            $new_product->sku = $sku;
                                            $new_product->type_id = 1;
                                            $new_product->url_slug = $url_slug;
                                            $new_product->category_id = $category_id;
                                            $new_product->vendor_id = $request_data->vendor_id;
                                            $new_product->save();
                                            $vendor_media = new VendorMedia();
                                            $vendor_media->media_type = 1;
                                            $vendor_media->vendor_id = $request_data->vendor_id;
                                            $vendor_media->path = $filePath;
                                            $vendor_media->save();
                                            $product_image = new ProductImage();
                                            $product_image->is_default = 1;
                                            $product_image->media_id = $vendor_media->id;
                                            $product_image->product_id = $new_product->id;
                                            $product_image->save();
                                            ProductCategory::insert(['product_id' => $new_product->id, 'category_id' => $category_id]);
                                            ProductTranslation::insert(['product_id' => $new_product->id,'language_id' => 1 , 'title' => $product->title, 'meta_description' => $product->description]);
                                            ProductVariant::insert(['sku' => $new_product->sku, 'product_id' => $new_product->id, 'barcode' => $this->generateBarcodeNumber(), 'price' => $product->price]);
                                        }
                                    }
                                    $request_data->status = 2;
                                    $request_data->save();
                                }catch(Exception $ex){
                                    \Log::info("Error ".$ex->getMessage());
                                    $this->error('Error '.$ex->getMessage());
                                    $request_data->status = 3;
                                    $request_data->status = json_encode([$ex->getMessage()]);;
                                    $request_data->save();
                                }
                            }
                        });
                    }
                    
                Log::info('End command:productImportData !'.time());
            }catch(Exception $ex){
                $this->info($ex->getMessage());
                continue;
            }
        }
        } catch (Exception $e) {
            
        }
    }
    private function generateBarcodeNumber(){
        $random_string = substr(md5(microtime()), 0, 14);
        while (ProductVariant::where('barcode', $random_string)->exists()) {
            $random_string = substr(md5(microtime()), 0, 14);
        }
        return $random_string;
    }
}
