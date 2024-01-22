<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Laravel Quickstart - Basic</title>
    <script src="https://kit.fontawesome.com/96aa7a02df.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light border text-muted text-nowrap overflow-auto">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand text-muted" href="{{ route('tasks.index') }}">
                Task List
                </a>
                @if(is_null(session('login_credential_token')))
                <a class="navbar-brand text-muted" href="{{ route('authentications.create') }}">
                会員登録
                </a>
                <a class="navbar-brand text-muted" href="{{ route('login_credential.create') }}">
                ログイン
                </a>
                @else
                <a class="navbar-brand text-muted" href="{{ route('users.show', session('login_credential_token')) }}">
                アカウント
                </a>
                <a class="navbar-brand text-muted" href="{{ route('login_credential.destroy') }}">
                ログアウト
                </a>
                <div class="navbar-brand text-muted">
                    <div class="w-25 h-25 img-fluid ">
                        {{ session('weather_info')['prefecture'] }}
                        {{ session('weather_info')['current_weather'] }}
                        {{ session('weather_info')['current_temperature'].'℃' }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </nav>
    @yield('content')
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>