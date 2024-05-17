<?php

namespace Database\Seeders;

use App\Models\EAOTDetail;
use App\Models\PalletizerModule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EAOTDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Single Vacuum Gripper',
                'weight' => 5.5,
                'z_height' => 8.3,
                'y_offset' => 0,
                'palletizer_module_id' => PalletizerModule::where('slug', '=', Str::slug('vacuum-gripper-single'))->first()->id,

            ],
            [
                'name' => 'Single vacuum (extended)',
                'weight' => 5.5,
                'z_height' => 8.3,
                'y_offset' => -5,
            ],
            [
                'name' => 'Dual vacuum',
                'weight' => 19,
                'z_height' => 10.69,
                'y_offset' => 2.625,
                'palletizer_module_id' => PalletizerModule::where('slug', '=', Str::slug('vacuum-gripper-double'))->first()->id,
            ],
            [
                'name' => 'Fork',
                'weight' => 49.5,
                'z_height' => 29,
                'y_offset' => -14.3,
                'palletizer_module_id' => PalletizerModule::where('slug', '=', Str::slug('fork-gripper'))->first()->id,
            ],
        ];

        foreach ($data as $item) {
            EAOTDetail::create($item);
        }
    }
}
