<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 top-16 md:top-24 left-0 z-40 w-64 overflow-y-auto bg-white border-r border-gray-200 transform transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:inset-0 shadow-md">
    <div class="py-4">
        <a href="{{ route('dashboard') }}"
            class="flex text-lg text-gray-600 mb-3 px-3 border-t border-b border-gay-300 h-12 items-center items-center shadow-lg focus:bg-gray-200 focus:border-outline hover:bg-gray-200 hover:border-outline">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-house-icon lucide-house">
                <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8" />
                <path
                    d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
            </svg>
            <span class="px-2">Menu Principal</span>
        </a>
        <a href="{{ route('service.index') }}"
            class="flex text-lg text-gray-600 mb-3 px-3 border-t border-b border-gay-300 h-12 items-center items-center shadow-lg focus:bg-gray-200 focus:border-outline hover:bg-gray-200 hover:border-outline">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-truck-icon lucide-truck">
                <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2" />
                <path d="M15 18H9" />
                <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14" />
                <circle cx="17" cy="18" r="2" />
                <circle cx="7" cy="18" r="2" />
            </svg>
            <span class="px-2">Servicios</span>
        </a>
        @if (Auth::user()?->hasRole('admin'))
            <a href="{{ route('user.index') }}"
                class="flex text-lg text-gray-600 mb-3 px-3 border-t border-b border-gay-300 h-12 items-center items-center shadow-lg focus:bg-gray-200 focus:border-outline hover:bg-gray-200 hover:border-outline">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-users-icon lucide-users">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                </svg>
                <span class="px-2">Usuarios</span>
            </a>
            <button @click="open = !open"
                class="flex w-full items-center px-4 py-2 text-left text-gray-700 hover:bg-gray-200 focus:outline-none border shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                    stroke-width="2" viewBox="0 0 24 24">
                    <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                    <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                    <path d="M10 12a1 1 0 0 0-1 1v1a1 1 0 0 1-1 1 1 1 0 0 1 1 1v1a1 1 0 0 0 1 1" />
                    <path d="M14 18a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1 1 1 0 0 1-1-1v-1a1 1 0 0 0-1-1" />
                </svg>
                <span class="flex-1">Parsed Files</span>
                <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M5.23 7.21a.75.75 0 011.06.02L10 11.293l3.71-4.06a.75.75 0 111.1 1.02l-4.25 4.65a.75.75 0 01-1.1 0l-4.25-4.65a.75.75 0 01.02-1.06z"
                        clip-rule="evenodd" />
                </svg>
            </button>

            <!-- Contenido desplegable -->
            <div x-show="open" x-cloak class="pl-5 mt-1 space-y-1 border">
                <a href="{{ route('edifact.viewer') }}"
                    class="block px-2 py-1 text-gray-600 rounded hover:bg-gray-100">Messages Viewer</a>
                <a href="{{ route('edifactfiles.index') }}"
                    class="block px-2 py-1 text-gray-600 rounded hover:bg-gray-100">Uploaded Files</a>
            </div>
        @endif
    </div>
</aside>

