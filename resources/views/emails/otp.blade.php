<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 24px;
        }
        .content {
            padding: 20px;
            color: #333333;
            line-height: 1.6;
        }
        .otp {
            display: block;
            margin: 20px auto;
            font-size: 36px;
            font-weight: bold;
            color: #4CAF50;
            text-align: center;
            border: 1px dashed #4CAF50;
            padding: 10px 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .footer {
            background-color: #f1f1f1;
            text-align: center;
            padding: 10px;
            font-size: 14px;
            color: #777777;
        }
        .footer a {
            color: #4CAF50;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            Smug.link Password Reset
        </div>
        <div class="content">
            <p>Dear User,</p>
            <p>We received a request to reset your password. Use the OTP below to proceed. This OTP is valid for the next 15 minutes.</p>
            <p class="otp">{{ $otp }}</p>
            <p>If you did not request a password reset, please ignore this email or contact our support team.</p>
            <p>Thanks and regards,</p>
            <p><strong>The Smug.link Team</strong></p>
        </div>
        <div class="footer">
            <p>If you need help, please visit our <a href="https://smug.link/help">Help Center</a>.</p>
        </div>
    </div>
</body>
</html>
