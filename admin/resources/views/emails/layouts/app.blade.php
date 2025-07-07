<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="x-apple-mobile-web-app-capable" content="yes">
    <meta name="x-apple-mobile-web-app-status-bar-style" content="black">
    <meta name="x-apple-mobile-web-app-title" content="Hubber">
    <meta name="msapplication-TileImage" content="https://laravel.com/favicon.png">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    <title>{{ config('app.name') }}</title>
    <style>
        /* Base */
        body,
        table,
        td,
        a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table,
        td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }

        /* Reset */
        table { border-collapse: collapse !important; }
        body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }

        /* iOS BLUE LINKS */
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        /* SAMSUNG MAIL BLUE LINKS */
        u + #body a {
            color: inherit;
            text-decoration: none;
            font-size: inherit;
            font-family: inherit;
            font-weight: inherit;
            line-height: inherit;
        }

        /* GMAIL BLUE LINKS */
        #MessageViewBody a {
            color: inherit;
            text-decoration: none;
            font-size: inherit;
            font-family: inherit;
            font-weight: inherit;
            line-height: inherit;
        }

        /* Universal */
        .button {
            background-color: #3490dc;
            border-radius: 4px;
            color: white !important;
            display: inline-block;
            font-size: 14px;
            font-weight: bold;
            line-height: 50px;
            text-align: center;
            text-decoration: none;
            width: 200px;
            -webkit-text-size-adjust: none;
            mso-hide: all;
        }

        .button:hover {
            background-color: #2779bd !important;
        }

        /* Media Queries */
        @media only screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
            }
            .fluid {
                max-width: 100% !important;
                height: auto !important;
                margin-left: auto !important;
                margin-right: auto !important;
            }
            .stack-column,
            .stack-column-center {
                display: block !important;
                width: 100% !important;
                max-width: 100% !important;
                direction: ltr !important;
            }
            .stack-column-center {
                text-align: center !important;
            }
            .center-on-narrow {
                text-align: center !important;
                display: block !important;
                margin-left: auto !important;
                margin-right: auto !important;
                float: none !important;
            }
            table.center-on-narrow {
                display: inline-block !important;
            }
        }
    </style>
</head>
<body width="100%" bgcolor="#ffffff" id="body" style="margin: 0; padding: 0; min-width: 100%; background-color: #ffffff;">
    <center role="article" aria-roledescription="email" lang="en" style="-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; background-color: #ffffff;">
        <!--[if mso | IE]>
        <table role="presentation" width="100%" style="text-align: center; font-family: Arial, sans-serif; font-size: 15px; line-height: 19px; color: #333333;" border="0" cellpadding="0" cellspacing="0">
        <tr>
        <td style="width: 100%;">
        <![endif]-->
        <div style="display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">
            {{ Illuminate\Mail\Markdown::parse($slot) }}
        </div>
        <div style="display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">
            &#160;
        </div>
        <!--[if mso | IE]>
        </td>
        </tr>
        </table>
        <![endif]-->
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="600" style="width: 100%; max-width: 600px;" class="email-container">
            <tr>
                <td style="background-color: #ffffff;">
                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tr>
                            <td style="padding: 20px 0; text-align: center; background-color: #3490dc;">
                                <h1 style="margin: 0; font-family: Arial, sans-serif; font-size: 24px; line-height: 30px; color: #ffffff; font-weight: bold;">
                                    🚗 Hubber
                                </h1>
                                <p style="margin: 10px 0 0 0; font-family: Arial, sans-serif; font-size: 14px; line-height: 18px; color: #ffffff;">
                                    Your trusted ride booking platform
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 40px 30px;">
                                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                    <tr>
                                        <td style="font-family: Arial, sans-serif; font-size: 15px; line-height: 19px; color: #333333;">
                                            {{ Illuminate\Mail\Markdown::parse($slot) }}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 30px; background-color: #f8f9fa; text-align: center;">
                                <p style="margin: 0; font-family: Arial, sans-serif; font-size: 12px; line-height: 16px; color: #666666;">
                                    © {{ date('Y') }} Hubber. All rights reserved.
                                </p>
                                <p style="margin: 5px 0 0 0; font-family: Arial, sans-serif; font-size: 12px; line-height: 16px; color: #666666;">
                                    This email was sent to you because you made a booking on our platform.
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <!--[if mso | IE]>
        </td>
        </tr>
        </table>
        <![endif]-->
    </center>
</body>
</html> 