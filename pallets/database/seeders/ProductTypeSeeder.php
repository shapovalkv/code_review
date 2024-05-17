<?php

namespace Database\Seeders;

use App\Models\PalletizerConfiguration;
use App\Models\PalletizerConfigurationSegment;
use App\Models\ProductType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'box', 'no_infeed_exclusions' => true, 'lr_infeed_not_compatible' => false],
            ['name' => 'bag', 'no_infeed_exclusions' => true, 'lr_infeed_not_compatible' => false],
            ['name' => 'pail', 'no_infeed_exclusions' => true, 'lr_infeed_not_compatible' => false],
            ['name' => 'tote', 'no_infeed_exclusions' => false, 'lr_infeed_not_compatible' => true],
            ['name' => 'tray', 'no_infeed_exclusions' => false, 'lr_infeed_not_compatible' => true],
        ];

        foreach ($data as $item) {
            $item['slug'] = Str::slug($item['name']);
            ProductType::create($item);
        }
    }
}
