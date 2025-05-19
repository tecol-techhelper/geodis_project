<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    public array $items = [];

    public function mount(array $items = [])
    {
        $this->items = $items;
    }
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>


<nav
    class="fixed top-0 left-0 right-0 z-50 bg-red-600 px-6 py-3 flex items-center justify-between border-b h-16 md:h-24 shadow-md md:shadow-xl">

    {{-- Botón hamburguesa y logo --}}
    <div class="flex items-center space-x-4">
        <button @click="sidebarOpen = !sidebarOpen"
            class="text-gray-600 bg-white border border-md border-gray-400 rounded focus:outline-none md:hidden">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <a href="{{ route('dashboard') }}">
            <img src="{{ asset('images/logos/logo_top.png') }}" alt="Logo Empresa"
                class="h-8 md:h-10 lg:h-12 xl:h-14 w-auto rounded-md bg-white">
        </a>
    </div>

    {{-- Campana + Menú Usuario --}}
    <div class="flex items-center space-x-1 relative">
        {{-- 🔔 Notificaciones --}}
        <livewire:services.notifications-bell />


        {{-- 👤 Menú usuario --}}
        <div class="relative" x-data="{ userMenuOpen: false }">
            <button @click="userMenuOpen = !userMenuOpen" class="flex items-center px-3 md:px-6 focus:outline-none">
                <img src="{{ Storage::url(Auth::user()->user_icon) ?? asset('images/logos/logo_top.png') }}"
                    alt="Icono Usuario"
                    class="h-8 md:h-10 lg:h-12 xl:h-14 w-8 md:w-10 lg:w-12 xl:w-14 rounded-full border-2 border-gray-200 hover:border-gray-700 transition duration-300 object-cover bg-white">
            </button>

            {{-- Dropdown de usuario --}}
            <div x-show="userMenuOpen" @click.outside="userMenuOpen = false"
                class="absolute right-0 top-full mt-2 w-48 bg-white rounded-md overflow-hidden shadow-xl z-40">
                <div class="flex items-center border-b border-outline block px-4 py-2 text-gray-700 hover:bg-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-id-card w-5 h-5" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 10h2" />
                        <path d="M16 14h2" />
                        <path d="M6.17 15a3 3 0 0 1 5.66 0" />
                        <circle cx="9" cy="11" r="2" />
                        <rect x="2" y="5" width="20" height="14" rx="2" />
                    </svg>
                    <div class="px-2">
                        <span>{{ Auth::user()->username }}</span>
                        <p class="text-xs">{{ Auth::user()->role->rol_key }}</p>
                    </div>
                </div>

                <a href="#" class="flex items-center block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <svg class="lucide lucide-user w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                    <span class="px-2">Perfil</span>
                </a>

                <button wire:click="logout"
                    class="flex items-center w-full text-start px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <svg class="lucide lucide-log-out w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                        <polyline points="16 17 21 12 16 7" />
                        <line x1="21" x2="9" y1="12" y2="12" />
                    </svg>
                    <span class="px-2">Cerrar Sesión</span>
                </button>
            </div>
        </div>
    </div>
</nav>
