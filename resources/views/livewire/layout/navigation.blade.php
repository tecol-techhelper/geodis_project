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


<nav class="bg-red-600 px-4 py-3 flex items-center justify-between border-b" style="box-shadow: 0 4px 6px -1px rgba(0,0,0,0.2)">
    <div class="flex items-center space-x-4">
        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-600 bg-white border border-md border-gray-400 rounded focus:outline-none md:hidden">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <div>
            <img src="{{ asset('images/logos/logo_top.png') }}" alt="Logo Empresa" class="h-7 w-auto rounded-md bg-white">
        </div>
    </div>
    <div class="relative">
        <button @click="userMenuOpen = !userMenuOpen" class="flex items-center focus:outline-none">
            <img src="{{ Auth::user()->user_icon ?? asset('images/logos/logo_top.png') }}" alt="Icono Usuario"
                class="h-8 w-8 rounded-full border-2 border-gray-300 object-cover bg-white">
        </button>
        <div x-show="userMenuOpen" @click.outside="userMenuOpen = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md overflow-hidden shadow-xl z-20">
            <div class="border-b border-outline block px-4 py-2 text-gray-700 hover:bg-gray-300 dark:hover:bg-gray-300">
                <span>{{ Auth::user()->username }}</span>
                <p class="text-xs">{{Auth::user()->role->rol_key}}</p>
            </div>
            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Perfil</a>
            <button wire:click="logout" class="w-full text-start px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Cerrar Sesión</button>
        </div>
    </div>
</nav>
{{--

                <div class="flex items-center">
                    <ol class="flex w-full rounded-md px-4 py-2">
                        @foreach ($items as $index => $item)
                            @if (isset($item['url']) && count($items) - 1)
                                <li
                                    class="flex cursor-pointer text-sm text-slate-500 transition-colors duration-300 hover:text-slate-800">
                                    <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
                                    <span class="pointer-events-none mx-2 text-slate-800">
                                        /
                                    </span>
                                </li>
                            @else
                                <li
                                    class="flex cursor-pointer text-sm text-slate-500 transition-colors duration-300 hover:text-slate-800">
                                    {{ $item['label'] }}

                                </li>
                            @endif
                        @endforeach
                    </ol>
                </div>
            </div> --}}

