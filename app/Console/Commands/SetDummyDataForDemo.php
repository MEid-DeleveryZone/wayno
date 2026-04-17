<?php

namespace App\Console\Commands;

use App\Models\Client;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Config;
use Log;
use App\Models\{AddonOption, AddonOptionTranslation, AddonSet, AddonSetTranslation, OrderVendorProduct, Banner, MobileBanner, Brand, BrandCategory, BrandTranslation, Cart, CartAddon, CartCoupon, CartProduct, CartProductPrescription, Category, CategoryHistory, CategoryTranslation, Celebrity, CsvProductImport, CsvVendorImport, LoyaltyCard, Order, OrderProductAddon, OrderProductPrescription, OrderProductRating, OrderProductRatingFile, OrderReturnRequest, OrderReturnRequestFile, OrderTax, OrderVendor, Payment, PaymentOption, Product, ProductAddon, ProductCategory, ProductCelebrity, ProductCrossSell, ProductImage, ProductInquiry, ProductRelated, ProductTranslation, ProductUpSell, ProductVariant, ProductVariantImage, ProductVariantSet, Promocode, PromoCodeDetail, PromocodeRestriction, ServiceArea, SlotDay, SocialMedia, Transaction, User, UserAddress, UserDevice, UserLoyaltyPoint, UserPermissions, UserRefferal, UserVendor, UserWishlist, Variant, VariantCategory, VariantOption, VariantOptionTranslation, VariantTranslation, Vendor, VendorCategory, VendorMedia, VendorOrderStatus, VendorSlot, VendorSlotDate, Wallet,CabBookingLayout,CabBookingLayoutCategory,CabBookingLayoutTranslation,ClientPreference};
use Exception;
use Spatie\DbDumper\Databases\MySql;
use Illuminate\Support\Facades\Hash;

