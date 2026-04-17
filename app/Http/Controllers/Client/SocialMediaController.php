<?php

namespace App\Http\Controllers\Client;
use App\Models\SocialMedia;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use App\Http\Controllers\Client\BaseController;

class SocialMediaController extends BaseController{   
    use ApiResponser;

    public function index(Request $request){
        $social_media_details = SocialMedia::get();
        return view('backend.socialmedia.index', compact('social_media_details'));
    }

    public function create(Request $request){
        try {
            $this->validate($request, [
              'social_media_icon' => 'required',
              'social_media_url' => 'required'
            ],
            ['social_media_url.url' => 'Invalid URL format']);

            SocialMedia::create(['icon' => $request->social_media_icon, 'title' => $request->social_media_icon, 'url' => $request->social_media_url]);
            return $this->successResponse([], 'Social Media Added Successfully.');
        } catch (Exception $e) {
            return $this->errorResponse([], $e->getMessage());
        }
    }
    public function edit(Request $request){
        try {

            $social_media = SocialMedia::where(['id' => $request->social_media_detail_id])->firstOrFail();
            return $this->successResponse($social_media, 'Social Media Added Successfully.');
        } catch (Exception $e) {
            return $this->errorResponse([], $e->getMessage());
        }
    }
    public function update(Request $request){
        try {
            $this->validate($request, [
              'social_media_id' => 'required',
              'social_media_url' => 'required',
              'social_media_icon' => 'required',
            ]);
            SocialMedia::where('id', $request->social_media_id)->update(['icon' => $request->social_media_icon, 'url' => $request->social_media_url]);
            return $this->successResponse([], 'Social Media Updated Successfully.');
        } catch (Exception $e) {
            return $this->errorResponse([], $e->getMessage());
        }
    }
    public function delete(Request $request){
        try {
            SocialMedia::where('id', $request->social_media_detail_id)->delete();
            return $this->successResponse([], 'Social Media Deleted Successfully.');
        } catch (Exception $e) {
            return $this->errorResponse([], $e->getMessage());
        }
    }
}
