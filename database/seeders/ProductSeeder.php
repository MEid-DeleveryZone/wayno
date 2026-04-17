<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('products')->delete();
 
        $product = array(
            array(
                'id' => 1,
                'sku' => 'sku-id',
                'title' => 1,
                'url_slug' => 'sku-id',
                'vendor_id' => 1,
                'type_id' => 1,
                'is_new' => 1,
                'is_featured' => 1,
                'is_live' => 1,
                'is_physical' => 1,
            )
        ); 
        \DB::table('products')->insert($product);

        /*      product_categories      */
        \DB::table('product_categories')->delete();
 
        $product = array(
            array(
                'product_id' => 1,
                'category_id' => 11
            )
        ); 
        \DB::table('product_categories')->insert($product);

        /*      product_categories      */
        \DB::table('product_translations')->delete();
 
        $product = array(
            array(
                'id' => 1,
                'title' => 'Xiaomi',
                'body_html' => NULL,
                'meta_title' => 'Xiaomi',
                'meta_keyword' => 'Xiaomi',
                'meta_description' => NULL,
                'product_id' => 1,
                'language_id' => 1
            )
        ); 
        \DB::table('product_translations')->insert($product);


        /*      product_variants      */
        \DB::table('product_variants')->delete();
 
        $product = array(
            array(
                'id' => 1, 
                'sku' => 'sku-id', 
                'product_id' => 1, 
                'title' => NULL, 
                'quantity' => '100', 
                'price' => '500', 
                'position' => NULL, 
                'compare_at_price' => '500', 
                'barcode' => '7543ebf012007e', 
                'cost_price' => '300', 
                'currency_id' => NULL, 
                'tax_category_id' => NULL, 
                'inventory_policy' => NULL, 
                'fulfillment_service' => NULL, 
                'inventory_management' => NULL,
                'position' => '1'
            ),
            array(
                'id' => 2, 
                'sku' => 'sku-id-1*5', 
                'product_id' => 1, 
                'title' => 'sku-id-Black-Black', 
                'quantity' => '100', 
                'price' => '500', 
                'position' => NULL, 
                'compare_at_price' => '500', 
                'barcode' => '1500cdf2d597df', 
                'cost_price' => '300', 
                'currency_id' => NULL, 
                'tax_category_id' => NULL, 
                'inventory_policy' => NULL, 
                'fulfillment_service' => NULL, 
                'position' => '1',
                'inventory_management' => NULL
            ),
            array(
                'id' => 3, 
                'sku' => 'sku-id-1*6', 
                'product_id' => 1, 
                'title' => 'sku-id-Black-Grey', 
                'quantity' => '100', 
                'price' => '500', 
                'position' => NULL, 
                'compare_at_price' => '500', 
                'barcode' => '2ea56327679387', 
                'cost_price' => '300', 
                'currency_id' => NULL, 
                'tax_category_id' => NULL, 
                'inventory_policy' => NULL, 
                'position' => '1',
                'fulfillment_service' => NULL, 
                'inventory_management' => NULL
            ),
            array(
                'id' => 4, 
                'sku' => 'sku-id-7*5', 
                'product_id' => 1, 
                'title' => 'sku-id-Medium-Black', 
                'quantity' => '100', 
                'price' => '500', 
                'position' => NULL, 
                'compare_at_price' => '500', 
                'barcode' => '8f47f11a19433f', 
                'cost_price' => '300', 
                'currency_id' => NULL, 
                'tax_category_id' => NULL, 
                'inventory_policy' => NULL, 
                'position' => '1',
                'fulfillment_service' => NULL, 
                'inventory_management' => NULL
            ),
            array(
                'id' => 5, 
                'sku' => 'sku-id-7*6', 
                'product_id' => 1, 
                'title' => 'sku-id-Medium-Grey', 
                'quantity' => '100', 
                'price' => '500', 
                'position' => NULL, 
                'compare_at_price' => '500', 
                'barcode' => '8f7318b112bbe9', 
                'cost_price' => '300', 
                'currency_id' => NULL, 
                'tax_category_id' => NULL, 
                'inventory_policy' => NULL, 
                'position' => '1',
                'fulfillment_service' => NULL, 
                'inventory_management' => NULL
            ),
        ); 
        \DB::table('product_variants')->insert($product);


        /*      product_categories      */
        \DB::table('product_variant_sets')->delete();
 
        $product = array(
            array(
                'id' => 1,
                'product_id' => 1,
                'product_variant_id' => 2,
                'variant_type_id' => 1,
                'variant_option_id' => 1,
            ),
            array(
                'id' => 2,
                'product_id' => 1,
                'product_variant_id' => 2,
                'variant_type_id' => 2,
                'variant_option_id' => 5,
            ),
            array(
                'id' => 3,
                'product_id' => 1,
                'product_variant_id' => 3,
                'variant_type_id' => 1,
                'variant_option_id' => 1,
            ),
            array(
                'id' => 4,
                'product_id' => 1,
                'product_variant_id' => 3,
                'variant_type_id' => 2,
                'variant_option_id' => 6,
            ),
            array(
                'id' => 5,
                'product_id' => 1,
                'product_variant_id' => 3,
                'variant_type_id' => 1,
                'variant_option_id' => 1,
            ),
            array(
                'id' => 6,
                'product_id' => 1,
                'product_variant_id' => 3,
                'variant_type_id' => 2,
                'variant_option_id' => 6,
            ),
            array(
                'id' => 7,
                'product_id' => 1,
                'product_variant_id' => 4,
                'variant_type_id' => 1,
                'variant_option_id' => 7,
            ),
            array(
                'id' => 8,
                'product_id' => 1,
                'product_variant_id' => 4,
                'variant_type_id' => 2,
                'variant_option_id' => 5,
            ),

            array(
                'id' => 9,
                'product_id' => 1,
                'product_variant_id' => 5,
                'variant_type_id' => 1,
                'variant_option_id' => 7,
            ),
            array(
                'id' => 10,
                'product_id' => 1,
                'product_variant_id' => 5,
                'variant_type_id' => 2,
                'variant_option_id' => 6,
            ),
        ); 
        \DB::table('product_variant_sets')->insert($product);
    }
}
