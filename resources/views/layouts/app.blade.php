<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Laravel Quickstart - Basic</title>
    {{-- Fonts --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css"
    type="text/css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-default">
        <div class="container">
            {{-- navbar --}}
            <div class="navbar-header">
                <a class="navbar-brand" href="{{ url('/') }}">
                Task List
                </a>
                <a class="navbar-brand" href="{{ url('/register') }}">
                会員登録
                </a>
            </div>
        </div>
    </nav>
    @yield('content')
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</body>
</html>