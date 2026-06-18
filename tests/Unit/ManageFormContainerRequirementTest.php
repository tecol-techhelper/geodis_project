<?php

namespace Tests\Unit;

use App\Livewire\Forms\Services\ManageForm;
use App\Models\Resource;
use App\Models\Service;
use Livewire\Component;
use PHPUnit\Framework\TestCase;

class ManageFormContainerRequirementTest extends TestCase
{
    public function test_container_bitmask_requires_container(): void
    {
        $form = $this->makeForm(Resource::REQUIRES_CONTAINER);

        $this->assertTrue($form->requirementsForRow('row-1')['container']);
        $this->assertTrue($form->requiresAdditionalInformationForRow('row-1'));
    }

    public function test_container_is_not_required_without_container_bitmask(): void
    {
        $form = $this->makeForm(0);

        $this->assertFalse($form->requirementsForRow('row-1')['container']);
        $this->assertFalse($form->requiresAdditionalInformationForRow('row-1'));
    }

    private function makeForm(int $requiredReportMask): ManageForm
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
            'resource_operation' => 'OCONCEPTOS',
            'personnel_requirements' => [],
        ]];

        return $form;
    }
}
