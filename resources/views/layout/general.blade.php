<!DOCTYPE HTML>
<html lang="en">
<head>
    <title>Gametracker</title>
    {{--CSRF token--}}
    <script type="text/javascript">
        window.Laravel = {'csrfToken': '{{ csrf_token() }}' }
    </script>
    {{--compiled css--}}
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    <meta name="google" content="notranslate">
</head>
<body>
    <div class="own-container">
        @yield('content')
    </div>

    {{--compiled js--}}
    <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>

    @yield('script')
</body>