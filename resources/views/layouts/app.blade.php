<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Laravel Quickstart - Basic</title>
    {{-- Fonts --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css"
    type="text/css">
    {{-- CSS bootstrap4 --}}
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">


</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light border text-muted">
        <div class="container">
            {{-- navbar --}}
            <div class="navbar-header">
                <a class="navbar-brand p-0 text-muted" href="{{ route('home') }}">
                Task List
                </a>
                <a class="navbar-brand p-0 text-muted" href="{{ route('register') }}">
                会員登録
                </a>
            </div>
        </div>
    </nav>
    @yield('content')
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>