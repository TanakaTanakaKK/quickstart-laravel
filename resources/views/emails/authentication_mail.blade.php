<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>仮登録完了メール</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
    仮登録が完了しました。
    <br><br>
    下記リンクへ進んでください。
    <br>
    <a href="{{ route('users.create',$authentication_token) }}">{{ route('users.create',$authentication_token) }}</a>
</body>
</html>