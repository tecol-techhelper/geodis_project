<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="antialiased text-gray-800 dark">
    <main class="min-h-screen flex items-center justify-center bg-gray-100 px-6 ">
        <div class="grid grid-cols-12 px-4">
            <div class="col-span-1 md:col-span-3 lg:col-span-4"></div>
            <div class="col-span-10 md:col-span-6 lg:col-span-4">
                {{ $slot }}
            </div>
            <div class="col-span-1 md:col-span-3 lg:col-span-4"></div>
        </div>
    </main>
    @livewireScripts
</body>

</html>
