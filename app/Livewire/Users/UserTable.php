<?php

namespace App\Livewire\Users;

use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class UserTable extends PowerGridComponent
{
    public string $tableName = 'user-table-z7qiwp-table';
    public bool $showDetailRow = true;

    public function setUp(): array
    {
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
        return User::query();
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('username')
            ->add('fullname', fn(User $model)=> $model->first_name . ' ' . $model->last_name)
            ->add('email')
            ->add('is_active_value', fn(User $model)=>$model->is_active->value)
            ->add('is_active_label', fn(User $model)=>$model->is_active->label());
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->sortable(),

            Column::make('Nombre de Usuario', 'username')
                ->searchable()
                ->sortable(),

            Column::make('Nombre Completo', 'fullname')
                ->searchable()
                ->sortable(),

            Column::make('Correo', 'email')
                ->searchable()
                ->sortable(),

            Column::make('Estado', 'is_active_value', 'is_active_label')
            ->toggleable('is_active', UserStatus::Active->value, UserStatus::Unactive->value)
                ->sortable()
                ->editOnClick(true),

            Column::action('Acciones')
        ];
    }


    public function actions(User $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit: '.$row->id)
                ->id($row->id)
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
        ];
    }

    public function detailRow(User $user){
        return view('livewire.pages.users.partials.user-details', compact('user'))->render();
    }

    public function filters(): array
    {
        return[
            Filter::inputText('username','username'),
            Filter::inputText('email','email'),
            Filter::select('is_active','is_active'),
            Filter::select('is_active', 'is_active')
                ->dataSource(collect(UserStatus::cases())->map(fn($status) => [
                    'id' => $status->value,
                    'name' => $status->label(),
                    ]))
                    ->optionValue('id')
                    ->optionLabel('name')
        ];
    }
}
