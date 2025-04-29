<x-app-layout>
    <div class="">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                    {{-- {{Auth::user()->username}}
                    {{Auth::user()->role->rol_key}} --}}
                </div>
            </div>
        </div>
    </div>
    <footer class="bg-white border-t py-4 px-6 text-sm text-gray-500 text-center shadow-inner">hola</footer>
</x-app-layout>
