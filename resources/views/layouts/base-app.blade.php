<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="csrf_token" content="{{ csrf_token() }}">

        <title>Workout-Lottery - Let's get movin'</title>

        <script type="text/javascript" data-main="js/main" src="{{ asset('js/libs/requirejs/require.js') }}"></script>

        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="{{ asset('js/libs/angular-material/angular-material.min.css') }}">
        <link rel="stylesheet" href="{{ asset('js/libs/angular-ui-notification/dist/angular-ui-notification.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
    </head>
    <body layout="column">
        @yield('content')
    </body>
</html>