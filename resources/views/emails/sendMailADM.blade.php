<!DOCTYPE html>

<html>

<head>

    <title>{{ config('app.name', 'Laravel') }}</title>

</head>

<body>

    <h2>{{ $content['subject'] }}</h2>
    <h3>{{ $content['cust_ID'] }}</h3>
    <p>Dear Concern,</p>
    <p>{{ $content['body'] }}</p>
    <p>Thank you</p>

</body>

</html>
