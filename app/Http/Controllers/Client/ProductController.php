<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\{CsvProductImport, Product, Category, ProductTranslation, Vendor, AddonSet, ProductRelated, ProductCrossSell, ProductAddon, ProductCategory, ClientLanguage, ProductVariant, ProductImage, TaxCategory, ProductVariantSet, Country, Variant, VendorMedia, ProductVariantImage, Brand, Celebrity, ClientPreference, ProductCelebrity, Type, ProductUpSell, CartProduct, CartAddon, UserWishlist};
use Illuminate\Support\Facades\Storage;
use App\Http\Traits\ToasterResponser;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;
use GuzzleHttp\Client as GCLIENT;
use Illuminate\Support\Facades\Log;

class ProductController extends BaseController
{
    private $folderName = 'prods';
    use ToasterResponser;
    /**   Display   List of products  */
    public function index() {}

    /**   Add new product view - currently not working add product show in modal  */
    public function create($domain = '', $id)
    {
        $vendor = Vendor::findOrFail($id);
        $type = Type::all();
        $countries = Country::all();
        $addons = AddonSet::with('option')->select('id', 'title')
            ->where('status', '!=', 2)
            ->where('vendor_id', $id)
            ->orderBy('position', 'asc')->get();

        $categories = Category::with('english')->select('id', 'slug')
            ->where('id', '>', '1')->where('status', '!=', '2')
            ->where('can_add_products', 1)->orderBy('parent_id', 'asc')
            ->orderBy('position', 'asc')->get();

        $langs = ClientLanguage::join('languages as lang', 'lang.id', 'client_languages.language_id')
            ->select('lang.id as langId', 'lang.name as langName', 'lang.sort_code')
            ->where('client_languages.client_code', Auth::user()->code)->get();

        $taxCate = TaxCategory::all();
        return view('backend/product/create', ['typeArray' => $type, 'categories' => $categories, 'vendor_id' => $vendor->id, 'addons' => $addons, 'languages' => $langs, 'taxCate' => $taxCate, 'countries' => $countries]);
    }

