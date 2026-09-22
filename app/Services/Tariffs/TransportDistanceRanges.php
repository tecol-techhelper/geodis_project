<?php

namespace App\Services\Tariffs;

class TransportDistanceRanges
{
    /** @return array<int, array{label:string, minimum_km:?string, maximum_km:?string, is_minimum_inclusive:bool, is_maximum_inclusive:bool}> */
    public static function definitions(): array
    {
        return [
            ['label' => '≤ 30 km', 'minimum_km' => null, 'maximum_km' => '30', 'is_minimum_inclusive' => true, 'is_maximum_inclusive' => true],
            ['label' => '31 - 50 km', 'minimum_km' => '31', 'maximum_km' => '50', 'is_minimum_inclusive' => true, 'is_maximum_inclusive' => true],
            ['label' => '51 - 100 km', 'minimum_km' => '51', 'maximum_km' => '100', 'is_minimum_inclusive' => true, 'is_maximum_inclusive' => true],
            ['label' => '101 - 200 km', 'minimum_km' => '101', 'maximum_km' => '200', 'is_minimum_inclusive' => true, 'is_maximum_inclusive' => true],
            ['label' => '201 - 300 km', 'minimum_km' => '201', 'maximum_km' => '300', 'is_minimum_inclusive' => true, 'is_maximum_inclusive' => true],
            ['label' => '301 - 400 km', 'minimum_km' => '301', 'maximum_km' => '400', 'is_minimum_inclusive' => true, 'is_maximum_inclusive' => true],
            ['label' => '401 - 600 km', 'minimum_km' => '401', 'maximum_km' => '600', 'is_minimum_inclusive' => true, 'is_maximum_inclusive' => true],
            ['label' => '601 - 800 km', 'minimum_km' => '601', 'maximum_km' => '800', 'is_minimum_inclusive' => true, 'is_maximum_inclusive' => true],
            ['label' => '801 - 1000 km', 'minimum_km' => '801', 'maximum_km' => '1000', 'is_minimum_inclusive' => true, 'is_maximum_inclusive' => true],
            ['label' => '1001 - 1400 km', 'minimum_km' => '1001', 'maximum_km' => '1400', 'is_minimum_inclusive' => true, 'is_maximum_inclusive' => true],
            ['label' => '> 1400 km', 'minimum_km' => '1400', 'maximum_km' => null, 'is_minimum_inclusive' => false, 'is_maximum_inclusive' => true],
        ];
    }
}
