<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <title>New Vendor Signup</title>
      <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">
      <style type="text/css">
         body{
            padding: 0;
            margin: 0;font-family: 'Lato', sans-serif;
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
         }
         table {
            border-collapse: separate;
            text-indent: initial;
            border-spacing: 0;
            text-align: left;
         }
         table th,table td{
            padding: 10px 15px;
         }
         ul {
            margin: 0;padding: 0;
         }
         ul li{
            list-style: none;
         }
      </style>
   </head>
   <body>
      <section class="wrapper">
         <div class="container" style="background: #fff;border-radius: 10px;">
            <table style="width: 100%;">
              <thead>
                 <tr>
                    <th style="text-align: center;">
                        <a style="display: block;" href="#">
                           <img style="height:50px" src="{{$mailData['logo']}}" alt="">
                        </a>
                    </th>
                 </tr>
              </thead>
                 <tr>
                    <td>
                        <div style="height: 300px;center;position: relative;">
                           <img src="{{$mailData['banner']['image_fit'].'600/400'.$mailData['banner']['image_path']}}" style="height: 100%;width: 100%;object-fit: cover;">
                           <div style="border-radius: 50%;width: 120px;height: 120px;padding: 20px; margin-top:-150px; display: block; position: absolute; bottom: -10px; right: -10px;margin: auto;">
                              <img style="object-fit: contain; width: 100%;height: 100%;" src="{{$mailData['vendor_logo']['proxy_url'].'100/100'.$mailData['vendor_logo']['image_path']}}">
                           </div>
                        </div>
                    </td>
                 </tr>
                 {!! $mailData['email_template_content'] !!}
            </table>
         </div>
      </section>
   </body>
</html>