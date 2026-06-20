<?php

namespace Tests\Unit;

use App\Models\Operator;
use PHPUnit\Framework\TestCase;

class OperatorIdentificationNormalizationTest extends TestCase
{
    public function test_it_removes_common_separators_from_identification(): void
    {
        $this->assertSame('1007439712', Operator::normalizeIdentification('1.007.439.712'));
        $this->assertSame('1007439712', Operator::normalizeIdentification('1-007 439/712'));
    }

    public function test_it_keeps_letters_and_numbers_uppercase(): void
    {
        $this->assertSame('AB12345', Operator::normalizeIdentification(' ab-123.45 '));
    }
}
