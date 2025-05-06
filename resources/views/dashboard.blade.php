<x-app-layout>
            <div class="">
                <x-breadcrums :items="[
                    ['label' => 'Inicio','icon' =>'home']
                ]"></x-breadcrums>
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>
    {{-- <footer class="bg-white border-t py-4 px-6 text-sm text-gray-500 text-center shadow-inner">hola</footer> --}}
</x-app-layout>
