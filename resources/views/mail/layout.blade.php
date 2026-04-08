<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $title ?? 'Mazraa.com' }}</title>
<style>
    body { font-family: 'Inter', 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f8fafc; margin: 0; padding: 0; -webkit-font-smoothing: antialiased; }
    .wrapper { width: 100%; table-layout: fixed; background-color: #f8fafc; padding: 40px 0; }
    .main { background-color: #ffffff; max-width: 600px; margin: 0 auto; width: 100%; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
    .header { background-color: #020617; text-align: center; padding: 30px 20px; }
    .header h1 { color: #ffffff; font-size: 28px; font-weight: 900; margin: 0; letter-spacing: -0.5px; }
    .header h1 span { color: #f4e4c1; }
    .content { padding: 40px 30px; color: #334155; line-height: 1.6; font-size: 16px; }
    .content h2 { color: #0f172a; font-size: 22px; font-weight: 800; margin-top: 0; margin-bottom: 20px; }
    .content p { margin-bottom: 20px; }
    .button { display: inline-block; background-color: #1d5c42; color: #ffffff !important; font-weight: bold; text-decoration: none; padding: 14px 28px; border-radius: 12px; margin: 20px 0; text-transform: uppercase; font-size: 14px; letter-spacing: 1px; }
    .button.info { background-color: #3b82f6; }
    .button.alert { background-color: #ef4444; }
    .footer { text-align: center; padding: 30px; font-size: 12px; color: #94a3b8; border-top: 1px solid #f1f5f9; background-color: #f8fafc; }
    .details-box { background-color: #f8fafc; border: 1px solid #e2e8f0; padding: 20px; border-radius: 12px; margin-bottom: 20px; font-size: 15px; }
    .details-row { display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px; }
    .details-row:last-child { margin-bottom: 0; border-bottom: none; padding-bottom: 0; }
    .details-label { font-weight: bold; color: #475569; }
    .details-value { color: #020617; font-weight: 600; text-align: right; }
</style>
</head>
<body>
<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
        <td align="center">
            <table class="main" cellpadding="0" cellspacing="0" role="presentation">
                <!-- Header -->
                <tr>
                    <td class="header">
                        <h1>Mazraa<span>.com</span></h1>
                    </td>
                </tr>
                <!-- Body -->
                <tr>
                    <td class="content">
                        @yield('content')
                    </td>
                </tr>
                <!-- Footer -->
                <tr>
                    <td class="footer">
                        &copy; {{ date('Y') }} Mazraa.com. All rights reserved.<br>
                        Delivering Premium Agricultural & Farm Retreat Experiences.
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
