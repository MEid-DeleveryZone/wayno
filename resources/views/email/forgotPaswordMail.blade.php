<!DOCTYPE html>
<html lang="en">
   <head>
      <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
      <title>Reset Password Email Template</title>
      <meta name="description" content="Reset Password Email Template.">
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
            /*background-image: url(./assets/invoice-images/pattern.png);*/
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
   <body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0px; background-color: #f2f3f8;" leftmargin="0">
      <table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="#f2f3f8"
          style="@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: 'Open Sans', sans-serif;">
          <thead>
             <tr>
                  <th style="height:80px;">&nbsp;</th>
              </tr>
              <tr>
                  <th style="text-align:center;">
                    <a href="{{url('/')}}" title="logo" target="_blank">
                      <img width="100" src="{{$mailData['logo']}}" title="logo" alt="logo">
                    </a>
                  </th>
              </tr>
          </thead>
          {!! $mailData['email_template_content'] !!}
      </table>
  </body>
</html>