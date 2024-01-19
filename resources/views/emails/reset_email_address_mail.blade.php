<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>パスワード再設定メール</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
    アカウントのメールアドレスが更新されました
    <br>
    下記リンクへアクセスしてください。
    <br><br>
    <a href="{{ route('reset_email.edit', $reset_email_token) }}">{{ route('reset_email.edit', $reset_email_token) }}</a>
</body>
</html>