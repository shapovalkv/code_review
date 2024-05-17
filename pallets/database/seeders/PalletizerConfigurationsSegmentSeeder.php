<?php

namespace Database\Seeders;

use App\Models\PalletizerConfiguration;
use App\Models\PalletizerConfigurationSegment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PalletizerConfigurationsSegmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Main Palletizer Structure'],
            ['name' => 'Infeed Selection'],
            ['name' => 'Left Side Pallet Position'],
            ['name' => 'Right Side Pallet Position'],
            ['name' => 'Robot'],
            ['name' => 'EOAT']
        ];

        foreach ($data as $item) {
            $item['slug'] = Str::slug($item['name']);
            PalletizerConfigurationSegment::create($item);
        }
    }
}
