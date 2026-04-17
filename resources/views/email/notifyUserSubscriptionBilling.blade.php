<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <title>Verify Mail</title>
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
                           <img src="{{ $mailData['logo']}}" height="50px" alt="">
                        </a>
                    </th>
                 </tr>
              </thead>
              <tbody style="text-align: center;">
                 <tr>
                    <td style="padding-top: 0;">
                     <div style="background: #fff;box-shadow: 0 3px 4px #ddd;border-bottom-left-radius: 20px;border-bottom-right-radius: 20px;padding: 15px 40px 30px;">
                       <b style="margin-bottom: 10px; display: block;">Hi {{$mailData['customer_name']}},</b>
                       <p>Your upcoming billing date for {{$mailData['frequency']}} subscription plan is {{$mailData['end_date']}}. </p>
                       <p>Please go to following link to continue your subscription plan: </p>
                        <div style="padding:10px;border: 2px dashed #cb202d;word-break:keep-all!important;width: calc(100% - 40px);margin: 25px auto;">
                        <p style="Margin:0;Margin-bottom:16px;color:#cb202d;font-family:-apple-system,Helvetica,Arial,sans-serif;font-size:20px;font-weight:600;line-height:1.5;margin:0;margin-bottom:0;padding:0;text-align:center;word-break:keep-all!important">
                           <a href="{{$mailData['link']}}">{{$mailData['link']}}</a>
                        </p>
                        </div>
                        <p>NOTE: Your subscription will be deactivated automatically if billing is not done.</p>
                        <!-- <div style="margin: 30px 0 0;color: #ddd;">
                           Thank you, <br>
                           Team Royo <br><br>

                           If you did not make this request, you can safely ignore this email.
                        </div> -->
                     </div>
                    </td>
                 </tr>
              </tbody>
              <!-- <tfoot style="text-align: center;">
               <tr>
                  <td colspan="2" style="padding: 0 15px 20px;">
                     <div style="background: #fff;box-shadow: 0 -2px 4px #ddd;border-radius: 20px;padding: 15px 0 0;margin-top: 20px;">
                        <p colspan="2" style="padding: 0 35px 20px;color: #1F2431;font-size: 18px;letter-spacing: 0;line-height: 24px;text-align: center;font-weight: 400;">
                           Please download <b>sales.royoorders.com</b> app <br> to start your consultation.
                        </p>
                        <a href="#"><img src="images/ic_App_Store_Badgex48.png" alt=""></a>
                        <a href="#"><img src="images/ic_App_Store_Badgex48.png" alt=""></a>
                        <p style="background-color: #8142ff;padding:5px 0;text-align:center;color: #fff;margin-top: 30px; ">Powered by <b>sales.royoorders.com</b></p>
                     </div>
                  </td>
               </tr>
            </tfoot> -->
            </table>
         </div>
      </section>
   </body>
</html>