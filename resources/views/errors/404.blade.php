<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>404 Page not found</title>
    @vite(['resources/css/404.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body>
    <div class="site">
        <div class="sketch">
            <div class="bee-sketch red"></div>
            <div class="bee-sketch blue"></div>
        </div>

        <h1>404:
            <small>Page not found</small>
        </h1>
    </div>
    @livewireScripts
</body>

</html>
