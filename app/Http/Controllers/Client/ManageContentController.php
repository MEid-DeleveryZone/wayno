<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;
use App\Models\{AddonOption, AddonOptionTranslation, AddonSet, AddonSetTranslation, OrderVendorProduct, Banner, Brand, BrandCategory, BrandTranslation, Cart, CartAddon, CartCoupon, CartProduct, CartProductPrescription, Category, CategoryHistory, CategoryTranslation, Celebrity, CsvProductImport, CsvVendorImport, LoyaltyCard, Order, OrderProductAddon, OrderProductPrescription, OrderProductRating, OrderProductRatingFile, OrderReturnRequest, OrderReturnRequestFile, OrderTax, OrderVendor, Payment, PaymentOption, Product, ProductAddon, ProductCategory, ProductCelebrity, ProductCrossSell, ProductImage, ProductInquiry, ProductRelated, ProductTranslation, ProductUpSell, ProductVariant, ProductVariantImage, ProductVariantSet, Promocode, PromoCodeDetail, PromocodeRestriction, ServiceArea, SlotDay, SocialMedia, Transaction, User, UserAddress, UserDevice, UserLoyaltyPoint, UserPermissions, UserRefferal, UserVendor, UserWishlist, Variant, VariantCategory, VariantOption, VariantOptionTranslation, VariantTranslation, Vendor, VendorCategory, VendorMedia, VendorOrderStatus, VendorSlot, VendorSlotDate, Wallet};
use Exception;
use Spatie\DbDumper\Databases\MySql;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ManageContentController extends BaseController
{
    public function deleteAllSoftDeleted()
    {
        try {
            DB::beginTransaction();
            $pro = Product::onlyTrashed()->forceDelete();
            $cat = Category::where('slug','!=','root')->onlyTrashed()->forceDelete();
            $user = User::where('status', 3)->forceDelete();
            $ven = Vendor::where('status', 2)->forceDelete();
            $banners = Banner::where('status', 2)->forceDelete();
            $variants = Variant::where('status', 2)->forceDelete();
            $promo_codes = Promocode::where('is_deleted', 1)->forceDelete();
            DB::commit();
            return response()->json(['success' => 'Cleaned Successfully']);
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function hardDeleteEverything()
    {
        try {
            DB::beginTransaction();
            DB::statement("SET foreign_key_checks=0");
            Cart::truncate();
            Brand::truncate();
            Order::truncate();
            Banner::truncate();
            Vendor::truncate();
            SlotDay::truncate();
            Payment::truncate();
            Variant::truncate();
            Product::truncate();
            AddonSet::truncate();
            Category::truncate();
            OrderTax::truncate();
            Promocode::truncate();
            CartAddon::truncate();
            Celebrity::truncate();
            VendorSlot::truncate();
            CartCoupon::truncate();
            AddonOption::truncate();
            LoyaltyCard::truncate();
            ServiceArea::truncate();
            VendorMedia::truncate();
            CartProduct::truncate();
            SocialMedia::truncate();
            Transaction::truncate();
            OrderVendor::truncate();
            ProductAddon::truncate();
            ProductImage::truncate();
            ProductUpSell::truncate();
            VariantOption::truncate();
            BrandCategory::truncate();
            VendorSlotDate::truncate();
            VendorCategory::truncate();
            ProductRelated::truncate();
            ProductVariant::truncate();
            ProductInquiry::truncate();
            ProductCategory::truncate();
            CsvVendorImport::truncate();
            VariantCategory::truncate();
            PromoCodeDetail::truncate();
            CategoryHistory::truncate();
            CsvProductImport::truncate();
            BrandTranslation::truncate();
            ProductCelebrity::truncate();
            ProductCrossSell::truncate();
            ProductVariantSet::truncate();
            VendorOrderStatus::truncate();
            OrderProductAddon::truncate();
            OrderProductRating::truncate();
            ProductTranslation::truncate();
            VariantTranslation::truncate();
            OrderVendorProduct::truncate();
            OrderReturnRequest::truncate();
            AddonSetTranslation::truncate();
            CategoryTranslation::truncate();
            ProductVariantImage::truncate();
            PromocodeRestriction::truncate();
            AddonOptionTranslation::truncate();
            OrderProductRatingFile::truncate();
            OrderReturnRequestFile::truncate();
            CartProductPrescription::truncate();
            CartProductPrescription::truncate();
            VariantOptionTranslation::truncate();
            OrderProductPrescription::truncate();
            $users = User::where('is_superadmin', 0)->where('is_admin', 0)->get();
            foreach ($users as $user) {
                Wallet::where('holder_id', $user->id)->forceDelete();
                UserDevice::where('user_id', $user->id)->forceDelete();
                UserVendor::where('user_id', $user->id)->forceDelete();
                UserAddress::where('user_id', $user->id)->forceDelete();
                UserWishlist::where('user_id', $user->id)->forceDelete();
                UserRefferal::where('user_id', $user->id)->forceDelete();
                UserPermissions::where('user_id', $user->id)->forceDelete();
                UserLoyaltyPoint::where('user_id', $user->id)->forceDelete();
                $user->forceDelete();
            }
            DB::statement("SET foreign_key_checks=1");
            $categories = array(
                array(
                    'id' => '1',
                    'slug' => 'Root',
                    'type_id' => 3,
                    'is_visible' => 0,
                    'status' => 1,
                    'position' => 1,
                    'is_core' => 1,
                    'can_add_products' => 0,
                    'display_mode' => 1,
                    'parent_id' => NULL
                )
            ); 
            DB::table('categories')->insert($categories);
            DB::table('category_translations')->delete();
            $category_translations = array(
                array(
                    'id' => 1,
                    'name' => 'root',
                    'trans-slug' => '',
                    'meta_title' => 'root',
                    'meta_description' => '',
                    'meta_keywords' => '',
                    'category_id' => 1,
                    'language_id' => 1,
                )
            );
            DB::table('category_translations')->insert($category_translations);
            DB::commit();
            return response()->json(['success' => 'Deleted Successfully']);
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function importDemoContent()
    {
        try {
            DB::beginTransaction();

            // $filename = "backup-" . Carbon::now()->format('Y-m-d') . ".gz";
            // $command = "mysqldump --user=" . env('DB_USERNAME_SECOND') ." --password=" . env('DB_PASSWORD_SECOND') . " --host=" . env('DB_HOST') . " " . env('DB_DATABASE_SECOND') . "  | gzip > " . storage_path() . "/app/backup/" . $filename;
            // $returnVar = NULL;
            // $output  = NULL;
            // exec($command, $output, $returnVar);

           
            $path = url('public/data.sql');
            DB::unprepared(file_get_contents($path));
        
            DB::commit();
            return response()->json(['success' => 'Imported Successfully']);
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
