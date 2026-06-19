<aside x-show="sidebarOpen" x-transition:enter="transition transform ease-out duration-300"
    x-transition:enter-start="-translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100"
    x-transition:leave="transition transform ease-in duration-300" x-transition:leave-start="translate-x-0 opacity-100"
    x-transition:leave-end="-translate-x-full opacity-0" @click.outside.window="if (!isDesktop) sidebarOpen = false"
    class="fixed inset-y-0 left-0 z-40 w-64 transform overflow-y-auto border-r border-red-900/30 bg-red-700 shadow-md md:static md:inset-0 md:translate-x-0"
    style="display: none;">
    <div>
        <a href="{{ route('dashboard') }}" class="flex items-center justify-center border-b border-red-900/40 py-4">
            <img src="{{ asset('images/logos/logo_top.png') }}" alt="Logo Empresa"
                class="h-20 w-auto rounded-xl bg-white">
        </a>

        <nav class="divide-y divide-gray-200 bg-white">
            <a href="{{ route('dashboard') }}"
                class="flex h-12 items-center gap-3 border-l-4 border-transparent bg-white px-4 text-sm font-medium text-gray-800 transition hover:border-gray-700 hover:bg-gray-100 focus:border-gray-700 focus:bg-gray-100 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    aria-hidden="true">
                    <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8" />
                    <path
                        d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                </svg>
                <span>Menú principal</span>
            </a>

            <a href="{{ route('services.index') }}"
                class="flex h-12 items-center gap-3 border-l-4 border-transparent bg-white px-4 text-sm font-medium text-gray-800 transition hover:border-gray-700 hover:bg-gray-100 focus:border-gray-700 focus:bg-gray-100 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    aria-hidden="true">
                    <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2" />
                    <path d="M15 18H9" />
                    <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14" />
                    <circle cx="17" cy="18" r="2" />
                    <circle cx="7" cy="18" r="2" />
                </svg>
                <span>Servicios</span>
            </a>

            @if (Auth::user()?->hasRole('admin'))
                <button type="button" @click="openAD = !openAD"
                    class="flex h-12 w-full items-center gap-3 border-l-4 border-transparent bg-white px-4 text-left text-sm font-medium text-gray-800 transition hover:border-gray-700 hover:bg-gray-100 focus:border-gray-700 focus:bg-gray-100 focus:outline-none"
                    :aria-expanded="openAD.toString()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" aria-hidden="true">
                        <path
                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                        <circle cx="12" cy="12" r="4" />
                    </svg>
                    <span class="flex-1">Administración</span>
                    <svg :class="{ 'rotate-180': openAD }" class="h-4 w-4 transition-transform" viewBox="0 0 20 20"
                        fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M5.23 7.21a.75.75 0 011.06.02L10 11.293l3.71-4.06a.75.75 0 111.1 1.02l-4.25 4.65a.75.75 0 01-1.1 0l-4.25-4.65a.75.75 0 01.02-1.06z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                <div x-show="openAD" x-cloak class="border-t border-gray-200 bg-red-50">
                    <a href="{{ route('user.index') }}"
                        class="block border-l-4 border-transparent px-12 py-2 text-sm text-gray-800 transition hover:border-gray-700 hover:bg-white focus:border-gray-700 focus:bg-white focus:outline-none">
                        Usuarios
                    </a>
                    <a href="{{ route('audits.index') }}"
                        class="block border-l-4 border-transparent px-12 py-2 text-sm text-gray-800 transition hover:border-gray-700 hover:bg-white focus:border-gray-700 focus:bg-white focus:outline-none">
                        Auditoría
                    </a>
                    <a href="{{ route('edifact.viewer') }}"
                        class="block border-l-4 border-transparent px-12 py-2 text-sm text-gray-800 transition hover:border-gray-700 hover:bg-white focus:border-gray-700 focus:bg-white focus:outline-none">
                        Mensajes extraídos
                    </a>
                    <a href="{{ route('edifactfiles.index') }}"
                        class="block border-l-4 border-transparent px-12 py-2 text-sm text-gray-800 transition hover:border-gray-700 hover:bg-white focus:border-gray-700 focus:bg-white focus:outline-none">
                        EDI procesados
                    </a>
                </div>
            @endif
        </nav>
    </div>
</aside>
