<!DOCTYPE html>
<html>
<head>
    <title>New Message</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: #007bff;
            color: #ffffff;
            text-align: center;
            padding: 15px;
            font-size: 20px;
            border-radius: 8px 8px 0 0;
        }
        .content {
            padding: 20px;
            font-size: 16px;
            line-height: 1.6;
            color: #333;
        }
        .footer {
            text-align: center;
            font-size: 14px;
            color: #666;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            New Contact Form Message
        </div>
        <div class="content">
            <p><strong>Email:</strong> {{ $mailData['email'] }}</p>
            <p><strong>Name:</strong> {{ $mailData['fname'] }} {{ $mailData['lname'] }}</p>
            <p><strong>Subject:</strong> {{ $mailData['subject'] }}</p>
            <p><strong>Message:</strong></p>
            <p>{{ $mailData['message'] }}</p>
        </div>
        <div class="footer">
            <p>{{ getSetting("site_copyright_text") }}</p>
        </div>
    </div>
</body>
</html>
