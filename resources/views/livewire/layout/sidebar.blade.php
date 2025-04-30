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
        <a href="#"
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
            <a href="#"
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
        @endif
    </div>
</aside>
