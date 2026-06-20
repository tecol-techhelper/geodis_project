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

<nav class="flex h-16 items-center justify-between border-b border-gray-200 bg-white px-4 shadow-sm sm:px-6">
    <div class="flex items-center gap-4">
        <button type="button" @click="sidebarOpen = !sidebarOpen"
            class="inline-flex h-10 w-10 items-center justify-center rounded-md border border-gray-300 bg-white text-gray-600 transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 md:hidden"
            aria-label="Abrir menú">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"
                aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <a href="{{ route('dashboard') }}" class="block md:hidden">
            <img src="{{ asset('images/logos/logo_top.png') }}" alt="Logo Empresa"
                class="h-12 w-auto rounded-md bg-white">
        </a>

        <div class="hidden items-center gap-2 text-sm text-gray-600 sm:flex">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                aria-hidden="true">
                <path d="M8 2v4" />
                <path d="M16 2v4" />
                <rect width="18" height="18" x="3" y="4" rx="2" />
                <path d="M3 10h18" />
                <path d="M8 14h.01" />
                <path d="M12 14h.01" />
                <path d="M16 14h.01" />
                <path d="M8 18h.01" />
                <path d="M12 18h.01" />
                <path d="M16 18h.01" />
            </svg>
            <span>{{ ucfirst(\Carbon\Carbon::now()->locale('es')->translatedFormat('F d, Y')) }}</span>
        </div>
    </div>

    <div class="relative flex items-center justify-center gap-2">
        <livewire:services.notifications-bell />

        <div class="relative" x-data="{ userMenuOpen: false }">
            <button type="button" @click="userMenuOpen = !userMenuOpen"
                class="flex items-center rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                aria-label="Abrir menú de usuario" :aria-expanded="userMenuOpen.toString()">
                <img src="{{ Auth::user()->user_icon ? Storage::url(Auth::user()->user_icon) : asset('images/logos/logo_top.png') }}"
                    alt="Icono Usuario"
                    class="h-10 w-10 rounded-full border-2 border-gray-300 bg-white object-cover transition hover:border-gray-700 md:h-11 md:w-11">
            </button>

            <div x-show="userMenuOpen" @click.outside="userMenuOpen = false"
                class="absolute right-0 top-full z-40 mt-2 w-56 overflow-hidden rounded-lg border border-gray-200 bg-white shadow-lg">
                <div class="flex items-center border-b border-gray-200 px-4 py-3 text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-gray-500" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        aria-hidden="true">
                        <path d="M16 10h2" />
                        <path d="M16 14h2" />
                        <path d="M6.17 15a3 3 0 0 1 5.66 0" />
                        <circle cx="9" cy="11" r="2" />
                        <rect x="2" y="5" width="20" height="14" rx="2" />
                    </svg>
                    <div class="min-w-0 px-2">
                        <span class="block truncate text-sm font-medium">{{ ucwords(Auth::user()->first_name . ' ' . Auth::user()->last_name) }}</span>
                        <p class="truncate text-xs text-gray-500">{{ Auth::user()->roles->first()?->rol_name }}</p>
                    </div>
                </div>

                <a href="{{ route('user.edit', Auth::user()->id) }}"
                    class="flex items-center px-4 py-2 text-sm text-gray-700 transition hover:bg-gray-100 focus:bg-gray-100 focus:outline-none">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                    <span class="px-2">Perfil</span>
                </a>

                <button type="button" wire:click="logout"
                    class="flex w-full items-center px-4 py-2 text-start text-sm text-gray-700 transition hover:bg-gray-100 focus:bg-gray-100 focus:outline-none">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                        <polyline points="16 17 21 12 16 7" />
                        <line x1="21" x2="9" y1="12" y2="12" />
                    </svg>
                    <span class="px-2">Cerrar sesión</span>
                </button>
            </div>
        </div>
    </div>
</nav>
