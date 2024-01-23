<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Laravel Quickstart - Basic</title>
    <script src="https://kit.fontawesome.com/96aa7a02df.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light border text-muted">
        <div class="container">
            <div class="navbar-header">
                @if(is_null(session('login_credential_token')))
                <a class="navbar-brand p-0 text-muted" href="{{ route('authentications.create') }}">
                会員登録
                </a>
                <a class="navbar-brand p-0 text-muted" href="{{ route('login_credential.create') }}">
                ログイン
                </a>
                @else
                <a class="navbar-brand p-0 text-muted" href="{{ route('tasks.index') }}">
                Task List
                </a>
                <a class="navbar-brand p-0 text-muted" href="{{ route('users.show', session('login_credential_token')) }}">
                アカウント
                </a>
                <a class="navbar-brand p-0 text-muted" href="{{ route('login_credential.destroy') }}">
                ログアウト
                </a>
                @endif
            </div>
        </div>
    </nav>
    @yield('content')
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>