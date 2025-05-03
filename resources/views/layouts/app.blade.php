<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://unpkg.com/tabulator-tables/dist/css/tabulator.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="font-sans antialiased flex flex-col min-h-screen overflow-hidden" x-data="{ sidebarOpen: false, userMenuOpen: false }">
    <div class="flex flex-1">
        @livewire('layout.navigation')
        <div class="flex h-screen overflow-hidden pt-20 md:pt-24">
            @include('livewire.layout.sidebar')
            <main class="pt-4 max-w-7xl mx-auto px-6 lg:px-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                {{ $slot }}
            </main>
        </div>
    </div>
    @livewireScripts
    <script type="text/javascript" src="https://unpkg.com/tabulator-tables/dist/js/tabulator.min.js"></script>
</body>

</html>
