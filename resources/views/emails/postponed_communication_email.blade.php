<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>تحديد موعد للتواصل</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@700&display=swap" rel="stylesheet">
    <style type="text/css">
        body,
        table,
        td,
        a {
            -ms-text-size-adjust: 100%; /* 1 */
            -webkit-text-size-adjust: 100%; /* 2 */
        }
        table,
        td {
            mso-table-rspace: 0pt;
            mso-table-lspace: 0pt;
        }
        img {
            -ms-interpolation-mode: bicubic;
        }
        p,h1,h2,h3,h4{
            text-align: center;
        }
        a[x-apple-data-detectors] {
            font-family: inherit !important;
            font-size: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
            color: inherit !important;
            text-decoration: none !important;
        }
        div[style*="margin: 16px 0;"] {
            margin: 0 !important;
        }
        body {
            width: 100% !important;
            height: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        table {
            border-collapse: collapse !important;
        }

        a {
            color: #1a82e2;
        }
        img {
            height: auto;
            line-height: 100%;
            text-decoration: none;
            border: 0;
            outline: none;
        }
    </style>

</head>
<body style="background-color: #e9ecef;">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td align="center" bgcolor="#e9ecef">
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
            <tr>
                <td align="center" valign="top" width="600">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                <tr>
                    <td align="center" valign="top" style="padding: 36px 24px;">
                        <a href="https://sendgrid.com" target="_blank" style="display: inline-block;">
                            <img src="https://www.alwsata.com.sa/newWebsiteStyle/images/logo.png" alt="Logo" border="0" width="48" style="display: block; width: 48px; max-width: 48px; min-width: 48px;">
                        </a>
                    </td>
                </tr>
            </table>
            </td>
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td align="center" bgcolor="#e9ecef">
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
            <tr>
                <td align="center" valign="top" width="600">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                <tr>
                    <td align="center" bgcolor="#ffffff" style="padding: 36px 24px 0; font-family: 'Cairo', Helvetica, Arial, sans-serif; border-top: 3px solid #d4dadf;">
                        <h1 style="margin: 0; font-size: 32px; font-weight: 700; letter-spacing: -1px; line-height: 48px;text-align: center">طلب تأجيل التواصل</h1>
                    </td>
                </tr>
            </table>
            </td>
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td align="center" bgcolor="#e9ecef">
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
            <tr>
                <td align="center" valign="top" width="600">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                <tr>
                    <td align="center" bgcolor="#ffffff" style="padding: 24px; font-family: 'Cairo', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;">
                        <img src="https://www.alwsata.com.sa/newWebsiteStyle/images/logo.png" alt="">
                        <p style="margin: 0;text-align: center">
                              عزيزي: {{ $name }}
                            <br/>
                            لقد طلبت تأجيل التواصل  لمتابعة طلب التمويل العقاري الخاص بك
                            <br/>
                            لتحديد الموعد المناسب للتواصل
                            <a href="{{ url('/customer/set-date/' . $request_id) }}">
                                انقر هنا</a>
                            <br/>
                            أو بامكانك تحميل تطبيقنا متوفر على جوجل و أبل بلاي
                            <a href="https://www.alwsata.com.sa/ar/app">
                                انقر هنا</a>
                            <br/>
                            .وتحديد الوقت كما يمكنك التواصل مباشرة مع مستشارك وتابع حالة طلبك أولًا بأول

                        </p>
                    </td>
                </tr>
                <tr>
                    <td align="center" bgcolor="#ffffff">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tr>
                                <td align="center" bgcolor="#ffffff" style="padding: 12px;">
                                    <table border="0" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td align="center" bgcolor="#1a82e2" style="border-radius: 6px;text-align: center">
                                                <p>
                                                    <a href="{{ url('/customer/postponed-status/' . $request_id) }}">
                                                        <button class="bg-primary btn btn-primary px-5 w-100   bg-main"> لم أطلب تأجيل التواصل </button>
                                                    </a>
                                                </p>

                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td align="center" bgcolor="#ffffff" style="padding: 24px; font-family: 'Cairo', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px; border-bottom: 3px solid #d4dadf">
                        <p style="margin: 0;text-align: center">شكرا,<br> شركة الوساطة العقارية</p>
                    </td>
                </tr>
            </table>
            </td>
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td align="center" bgcolor="#e9ecef" style="padding: 24px;">
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
            <tr>
                <td align="center" valign="top" width="600">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                <tr>
                    <td align="center" bgcolor="#e9ecef" style="padding: 12px 24px; font-family: 'Cairo', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 20px; color: #666;">
                        <p style="margin: 0;">You received this email because we received a request for [type_of_action] for your account. If you didn't request [type_of_action] you can safely delete this email.</p>
                    </td>
                </tr>
                <tr>
                    <td align="center" bgcolor="#e9ecef" style="padding: 12px 24px; font-family: 'Cairo', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 20px; color: #666;">
                        <p style="margin: 0;">To stop receiving these emails, you can <a href="https://sendgrid.com" target="_blank">unsubscribe</a> at any time.</p>
                        <p style="margin: 0;">Paste 1234 S. Broadway St. City, State 12345</p>
                    </td>
                </tr>
            </table>
            </td>
            </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
