<?php

namespace App\Livewire\Audits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class AuditTable extends PowerGridComponent
{
    public string $tableName = 'audit-table-1xg3q2-table';
    public string $primaryKey = 'audit_id';
    public ?string $primaryKeyAlias = 'audit_id';
    public bool $supportModel = false;

    public function setUp(): array
    {
        return [
            PowerGrid::header()->showSearchInput(),
            PowerGrid::footer()->showPerPage()->showRecordCount(),
        ];
    }

    public function datasource()
    {
        return DB::table('audits')
            ->select([
                DB::raw('audit_id as id'),
                'audit_id',
                'auditable_type',
                'auditable_id',
                'auditable_action',
                'old_value',
                'new_value',
                'user_id',
                'username',
                'user_rol',
                'ip_address',
                'user_agent',
                'performed_at',
            ])
            ->orderByDesc('performed_at');
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('audit_id')
            ->add('auditable_type')
            ->add('auditable_id')
            ->add('auditable_action')
            ->add('username')
            ->add('user_rol')
            ->add('ip_address')
            ->add('performed_at')
            ->add('old_value_short', fn($row) => $this->shortJson($row->old_value))
            ->add('new_value_short', fn($row) => $this->shortJson($row->new_value))
            ->add('user_agent_short', fn($row) => Str::limit((string) $row->user_agent, 60));
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'audit_id')->sortable(),
            Column::make('Modelo', 'auditable_type')->searchable(),
            Column::make('ID Modelo', 'auditable_id')->sortable(),
            Column::make('Acción', 'auditable_action')->sortable(),
            Column::make('Usuario', 'username')->searchable(),
            Column::make('Rol', 'user_rol')->searchable(),
            Column::make('IP', 'ip_address')->searchable(),
            Column::make('Fecha', 'performed_at')->sortable(),
            Column::make('Old', 'old_value_short'),
            Column::make('New', 'new_value_short'),
            Column::make('User Agent', 'user_agent_short'),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('username'),
            Filter::inputText('auditable_type'),
            Filter::inputText('auditable_action'),
            Filter::inputText('ip_address'),
            Filter::datepicker('performed_at'),
        ];
    }

    private function shortJson(?string $json): string
    {
        if (!$json) {
            return '-';
        }
        return Str::limit($json, 120);
    }
}
