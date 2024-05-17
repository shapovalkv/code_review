<?php

namespace Database\Seeders;

use App\Models\MeasurementSystem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class measurementSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'field' => 'PRODUCT_MIN_LENGTH',
                'value' => 100,
                'type' => 'dimensions',
            ],
            [
                'field' => 'PRODUCT_MAX_LENGTH',
                'value' => 600,
                'type' => 'dimensions',
            ],
            [
                'field' => 'PRODUCT_MIN_WIDTH',
                'value' => 100,
                'type' => 'dimensions',
            ],
            [
                'field' => 'PRODUCT_MAX_WIDTH',
                'value' => 400,
                'type' => 'dimensions',
            ],
            [
                'field' => 'PRODUCT_MIN_HEIGHT',
                'value' => 0.1,
                'type' => 'dimensions',

            ],
            [
                'field' => 'PRODUCT_MAX_HEIGHT',
                'value' => 400,
                'type' => 'dimensions',

            ],
            [
                'field' => 'PRODUCT_MIN_WEIGHT',
                'value' => 1,
                'type' => 'weight',
            ],
            [
                'field' => 'PRODUCT_REQUIRE_INFEED_RATE_WEIGHT',
                'value' => 25,
                'type' => 'weight',
            ],
            [
                'field' => 'PRODUCT_MAX_WEIGHT',
                'value' => 100,
                'type' => 'weight',
            ],
            [
                'field' => 'PRODUCT_MIN_INFEED_RATE',
                'value' => 1,
            ],
            [
                'field' => 'PRODUCT_MAX_INFEED_RATE',
                'value' => 15,
            ],
            [
                'field' => 'PALLET_MIN_LENGTH',
                'value' => 600,
                'type' => 'dimensions',
            ],
            [
                'field' => 'PALLET_MAX_LENGTH',
                'value' => 1220,
                'type' => 'dimensions',
            ],
            [
                'field' => 'PALLET_MIN_WIDTH',
                'value' => 600,
                'type' => 'dimensions',
            ],
            [
                'field' => 'PALLET_MAX_WIDTH',
                'value' => 1220,
                'type' => 'dimensions',
            ],
            [
                'field' => 'PALLET_MIN_HEIGHT',
                'value' => 0.1,
                'type' => 'dimensions',
            ],
            [
                'field' => 'PALLET_MAX_HEIGHT',
                'value' => 200,
                'type' => 'dimensions',
            ],
            [
                'field' => 'SYSTEM_PALLET_MIN_HIGHT',
                'value' => 1,
                'type' => 'dimensions',
            ],
            [
                'field' => 'SYSTEM_PALLET_MAX_HIGHT',
                'value' => 1800,
                'type' => 'dimensions',
            ],
        ];

        foreach ($data as $item) {
            MeasurementSystem::create($item);
        }
    }
}
