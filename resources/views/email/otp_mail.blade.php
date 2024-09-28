<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Email Template</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .email-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            background-color: #007bff;
            color: #ffffff;
            padding: 20px;
            border-radius: 8px 8px 0 0;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            padding: 20px;
            text-align: center;
        }

        .otp-code {
            font-size: 32px;
            color: #007bff;
            letter-spacing: 5px;
            font-weight: bold;
            margin: 20px 0;
        }

        .footer {
            text-align: center;
            padding: 10px;
            font-size: 14px;
            color: #777777;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <h1>Your OTP Code</h1>
        </div>
        <div class="content">
            <p>Hi,</p>
            <p>Use the following One-Time Password (OTP) to complete your login or verification process. This OTP is
                valid for 10 minutes.</p>
            <div class="otp-code">{{ $otp }}</div>
            <p>If you did not request this, please ignore this email.</p>
        </div>
        <div class="footer">
            <p>Thank you for using our service!</p>
        </div>
    </div>
</body>

</html>
