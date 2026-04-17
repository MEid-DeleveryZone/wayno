<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Front\FrontController;
use App\Models\{UserWishlist, Product, ClientCurrency};
use Carbon\Carbon;
use Auth;
use Session;

class WishlistController extends FrontController
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function wishlists(){
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
        $currency_id = Session::get('customerCurrency');
        $clientCurrency = ClientCurrency::where('currency_id', $currency_id)->first();
        $wishList = UserWishlist::with(['product.media.image', 'product.translation' => function($q) use($langId){
            $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
            },
            'product.variant' => function($q){
                $q->select('id', 'sku', 'product_id', 'quantity', 'price', 'barcode');
                $q->groupBy('product_id');
            },
        ])->select( "id", "user_id", "product_id", "added_on")
        ->where('user_id', Auth::user()->id)->get();
        if(!empty($wishList)){
            foreach($wishList as $key => $wish){
                $wish->product->translation_title = (!empty($wish->product->translation)) ? $wish->product->translation->first()->title : 'NA';
                $wish->product->variant_price = (!empty($wish->product->variant)) ? ($wish->product->variant->first()->price * $clientCurrency->doller_compare) : 0;
                $wish->product->variant_quantity = (!empty($wish->product->variant)) ? $wish->product->variant->first()->quantity : 0;
            }
            $wishList = $wishList->toArray();
        }
       return view('frontend/account/wishlist')->with(['navCategories' => $navCategories, 'wishList' => $wishList, 'clientCurrency'=>$clientCurrency]);
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateWishlist(Request $request)
    {
        $product = Product::where('sku', $request->sku)->firstOrFail();
        $exist = UserWishlist::where('user_id', Auth::user()->id)->where('product_id', $product->id)->first();
        if($exist){
            $exist->delete();
            return response()->json(array('status' => 'success', 'message'=> 'Product has been removed from wishlist.'));
        }
        $wishlist = new UserWishlist();
        $wishlist->user_id = Auth::user()->id;
        $wishlist->product_id = $product->id;
        $wishlist->product_variant_id = $request->variant_id;
        $wishlist->added_on = Carbon::now();
        $wishlist->save();
        return response()->json(array('status' => 'success', 'message' => 'Product has been added in wishlist.'));
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function removeWishlist($domain = '', Request $request, $sku)
    {
        $product = Product::withTrashed()->where('sku', $sku)->firstOrFail();

        $exist = UserWishlist::where('user_id', Auth::user()->id)->where('product_id', $product->id)->first();

        if($exist){
            $exist->delete();
            return redirect()->route('user.wishlists');
        }
        $wishlist = new UserWishlist();
        $wishlist->user_id = Auth::user()->id;
        $wishlist->product_id = $product->id;
        $wishlist->added_on = Carbon::now();
        $wishlist->save();

        return redirect()->route('user.wishlists');   
     }

}
