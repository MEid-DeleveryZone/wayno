<?php

namespace App\Services;

use App\Models\EmailTemplate;
use App\Models\EmailTemplateTranslation;
use App\Models\Client;
use App\Models\ClientPreference;
use App\Models\User;
use App\Models\Language;
use App\Models\ClientLanguage;
use App\Mail\newTemplateEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Http\Request;

class EmailService
{
    protected $client;
    protected $clientPreference;
    protected $mailConfigured = false;

    /**
     * Configure mail settings from ClientPreference
     */
    protected function configureMail()
    {
        if ($this->mailConfigured) {
            return true;
        }

        $this->clientPreference = ClientPreference::select(
            'mail_driver',
            'mail_host',
            'mail_port',
            'mail_username',
            'mail_password',
            'mail_encryption',
            'mail_from',
            'android_app_link',
            'ios_link'
        )->where('id', '>', 0)->first();

        if (!$this->clientPreference) {
            Log::error('ClientPreference not found for email configuration');
            return false;
        }

        if (empty($this->clientPreference->mail_driver) || 
            empty($this->clientPreference->mail_host) || 
            empty($this->clientPreference->mail_port) || 
            empty($this->clientPreference->mail_password) || 
            empty($this->clientPreference->mail_encryption)) {
            Log::error('Mail configuration incomplete');
            return false;
        }

        $this->client = Client::select('id', 'name', 'email', 'phone_number', 'logo')
            ->where('id', '>', 0)
            ->first();

        if (!$this->client) {
            Log::error('Client not found for email');
            return false;
        }

        $config = [
            'pretend' => false,
            'host' => $this->clientPreference->mail_host,
            'port' => $this->clientPreference->mail_port,
            'driver' => $this->clientPreference->mail_driver,
            'username' => $this->clientPreference->mail_username,
            'password' => $this->clientPreference->mail_password,
            'encryption' => $this->clientPreference->mail_encryption,
            'sendmail' => '/usr/sbin/sendmail -bs',
        ];

        Config::set('mail', $config);
        $app = App::getInstance();
        $app->register('Illuminate\Mail\MailServiceProvider');
        
        $this->mailConfigured = true;
        return true;
    }

    /**
     * Get language RTL status
     * 
     * @param int|null $languageId Language ID
     * @return array Returns array with 'is_rtl' and 'direction'
     */
    protected function getLanguageRtlInfo($languageId = null)
    {
        if (!$languageId) {
            $languageId = session()->get('customerLanguage', 1);
        }

        $language = Language::select('id', 'is_rtl', 'sort_code')->where('id', $languageId)->first();
        
        if (!$language) {
            // Default to LTR if language not found
            return [
                'is_rtl' => 0,
                'direction' => 'ltr',
                'lang_code' => 'en'
            ];
        }

        return [
            'is_rtl' => $language->is_rtl ?? 0,
            'direction' => ($language->is_rtl == 1) ? 'rtl' : 'ltr',
            'lang_code' => $language->sort_code ?? 'en'
        ];
    }