    /**
     * Validate add prodect fields
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function validateData(Request $request)
    {
        $rules = array(
            'sku' => 'required|unique:products',
            'url_slug' => 'required',
            'category' => 'required',
        );
        $validation = Validator::make($request->all(), $rules)->validate();

        if ($validation) {
            return response()->json([
                'status' => 'success',
            ]);
        } else {
            return response()->json([
                'status' => 'error',
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rule = array(
            'sku' => 'required|unique:products',
            'url_slug' => 'required',
            'category' => 'required',
        );
        $validation  = Validator::make($request->all(), $rule);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation);
        }
        $product = new Product();
        $product->sku = $request->sku;
        $product->url_slug = empty($request->url_slug) ? $request->sku : $request->url_slug;
        $product->type_id = $request->type_id;
        $product->category_id = $request->category;
        $product->vendor_id = $request->vendor_id;
        $client_lang = ClientLanguage::where('is_primary', 1)->first();
        if (!$client_lang) {
            $client_lang = ClientLanguage::where('is_active', 1)->first();
        }
        $product->save();
        if ($product->id > 0) {
            $datatrans[] = [
                'title' => '',
                'body_html' => '',
                'meta_title' => '',
                'meta_keyword' => '',
                'meta_description' => '',
                'product_id' => $product->id,
                'language_id' => $client_lang->language_id
            ];
            $product_category = new ProductCategory();
            $product_category->product_id = $product->id;
            $product_category->category_id = $request->category;
            $product_category->save();
            $proVariant = new ProductVariant();
            $proVariant->sku = $request->sku;
            $proVariant->product_id = $product->id;
            $proVariant->product_id = $product->id;
            $proVariant->barcode = $this->generateBarcodeNumber();
            $proVariant->save();
            
            // Create default image for the product
            $defaultMedia = new VendorMedia();
            $defaultMedia->media_type = 1; // 1 for image
            $defaultMedia->vendor_id = $request->vendor_id;
            $defaultMedia->path = 'default/default_image.png';
            $defaultMedia->save();
            
            // Link the default image to the product
            $defaultProductImage = new ProductImage();
            $defaultProductImage->product_id = $product->id;
            $defaultProductImage->media_id = $defaultMedia->id;
            $defaultProductImage->is_default = 1;
            $defaultProductImage->save();
            
            ProductTranslation::insert($datatrans);
            return redirect('client/product/' . $product->id . '/edit')->with('success', 'Product added successfully!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($domain = '', $id)
    {
        $product = Product::with('brand', 'variant.set', 'variant.vimage.pimage.image', 'primary', 'category.cat', 'variantSet', 'vatoptions', 'addOn', 'media.image', 'related', 'upSell', 'crossSell', 'celebrities')->where('id', $id)->firstOrFail();
        $type = Type::all();
        $countries = Country::all();
        $addons = AddonSet::with('option')->select('id', 'title')
            ->where('status', '!=', 2)
            ->where('vendor_id', $product->vendor_id)
            ->orderBy('position', 'asc')->get();
        $brands = Brand::join('brand_categories as bc', 'bc.brand_id', 'brands.id')
            ->select('brands.id', 'brands.title', 'brands.image')
            ->where('bc.category_id', $product->category_id)->where('status', 1)->get();
        $clientLanguages = ClientLanguage::join('languages as lang', 'lang.id', 'client_languages.language_id')
            ->select('lang.id as langId', 'lang.name as langName', 'lang.sort_code', 'client_languages.is_primary')
            ->where('client_languages.client_code', Auth::user()->code)
            ->where('client_languages.is_active', 1)
            ->orderBy('client_languages.is_primary', 'desc')->get();


        $productVariants = Variant::with('option', 'varcategory.cate.primary')
            ->select('variants.*')
            ->join('variant_categories', 'variant_categories.variant_id', 'variants.id')
            ->where('variant_categories.category_id', $product->category_id)
            ->where('variants.status', '!=', 2)
            ->orderBy('position', 'asc')->get();

        $taxCate = TaxCategory::all();

        $celeb_ids = $related_ids = $upSell_ids = $crossSell_ids = $existOptions = $addOn_ids = array();

        foreach ($product->addOn as $key => $value) {
            $addOn_ids[] = $value->addon_id;
        }

        foreach ($product->related as $key => $value) {
            $related_ids[] = $value->related_product_id;
        }
        foreach ($product->upSell as $key => $value) {
            $upSell_ids[] = $value->upsell_product_id;
        }
        foreach ($product->crossSell as $key => $value) {
            $crossSell_ids[] = $value->cross_product_id;
        }

        foreach ($product->crossSell as $key => $value) {
            $crossSell_ids[] = $value->cross_product_id;
        }

        foreach ($product->celebrities as $key => $value) {
            if (!in_array($value->celebrity_id, $celeb_ids)) {
                $celeb_ids[] = $value->celebrity_id;
            }
        }
        $otherProducts = Product::with('primary')->select('id', 'sku')->where('is_live', 1)->where('id', '!=', $product->id)->where('vendor_id', $product->vendor_id)->get();
        $configData = ClientPreference::select('celebrity_check', 'pharmacy_check', 'need_dispacher_ride', 'need_delivery_service', 'enquire_mode', 'need_dispacher_home_other_service')->first();
        $celebrities = Celebrity::select('id', 'name')->where('status', '!=', 3)->get();

        $agent_dispatcher_tags = [];
        $agent_dispatcher_on_demand_tags = [];

        if (isset($product->category->categoryDetail) && ($product->category->categoryDetail->type_id == 7 || $product->category->categoryDetail->type_id == 9 || $product->category->categoryDetail->type_id == 10)) # if type is pickup delivery then get dispatcher tags
        {
            $vendor_id = $product->vendor_id;
            $agent_dispatcher_tags = $this->getDispatcherTags($vendor_id);
        }
        if (isset($product->category->categoryDetail) && $product->category->categoryDetail->type_id == 8) # if type is on demand
        {
            $vendor_id = $product->vendor_id;
            $agent_dispatcher_on_demand_tags = $this->getDispatcherOnDemandTags($vendor_id);
        }


        return view('backend/product/edit', ['agent_dispatcher_on_demand_tags' => $agent_dispatcher_on_demand_tags, 'agent_dispatcher_tags' => $agent_dispatcher_tags, 'typeArray' => $type, 'addons' => $addons, 'productVariants' => $productVariants, 'languages' => $clientLanguages, 'taxCate' => $taxCate, 'countries' => $countries, 'product' => $product, 'addOn_ids' => $addOn_ids, 'existOptions' => $existOptions, 'brands' => $brands, 'otherProducts' => $otherProducts, 'related_ids' => $related_ids, 'upSell_ids' => $upSell_ids, 'crossSell_ids' => $crossSell_ids, 'celebrities' => $celebrities, 'configData' => $configData, 'celeb_ids' => $celeb_ids]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $domain = '', $id)
    {
        $product = Product::where('id', $id)->firstOrFail();
        $rule = array(
            'product_name' => 'required|string',
            'sku' => 'required|unique:products,sku,' . $product->id,
            'url_slug' => 'required|unique:products,url_slug,' . $product->id,
        );
        $validation  = Validator::make($request->all(), $rule);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation);
        }
        $product_category = ProductCategory::where('product_id', $id)->where('category_id', $request->category_id)->first();
        if (!$product_category) {
            $product_category = new ProductCategory();
            $product_category->product_id = $id;
            $product_category->category_id = $request->category_id;
            $product_category->save();
        }
        if ($product->is_live == 0) {
            $product->publish_at = ($request->is_live == 1) ? date('Y-m-d H:i:s') : '';
        }
        foreach ($request->only('country_origin_id', 'weight', 'weight_unit', 'is_live', 'brand_id') as $k => $val) {
            $product->{$k} = $val;
        }
        // Check if current language is primary language
        $isPrimaryLanguage = ClientLanguage::where('client_code', Auth::user()->code)
            ->where('language_id', $request->language_id)
            ->where('is_primary', 1)
            ->exists();
        
        $product->sku = $request->sku;
        $product->url_slug = $request->url_slug;
        $product->tags        = $request->tags ?? null;
        $product->category_id = $request->category_id;
        $product->inquiry_only = ($request->has('inquiry_only') && $request->inquiry_only == 'on') ? 1 : 0;
        $product->tax_category_id = $request->tax_category;
        $product->is_new                    = ($request->has('is_new') && $request->is_new == 'on') ? 1 : 0;
        $product->is_featured               = ($request->has('is_featured') && $request->is_featured == 'on') ? 1 : 0;
        $product->is_physical               = ($request->has('is_physical') && $request->is_physical == 'on') ? 1 : 0;
        $product->pharmacy_check            = ($request->has('pharmacy_check') && $request->pharmacy_check == 'on') ? 1 : 0;
        $product->has_inventory             = ($request->has('has_inventory') && $request->has_inventory == 'on') ? 1 : 0;
        $product->sell_when_out_of_stock    = ($request->has('sell_stock_out') && $request->sell_stock_out == 'on') ? 1 : 0;
        $product->requires_shipping         = ($request->has('require_ship') && $request->require_ship == 'on') ? 1 : 0;
        $product->Requires_last_mile        = ($request->has('last_mile') && $request->last_mile == 'on') ? 1 : 0;
        $product->need_price_from_dispatcher = ($request->has('need_price_from_dispatcher') && $request->need_price_from_dispatcher == 'on') ? 1 : 0;
        $product->mode_of_service        = $request->mode_of_service ?? null;
        
        // Only update products table fields if editing in primary language
        if ($isPrimaryLanguage) {
            $product->title = $request->product_name;
            $product->dimensions = $request->dimensions;
            $product->weight_description = $request->weight_description;
        }

        $product->sla_same_emirates      = $request->sla_same_emirates;
        $product->same_emirate_frequency = $request->same_emirate_frequency;
        $product->sla_diff_emirates      = $request->sla_diff_emirates;
        $product->diff_emirate_frequency = $request->diff_emirate_frequency;
        if (empty($product->publish_at)) {
            $product->publish_at = ($request->is_live == 1) ? date('Y-m-d H:i:s') : '';
        }
        $product->has_variant = ($request->has('variant_ids') && count($request->variant_ids) > 0) ? 1 : 0;
        $product->returnable = ($request->has('returnable') && $request->returnable == 'on') ? 1 : 0;
        $product->save();
        if ($product->id > 0) {
            $trans = ProductTranslation::where('product_id', $product->id)->where('language_id', $request->language_id)->first();
            if (!$trans) {
                $trans = new ProductTranslation();
                $trans->product_id = $product->id;
                $trans->language_id = $request->language_id;
            }
            $trans->title               = $request->product_name;
            $trans->body_html           = $request->body_html;
            $trans->dimensions          = $request->dimensions;
            $trans->weight_description  = $request->weight_description;
            $trans->meta_title          = $request->meta_title;
            $trans->meta_keyword        = $request->meta_keyword;
            $trans->meta_description    = $request->meta_description;
            $trans->save();
            $varOptArray = $prodVarSet = $updateImage = array();
            $i = 0;
            $productImageSave = array();
            if ($request->has('fileIds')) {
                foreach ($request->fileIds as $key => $value) {
                    $productImageSave[] = [
                        'product_id' => $product->id,
                        'media_id' => $value,
                        'is_default' => 1
                    ];
                }
            }
            ProductImage::insert($productImageSave);
            $cat = $addonsArray = $upArray = $crossArray = $relateArray = array();
            $delete = ProductAddon::where('product_id', $product->id)->delete();
            $delete = ProductUpSell::where('product_id', $product->id)->delete();
            $delete = ProductCrossSell::where('product_id', $product->id)->delete();
            $delete = ProductRelated::where('product_id', $product->id)->delete();
            $delete = ProductCelebrity::where('product_id', $product->id)->delete();

            if ($request->has('addon_sets') && count($request->addon_sets) > 0) {
                foreach ($request->addon_sets as $key => $value) {
                    $addonsArray[] = [
                        'product_id' => $product->id,
                        'addon_id' => $value
                    ];
                }
                ProductAddon::insert($addonsArray);
            }

            if ($request->has('celebrities') && count($request->celebrities) > 0) {
                foreach ($request->celebrities as $key => $value) {
                    $celebArray[] = [
                        'celebrity_id' => $value,
                        'product_id' => $product->id
                    ];
                }
                ProductCelebrity::insert($celebArray);
            }

            if ($request->has('up_cell') && count($request->up_cell) > 0) {
                foreach ($request->up_cell as $key => $value) {
                    $upArray[] = [
                        'product_id' => $product->id,
                        'upsell_product_id' => $value
                    ];
                }
                ProductUpSell::insert($upArray);
            }

            if ($request->has('cross_cell') && count($request->cross_cell) > 0) {
                foreach ($request->cross_cell as $key => $value) {
                    $crossArray[] = [
                        'product_id' => $product->id,
                        'cross_product_id' => $value
                    ];
                }
                ProductCrossSell::insert($crossArray);
            }

            if ($request->has('releted_product') && count($request->releted_product) > 0) {
                foreach ($request->releted_product as $key => $value) {
                    $relateArray[] = [
                        'product_id' => $product->id,
                        'related_product_id' => $value
                    ];
                }
                ProductRelated::insert($relateArray);
            }

            $existv = array();

            if ($request->has('variant_ids')) {
                foreach ($request->variant_ids as $key => $value) {
                    $variantData = ProductVariant::where('id', $value)->first();
                    $existv[] = $value;

                    if ($variantData) {
                        $variantData->title             = $request->variant_titles[$key];
                        $variantData->price             = $request->variant_price[$key];
                        $variantData->compare_at_price  = $request->variant_compare_price[$key];
                        $variantData->cost_price        = $request->variant_cost_price[$key];
                        $variantData->quantity          = $request->variant_quantity[$key];
                        $variantData->tax_category_id   = $request->tax_category;
                        $variantData->save();
                    }
                }
                $delOpt = ProductVariant::whereNotIN('id', $existv)->where('product_id', $product->id)->whereNull('title')->delete();
            } else {
                $variantData = ProductVariant::where('product_id', $product->id)->first();
                if (!$variantData) {
                    $variantData = new ProductVariant();
                    $variantData->product_id    = $product->id;
                    // $variantData->sku           = $product->sku;
                    // $variantData->title         = $product->sku;
                    $variantData->barcode       = $this->generateBarcodeNumber();
                }
                $variantData->sku               = $product->sku;
                $variantData->title             = $product->sku;
                $variantData->price             = $request->price;
                $variantData->compare_at_price  = $request->compare_at_price;
                $variantData->cost_price        = $request->cost_price;
                $variantData->quantity          = $request->quantity;
                $variantData->tax_category_id   = $request->tax_category;
                $variantData->save();
            }
        }
        $toaster = $this->successToaster('Success', 'Product  updated successfully.');
        return redirect('client/vendor/catalogs/' . $product->vendor_id)->with('toaster', $toaster);
        // return redirect()->back()->with('toaster', $toaster);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($domain = '', $id)
    {
        try {

            DB::beginTransaction();
            $product = Product::find($id);
            $dynamic = time();

            Product::where('id', $id)->update(['sku' => $product->sku . $dynamic, 'url_slug' => $product->url_slug . $dynamic]);

            $tot_var  = ProductVariant::where('product_id', $id)->get();
            foreach ($tot_var as $varr) {
                $dynamic = time() . substr(md5(mt_rand()), 0, 7);
                ProductVariant::where('id', $varr->id)->update(['sku' => $product->sku . $dynamic]);
            }

            Product::where('id', $id)->delete();

            CartProduct::where('product_id', $id)->delete();
            UserWishlist::where('product_id', $id)->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Product deleted successfully!');
        } catch (\Exception $ex) {
            DB::rollback();
            redirect()->back()->with('error', $ex->getMessage());
        }
    }

    /**      Make variant rows          */
    public function makeVariantRows(Request $request)
    {
        $multiArray = array();
        $variantNames = array();
        $product = Product::where('id', $request->pid)->firstOrFail();
        $msgRes = 'Please check variants to create variant set.';
        if (!$request->has('optionIds') || !$request->has('variantIds')) {
            return response()->json(array('success' => 'false', 'msg' => $msgRes));
        }
        foreach ($request->optionIds as $key => $value) {
            $name = explode(';', $request->variantIds[$key]);
            if (!in_array($name[1], $variantNames)) {
                $variantNames[] = $name[1];
            }
            $multiArray[$request->variantIds[$key]][] = $value;
        }

        $combination = $this->array_combinations($multiArray);
        $new_combination = array();
        $edit = 0;

        if ($request->has('existing') && !empty($request->existing)) {
            $existingComb = $request->existing;
            $edit = 1;
            foreach ($combination as $key => $value) {
                $comb = $arrayVal = '';
                foreach ($value as $k => $v) {
                    $arrayVal = explode(';', $v);
                    $comb .= $arrayVal[0] . '*';
                }

                $comb = rtrim($comb, '*');

                if (!in_array($comb, $existingComb)) {
                    $new_combination[$key] = $value;
                }
            }
            $combination = $new_combination;
            $msgRes = 'No new variant set found.';
        }

        if (count($combination) < 1) {
            return response()->json(array('success' => 'false', 'msg' => $msgRes));
        }

        $makeHtml = $this->combinationHtml($combination, $multiArray, $variantNames, $product->id, $request->sku, $edit);
        return response()->json(array('success' => true, 'html' => $makeHtml));
    }

