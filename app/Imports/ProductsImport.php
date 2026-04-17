<?php

namespace App\Imports;

use Maatwebsite\Excel\Row;
use Illuminate\Support\Collection;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\{Brand, Category, ClientLanguage, CategoryTranslation, CsvProductImport, Product, ProductCategory, ProductTranslation, ProductVariant, ProductVariantSet, TaxCategory, Variant, VariantOption, VendorCategory, VendorMedia, ProductImage};

class ProductsImport implements ToCollection{
    public $vendor_id;
    public $csv_product_import_id;
    public $status;
    public $validation_arr; // declare one public variable
    public $error_arr; // declare one public variable
    
    public function  __construct($vendor_id ,$csv_product_import_id,$status = 0){
        $this->vendor_id= $vendor_id;
        $this->csv_product_import_id = $csv_product_import_id;
        $this->status = $status;
    }
    public function collection(Collection $rows){
        $i = 0;
        $data = array();
        $error = array();
        $variant_exist = 0;
        $csv_titles = array("Handle", "Title", "Description (HTML)", "Published", "Category", "Option1 Name", "Option1 Value", "Option2 Name", "Option2 Value", "Option3 Name", "Option3 Value", "Variant SKU", "Variant Price", "Variant Quantity", "Variant Compare At Price", "Variant Taxable", "Variant Barcode", "Image Src", "Image Position", "Image Position", "Image Alt Text", "Variant Image", "Variant Cost Price", "Status", "Brand", "Tax Category", "Product Quantity", "Product Price", "Product Compare At Price");
        foreach ($rows[0] as $key => $title) {
            if (!in_array($title, $csv_titles)){
                $this->validation_arr = 'The csv titles are not as in sample file';
                return 0;
            }
        }
        foreach ($rows as $row) {
            $checker = 0;
            if ($row[0] != "Handle") { //header of excel check              

                if ($row[0] == "") { //if sku or handle is empty
                    $error[$i][0] = "handle is empty";
                    $checker = 1;
                }
                if (Product::where('sku', $row[0])->exists()) { //if sku or handle is empty
                    $error[$i][0] = "Product with this sku already exist";
                    $checker = 1;
                }
                if ($row[3] == "") { //check if published is empty
                    $error[$i][3] = "Please mark published either true or false";
                    $checker = 1;
                }
                if ($row[4] == "") { // check if category is empty
                    $error[$i][4] = "Category cannot be empty";
                    $checker = 1;
                }
                if ($row[4] != "") {
                    $category = $row[4];
                    $vendorCategoryExists = VendorCategory::with('category.translation')
                    ->whereHas('category.translation', function($q)use($category){
                        $q->select('category_translations.name')
                        ->join('client_languages as cl', 'cl.language_id', 'category_translations.language_id')
                        ->join('languages', 'category_translations.language_id', 'languages.id')
                        ->where('cl.is_active', 1)
                        ->where('category_translations.name', 'LIKE', $category);
                    })->where('vendor_id', $this->vendor_id)->first();

                    if (!$vendorCategoryExists) { //check if category doesn't exist
                        $error[$i][4] = "Category doesn't exist";
                        $checker = 1;
                    }
                    else{
                        if($vendorCategoryExists->status != 1){
                            $error[$i][4] = "This category is not activated for this vendor";
                            $checker = 1;
                        }
                    }

                    /*$category_check = CategoryTranslation::where('name', "LIKE", $row[4])->first();
                    if (!$category_check) { //check if category doesn't exist
                        $error[] = "Category doesn't exist";
                        $checker = 1;
                    } else {
                        $category_id = $category_check->category_id;
                        if (!VendorCategory::where([['vendor_id', '=', $this->vendor_id], ['category_id', '=', $category_id]])->exists()) { //check if category is activated for this vendor
                            $error[] = "This category is not activated for this vendor";
                            $checker = 1;
                        }
                    }*/
                }
                if ($row[5] != "" && $row[6] == "") {
                    $error[$i][5] = "There is no value for option 1";
                    $checker = 1;
                }

                if ($row[7] != "" && $row[8] == "") {
                    $error[$i][7] = "There is no value for option 2";
                    $checker = 1;
                }

                if ($row[9] != "" && $row[10] == "") {
                    $error[$i][9] = "There is no value for option 3";
                    $checker = 1;
                }

                if ($row[5] == "" && $row[6] != "") {
                    $error[$i][6] = "There is no name for option 1";
                    $checker = 1;
                }

                if ($row[7] == "" && $row[8] != "") {
                    $error[$i][8] = "There is no name for option 2";
                    $checker = 1;
                }

                if ($row[9] == "" && $row[10] != "") {
                    $error[$i][10] = "There is no name for option 3";
                    $checker = 1;
                }

                if ($row[5] != "" && $row[6] != "") {
                    $variant_check = Variant::where('title', $row[5])->first();
                    if (!$variant_check) {
                        $error[$i][5] = "Option1 Name doesn't exist";
                        $checker = 1;
                    }

                    $variant_option = VariantOption::where('title', $row[6])->first();
                    if (!$variant_option) {
                        $error[$i][6] = "Option1 value doesn't exist";
                        $checker = 1;
                    }

                    if ($variant_check && $variant_option) {
                        $checkVariantMatch = VariantOption::where(['title' => $row[6], 'variant_id' => $variant_check->id])->first();
                        if (!$checkVariantMatch) {
                            $error[$i][5] = "Option1 value is not available for this Name";
                            $checker = 1;
                        } else {
                            $variant_exist = 1;
                        }
                    }
                }
                if ($row[7] != "" && $row[8] != "") {
                    $variant_check = Variant::where('title', $row[7])->first();
                    if (!$variant_check) {
                        $error[$i][7] = "Option2 Name doesn't exist";
                        $checker = 1;
                    }

                    $variant_option = VariantOption::where('title', $row[8])->first();
                    if (!$variant_option) {
                        $error[$i][8] = "Option2 value doesn't exist";
                        $checker = 1;
                    }

                    if ($variant_check && $variant_option) {
                        $checkVariantMatch = VariantOption::where(['title' => $row[8], 'variant_id' => $variant_check->id])->first();
                        if (!$checkVariantMatch) {
                            $error[$i][7] = "Option2 value is not available for this Name";
                            $checker = 1;
                        } else {
                            $variant_exist = 1;
                        }
                    }
                }

                if ($row[9] != "" && $row[10] != "") {
                    $variant_check = Variant::where('title', $row[9])->first();
                    if (!$variant_check) {
                        $error[$i][9] = "Option3 Name doesn't exist";
                        $checker = 1;
                    }

                    $variant_option = VariantOption::where('title', $row[10])->first();
                    if (!$variant_option) {
                        $error[$i][10] = "Option3 value doesn't exist";
                        $checker = 1;
                    }

                    if ($variant_check && $variant_option) {
                        $checkVariantMatch = VariantOption::where(['title' => $row[10], 'variant_id' => $variant_check->id])->first();
                        if (!$checkVariantMatch) {
                            $error[$i][9] = "Option3 value is not available for this Name";
                            $checker = 1;
                        } else {
                            $variant_exist = 1;
                        }
                    }
                }

                if ($variant_exist == 1) {
                    if ($row[11] == "") {
                        $error[$i][11] = "Variant Sku is empty";
                        $checker = 1;
                    } else {
                        $proVariant = ProductVariant::where('sku', $row[11])->first();
                        if ($proVariant) {
                            $error[$i][11] = "Variant Sku already exist";
                            $checker = 1;
                        }
                    }
                }

                if($row[23] != ""){
                    $brand = Brand::where('title', "LIKE", $row[23])->first();
                    if(!$brand){
                        $error[$i][23] = "Brand doesn't exist";
                        $checker = 1;
                    }
                }

                if($row[24] != ""){
                    $tax_category = TaxCategory::where('title', "LIKE", $row[24])->first();
                    if(!$tax_category){
                        $error[$i][24] = "Tax Category doesn't exist";
                        $checker = 1;
                    }
                }

                if ($checker == 0) {
                    $data[] = $row;
                }else{
                    $this->error_arr[$i] = $error[$i];
                }
            }
            $i++;
        }
        if (!empty($data) && $this->status == 1) {
            foreach ($data as $da) {

                if (!Product::where('sku', $da[0])->exists()) {

                    if($da[23] != ""){
                        $brand = Brand::where('title', "LIKE", $da[23])->first();
                        if($brand){
                            $brand_id = $brand->id;
                        }
                    }
                    else{
                        $brand_id = null;
                    }

                    if($da[24] != ""){
                        $tax_category = TaxCategory::where('title', "LIKE", $da[24])->first();
                        if($tax_category){
                            $tax_category_id = $tax_category->id;
                        }
                    }
                    else{
                        $tax_category_id = null;
                    }

                    // insert product
                    // $category = CategoryTranslation::where('name', $da[4])->first();
                    $categoryName = $da[4];
                    $category = VendorCategory::with('category.translation')
                    ->whereHas('category.translation', function($q)use($categoryName){
                        $q->select('category_translations.name')
                        ->join('client_languages as cl', 'cl.language_id', 'category_translations.language_id')
                        ->join('languages', 'category_translations.language_id', 'languages.id')
                        ->where('cl.is_active', 1)
                        ->where('category_translations.name', 'LIKE', $categoryName);
                    })->where('vendor_id', $this->vendor_id)->first();
                    $product = Product::insertGetId([
                        'is_new' => 1,
                        'type_id' => 1,
                        'sku' => $da[0],
                        'is_featured' => 0,
                        'is_physical' => 0,
                        'has_inventory' => 0,
                        'url_slug' => $da[0],
                        'brand_id' => $brand_id,
                        'requires_shipping' => 0,
                        'Requires_last_mile' => 0,
                        'sell_when_out_of_stock' => 0,
                        'vendor_id' => $this->vendor_id,
                        'category_id' => $category->category_id,
                        'tax_category_id' => $tax_category_id,
                        'title' => ($da[1] == "") ? "" : $da[1],
                        'is_live' => ($da[3] == 'TRUE') ? 1 : 0,
                        'body_html' => ($da[2] == "") ? "" : $da[2],
                    ]);

                    //insertion into product category
                    $cat[] = [
                        'product_id' => $product,
                        'Category_id' => $category->category_id,
                    ];
                    ProductCategory::insert($cat);

                    $client_lang = ClientLanguage::where('is_primary', 1)->first();
                    if (!$client_lang) {
                        $client_lang = ClientLanguage::where('is_active', 1)->first();
                    }

                    //insertion into product translations
                    $datatrans[] = [
                        'title' => ($da[1] == "") ? "" : $da[1],
                        'body_html' => ($da[2] == "") ? "" : $da[2],
                        'meta_title' => '',
                        'meta_keyword' => '',
                        'meta_description' => '',
                        'product_id' => $product,
                        'language_id' => $client_lang->language_id
                    ];

                    ProductTranslation::insert($datatrans);

                    if ($da[5] != "" || $da[7] != "" || $da[9] != "") {

                        $product_hasvariant = Product::where('id', $product)->first();
                        $product_hasvariant->has_variant = 1;
                        $product_hasvariant->save();

                        //inserting product variant
                        $proVariant = ProductVariant::insertGetId([
                            'sku' => $da[11],
                            'title' => $da[11],
                            'product_id' => $product,
                            'quantity' => $da[13],
                            'price' => $da[12],
                            'compare_at_price' => $da[14],
                            'cost_price' => $da[21],
                            'barcode' => $this->generateBarcodeNumber(),
                        ]);

                        if ($da[5] != "") {
                            $variant = Variant::where('title', $da[5])->first();
                            $variant_optionn = VariantOption::where(['title' => $da[6], 'variant_id' => $variant->id])->first();
                            //inserting product variant sets
                            $proVariantSet = new ProductVariantSet();
                            $proVariantSet->product_id = $product;
                            $proVariantSet->product_variant_id = $proVariant;
                            $proVariantSet->variant_type_id = $variant->id;
                            $proVariantSet->variant_option_id = $variant_optionn->id;
                            $proVariantSet->save();
                        }

                        if ($da[7] != "") {
                            $variant = Variant::where('title', $da[7])->first();
                            $variant_optionn = VariantOption::where(['title' => $da[8], 'variant_id' => $variant->id])->first();
                            //inserting product variant sets
                            $proVariantSet = new ProductVariantSet();
                            $proVariantSet->product_id = $product;
                            $proVariantSet->product_variant_id = $proVariant;
                            $proVariantSet->variant_type_id = $variant->id;
                            $proVariantSet->variant_option_id = $variant_optionn->id;
                            $proVariantSet->save();
                        }

                        if ($da[9] != "") {
                            $variant = Variant::where('title', $da[9])->first();
                            $variant_optionn = VariantOption::where(['title' => $da[10], 'variant_id' => $variant->id])->first();
                            //inserting product variant sets
                            $proVariantSet = new ProductVariantSet();
                            $proVariantSet->product_id = $product;
                            $proVariantSet->product_variant_id = $proVariant;
                            $proVariantSet->variant_type_id = $variant->id;
                            $proVariantSet->variant_option_id = $variant_optionn->id;
                            $proVariantSet->save();
                        }
                    }
                    else{
                        $proVariant = new ProductVariant();
                        $proVariant->sku = $da[0];
                        $proVariant->product_id = $product;
                        $proVariant->barcode = $this->generateBarcodeNumber();
                        $proVariant->quantity = $da[25]??0;
                        $proVariant->price = $da[26]??"";
                        $proVariant->compare_at_price = $da[27]??"";
                        $proVariant->save();
                    }
                    if (!empty($da[17])) {
                        foreach (explode(',', $da[17]) as $file_key => $file) {
                            $img = new VendorMedia();
                            $img->media_type = 1;
                            $img->vendor_id = $this->vendor_id;
                            $img->path = $file;
                            $img->save();
                            $image = new ProductImage();
                            $image->product_id = $product;
                            $image->is_default = ($file_key == 0)?1:0;
                            $image->media_id = $img->id;
                            $image->save();
                        }
                    } else {
                        // Create default image for imported product when no images are provided
                        $defaultMedia = new VendorMedia();
                        $defaultMedia->media_type = 1; // 1 for image
                        $defaultMedia->vendor_id = $this->vendor_id;
                        $defaultMedia->path = 'default/default_image.png';
                        $defaultMedia->save();
                        
                        // Link the default image to the product
                        $defaultProductImage = new ProductImage();
                        $defaultProductImage->product_id = $product;
                        $defaultProductImage->media_id = $defaultMedia->id;
                        $defaultProductImage->is_default = 1;
                        $defaultProductImage->save();
                    }
                }
                else{
                    $product_id = Product::where('sku', $da[0])->first();
                    if ($da[5] != "" || $da[7] != "" || $da[9] != "") {
                        $product_hasvariant = Product::where('id', $product_id->id)->first();
                        $product_hasvariant->has_variant = 1;
                        $product_hasvariant->save();
                        //inserting product variant
                        $proVariant = ProductVariant::insertGetId([
                            'sku' => $da[11],
                            'title' => $da[11],
                            'product_id' => $product_id->id,
                            'quantity' => $da[13],
                            'price' => $da[12],
                            'compare_at_price' => $da[14],
                            'cost_price' => $da[21],
                            'barcode' => $this->generateBarcodeNumber(),
                        ]);

                        if ($da[5] != "") {
                            $variant = Variant::where('title', $da[5])->first();
                            $variant_optionn = VariantOption::where('title', $da[6])->first();
                            //inserting product variant sets
                            $proVariantSet = new ProductVariantSet();
                            $proVariantSet->product_id = $product_id->id;
                            $proVariantSet->product_variant_id = $proVariant;
                            $proVariantSet->variant_type_id = $variant->id;
                            $proVariantSet->variant_option_id = $variant_optionn->id;
                            $proVariantSet->save();
                        }

                        if ($da[7] != "") {
                            $variant = Variant::where('title', $da[7])->first();
                            $variant_optionn = VariantOption::where('title', $da[8])->first();
                            //inserting product variant sets
                            $proVariantSet = new ProductVariantSet();
                            $proVariantSet->product_id = $product_id->id;
                            $proVariantSet->product_variant_id = $proVariant;
                            $proVariantSet->variant_type_id = $variant->id;
                            $proVariantSet->variant_option_id = $variant_optionn->id;
                            $proVariantSet->save();
                        }

                        if ($da[9] != "") {
                            $variant = Variant::where('title', $da[9])->first();
                            $variant_optionn = VariantOption::where('title', $da[10])->first();
                            //inserting product variant sets
                            $proVariantSet = new ProductVariantSet();
                            $proVariantSet->product_id = $product_id->id;
                            $proVariantSet->product_variant_id = $proVariant;
                            $proVariantSet->variant_type_id = $variant->id;
                            $proVariantSet->variant_option_id = $variant_optionn->id;
                            $proVariantSet->save();
                        }
                    }
                }
            }
        } 
        // $vendor_csv = CsvProductImport::where('vendor_id', $this->vendor_id)->where('id', $this->csv_product_import_id)->first();
        // if (!empty($error)) {
        //     $vendor_csv->status = 3;
        //     $vendor_csv->error = json_encode($error);
        // }else{
        //     $vendor_csv->status = 2;
        // }
        // $vendor_csv->save();
    }
    private function generateBarcodeNumber(){
        $random_string = substr(md5(microtime()), 0, 14);
        while (ProductVariant::where('barcode', $random_string)->exists()) {
            $random_string = substr(md5(microtime()), 0, 14);
        }
        return $random_string;
    }
}
