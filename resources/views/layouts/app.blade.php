<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Icon page --}}
    <link rel="icon" href="{{ asset('images/logos/logo_top.png') }}" type="image/png">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- flatpickr --}}
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script> --}}

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="font-sans antialiased flex flex-col min-h-screen overflow-hidden" x-data="{ sidebarOpen: false, userMenuOpen: false, open: false }">
    <div class="flex flex-1">
        @livewire('layout.navigation')
        <div class="flex flex-1 h-screen overflow-hidden pt-20 md:pt-24">
            @include('livewire.layout.sidebar')
            <main
                class="flex-1 pt-4 px-6 lg:px-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg overflow-y-auto">
                {{ $slot }}
            </main>
        </div>
    </div>
    @livewireScripts
    @stack('scripts')
    <script src="https://unpkg.com/lucide@latest"></script>

</body>

</html>