    function combinationHtml($combination, $multiArray, $variantNames, $product_id, $sku = '',  $edit = 0)
    {
        $arrVal = array();
        foreach ($multiArray as $key => $value) {
            $varStr = $optStr = array();
            $vv = explode(';', $key);

            foreach ($value as $k => $v) {
                $ov = explode(';', $v);
                $optStr[] = $ov[0];
            }

            $arrVal[$vv[0]] = $optStr;
        }
        $name1 = '';

        $all_variant_sets = array();

        $html = '';
        if ($edit == 1) {
            $html .= '<h5 >New Variants Set</h5>';
        }
        $html .= '<table class="table table-centered table-nowrap table-striped">
            <thead>
                <th>Image</th>
                <th>Name</th>
                <th>Variants</th>
                <th>Price</th>
                <th>Compare at price</th>
                <th>Cost Price</th>
                <th>Quantity</th>
                <th> </th>
                </thead>';
        $inc = 0;
        foreach ($combination as $key => $value) {
            $names = array();
            $ids = array();
            foreach ($value as $k => $v) {
                $variant = explode(';', $v);
                $ids[] = $variant[0];
                $names[] = $variant[1];
            }
            $proSku = $sku . '-' . implode('*', $ids);
            $proVariant = ProductVariant::where('sku', $proSku)->first();
            if (!$proVariant) {
                $proVariant = new ProductVariant();
                $proVariant->sku = $proSku;
                $proVariant->title = $sku . '-' . implode('-', $names);
                $proVariant->product_id = $product_id;
                $proVariant->barcode = $this->generateBarcodeNumber();
                $proVariant->save();

                foreach ($ids as $id1) {
                    $all_variant_sets[$inc] = [
                        'product_id' => $product_id,
                        'product_variant_id' => $proVariant->id,
                        'variant_option_id' => $id1,
                    ];

                    foreach ($arrVal as $key => $value) {

                        if (in_array($id1, $value)) {
                            $all_variant_sets[$inc]['variant_type_id'] = $key;
                        }
                    }
                    $inc++;
                }
            }

            $html .= '<tr>';
            $html .= '<td><div class="image-upload">
                      <label class="file-input" for="file-input_' . $proVariant->id . '"><img src="' . asset("assets/images/default_image.png") . '" width="30" height="30" class="uploadImages" for="' . $proVariant->id . '"/> </label>
                    </div>
                    <div class="imageCountDiv' . $proVariant->id . '"></div>
                    </td>';
            $html .= '<td> <input type="hidden" name="variant_ids[]" value="' . $proVariant->id . '">';

            $html .= '<input type="text" name="variant_titles[]" value="' . $proVariant->title . '"></td>';
            $html .= '<td>' . implode(", ", $names) . '</td>';
            $html .= '<td> <input type="text" style="width: 70px;" name="variant_price[]" value="0" onkeypress="return isNumberKey(event)"> </td>';
            $html .= '<td> <input type="text" style="width: 100px;" name="variant_compare_price[]" value="0" onkeypress="return isNumberKey(event)"> </td>';
            $html .= '<td> <input type="text" style="width: 70px;" name="variant_cost_price[]" value="0" onkeypress="return isNumberKey(event)"> </td>';
            $html .= '<td> <input type="text" style="width: 70px;" name="variant_quantity[]" value="0" onkeypress="return isNumberKey(event)"> </td><td>
            <a href="javascript:void(0);" class="action-icon deleteCurRow"> <i class="mdi mdi-delete"></i></a></td>';

            $html .= '</tr>';
        }
        ProductVariantSet::insert($all_variant_sets);
        $html .= '</table>';
        return $html;
    }

