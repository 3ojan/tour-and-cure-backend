<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Email</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }

        .container {
            width: 80%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f0f0f0;
        }

        .header {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
        }

        .footer {
            max-width: 600px;
            text-align: center;
            padding: 20px;
            background-color: #ffffff;
        }
    </style>
</head>
<body>
<div class="header">
    <img src="{{ asset('path-to-your-logo.png') }}" alt="{{ config('app.name') }} Logo" height="50">
</div>

<div class="container">
    <p>Greeating,</p>

    <div style="margin-bottom: 10px;">
        @yield('content')
    </div>

    <p>Best regards,</p>
    <p>{{ config('app.name') }} Team</p>
</div>

<div class="footer">
    <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
</div>
</body>
</html>
