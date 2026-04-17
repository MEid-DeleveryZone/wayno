@php
$clientData = \App\Models\Client::where('id', '>', 0)->first();
$urlImg = $clientData->logo['image_fit'].'200/80'.$clientData->logo['image_path'];
$pages = \App\Models\Page::with(['translations' => function($q) {$q->where('language_id', session()->get('customerLanguage') ??1);}])->whereHas('translations', function($q) {$q->where(['is_published' => 1, 'language_id' => session()->get('customerLanguage') ??1]);})->get();

// Get RTL information from mailData
$isRtl = isset($mailData['is_rtl']) ? $mailData['is_rtl'] : 0;
$direction = isset($mailData['direction']) ? $mailData['direction'] : 'ltr';
$langCode = isset($mailData['lang_code']) ? $mailData['lang_code'] : 'en';
@endphp
<!DOCTYPE html>
<html lang="{{ $langCode }}" dir="{{ $direction }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $mailData['subject'] ?? 'Email' }}</title>
    <style type="text/css">
        /* Base styles */
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
        }
        
        /* RTL Support */
        [dir="rtl"] {
            direction: rtl;
            text-align: right;
        }
        
        [dir="rtl"] .container {
            direction: rtl;
            text-align: right;
        }
        
        [dir="rtl"] .footer {
            direction: rtl;
            text-align: center;
        }
        
        [dir="rtl"] .footer-links {
            direction: rtl;
        }
        
        [dir="rtl"] .footer-links a {
            margin: 0 10px;
        }
        
        /* LTR Support */
        [dir="ltr"] {
            direction: ltr;
            text-align: left;
        }
        
        [dir="ltr"] .container {
            direction: ltr;
            text-align: left;
        }
        
        /* Container styles */
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            background-color: #ffffff;
            {{ $isRtl ? 'direction: rtl; text-align: right;' : 'direction: ltr; text-align: left;' }}
        }
        
        /* Content area */
        .email-content {
            {{ $isRtl ? 'direction: rtl; text-align: right;' : 'direction: ltr; text-align: left;' }}
        }
        
        /* Footer styles */
        .footer {
            text-align: center;
            color: #888;
            font-size: 12px;
            padding-top: 20px;
            margin-top: 30px;
            border-top: 1px solid #eee;
        }
        
        .footer-links {
            margin: 15px 0;
        }
        
        .footer-links a {
            color: #888;
            text-decoration: none;
            margin: 0 10px;
        }
        
        .footer-links a:hover {
            text-decoration: underline;
        }
        
        .app-store {
            max-width: 150px;
            height: auto;
            margin: 10px;
        }
        
        /* RTL specific adjustments */
        [dir="rtl"] .app-store {
            margin: 10px;
        }
        
        /* Email client compatibility */
        @media only screen and (max-width: 600px) {
            .container {
                width: 100% !important;
                padding: 15px !important;
            }
        }
        
        /* Ensure images don't break layout */
        img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>

<body>
    <section class="wrapper">
        <div class="container" style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; background-color: #ffffff; {{ $isRtl ? 'direction: rtl; text-align: right;' : 'direction: ltr; text-align: left;' }}">
            <div class="email-content" style="{{ $isRtl ? 'direction: rtl; text-align: right;' : 'direction: ltr; text-align: left;' }}">
                {!! $mailData['email_template_content'] !!}
            </div>
            <div class="footer" style="text-align: center; color: #888; font-size: 12px; padding-top: 20px; margin-top: 30px; border-top: 1px solid #eee;">
                <div>
                    <a href="{{ $mailData['android_app_link'] ?? '#' }}">
                        <img src="{{ asset("assets/images/playstore.png") }}" alt="Google Play Store" class="app-store" style="max-width: 150px; height: auto; margin: 10px;">
                    </a>
                    <a href="{{ $mailData['ios_link'] ?? '#' }}">
                        <img src="{{ asset("assets/images/iosstore.png") }}" alt="Apple App Store" class="app-store" style="max-width: 150px; height: auto; margin: 10px;">
                    </a>
                </div>
                <div class="footer-links" style="margin: 15px 0;">
                    @foreach($pages as $page)
                        <a href="{{url('page',['slug' => $page->slug])}}" style="color: #888; text-decoration: none; margin: 0 10px;">
                            @if(isset($page->translations) && $page->translations->first()->title != null)
								{{ $page->translations->first()->title ?? ''}}
                            @else
								{{ $page->primary->title ?? ''}}
                            @endif
                        </a>
                    @endforeach
                </div>
                <p style="margin: 10px 0;">&copy; {{ date('Y') }} {{ $clientData['company_name'] ?? '' }}. All rights reserved.</p>
            </div>
        </div>
    </section>
</body>

</html>