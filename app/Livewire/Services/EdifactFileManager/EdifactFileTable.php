<?php

namespace App\Livewire\Services\EdifactFileManager;

use App\Models\EdifactFile;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

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
        return EdifactFile::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('transmission_id')
            ->add('message_type')
            ->add('file_name')
            ->add('purchase_order')
            ->add('recived_at_formatted', fn(EdifactFile $model) => Carbon::parse($model->recived_at)->format('d/m/Y'))
            ->add('file_url', function (EdifactFile $file) {
                $url = Storage::url($file->file_url); // Esto asume que es una ruta pública
                return "<a href=\"{$url}\" download class='flex items-center text-blue-600 underline hover:text-blue-800 justify-center'><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='lucide lucide-download-icon lucide-download'><path d='M12 15V3'/><path d='M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4'/><path d='m7 10 5 5 5-5'/></svg></a>";
            });
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id'),
            Column::make('Id de la Transmisión', 'transmission_id')
                ->sortable()
                ->searchable(),

            Column::make('Tipo de mensaje', 'message_type')
                ->sortable()
                ->searchable(),

            Column::make('Nombre del Archivo', 'file_name')
                ->sortable()
                ->searchable(),

            Column::make('Orden de Servicio', 'purchase_order')
                ->sortable()
                ->searchable(),

            Column::make('Fecha de Recepción', 'recived_at_formatted', 'recived_at')
                ->sortable(),

            Column::make('Archivo', 'file_url')
                ->sortable()
                ->searchable()
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('file_name')->placeholder('Nombre de Archivo'),
            Filter::inputText('purchase_order')->placeholder('Orden de Servicio'),
            Filter::datepicker('recived_at_formatted', 'recived_at')
                ->params(['timezone' => 'America/Bogota']),
        ];
    }


    public function hydrate(): void
    {
        sleep(2);  // ⏳ Purposefully slow down the Component loading for demonstration purposes.
    }

    // #[\Livewire\Attributes\On('edit')]
    // public function edit($rowId): void
    // {
    //     $this->js('alert('.$rowId.')');
    // }

    // public function actions(EdifactFile $row): array
    // {
    //     return [
    //         Button::add('edit')
    //             ->slot('Edit: '.$row->id)
    //             ->id()
    //             ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
    //             ->dispatch('edit', ['rowId' => $row->id])
    //     ];
    // }

    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}
