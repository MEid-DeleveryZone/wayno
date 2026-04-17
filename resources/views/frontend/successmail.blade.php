<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Email Temp</title>
</head>

<body>

    <table role="presentation" align="center" border="0" cellspacing="0" cellpadding="0" width="100%" bgcolor="#F5F5F5" style="background-color: #F5F5F5; table-layout: fixed; height:100vh">
        <tbody>
            <tr>
                <td align="center">
                    <center style="width: 100%;">
                        <table role="presentation" border="0" cellspacing="0" cellpadding="0" width="680" bgcolor="#FFFFFF" style="background-color: #ffffff; margin: 0 auto; max-width: 680px; width: inherit;">
                            <tbody>
                                <tr>
                                    <td bgcolor="#fff" style="background-color: #fff; padding: 12px; border-bottom: 1px solid #ee5578;">
                                        <table role="presentation" border="0" cellspacing="0" cellpadding="0" width="100%" style="width: 100% !important; min-width: 100% !important;">
                                            <tbody>
                                                <tr>
                                                    <td align="left" valign="middle">
                                                        <img src="{{ asset('assets/images/favicon.png') }}" style="max-height: 100%;" alt="...">
                                                    </td>
                                                    <td valign="middle" width="100%" align="right">

                                                    </td>
                                                    <td width="1">&nbsp;</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table role="presentation" border="0" cellspacing="0" cellpadding="0" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td style="padding: 20px 24px 10px 24px;">
                                                        <table role="presentation" border="0" cellspacing="0" cellpadding="0" width="100%">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="padding-bottom: 20px;">
                                                                        <?php
                                                                         echo "$email_template_content";
                                                                        ?><br><br><br>                                                                       
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table role="presentation" border="0" cellspacing="0" cellpadding="0" width="100%" bgcolor="#EDF0F3" align="center" style="background-color: #edf0f3; padding: 0 24px; color: #6a6c6d; text-align: center;">
                                            <tbody>
                                                <tr>
                                                    <td align="center" style="padding: 16px 0 0 0; text-align: center;"></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <table role="presentation" border="0" cellspacing="0" cellpadding="0" width="100%">
                                                            <tbody>
                                                                <tr>
                                                                    <td align="center" style="padding: 0 0 12px 0; text-align: center;">
                                                                        <p style="margin: 0; color: #6a6c6d; font-weight: 400; font-size: 12px; line-height: 1.333;"></p>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="center" style="padding: 0 0 12px 0; text-align: center;">
                                                                        <p style="margin: 0; color: #6a6c6d; font-weight: 400; font-size: 12px; line-height: 1.333;">© Royo Orders
                                                                        </p>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </center>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>