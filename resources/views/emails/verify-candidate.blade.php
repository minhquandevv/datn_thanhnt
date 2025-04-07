<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác thực tài khoản - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .email-container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        .email-header {
            background-color: #D40000;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }

        .email-header h1 {
            margin: 0;
            font-size: 1.5rem;
        }

        .email-body {
            padding: 30px;
        }

        .email-body h2 {
            color: #D40000;
            margin-bottom: 20px;
        }

        .email-body p {
            line-height: 1.6;
        }

        .email-footer {
            background-color: #fafafa;
            padding: 20px;
            font-size: 0.85rem;
            text-align: center;
            color: #777;
        }

        .verify-button {
            display: inline-block;
            margin-top: 20px;
            background-color: #D40000;
            color: white !important;
            padding: 12px 25px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }

        .verify-button:hover {
            background-color: #b30000;
        }

        @media (max-width: 600px) {
            .email-body, .email-header, .email-footer {
                padding: 20px !important;
            }

            .verify-button {
                width: 100%;
                text-align: center;
                box-sizing: border-box;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <img src="{{ asset('assets/Logo_viettel.png') }}" style="max-height: 40px;" alt="Logo">
            <h1>{{ config('app.name') }}</h1>
        </div>
        <div class="email-body">
            <h2>Xác thực tài khoản của bạn</h2>
            <p>Chào bạn,</p>
            <p>Bạn đã đăng ký tài khoản tại hệ thống {{ config('app.name') }}. Vui lòng nhấn vào nút bên dưới để xác thực tài khoản của bạn và hoàn tất quá trình đăng ký.</p>

            <div style="text-align: center;">
                <a href="{{ route('verify.email', $token) }}" class="verify-button">Xác thực ngay</a>
            </div>

            <p>Nếu bạn không đăng ký tài khoản tại hệ thống, hãy bỏ qua email này.</p>
        </div>
        <div class="email-footer">
            &copy; {{ now()->year }} {{ config('app.name') }}. Mọi quyền được bảo lưu.<br>
            Email này được gửi tự động, vui lòng không trả lời lại email này.
        </div>
    </div>
</body>
</html>