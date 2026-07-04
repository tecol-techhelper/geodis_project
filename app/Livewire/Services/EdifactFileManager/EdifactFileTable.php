<?php

namespace App\Livewire\Services\EdifactFileManager;

use App\Models\EdifactFile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class EdifactFileTable extends PowerGridComponent
{
    public string $tableName = 'edifact-file-table-01b5o3-table';
    public bool $deferLoading = true;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return EdifactFile::query()
            ->leftJoin('services', 'services.id', '=', 'edifact_files.service_id')
            ->select('edifact_files.*', 'services.consecutive as service_consecutive');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('service_consecutive', fn(EdifactFile $model) => $model->service_consecutive ?: '-')
            ->add('message_type')
            ->add('purchase_order')
            ->add('purchase_order_lines', fn(EdifactFile $model) => $this->formatPurchaseOrders($model->purchase_order))
            ->add('received_at_formatted', fn(EdifactFile $model) => $model->received_at ? Carbon::parse($model->received_at)->format('d/m/Y') : '-')
            ->add('file_actions', function (EdifactFile $file) {
                $viewUrl = route('edifactfiles.view', ['edifactFile' => $file->id]);
                $downloadUrl = route('edifactfiles.download', ['edifactFile' => $file->id]);

                return <<<HTML
                    <div class="flex items-center justify-center gap-2">
                        <a href="{$viewUrl}" target="_blank" rel="noopener noreferrer" title="Visualizar archivo" aria-label="Visualizar archivo" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-indigo-600 transition hover:bg-indigo-50 hover:text-indigo-800">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
                        </a>
                        <a href="{$downloadUrl}" title="Descargar archivo" aria-label="Descargar archivo" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-blue-600 transition hover:bg-blue-50 hover:text-blue-800">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true"><path d="M12 15V3"/><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m7 10 5 5 5-5"/></svg>
                        </a>
                    </div>
                HTML;
            });
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id'),

            Column::make('Consecutivo', 'service_consecutive', 'services.consecutive')
                ->sortable()
                ->searchable(),

            Column::make('Tipo de mensaje', 'message_type')
                ->sortable(),

            Column::make('Orden de Servicio', 'purchase_order_lines', 'purchase_order')
                ->sortable()
                ->searchable(),

            Column::make('Fecha de Recepcion', 'received_at_formatted', 'received_at')
                ->sortable(),

            Column::make('Acciones', 'file_actions'),
        ];
    }

    public function filters(): array
    {
        return [];
    }

    public function hydrate(): void
    {
        sleep(2);
    }

    private function formatPurchaseOrders(?string $purchaseOrders): string
    {
        $orders = preg_split('/\s+/', trim((string) $purchaseOrders), -1, PREG_SPLIT_NO_EMPTY);

        if (!$orders) {
            return '-';
        }

        $lines = array_map(
            fn(string $order) => '<span class="block whitespace-nowrap">' . e($order) . '</span>',
            $orders,
        );

        return '<div class="space-y-1">' . implode('', $lines) . '</div>';
    }
}
