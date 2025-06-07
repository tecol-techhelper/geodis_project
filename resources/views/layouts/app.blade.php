<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Manifest para PWA -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#1f2937">
    

    <!-- Para iOS -->
    <link rel="apple-touch-icon" href="{{ asset('images/logos/logo_top.png') }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Administración Servicios GEODIS">


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
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/bell.css'])
    @livewireStyles
</head>

<body x-data="layout()" x-init="init()" class="h-screen font-sans antialiased bg-gray-100">
    <div class="flex h-full">

        <!-- SIDEBAR -->
        @include('livewire.layout.sidebar')

        <!-- OVERLAY cuando sidebar esté abierto en móviles -->
        <div x-show="sidebarOpen && !isDesktop" x-transition:enter="transition-opacity ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click="sidebarOpen = false"
            class="fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden" style="display: none;">
        </div>

        <!-- CONTENEDOR DERECHO: NAVBAR + CONTENIDO -->
        <div class="flex flex-col flex-1 overflow-hidden">

            <!-- NAVBAR -->
            @livewire('layout.navigation')

            <!-- MAIN -->
            <main class="flex-1 overflow-y-auto p-6 relative">
                {{ $slot }}
            </main>
        </div>
    </div>

    <script>
        function layout() {
            return {
                sidebarOpen: window.innerWidth >= 768,
                userMenuOpen: false,
                openFM: false,
                openPF: false,
                modalIsOpen: false,
                confirmClear: false,
                isDesktop: window.innerWidth >= 768,

                init() {
                    window.addEventListener('resize', () => {
                        this.isDesktop = window.innerWidth >= 768;
                        this.sidebarOpen = this.isDesktop;
                    });
                }
            }
        }
    </script>
</body>

</html>
