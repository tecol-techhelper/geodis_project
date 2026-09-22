@section('title', 'Configuración de servicios')

<div class="space-y-6">
    <x-breadcrums :items="[
        ['label' => 'Inicio', 'url' => route('dashboard'), 'icon' => 'home'],
        ['label' => 'Configuración de servicios', 'icon' => 'settings'],
    ]" />

    <section class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Configuración de servicios</h1>
            <p class="mt-2 max-w-3xl text-sm leading-6 text-gray-600">
                Administre los catálogos y reglas que se aplican al registro de información operativa de los servicios.
            </p>
        </div>
    </section>

    <section class="grid grid-cols-1 gap-4 xl:grid-cols-4">
        <a href="{{ route('resources.report-configuration') }}"
            class="group rounded-lg border border-gray-200 bg-white p-5 shadow-sm transition hover:border-blue-300 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
            <div class="flex items-start gap-3">
                <span class="rounded-md bg-blue-50 p-2 text-blue-700">
                    <i data-lucide="package-cog" class="h-5 w-5"></i>
                </span>
                <div>
                    <h2 class="font-semibold text-gray-900 group-hover:text-blue-700">Recursos</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">
                        Configure requisitos de reporte, operaciones y conceptos asociados a cada recurso.
                    </p>
                </div>
            </div>
        </a>

        <a href="{{ route('regions.index') }}"
            class="group rounded-lg border border-gray-200 bg-white p-5 shadow-sm transition hover:border-blue-300 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
            <div class="flex items-start gap-3">
                <span class="rounded-md bg-emerald-50 p-2 text-emerald-700">
                    <i data-lucide="map-pinned" class="h-5 w-5"></i>
                </span>
                <div>
                    <h2 class="font-semibold text-gray-900 group-hover:text-blue-700">Regionales y orígenes</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">
                        Administre las regionales y los orígenes que se pueden asociar a los servicios.
                    </p>
                </div>
            </div>
        </a>

        <a href="{{ route('operation-concepts.index') }}"
            class="group rounded-lg border border-gray-200 bg-white p-5 shadow-sm transition hover:border-blue-300 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
            <div class="flex items-start gap-3">
                <span class="rounded-md bg-violet-50 p-2 text-violet-700">
                    <i data-lucide="list-tree" class="h-5 w-5"></i>
                </span>
                <div>
                    <h2 class="font-semibold text-gray-900 group-hover:text-blue-700">Conceptos</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">
                        Administre los conceptos disponibles para cada tipo de operación.
                    </p>
                </div>
            </div>
        </a>

        <a href="{{ route('tariffs.index') }}"
            class="group rounded-lg border border-gray-200 bg-white p-5 shadow-sm transition hover:border-blue-300 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
            <div class="flex items-start gap-3">
                <span class="rounded-md bg-amber-50 p-2 text-amber-700">
                    <i data-lucide="badge-dollar-sign" class="h-5 w-5"></i>
                </span>
                <div>
                    <h2 class="font-semibold text-gray-900 group-hover:text-blue-700">Tarifario</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">
                        Consulte y configure tarifas generales o por regional para Izajes y OConceptos.
                    </p>
                </div>
            </div>
        </a>
    </section>
</div>
