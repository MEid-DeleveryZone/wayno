<?php

namespace App\Http\Controllers\Client\CMS;
use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use App\Models\EmailTemplateTranslation;
use App\Models\ClientLanguage;
use App\Http\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EmailController extends Controller{
    use ApiResponser;
    
    public function index(){
        $email_templates = EmailTemplate::with('primary')->latest('id')->get();
        $client_languages = ClientLanguage::join('languages as lang', 'lang.id', 'client_languages.language_id')
            ->select('lang.id as langId', 'lang.name as langName', 'lang.sort_code', 'client_languages.is_primary')
            ->where('client_languages.client_code', Auth::user()->code)
            ->where('client_languages.is_active', 1)
            ->orderBy('client_languages.is_primary', 'desc')->get();
        return view('backend.cms.email.index', compact('email_templates', 'client_languages'));
    }
    
    public function show(Request $request, $domain = '', $id){
        $language_id = $request->language_id;
        $email_template = EmailTemplate::with(array('translation' => function($query) use($language_id) {
            $query->where('language_id', $language_id);
        }))->where('id', $id)->first();
        return $this->successResponse($email_template);
    }
    
    public function update(Request $request, $id){
        $rules = array(
            'subject' => 'required',
            'content' => 'required',
        );
        $validation  = Validator::make($request->all(), $rules)->validate();
        
        $email_template = EmailTemplate::where('id', $request->email_template_id)->firstOrFail();
        
        $email_translation = EmailTemplateTranslation::where('email_template_id', $request->email_template_id)
            ->where('language_id', $request->language_id)->first();
        
        if(!$email_translation){
            $email_translation = new EmailTemplateTranslation();
        }
        
        $email_translation->email_template_id = $request->email_template_id;
        $email_translation->language_id = $request->language_id;
        $email_translation->subject = $request->subject;
        $email_translation->content = $request->content;
        $email_translation->save();
        
        return $this->successResponse($email_translation, 'Email Template Updated Successfully.');
    }
}