    /**
     * Get email template with translation based on user's language
     * 
     * @param int $templateId Email template ID
     * @param int|null $languageId Language ID (defaults to user's language or English)
     * @return array|null Returns array with 'subject', 'content', and RTL info or null if not found
     */
    protected function getEmailTemplate($templateId, $languageId = null)
    {
        $emailTemplate = EmailTemplate::where('id', $templateId)->first();
        
        if (!$emailTemplate) {
            Log::error("Email template not found: {$templateId}");
            return null;
        }

        // If language_id is not provided, try to get from user or default to English (1)
        if (!$languageId) {
            $languageId = session()->get('customerLanguage', 1);
        }

        // Get RTL information for the language
        $rtlInfo = $this->getLanguageRtlInfo($languageId);

        // Get translation for the specified language
        $translation = EmailTemplateTranslation::where('email_template_id', $templateId)
            ->where('language_id', $languageId)
            ->first();

        // Fallback to English (language_id = 1) if translation not found
        if (!$translation) {
            $translation = EmailTemplateTranslation::where('email_template_id', $templateId)
                ->where('language_id', 1)
                ->first();
            
            // If falling back to English, also update RTL info
            if ($translation) {
                $rtlInfo = $this->getLanguageRtlInfo(1);
            }
        }

        // If still no translation, try to get any available translation
        if (!$translation) {
            $translation = EmailTemplateTranslation::where('email_template_id', $templateId)
                ->first();
        }

        // Backward compatibility: If no translation exists, check if old structure has content
        if (!$translation) {
            // Check if email_templates table still has subject/content columns (before migration)
            if (isset($emailTemplate->subject) && isset($emailTemplate->content)) {
                return array_merge([
                    'subject' => $emailTemplate->subject,
                    'content' => $emailTemplate->content,
                    'tags' => $emailTemplate->tags,
                    'label' => $emailTemplate->label
                ], $rtlInfo);
            }
            
            Log::error("Email template translation not found for template: {$templateId}");
            return null;
        }

        return array_merge([
            'subject' => $translation->subject,
            'content' => $translation->content,
            'tags' => $emailTemplate->tags,
            'label' => $emailTemplate->label
        ], $rtlInfo);
    }

    /**
     * Replace template variables in content
     * 
     * @param string $content Email template content
     * @param array $variables Array of variables to replace (e.g., ['{customer_name}' => 'John'])
     * @return string Processed content
     */
    protected function replaceVariables($content, $variables = [])
    {
        foreach ($variables as $key => $value) {
            // Support both {key} and key formats
            $searchKey = (strpos($key, '{') === false) ? '{' . $key . '}' : $key;
            $content = str_ireplace($searchKey, $value, $content);
        }
        return $content;
    }

