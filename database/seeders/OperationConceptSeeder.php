<?php

namespace Database\Seeders;

use App\Models\OperationConcept;
use App\Models\ResourceOperation;
use Illuminate\Database\Seeder;

class OperationConceptSeeder extends Seeder
{
    /** @var array<string, array<int, string>> */
    private const CONCEPTS = [
        'OCONCEPTOS' => [
            'Día Operativo',
            'Día Disponibilidad',
            'Semana Operativa',
            'Mes Operativo',
            'Unidad',
        ],
        'IZAJES' => [
            'Mes Operativo',
            'Mes Operativo Doble Turno',
            'Día Operativo',
            'Día Operativo Doble Turno',
            'Hora Operativa',
            'Día Disponible',
            'Standby',
            'Movilización <45 Km',
            'Movilización >45 Km',
        ],
        'TRANSPORTE' => [
            'Viajes',
            'Km Adicional',
            'Mes Operativo',
            'Día Operativo',
            'Día Standby',
            'Día Disponibilidad',
            'Consolidación',
        ],
    ];

    public function run(): void
    {
        foreach (self::CONCEPTS as $operationName => $concepts) {
            $operation = ResourceOperation::query()->where('name', $operationName)->first();

            if ($operation === null) {
                continue;
            }

            foreach ($concepts as $concept) {
                OperationConcept::query()->updateOrCreate([
                    'resource_operation_id' => $operation->id,
                    'name' => $concept,
                ]);
            }

            $this->replaceTestConcept($operation);
        }
    }

    private function replaceTestConcept(ResourceOperation $operation): void
    {
        if ($operation->name !== 'IZAJES') {
            return;
        }

        $testConcept = OperationConcept::query()
            ->where('resource_operation_id', $operation->id)
            ->where('name', 'Día Disponible1231')
            ->first();

        $canonicalConceptExists = OperationConcept::query()
            ->where('resource_operation_id', $operation->id)
            ->where('name', 'Día Disponible')
            ->exists();

        if ($testConcept === null) {
            return;
        }

        if (!$canonicalConceptExists) {
            $testConcept->update(['name' => 'Día Disponible']);

            return;
        }

        $testConcept->delete();
    }
}