class SetDummyDataForDemo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set_default_dummy:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'set default dummy';

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
        $code_array = ['2f3120','d1b1a0','d2cca0','638bd1','d8473d','574467','c8fbba','fb78f0','6865aa','2d98b5'];
        $domain_array = ['grub','gusto','punnet','suel','voltaic','elixir','homeric','gokab','zest','ace'];
        $clients = Client::select('database_name', 'sub_domain')->whereIN('code',$code_array)->whereIN('sub_domain',$domain_array)->get();
        foreach ($clients as $client) {
                $this->migrateDefaultDataDaily($client);
            }
        
    }

    /////////////// *********************** migrate Default data daily********************************* ////////////////////////////////////////

    public function migrateDefaultDataDaily($client)
    {
        try {
            $database_name = 'royo_' . $client->database_name;
            // Log::info("checking cart start: {$database_name}!");
            $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME =  ?";
            $db = DB::select($query, [$database_name]);
            if ($db) {
                $schemaName = 'royo_' . $client->database_name;
                $database_host = !empty($client->database_host) ? $client->database_host : env('DB_HOST', '127.0.0.1');
                $database_port = !empty($client->database_port) ? $client->database_port : env('DB_PORT', '3306');
                $database_username = !empty($client->database_username) ? $client->database_username : env('DB_USERNAME', 'root');
                $database_password = !empty($client->database_password) ? $client->database_password : env('DB_PASSWORD', '');

                $default = [
                'driver' => env('DB_CONNECTION', 'mysql'),
                'host' => $database_host,
                'port' => $database_port,
                'database' => $schemaName,
                'username' => $database_username,
                'password' => $database_password,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'prefix_indexes' => true,
                'strict' => false,
                'engine' => null
                ];
           
                Config::set("database.connections.$schemaName", $default);
                config(["database.connections.mysql.database" => $schemaName]);
            
                DB::connection($schemaName)->beginTransaction();
                DB::connection($schemaName)->statement("SET foreign_key_checks=0");
                Cart::on($schemaName)->truncate();
                Brand::on($schemaName)->truncate();
                Order::on($schemaName)->truncate();
                Banner::on($schemaName)->truncate();
                MobileBanner::on($schemaName)->truncate();
                Vendor::on($schemaName)->truncate();
                SlotDay::on($schemaName)->truncate();
                Payment::on($schemaName)->truncate();
                Variant::on($schemaName)->truncate();
                Product::on($schemaName)->truncate();
                AddonSet::on($schemaName)->truncate();
                Category::on($schemaName)->truncate();
                OrderTax::on($schemaName)->truncate();
                Promocode::on($schemaName)->truncate();
                CartAddon::on($schemaName)->truncate();
                Celebrity::on($schemaName)->truncate();
                VendorSlot::on($schemaName)->truncate();
                CartCoupon::on($schemaName)->truncate();
                AddonOption::on($schemaName)->truncate();
                LoyaltyCard::on($schemaName)->truncate();
                ServiceArea::on($schemaName)->truncate();
                VendorMedia::on($schemaName)->truncate();
                CartProduct::on($schemaName)->truncate();
                SocialMedia::on($schemaName)->truncate();
                Transaction::on($schemaName)->truncate();
                OrderVendor::on($schemaName)->truncate();
                ProductAddon::on($schemaName)->truncate();
                ProductImage::on($schemaName)->truncate();
                ProductUpSell::on($schemaName)->truncate();
                VariantOption::on($schemaName)->truncate();
                BrandCategory::on($schemaName)->truncate();
                VendorSlotDate::on($schemaName)->truncate();
                VendorCategory::on($schemaName)->truncate();
                ProductRelated::on($schemaName)->truncate();
                ProductVariant::on($schemaName)->truncate();
                ProductInquiry::on($schemaName)->truncate();
                ProductCategory::on($schemaName)->truncate();
                CsvVendorImport::on($schemaName)->truncate();
                VariantCategory::on($schemaName)->truncate();
                PromoCodeDetail::on($schemaName)->truncate();
                CategoryHistory::on($schemaName)->truncate();
                CsvProductImport::on($schemaName)->truncate();
                BrandTranslation::on($schemaName)->truncate();
                ProductCelebrity::on($schemaName)->truncate();
                ProductCrossSell::on($schemaName)->truncate();
                ProductVariantSet::on($schemaName)->truncate();
                VendorOrderStatus::on($schemaName)->truncate();
                OrderProductAddon::on($schemaName)->truncate();
                OrderProductRating::on($schemaName)->truncate();
                ProductTranslation::on($schemaName)->truncate();
                VariantTranslation::on($schemaName)->truncate();
                OrderVendorProduct::on($schemaName)->truncate();
                OrderReturnRequest::on($schemaName)->truncate();
                AddonSetTranslation::on($schemaName)->truncate();
                CategoryTranslation::on($schemaName)->truncate();
                ProductVariantImage::on($schemaName)->truncate();
                PromocodeRestriction::on($schemaName)->truncate();
                AddonOptionTranslation::on($schemaName)->truncate();
                OrderProductRatingFile::on($schemaName)->truncate();
                OrderReturnRequestFile::on($schemaName)->truncate();
                CartProductPrescription::on($schemaName)->truncate();
                CartProductPrescription::on($schemaName)->truncate();
                VariantOptionTranslation::on($schemaName)->truncate();
                OrderProductPrescription::on($schemaName)->truncate();
                CabBookingLayout::on($schemaName)->truncate();
                CabBookingLayoutCategory::on($schemaName)->truncate();
                CabBookingLayoutTranslation::on($schemaName)->truncate();
               
                $sql_file = $client->database_name.".sql";
                //  DB::connection($schemaName)->unprepared(file_get_contents((asset('sql_files/'.$sql_file))));
                DB::connection($schemaName)->unprepared(file_get_contents((public_path('sql_files/'.$sql_file))));
                DB::connection($schemaName)->commit();
                DB::connection($schemaName)->statement("SET foreign_key_checks=1");
                $email = "admin@".$client->sub_domain.".com";
                $password = "admin@".$client->sub_domain;
                User::on($schemaName)->where('id', 1)->where('is_superadmin', 1)->update(['email' =>  $email ,'password' => Hash::make($password)]);
                ClientPreference::on($schemaName)->where('id', 1)->update(['is_hyperlocal' => 0]);
                
                DB::disconnect($schemaName);
              //  Log::info("import dummy data: {$schemaName}!");
            }
        } catch (\PDOException $e) {
            DB::connection($schemaName)->rollBack();
          //  Log::info("import dummy data: {$schemaName}!{$e->getMessage()}");
            
        }
            
            
    }
}
