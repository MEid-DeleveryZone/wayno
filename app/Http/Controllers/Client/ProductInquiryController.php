<?php

namespace App\Http\Controllers\Client;
use DataTables;
use App\Models\Role;
use App\Models\User;
use App\Models\Country;
use App\Models\Product;
use Illuminate\Support\Str; 
use Illuminate\Http\Request;
use App\Models\ProductInquiry;
use App\Http\Controllers\Client\BaseController;
use Auth;
class ProductInquiryController extends BaseController{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        // total vendor 
        $total_vendor = ProductInquiry::distinct();
        if (Auth::user()->is_superadmin == 0) {
            $total_vendor = $total_vendor->whereHas('product.vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $total_vendor = $total_vendor->count('vendor_id');

         // total product 
        $total_product = ProductInquiry::distinct();
        if (Auth::user()->is_superadmin == 0) {
            $total_product = $total_product->whereHas('product.vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $total_product =$total_product->count('product_id');
        return view('backend.inquries.index')->with([
            'total_vendor' => $total_vendor, 
            'total_product' => $total_product
        ]);
    }
    public function show(Request $request){
        $product_inquiries = ProductInquiry::with('product.primary');
        if (Auth::user()->is_superadmin == 0) {
            $product_inquiries = $product_inquiries->whereHas('product.vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $product_inquiries = $product_inquiries->get();
        foreach ($product_inquiries as $product_inquiry) {
            $product_inquiry->view_url = route('productDetail', [$product_inquiry->product->sku]);
        }
        return Datatables::of($product_inquiries)
            ->addIndexColumn()
            ->filter(function ($instance) use ($request) {
            if (!empty($request->get('search'))) {
                $instance->collection = $instance->collection->filter(function ($row) use ($request){
                    if (Str::contains(Str::lower($row['name']), Str::lower($request->get('search')))){
                        return true;
                    }
                    return false;
                });
            }
        })->make(true);
    }
}
