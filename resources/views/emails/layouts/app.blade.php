<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f7fafc;
        }

        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }

        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
            text-align: center;
        }

        .email-logo {
            font-size: 28px;
            font-weight: bold;
            color: #ffffff;
            text-decoration: none;
        }

        .email-body {
            padding: 40px 30px;
        }

        .email-footer {
            background-color: #f7fafc;
            padding: 30px;
            text-align: center;
            font-size: 14px;
            color: #718096;
        }

        .button {
            display: inline-block;
            padding: 14px 32px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
        }

        h1 {
            color: #1a202c;
            font-size: 24px;
            margin-bottom: 16px;
        }

        p {
            color: #4a5568;
            line-height: 1.6;
            margin-bottom: 16px;
        }
    </style>
</head>

<body>
    <div class="email-wrapper">
        <div class="email-header">
            <a href="{{ config('app.url') }}" class="email-logo">
                {{ config('app.name') }}
            </a>
        </div>
        <div class="email-body">
            @yield('content')
        </div>
        <div class="email-footer">
            <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p>
                <a href="{{ config('app.url') }}" style="color: #667eea; text-decoration: none;">Visit our website</a>
            </p>
        </div>
    </div>
</body>

</html>