    private function array_combinations($arrays)
    {
        $result = array();
        $arrays = array_values($arrays);
        $sizeIn = sizeof($arrays);
        $size = $sizeIn > 0 ? 1 : 0;
        foreach ($arrays as $array)
            $size = $size * sizeof($array);
        for ($i = 0; $i < $size; $i++) {
            $result[$i] = array();
            for ($j = 0; $j < $sizeIn; $j++)
                array_push($result[$i], current($arrays[$j]));
            for ($j = ($sizeIn - 1); $j >= 0; $j--) {
                if (next($arrays[$j]))
                    break;
                elseif (isset($arrays[$j]))
                    reset($arrays[$j]);
            }
        }
        return $result;
    }

    private function generateBarcodeNumber()
    {
        $random_string = substr(md5(microtime()), 0, 14);
        while (ProductVariant::where('barcode', $random_string)->exists()) {
            $random_string = substr(md5(microtime()), 0, 14);
        }
        return $random_string;
    }

    public function deleteVariant(Request $request)
    {
        $product_variant = ProductVariant::where('id', $request->product_variant_id)->where('product_id', $request->product_id)->first();
        $product_variant->status = 0;
        $product_variant->save();
        if ($request->is_product_delete > 0) {
            Product::where('id', $request->product_id)->delete();
        }
        return response()->json(array('success' => true, 'msg' => 'Product variant deleted successfully.'));
    }

