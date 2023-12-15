<!DOCTYPE html>
<html lang="en">
<head>
    <title>Laravel Quickstart - Basic</title>
    {{-- Fonts --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css"
    type="text/css">
    {{-- CSS bootstrap4 --}}
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="/resources/css/app.css">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

</head>
<body>
    {{-- navbar-defaultからnavbar-lightに 文字を黒に--}}
    {{-- navbar-expand-lgを追加 狭い画面の際折りたたまれて表示--}}
    {{-- bg-lightを追加 バーの背景色が灰色に--}}
    {{-- borderを追加 ナビゲーションバーに枠線が追加 --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-light border text-muted">
        <div class="container">
            {{-- navbar --}}
            <div class="navbar-header">
                {{-- p-0を追加 ナビゲーションバーの高さを調整 --}}
                {{-- text-mutedを追加 文字列を灰色に --}}
                <a class="navbar-brand p-0 text-muted" href="{{ url('/') }}">
                Task List
                </a>
            </div>
        </div>
    </nav>
    {{--　子が自由に書ける場所 --}}
    @yield('content')
    {{-- JavaScripts --}}
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>