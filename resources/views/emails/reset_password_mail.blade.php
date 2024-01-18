<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>パスワード再設定メール</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
    下記リンクへ進んでパスワードを再設定してください。
    <br><br>
    <a href="{{ route('reset_password.edit', $reset_password_token) }}">{{ route('reset_password.edit', $reset_password_token) }}</a>
</body>
</html>