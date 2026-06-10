<?php

namespace Tests\Unit;

use App\Models\Resource;
use PHPUnit\Framework\TestCase;

class ResourceReportRequirementsTest extends TestCase
{
    public function test_it_detects_each_report_requirement_from_the_mask(): void
    {
        $resource = new Resource([
            'required_report_mask' => Resource::REQUIRES_VEHICLE
                | Resource::REQUIRES_OPERATOR
                | Resource::REQUIRES_REMITTANCE
                | Resource::REQUIRES_CONTAINER,
        ]);

        $this->assertTrue($resource->requiresVehicle());
        $this->assertTrue($resource->requiresOperator());
        $this->assertTrue($resource->requiresRemittance());
        $this->assertTrue($resource->requiresContainer());
        $this->assertTrue($resource->requiresAdditionalInformation());
    }

    public function test_it_does_not_require_information_when_the_mask_is_zero(): void
    {
        $resource = new Resource(['required_report_mask' => 0]);

        $this->assertFalse($resource->requiresVehicle());
        $this->assertFalse($resource->requiresOperator());
        $this->assertFalse($resource->requiresRemittance());
        $this->assertFalse($resource->requiresContainer());
        $this->assertFalse($resource->requiresAdditionalInformation());
    }

    public function test_operator_is_a_single_requirement_bit(): void
    {
        $resource = new Resource([
            'required_report_mask' => Resource::REQUIRES_OPERATOR,
        ]);

        $this->assertTrue($resource->requiresOperator());
        $this->assertFalse($resource->requiresVehicle());
        $this->assertFalse($resource->requiresRemittance());
        $this->assertFalse($resource->requiresContainer());
    }
}
