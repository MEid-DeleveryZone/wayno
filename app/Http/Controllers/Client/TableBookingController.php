<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\{VendorDineinCategory, VendorDineinTable, VendorDineinCategoryTranslation, VendorDineinTableTranslation};
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Traits\ApiResponser;

class TableBookingController extends BaseController
{
    use ApiResponser;

    public function storeCategory(Request $request)
    {
        $rules = array(
            'title' => 'required|string|max:150|unique:vendor_dinein_categories',
            'name.0' => 'required|string|max:60',
        );
        $validation  = Validator::make($request->all(), $rules)->validate();

        $vendor_category = new VendorDineinCategory();
        $vendor_category->vendor_id = $request->vendor_id;
        $vendor_category->title = $request->title;
        $vendor_category->save();

        if ($vendor_category->id > 0) {
            foreach ($request->language_id as $key => $value) {
                $category_translation = new VendorDineinCategoryTranslation();
                $category_translation->title = $request->name[$key];
                $category_translation->category_id = $vendor_category->id;
                $category_translation->language_id = $request->language_id[$key];
                $category_translation->save();
            }

            return redirect()->back()->with('success', 'Added Successfully!');
        }
    }

    public function editCategory(Request $request)
    {
        $table_category = VendorDineinCategory::where('id', $request->table_category_id)->with('translations')->first();
        return $this->successResponse($table_category, '');
    }

    public function updateCategory(Request $request)
    {
        $rules = array(
            'title' => 'required|string|max:150|unique:vendor_dinein_categories,title,' . $request->table_category_id,
        );
        $validation  = Validator::make($request->all(), $rules)->validate();
        $vendor_category = VendorDineinCategory::where('id', $request->table_category_id)->first();
        if ($vendor_category) {
            $vendor_category->vendor_id = $request->vendor_id;
            $vendor_category->title = $request->title;
            $vendor_category->save();
            if ($vendor_category->id > 0) {
                foreach ($request->language_id as $key => $value) {
                    $table_translation = VendorDineinCategoryTranslation::where('language_id', $request->language_id[$key])->where('category_id', $vendor_category->id)->first();
                    if (!$table_translation) {
                        $table_translation = new VendorDineinCategoryTranslation();
                    }
                    $table_translation->title = $request->name[$key];
                    $table_translation->category_id = $vendor_category->id;
                    $table_translation->language_id = $request->language_id[$key];
                    $table_translation->save();
                }
            }
        }
        return redirect()->back()->with('success', 'Updated Successfully!');
    }

    public function destroyCategory($domain = "", Request $request, $vid)
    {
        VendorDineinCategory::where('vendor_dinein_category_id', $request->vendor_table_category_id)->where('vendor_id', $vid)->delete();
        return redirect()->back()->with('success', 'Deleted Successfully!');
    }

    public function destroyTable($domain = "", Request $request, $vid)
    {
        VendorDineinTable::where('id', $request->table_id)->where('vendor_id', $vid)->delete();
        return redirect()->back()->with('success', 'Deleted Successfully!');
    }

    public function storeTable(Request $request)
    {
        $rules = array(
            'name.0' => 'required|string|max:60',
            'table_number' => 'required|string|max:30|unique:vendor_dinein_tables',
        );
        $validation  = Validator::make($request->all(), $rules)->validate();
        $table = new VendorDineinTable();
        $table->table_number = $request->table_number;
        $table->image = Storage::disk('s3')->put('image', $request->image, 'public');
        $table->vendor_id = $request->vendor_id;
        $table->seating_number = $request->seating_number;
        $table->vendor_dinein_category_id = $request->vendor_dinein_category_id;
        $table->save();

        if ($table->id > 0) {
            foreach ($request->language_id as $key => $value) {
                $table_translation = new VendorDineinTableTranslation();
                $table_translation->name = $request->name[$key];
                $table_translation->meta_title = $request->meta_title[$key];
                $table_translation->meta_description = $request->meta_description[$key];
                $table_translation->meta_keywords = $request->meta_keywords[$key];
                $table_translation->vendor_dinein_table_id = $table->id;
                $table_translation->language_id = $request->language_id[$key];
                $table_translation->save();
            }

            return redirect()->back()->with('success', 'Table Added Successfully!');
        }
    }

    public function editTable(Request $request)
    {
        $table_data = VendorDineinTable::where('id', $request->table_id)->with('translations')->first();
        return $this->successResponse($table_data, '');
    }

    public function updateTable(Request $request)
    {
        $rules = array(
            'name.0' => 'required|string|max:60',
            'table_number' => 'required|string|max:30|unique:vendor_dinein_tables,table_number,' . $request->table_id,
        );
        $validation  = Validator::make($request->all(), $rules)->validate();
        $table = VendorDineinTable::where('id', $request->table_id)->first();
        if ($table) {
            $table->table_number = $request->table_number;
            if ($request->has('image')) {
                $table->image = Storage::disk('s3')->put('image', $request->image, 'public');
            }
            $table->vendor_id = $request->vendor_id;
            $table->seating_number = $request->seating_number;
            $table->vendor_dinein_category_id = $request->vendor_dinein_category_id;
            $table->save();
            if ($table->id > 0) {
                foreach ($request->language_id as $key => $value) {
                    $table_translation = VendorDineinTableTranslation::where('language_id', $request->language_id[$key])->where('vendor_dinein_table_id', $table->id)->first();
                    if (!$table_translation) {
                        $table_translation = new VendorDineinTableTranslation();
                    }
                    $table_translation->name = $request->name[$key];
                    $table_translation->meta_title = $request->meta_title[$key];
                    $table_translation->meta_description = $request->meta_description[$key];
                    $table_translation->meta_keywords = $request->meta_keywords[$key];
                    $table_translation->vendor_dinein_table_id = $table->id;
                    $table_translation->language_id = $request->language_id[$key];
                    $table_translation->save();
                }
            }
            return redirect()->back()->with('success', 'Table Updated Successfully!');
        }
    }
}
