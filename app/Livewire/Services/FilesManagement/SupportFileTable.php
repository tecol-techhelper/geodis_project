<?php

namespace App\Livewire\Services\FilesManagement;

use App\Models\SupportFile;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class SupportFileTable extends PowerGridComponent
{
    public string $tableName = 'support-file-table-jdagvc-table';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
            PowerGrid::detail()
                ->view('livewire.services.edifact-file-manager.partials.file-details')
                ->showCollapseIcon()
        ];
    }

    public function datasource(): Builder
    {
        return SupportFile::query()->with('file_type')
            ->join('users as newUsers', function ($user) {
                $user->on('support_files.user_id', '=', 'newUsers.id');
            })
            ->select('support_files.*', 'newUsers.username as username');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('file_name')
            ->add('uploaded_at_formatted', fn(SupportFile $model) => Carbon::parse($model->uploaded_at)->format('d/m/Y'))
            ->add('username')
            ->add('file_type_id', fn($supportfile) => e($supportfile->file_type->file_type_full_name))
            ->add('file_url', function (SupportFile $file) {
                return "<a href=\"{$file->file_url}\" class='flex items-center text-blue-600 underline hover:text-blue-800 justify-center' target='_blank'><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='lucide lucide-eye-icon lucide-eye'><path d='M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0'/><circle cx='12' cy='12' r='3'/></svg></a>";
            });
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id'),
            Column::make('Nombre del Archivo', 'file_name')
                ->sortable()
                ->searchable(),
            Column::make('Usuario Quién Cargo', 'username'),
            Column::make('Tipo de Archivo', 'file_type_id'),
            Column::make('Fecha de Cargue', 'uploaded_at_formatted', 'uploaded_at')
                ->sortable(),
            Column::make('Ver Soporte', 'file_url')
                ->sortable()
                ->searchable(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('file_name'),
            Filter::inputText('username'),
            Filter::datepicker('uploaded_at'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    // public function actions(SupportFile $row): array
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
