<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ \Illuminate\Support\Facades\Config::get('app.name', 'Laravel') }}</title>

    @vite('resources/js/app.ts')
</head>
<body>
    <div id="app">
        <div class="container" style="margin-top: 22px;">
            @yield('content')
        </div>
    </div>

</body>
</html>
