@php
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
      <title>Verify Mail</title>
      <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">
      <style type="text/css">
         body{
            padding: 0;
            margin: 0;
            font-family: 'Lato', sans-serif;
            font-weight: 400;
         }
         a{
            text-decoration: none;
         }
         h1,h2,h3,h4{
            font-weight: 700;
            margin: 0;
         }
         p{
            font-size: 16px;
            line-height: 22px;
            margin: 0 0 5px;
         }
         .container {
            background: #fff;
            padding: 0;
            max-width: 560px;
            margin: 0 auto;
            border-radius: 4px;
            background-repeat: repeat;
            width: 700px;
            {{ $isRtl ? 'direction: rtl; text-align: right;' : 'direction: ltr; text-align: left;' }}
         }
         table {
            border-collapse: separate;
            text-indent: initial;
            border-spacing: 0;
            {{ $isRtl ? 'text-align: right; direction: rtl;' : 'text-align: left; direction: ltr;' }}
         }
         table th,table td{
            padding: 10px 15px;
         }
         ul {
            margin: 0;
            padding: 0;
         }
         ul li{
            list-style: none;
         }
         /* RTL Support */
         [dir="rtl"] {
            direction: rtl;
            text-align: right;
         }
         [dir="rtl"] table {
            direction: rtl;
            text-align: right;
         }
         [dir="ltr"] {
            direction: ltr;
            text-align: left;
         }
         [dir="ltr"] table {
            direction: ltr;
            text-align: left;
         }
      </style>
   </head>
   <body>
      <section class="wrapper">
         <div class="container" style="background: #fff;border-radius: 10px; {{ $isRtl ? 'direction: rtl; text-align: right;' : 'direction: ltr; text-align: left;' }}">
            <table style="width: 100%; {{ $isRtl ? 'direction: rtl; text-align: right;' : 'direction: ltr; text-align: left;' }}">
              <thead>
                 <tr>
                    <th style="text-align: center;">
                        <a style="display: block;" href="#">
                           <img src="{{ $mailData['logo']}}" height="50px" alt="">
                        </a>
                    </th>
                 </tr>
              </thead>
              <tbody>
                 <tr>
                    <td style="{{ $isRtl ? 'direction: rtl; text-align: right;' : 'direction: ltr; text-align: left;' }}">
                       {!! $mailData['email_template_content'] !!}
                    </td>
                 </tr>
              </tbody>
            </table>
         </div>
      </section>
   </body>
</html>