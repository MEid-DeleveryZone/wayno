<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <title>Contact Us</title>
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
                    <th>
                        <a style="display: block;" href="#">
                           <img src="{{ $mailData['logo']}}" height="50px" alt="">
                        </a>
                    </th>
                 </tr>
              </thead>
              <tbody>
                 <tr>
                    <td style="padding-top: 0;">
                     <div style="background: #fff;box-shadow: 0 3px 4px #ddd;border-bottom-left-radius: 20px;border-bottom-right-radius: 20px;padding: 15px 40px 30px;">
                       <b style="margin-bottom: 10px; display: block;">Hi {{$mailData['superadmin_name']}},</b>
                       <p>A customer is requesting for contact you. </p>
                       <p>Customer Name: {{$mailData['customer_name']}}</p>
                       <p>Customer Email: {{$mailData['customer_email']}}</p>
                       @if($mailData['customer_phone_number'] != '')
                        <p>Customer Phone: {{$mailData['customer_phone_number']}}</p>
                       @endif
                       <p>Customer Message: {{$mailData['customer_message']}}</p>
                     </div>
                    </td>
                 </tr>
              </tbody>
            </table>
         </div>
      </section>
   </body>
</html>