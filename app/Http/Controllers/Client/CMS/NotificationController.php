<?php

namespace App\Http\Controllers\Client\CMS;
use Illuminate\Http\Request;
use App\Models\NotificationTemplate;
use App\Models\NotificationTemplateTranslation;
use App\Models\ClientLanguage;
use App\Http\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    use ApiResponser;

    public function index(){
        $notification_templates = NotificationTemplate::with('primary')->latest('id')->get();
        $client_languages = ClientLanguage::join('languages as lang', 'lang.id', 'client_languages.language_id')
            ->select('lang.id as langId', 'lang.name as langName', 'lang.sort_code', 'client_languages.is_primary')
            ->where('client_languages.client_code', Auth::user()->code)
            ->where('client_languages.is_active', 1)
            ->orderBy('client_languages.is_primary', 'desc')->get();
        return view('backend.cms.notification.index', compact('notification_templates', 'client_languages'));
    }
    
    public function show(Request $request, $domain = '', $id){
        $language_id = $request->language_id;
        $notification_template = NotificationTemplate::with(array('translation' => function($query) use($language_id) {
            $query->where('language_id', $language_id);
        }))->where('id', $id)->first();
        return $this->successResponse($notification_template);
    }
    
    public function update(Request $request, $id){
        $rules = array(
            'subject' => 'required',
            'content' => 'required',
        );
        $validation  = Validator::make($request->all(), $rules)->validate();
        
        $notification_template = NotificationTemplate::where('id', $request->notification_template_id)->firstOrFail();
        
        $notification_translation = NotificationTemplateTranslation::where('notification_template_id', $request->notification_template_id)
            ->where('language_id', $request->language_id)->first();
        
        if(!$notification_translation){
            $notification_translation = new NotificationTemplateTranslation();
        }
        
        $notification_translation->notification_template_id = $request->notification_template_id;
        $notification_translation->language_id = $request->language_id;
        $notification_translation->subject = $request->subject;
        $notification_translation->content = $request->content;
        $notification_translation->save();
        
        return $this->successResponse($notification_translation, 'Notification Template Updated Successfully.');
    }
}
