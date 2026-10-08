<?php

namespace Tests\Unit;

use App\Livewire\Forms\Services\ManageForm;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use ReflectionMethod;
use Tests\TestCase;

class OptionalFinalReportValidationTest extends TestCase
{
    private function formWithLines(array $lines): ManageForm
    {
        $component = new class extends Component {
            public ManageForm $form;
        };
        $form = new ManageForm($component, 'form');
        $component->form = $form;
        $form->service_resource_rows = [[
            'row_key' => 'resource-1',
            'resource_operation' => 'COSTO_AUTORIZADO',
            'required_report_mask' => 0,
            'personnel_requirements' => [],
        ]];
        $form->additional_information = ['resource-1' => ['operation_lines' => $lines]];

        return $form;
    }

    public function test_saving_without_opening_the_optional_final_report_passes_validation(): void
    {
        $form = $this->formWithLines([]);

        (new ReflectionMethod($form, 'validateAdditionalInformation'))->invoke($form);

        $this->assertNull($form->active_resource_row_key);
        $this->assertSame([], (new ReflectionMethod($form, 'operationLinesForPersistence'))->invoke($form, 'resource-1'));
    }

    public function test_an_empty_display_row_passes_validation_without_creating_report_lines(): void
    {
        $form = $this->formWithLines([[
            'origin_id' => null,
            'destination_id' => null,
            'operation_concept_id' => null,
            'quantity' => null,
            'unit_price' => null,
        ]]);

        (new ReflectionMethod($form, 'validateAdditionalInformation'))->invoke($form);

        $this->assertSame([], (new ReflectionMethod($form, 'operationLinesForPersistence'))->invoke($form, 'resource-1'));
    }

    public function test_entered_amounts_still_require_positive_values(): void
    {
        $form = $this->formWithLines([['quantity' => '-1']]);

        try {
            (new ReflectionMethod($form, 'validateAdditionalInformation'))->invoke($form);
            $this->fail('Invalid amounts must still fail validation.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('form.additional_information.resource-1.operation_lines.0.quantity', $exception->errors());
        }
    }
}
