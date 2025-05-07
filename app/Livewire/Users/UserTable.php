<?php

namespace App\Livewire\Users;

use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
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
                PowerGrid::detail()
                ->view('livewire.pages.users.partials.user-details')
                ->showCollapseIcon()
        ];
    }

    public function datasource(): Builder
    {
        return User::query()->with('role');
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('user_icon', fn(User $user)=>'<img class="w-8 h-8 shrink-0 grow-0 rounded-full" src="' . Storage::url($user->user_icon) . '">')
            ->add('username')
            ->add('full_name', fn(User $model)=> $model->first_name . ' ' .  $model->last_name)
            ->add('email')
            ->add('user_area')
            ->add('is_active');
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->sortable(),

            Column::make ('Icono','user_icon'),

            Column::make('Nombre de Usuario', 'username')
                ->sortable(),

            Column::make('Nombre Completo', 'full_name', 'first_name')
                ->bodyAttribute('whitespace-nowrap')
                ->sortable(),

            Column::make('Correo', 'email')
                ->searchable()
                ->sortable(),

            Column::make('Estado', 'is_active')
                ->toggleable()
                ->sortable(),

            Column::action('Acciones')
        ];
    }


    public function actions(User $row): array
    {
        return [
            Button::add('edit')
                ->slot('<span class="text-blue-600 hover:underline">Editar</span>')
                ->id($row->id)
                ->class('cursor-pointer')
                ->route('user.edit',['user'=>$row->id])
        ];
    }

    public function onUpdatedToggleable(string|int $id, string $field, string $value): void
    {
        User::query()->find($id)->update([
            $field => e($value),
        ]);

        $this->skipRender();
    }

    // public function detailRow(User $user){
    //     return view('livewire.pages.users.partials.user-details', compact('user'))->render();
    // }

    // public function filters(): array
    // {
    //     return[
    //         Filter::inputText('username','username'),
    //         Filter::inputText('email','email'),
    //         Filter::select('is_active', 'is_active')
    //             ->dataSource(collect(UserStatus::cases())->map(fn($status) => [
    //                 'id' => $status->value,
    //                 'name' => $status->label(),
    //                 ]))
    //                 ->optionValue('id')
    //                 ->optionLabel('name')
    //     ];
    // }

}
