<?php

namespace Tests\Unit;

use App\Livewire\Forms\Services\ManageForm;
use App\Models\Resource;
use App\Models\Service;
use Livewire\Component;
use PHPUnit\Framework\TestCase;

class ManageFormPersonnelRequirementTest extends TestCase
{
    public function test_personnel_requires_configured_role_entries(): void
    {
        $form = $this->makeForm(Resource::REQUIRES_PERSONNEL, [[
            'role_id' => 1,
            'role_code' => 'driver',
            'role_name' => 'Conductor',
            'quantity_required' => 2,
            'is_required' => true,
            'sort_order' => 10,
        ]]);

        $this->assertTrue($form->requirementsForRow('row-1')['personnel']);
        $this->assertTrue($form->requiresAdditionalInformationForRow('row-1'));
        $this->assertFalse($form->isAdditionalInformationComplete('row-1'));

        $form->additional_information['row-1']['personnel'][1][0] = [
            'identification' => '100',
            'first_name' => 'Ana',
            'last_name' => 'Diaz',
        ];
        $form->additional_information['row-1']['personnel'][1][1] = [
            'identification' => '200',
            'first_name' => 'Luis',
            'last_name' => 'Perez',
        ];

        $this->assertTrue($form->isAdditionalInformationComplete('row-1'));
    }

    public function test_personnel_bit_without_configuration_does_not_open_additional_information(): void
    {
        $form = $this->makeForm(Resource::REQUIRES_PERSONNEL, []);

        $this->assertFalse($form->requirementsForRow('row-1')['personnel']);
        $this->assertFalse($form->requiresAdditionalInformationForRow('row-1'));
    }

    private function makeForm(int $requiredReportMask, array $personnelRequirements): ManageForm
    {
        $component = new class extends Component
        {
            public function render(): string
            {
                return '';
            }
        };

        $form = new ManageForm($component, 'form');
        $form->service = new Service();
        $form->service_resource_rows = [[
            'row_key' => 'row-1',
            'pivot_id' => null,
            'resource_id' => 1,
            'required_report_mask' => $requiredReportMask,
            'resource_operation' => 'TRANSPORTE',
            'personnel_requirements' => $personnelRequirements,
        ]];
        $form->additional_information = [
            'row-1' => [
                'report_id' => null,
                'vehicle_plate' => null,
                'personnel' => [],
                'remesa_transporte' => null,
                'container_number' => null,
            ],
        ];

        return $form;
    }
}