    /**
     * Send email using template
     * 
     * @param string|array $to Recipient email address(es)
     * @param int $templateId Email template ID
     * @param array $variables Variables to replace in template (e.g., ['customer_name' => 'John', 'code' => '1234'])
     * @param array $additionalData Additional data to pass to email view
     * @param int|null $languageId Language ID for template translation
     * @param bool $queue Whether to queue the email (default: true)
     * @param string|null $queueName Queue name (default: 'verify_email')
     * @return bool Success status
     */
    public function sendEmail(
        $to,
        $templateId,
        $variables = [],
        $additionalData = [],
        $languageId = null,
        $queue = true,
        $queueName = 'verify_email'
    ) {
        try {
            // Configure mail settings
            if (!$this->configureMail()) {
                return false;
            }

            // Get email template
            $template = $this->getEmailTemplate($templateId, $languageId);
            if (!$template) {
                return false;
            }

            // Replace variables in subject and content
            $subject = $this->replaceVariables($template['subject'], $variables);
            $content = $this->replaceVariables($template['content'], $variables);

            // Get RTL information from template (already included in getEmailTemplate)
            $rtlInfo = [
                'is_rtl' => $template['is_rtl'] ?? 0,
                'direction' => $template['direction'] ?? 'ltr',
                'lang_code' => $template['lang_code'] ?? 'en'
            ];

            // Prepare email data
            $emailData = array_merge([
                'email' => is_array($to) ? $to[0] : $to,
                'mail_from' => $this->clientPreference->mail_from,
                'client_name' => $this->client->name,
                'logo' => $this->client->logo['original'] ?? '',
                'subject' => $subject,
                'email_template_content' => $content,
                'android_app_link' => $this->clientPreference->android_app_link ?? '',
                'ios_link' => $this->clientPreference->ios_link ?? '',
                'is_rtl' => $rtlInfo['is_rtl'],
                'direction' => $rtlInfo['direction'],
                'lang_code' => $rtlInfo['lang_code'],
            ], $additionalData);

            // Create mailable instance
            $email = new newTemplateEmail($emailData);

            // Send email directly or queue it
            if ($queue) {
                dispatch(new \App\Jobs\sendEmailJob($emailData))->onQueue($queueName);
            } else {
                Mail::to($to)->send($email);
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Email sending failed', [
                'to' => $to,
                'template_id' => $templateId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Send email with custom content (without template)
     * 
     * @param string|array $to Recipient email address(es)
     * @param string $subject Email subject
     * @param string $content Email content (HTML)
     * @param array $additionalData Additional data to pass to email view
     * @param bool $queue Whether to queue the email (default: true)
     * @param string|null $queueName Queue name (default: 'verify_email')
     * @return bool Success status
     */
    public function sendCustomEmail(
        $to,
        $subject,
        $content,
        $additionalData = [],
        $queue = true,
        $queueName = 'verify_email'
    ) {
        try {
            // Configure mail settings
            if (!$this->configureMail()) {
                return false;
            }

            // Get RTL information (default to LTR for custom emails, but check session language)
            $rtlInfo = $this->getLanguageRtlInfo(session()->get('customerLanguage', 1));

            // Prepare email data
            $emailData = array_merge([
                'email' => is_array($to) ? $to[0] : $to,
                'mail_from' => $this->clientPreference->mail_from,
                'client_name' => $this->client->name,
                'logo' => $this->client->logo['original'] ?? '',
                'subject' => $subject,
                'email_template_content' => $content,
                'android_app_link' => $this->clientPreference->android_app_link ?? '',
                'ios_link' => $this->clientPreference->ios_link ?? '',
                'is_rtl' => $rtlInfo['is_rtl'],
                'direction' => $rtlInfo['direction'],
                'lang_code' => $rtlInfo['lang_code'],
            ], $additionalData);

            // Create mailable instance
            $email = new newTemplateEmail($emailData);

            // Send email directly or queue it
            if ($queue) {
                dispatch(new \App\Jobs\sendEmailJob($emailData))->onQueue($queueName);
            } else {
                Mail::to($to)->send($email);
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Custom email sending failed', [
                'to' => $to,
                'subject' => $subject,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Get user's language ID for email template
     * 
     * @param User|null $user User model
     * @return int Language ID
     */
    protected function getUserLanguageId($user = null)
    {
        if ($user && $user->language_id) {
            return $user->language_id;
        }
        
        return session()->get('customerLanguage', 1);
    }

    /**
     * Get language ID from request header
     * Priority: Request header > User language > Session > Default (English)
     * 
     * @param Request|null $request Request object
     * @param User|null $user User model
     * @return int Language ID
     */
    public function getLanguageFromRequest($request = null, $user = null)
    {
        $languageId = null;
        
        // Priority 1: Check request header (highest priority)
        if ($request && $request->hasHeader('language')) {
            $langValue = $request->header('language');
            
            // Check if the value is numeric (old way: language_id) or string (new way: language code like 'en', 'ar')
            if (is_numeric($langValue)) {
                // Backward compatibility: support numeric language_id
                $checkLang = ClientLanguage::where('language_id', $langValue)->first();
                if ($checkLang) {
                    $languageId = $checkLang->language_id;
                }
            } else {
                // New way: support language codes like 'en', 'ae', 'ar'
                $checkLang = ClientLanguage::whereHas('language', function($q) use ($langValue) {
                    $q->where('sort_code', $langValue);
                })->first();
                if ($checkLang) {
                    $languageId = $checkLang->language_id;
                }
            }
        }
        
        // Priority 2: User's saved language preference
        if (!$languageId && $user && $user->language_id) {
            $languageId = $user->language_id;
        }
        
        // Priority 3: Session language
        if (!$languageId) {
            $languageId = session()->get('customerLanguage', 1);
        }
        
        // Priority 4: Default to English
        return $languageId ?: 1;
    }
}

