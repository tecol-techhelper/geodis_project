<?php

namespace App\Http\Requests\Api\Geodis;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ExpedienteIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $so = $this->normalizedStringInput('so');
        $srn = $this->normalizedStringInput('srn');

        $this->merge([
            'so' => $so ?? $srn,
            'srn' => $srn,
            'consolidado' => $this->normalizedStringInput('consolidado'),
            'page' => $this->input('page', 1),
            'per_page' => $this->input('per_page', 100),
        ]);
    }

    /**
     * @return array<string, array<int, ValidationRule|string>>
     */
    public function rules(): array
    {
        return [
            'so' => ['nullable', 'string', 'max:100'],
            'srn' => ['nullable', 'string', 'max:100'],
            'fecha_inicio' => ['nullable', 'date_format:Y-m-d', 'required_with:fecha_fin'],
            'fecha_fin' => ['nullable', 'date_format:Y-m-d', 'required_with:fecha_inicio', 'after_or_equal:fecha_inicio'],
            'consolidado' => ['nullable', 'string', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:500'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'so' => 'so',
            'srn' => 'srn',
            'fecha_inicio' => 'fecha_inicio',
            'fecha_fin' => 'fecha_fin',
            'consolidado' => 'consolidado',
            'page' => 'page',
            'per_page' => 'per_page',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function normalizedFilters(): array
    {
        /** @var array<string, mixed> $validated */
        $validated = $this->validated();

        return [
            'so' => $validated['so'] ?? null,
            'fecha_inicio' => $validated['fecha_inicio'] ?? null,
            'fecha_fin' => $validated['fecha_fin'] ?? null,
            'consolidado' => $validated['consolidado'] ?? null,
            'page' => (int) ($validated['page'] ?? 1),
            'per_page' => (int) ($validated['per_page'] ?? 100),
        ];
    }

    private function normalizedStringInput(string $key): mixed
    {
        $value = $this->input($key);

        if (! is_string($value)) {
            return $value;
        }

        $value = trim($value);

        return $value === '' ? null : $value;
    }
}
