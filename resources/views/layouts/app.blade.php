<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf_token" content="{{ csrf_token() }}">
    <title>Laravel Quickstart - Basic</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous" defer></script>
    <script src="https://kit.fontawesome.com/96aa7a02df.js" crossorigin="anonymous" defer></script>
    <script src="{{ asset('/functions.js') }}" defer></script>
    <script defer>
        $(function() {
            $('.delete_button').click(function() {

                let delete_button = $(this);
                let task_name = delete_button.data('name');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content'),},
                });
                $.ajax({
                    type: 'POST',
                    url: '/task/' + this.value,
                    data: {
                        '_method': 'DELETE'
                    }
                })
                .done(function() {
                    delete_button.parents('td').parents('tr').remove();
                })
                .fail(function() {
                    alert('エラー');
                })
                .then(function() {
                    alert('タスク名：' + task_name + 'を削除しました');
                })
                .catch((e) => {
                    alert('エラー');
                })
            });
        });
    </script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-muted border text-muted text-nowrap overflow-auto">
        <div class="container">
            <div class="navbar-header">
                @if(!Auth::check())
                <a class="navbar-brand text-muted" href="{{ route('authentications.create') }}">
                会員登録
                </a>
                <a class="navbar-brand text-muted" href="{{ route('login_credential.create') }}">
                ログイン
                </a>
                @else
                <a class="navbar-brand p-0 text-muted" href="{{ route('task.index') }}">
                Task List
                </a>
                <a class="navbar-brand p-0 text-muted" href="{{ route('users.show') }}">
                アカウント
                </a> 
                <a class="navbar-brand p-0 text-muted" href="{{ route('login_credential.destroy') }}">
                ログアウト
                </a>
                <div class="navbar-brand text-muted">
                    {{ Cache::get('weather_info'.session('prefecture_number'))['prefecture'] }}
                    <img src="{{ Cache::get('weather_info'.session('prefecture_number'))['icon_url'] }}">
                    {{ Cache::get('weather_info'.session('prefecture_number'))['temperature'] }}
                </div>
                @endif
            </div>
        </div>
    </nav>
    @yield('content')
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>