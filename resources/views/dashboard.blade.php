@section('title', 'Página Principal')

<x-app-layout>
    <div class="space-y-6">
        <x-breadcrums :items="[
            ['label' => 'Inicio', 'icon' => 'home'],
        ]" />

        <section class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">
                        {{ now()->locale('es')->translatedFormat('l, d \d\e F \d\e Y') }}
                    </p>
                    <h1 class="mt-1 text-2xl font-bold text-gray-900 sm:text-3xl">
                        Panel principal
                    </h1>
                    <p class="mt-2 max-w-3xl text-sm leading-6 text-gray-600">
                        Acceda rápidamente a las operaciones principales del sistema.
                    </p>
                </div>

                <a href="{{ route('services.index') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-md border border-blue-700 bg-blue-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
                    <i data-lucide="truck" class="h-4 w-4"></i>
                    Ver servicios
                </a>
            </div>
        </section>

        <section class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
            <a href="{{ route('services.index') }}"
                class="group rounded-lg border border-gray-200 bg-white p-5 shadow-sm transition hover:border-blue-300 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
                <div class="flex items-start gap-3">
                    <span class="rounded-md bg-blue-50 p-2 text-blue-700">
                        <i data-lucide="package-search" class="h-5 w-5"></i>
                    </span>
                    <div>
                        <h2 class="font-semibold text-gray-900 group-hover:text-blue-700">Servicios</h2>
                        <p class="mt-1 text-sm leading-6 text-gray-600">Consultar servicios, revisar resumen y abrir detalles operativos.</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('user.edit', Auth::id()) }}"
                class="group rounded-lg border border-gray-200 bg-white p-5 shadow-sm transition hover:border-blue-300 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
                <div class="flex items-start gap-3">
                    <span class="rounded-md bg-gray-100 p-2 text-gray-700">
                        <i data-lucide="user-round-cog" class="h-5 w-5"></i>
                    </span>
                    <div>
                        <h2 class="font-semibold text-gray-900 group-hover:text-blue-700">Perfil</h2>
                        <p class="mt-1 text-sm leading-6 text-gray-600">Actualizar información de usuario, contraseña e imagen de perfil.</p>
                    </div>
                </div>
            </a>

            @if (Auth::user()?->hasRole('admin'))
                <a href="{{ route('user.index') }}"
                    class="group rounded-lg border border-gray-200 bg-white p-5 shadow-sm transition hover:border-blue-300 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
                    <div class="flex items-start gap-3">
                        <span class="rounded-md bg-emerald-50 p-2 text-emerald-700">
                            <i data-lucide="users" class="h-5 w-5"></i>
                        </span>
                        <div>
                            <h2 class="font-semibold text-gray-900 group-hover:text-blue-700">Usuarios</h2>
                            <p class="mt-1 text-sm leading-6 text-gray-600">Gestionar usuarios, roles y estado de acceso.</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('edifactfiles.index') }}"
                    class="group rounded-lg border border-gray-200 bg-white p-5 shadow-sm transition hover:border-blue-300 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
                    <div class="flex items-start gap-3">
                        <span class="rounded-md bg-indigo-50 p-2 text-indigo-700">
                            <i data-lucide="file-stack" class="h-5 w-5"></i>
                        </span>
                        <div>
                            <h2 class="font-semibold text-gray-900 group-hover:text-blue-700">EDI procesados</h2>
                            <p class="mt-1 text-sm leading-6 text-gray-600">Revisar archivos EDI procesados y su información asociada.</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('audits.index') }}"
                    class="group rounded-lg border border-gray-200 bg-white p-5 shadow-sm transition hover:border-blue-300 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
                    <div class="flex items-start gap-3">
                        <span class="rounded-md bg-amber-50 p-2 text-amber-700">
                            <i data-lucide="shield-check" class="h-5 w-5"></i>
                        </span>
                        <div>
                            <h2 class="font-semibold text-gray-900 group-hover:text-blue-700">Auditoría</h2>
                            <p class="mt-1 text-sm leading-6 text-gray-600">Consultar actividad registrada y cambios relevantes del sistema.</p>
                        </div>
                    </div>
                </a>
            @endif
        </section>
    </div>
</x-app-layout>
