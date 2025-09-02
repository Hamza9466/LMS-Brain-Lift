<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <p>Hi {{ $name }},</p>
    <p>Thanks for reaching out! We’ve received your message and our team will get back to you shortly.</p>
    <p>Best regards,<br>{{ config('app.name') }}</p>
</body>
</html>