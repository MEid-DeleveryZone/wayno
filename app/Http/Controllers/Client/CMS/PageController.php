<?php

namespace App\Http\Controllers\Client\CMS;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\Page;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ClientLanguage;
use App\Models\PageTranslation;
use App\Http\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $client_lang = ClientLanguage::where('is_primary', 1)->first();
        $pages = Page::with('primary')->latest('id')->get();
        $client_languages = ClientLanguage::join('languages as lang', 'lang.id', 'client_languages.language_id')
            ->select('lang.id as langId', 'lang.name as langName', 'lang.sort_code', 'client_languages.is_primary')
            ->where('client_languages.client_code', Auth::user()->code)
            ->where('client_languages.is_active', 1)
            ->orderBy('client_languages.is_primary', 'desc')->get();
        return view('backend.cms.page.index', compact('client_languages', 'pages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $domain = ''){
        $rules = array(
            'edit_title' => 'required',
            'edit_description' => 'required',
        );
        $validation  = Validator::make($request->all(), $rules)->validate();
        $page = new Page();
        $page->slug = Str::slug($request->edit_title, '-');
        $page->save();
        $page_translation = new PageTranslation();
        $page_translation->page_id = $page->id;
        $page_translation->language_id = $request->language_id;
        $page_translation->is_published = $request->is_published;
        $page_translation->meta_title = $request->edit_meta_title;
        $page_translation->description = $request->edit_description;
        $page_translation->title = $request->edit_title;
        $page_translation->meta_keyword = $request->edit_meta_keyword;
        $page_translation->meta_description = $request->edit_meta_description;
        $page_translation->type_of_form = $request->type_of_form;
        $page_translation->save();
        return $this->successResponse($page_translation, 'Page Data Saved Successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $domain = '', $id){
        $language_id = $request->language_id;
        $page =  Page::with(array('translation' => function($query) use($language_id) {
            $query->where('language_id', $language_id);
        }))->where('id', $id)->first();
        return $this->successResponse($page);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $domain = ''){
        $rules = array(
            'edit_title' => 'required',
            'edit_description' => 'required',
        );
        $validation  = Validator::make($request->all(), $rules)->validate();
        $page_detail = Page::where('id', $request->page_id)->firstOrFail();
        if ($request->filled('slug')) {
            $page_detail->slug = Str::slug($request->slug, '-');
        }
        $page_detail->save();
        $page_translation = PageTranslation::where('page_id', $request->page_id)->where('language_id', $request->language_id)->first();
        if(!$page_translation){
            $page_translation = new PageTranslation();
        }
        $page_translation->page_id = $request->page_id;
        $page_translation->title = $request->edit_title;
        $page_translation->language_id = $request->language_id;
        $page_translation->is_published = $request->is_published;
        $page_translation->meta_title = $request->edit_meta_title;
        $page_translation->description = $request->edit_description;
        $page_translation->meta_keyword = $request->edit_meta_keyword;
        $page_translation->meta_description = $request->edit_meta_description;
        $page_translation->type_of_form = $request->type_of_form;
        $page_translation->save();
        return $this->successResponse($page_translation, 'Page Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $domain = ''){
        try {
            DB::beginTransaction();
            $user = Page::find($request->page_id);
            $user->translations()->delete();
            $user->delete();
            DB::commit();
            return $this->successResponse([], 'Page Deleted Successfully.');
        } catch (Exception $e) {
            DB::rollback();
        }
    }
}