    public function translation(Request $request)
    {
        $data = ProductTranslation::where('product_id', $request->prod_id)->where('language_id', $request->lang_id)->first();
        $product = Product::find($request->prod_id);
        
        $response = array('title' => '', 'body_html' => '', 'dimensions' => '', 'weight_description' => '', 'meta_title' => '', 'meta_keyword' => '', 'meta_description' => '');
        
        if ($data) {
            $response['title']              = $data->title;
            $response['body_html']          = $data->body_html;
            $response['dimensions']         = $data->dimensions;
            $response['weight_description'] = $data->weight_description;
            $response['meta_title']         = $data->meta_title;
            $response['meta_keyword']       = $data->meta_keyword;
            $response['meta_description']   = $data->meta_description;
        } else {
            // If no translation exists, fallback to products table values
            if ($product) {
                $response['title']              = $product->title ?? '';
                $response['body_html']          = '';
                $response['dimensions']         = $product->dimensions ?? '';
                $response['weight_description'] = $product->weight_description ?? '';
            }
        }
        return response()->json(array('success' => true, 'data' => $response));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function images(Request $request)
    {
        $resp = '';
        $product = Product::findOrFail($request->prodId);
        if ($request->has('file')) {
            $imageId = '';
            $files = $request->file('file');
            if (is_array($files)) {
                foreach ($files as $file) {
                    $img = new VendorMedia();
                    $img->media_type = 1;
                    $img->vendor_id = $product->vendor_id;
                    $img->path = Storage::disk('s3')->put($this->folderName, $file, 'public');
                    $img->save();
                    $path1 = $img->path['proxy_url'] . '40/40' . $img->path['image_path'];
                    if ($img->id > 0) {
                        $imageId = $img->id;
                        $image = new ProductImage();
                        $image->product_id = $product->id;
                        $image->is_default = 1;
                        $image->media_id = $img->id;
                        $image->save();
                        if ($request->has('variantId')) {
                            $resp .= '<div class="col-md-3 col-sm-4 col-12 mb-3">
                                        <div class="product-img-box">
                                            <div class="form-group checkbox checkbox-success">
                                                <input type="checkbox" id="image' . $image->id . '" class="imgChecks" imgId="' . $image->id . '" checked variant_id="' . $request->variantId . '">
                                                <label for="image' . $image->id . '">
                                                <img src="' . $path1 . '" alt="">
                                                </label>
                                            </div>
                                        </div>
                                    </div>';
                        }
                    }
                }
                return response()->json(['htmlData' => $resp]);
            } else {
                $img = new VendorMedia();
                $img->media_type = 1;
                $img->vendor_id = $product->vendor_id;
                $img->path = Storage::disk('s3')->put($this->folderName, $files, 'public');
                $img->save();
                $imageId = $img->id;
            }
            return response()->json(['imageId' => $imageId]);
        } else {
            return response()->json(['error' => 'No file']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cms  $cms
     * @return \Illuminate\Http\Response
     */
    public function getImages(Request $request)
    {
        $product = Product::where('id', $request->prod_id)->firstOrFail();
        $variId = ($request->has('variant_id') && $request->variant_id > 0) ? $request->variant_id : 0;
        $images = ProductImage::with('image')->where('product_images.product_id', $product->id)->get();
        $variantImages = array();
        if ($variId > 0) {
            $varImages = ProductVariantImage::where('product_variant_id', $variId)->get();
            if ($varImages) {
                foreach ($varImages as $key => $value) {
                    $variantImages[] = $value->product_image_id;
                }
            }
        }
        $returnHTML = view('backend.product.imageUpload')->with(['images' => $images, 'variant_id' => $variId, 'productId' => $product->id, 'variantImages' => $variantImages])->render();
        return response()->json(array('success' => true, 'htmlData' => $returnHTML));
    }

    public function updateVariantImage(Request $request)
    {
        $product = Product::where('id', $request->prod_id)->firstOrFail();
        $saveImage = array();
        if ($request->has('image_id')) {
            $deleteVarImg = ProductVariantImage::where('product_variant_id', $request->variant_id)->delete();
            foreach ($request->image_id as $key => $value) {

                $saveImage[] = [
                    'product_variant_id' => $request->variant_id,
                    'product_image_id' => $value
                ];
            }
            ProductVariantImage::insert($saveImage);
            return response()->json(array('success' => true, 'msg' => 'Image added successfully!'));
        }
        return response()->json(array('success' => 'false', 'msg' => 'Something went wrong!'));
    }

    public function deleteImage(Request $request, $domain = '', $pid = 0, $imgId = 0)
    {
        $product = Product::findOrfail($pid);
        $img = VendorMedia::findOrfail($imgId);
        $img->delete();
        return redirect()->back()->with('success', 'Product image deleted successfully!');
    }

    /**
     * Import Excel file for products
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function importCsv(Request $request)
    {
        $vendor_id = $request->vendor_id;
        $fileModel = new CsvProductImport;
        if ($request->file('product_excel')) {
            $import = new ProductsImport($vendor_id, $fileModel->id);
            Excel::import($import, $request->file('product_excel'));
            if ($import->validation_arr) {
                return response()->json([
                    'status' => 'validation_failed',
                    'message' => 'Error found!',
                    'error' => $import->validation_arr
                ]);
            } elseif ($import->error_arr) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Error found!',
                    'error' => $import->error_arr
                ]);
            } else {
                // $fileName = time().'_'.$request->file('product_excel')->getClientOriginalName();
                // $filePath = $request->file('product_excel')->storeAs('csv_products', $fileName, 'public');
                // $fileModel->vendor_id = $request->vendor_id;
                // $fileModel->name = $fileName;
                // $fileModel->path = '/storage/' . $filePath;
                // $fileModel->status = 1;
                // $fileModel->save();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Product imported successfully!',
                    'data' => $import->error_arr
                ]);
            }
        }
    }
    public function importUploadCsv(Request $request)
    {
        $vendor_id = $request->vendor_id;
        $fileModel = new CsvProductImport;
        if ($request->file('product_excel')) {
            $import = new ProductsImport($vendor_id, $fileModel->id, 1);
            Excel::import($import, $request->file('product_excel'));
            if ($import->validation_arr) {
                return response()->json([
                    'status' => 'validation_failed',
                    'message' => 'Error found!',
                    'error' => $import->validation_arr
                ]);
            } elseif ($import->error_arr) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Error found!',
                    'error' => $import->error_arr
                ]);
            } else {
                // $fileName = time().'_'.$request->file('product_excel')->getClientOriginalName();
                // $filePath = $request->file('product_excel')->storeAs('csv_products', $fileName, 'public');
                // $fileModel->vendor_id = $request->vendor_id;
                // $fileModel->name = $fileName;
                // $fileModel->path = '/storage/' . $filePath;
                // $fileModel->status = 1;
                // $fileModel->save();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Product imported successfully!',
                    'data' => $import->error_arr
                ]);
            }
        }
    }


    # get dispatcher tags from dispatcher panel  
    public function getDispatcherTags($vendor_id)
    {
        try {
            $dispatch_domain = $this->checkIfPickupDeliveryOn();
            if ($dispatch_domain && $dispatch_domain != false) {

                $unique = Auth::user()->code;
                $email =  $unique . $vendor_id . "_royodispatch@dispatch.com";

                $client = new GCLIENT([
                    'headers' => [
                        'personaltoken' => $dispatch_domain->pickup_delivery_service_key,
                        'shortcode' => $dispatch_domain->pickup_delivery_service_key_code,
                        'content-type' => 'application/json'
                    ]
                ]);
                $url = $dispatch_domain->pickup_delivery_service_key_url;
                //$res = $client->get($url.'/api/get-agent-tags?email_set='.$email);
                $res = $client->get($url . '/api/get-agent-tags?vendor_id=' . $unique . "_" . $vendor_id);
                $response = json_decode($res->getBody(), true);
                if ($response && $response['message'] == 'success') {
                    return $response['tags'];
                }
            }
        } catch (\Exception $e) {
        }
    }
    # check if last mile delivery on 
    public function checkIfPickupDeliveryOn()
    {
        $preference = ClientPreference::first();
        if ($preference->need_dispacher_ride == 1 && !empty($preference->pickup_delivery_service_key) && !empty($preference->pickup_delivery_service_key_code) && !empty($preference->pickup_delivery_service_key_url))
            return $preference;
        else
            return false;
    }


    # get dispatcher on demand tags from dispatcher panel  
    public function getDispatcherOnDemandTags($vendor_id)
    {
        try {
            $dispatch_domain = $this->checkIfOnDemandOn();
            if ($dispatch_domain && $dispatch_domain != false) {

                $unique = Auth::user()->code;
                $email =  $unique . $vendor_id . "_royodispatch@dispatch.com";

                $client = new GCLIENT([
                    'headers' => [
                        'personaltoken' => $dispatch_domain->dispacher_home_other_service_key,
                        'shortcode' => $dispatch_domain->dispacher_home_other_service_key_code,
                        'content-type' => 'application/json'
                    ]
                ]);
                $url = $dispatch_domain->dispacher_home_other_service_key_url;
                //$res = $client->get($url.'/api/get-agent-tags?email_set='.$email);
                $res = $client->get($url . '/api/get-agent-tags?vendor_id=' . $unique . "_" . $vendor_id);
                $response = json_decode($res->getBody(), true);
                if ($response && $response['message'] == 'success') {
                    return $response['tags'];
                }
                Log::info($response);
            }
        } catch (\Exception $e) {
            Log::info($e->getMessage());
        }
    }
    # check if last mile delivery on 
    public function checkIfOnDemandOn()
    {
        $preference = ClientPreference::first();
        if ($preference->need_dispacher_home_other_service == 1 && !empty($preference->dispacher_home_other_service_key) && !empty($preference->dispacher_home_other_service_key_url) && !empty($preference->dispacher_home_other_service_key_code))
            return $preference;
        else
            return false;
    }
}
