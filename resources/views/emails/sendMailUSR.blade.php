<!DOCTYPE html>

<html>

<head>

    <title>{{ config('app.name', 'Laravel') }}</title>

</head>

<body>

    <p>Dear {{ $content['cust_name'] }},</p>
    <p>We are pleased to inform you that your support ticket has been closed. If you have any further issues or questions, please don't hesitate to reach out to us.</p>

    <p>Best regards,</p>
    <p>{{ $content['admin_name'] }}</p>

</body>

</html>
