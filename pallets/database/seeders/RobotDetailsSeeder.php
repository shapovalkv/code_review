<?php

namespace Database\Seeders;

use App\Models\PalletizerModule;
use App\Models\RobotDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RobotDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'brand' => 'Autonox',
                'model_number' => 'AT_00006',
                'payload_weight' => 30,
                'reach_distance' => 2257,
                'concatenated_description' => 'Autonox AT_00006 - 30kg - 2257mm',
                'robot_base_height' => null,
                'reach_center_height' => null,
                'in_scope' => false,
            ],
            [
                'brand' => 'Autonox',
                'model_number' => 'AT_00010',
                'payload_weight' => 45,
                'reach_distance' => 2118,
                'concatenated_description' => 'Autonox AT_00010 - 45kg - 2118mm',
                'robot_base_height' => null,
                'reach_center_height' => null,
                'in_scope' => false,
            ],
            [
                'brand' => 'Autonox',
                'model_number' => 'AT_00005',
                'payload_weight' => 60,
                'reach_distance' => 1959,
                'concatenated_description' => 'Autonox AT_00005 - 60kg - 1959mm',
                'robot_base_height' => null,
                'reach_center_height' => null,
                'in_scope' => false,
            ],
            [
                'brand' => 'Comau',
                'model_number' => 'NJ-16-3.1',
                'payload_weight' => 16,
                'reach_distance' => 3108,
                'concatenated_description' => 'Comau NJ-16-3.1 - 16kg - 3108mm',
                'robot_base_height' => null,
                'reach_center_height' => null,
                'in_scope' => false,
            ],
            [
                'brand' => 'Comau',
                'model_number' => 'NJ-40-2.5',
                'payload_weight' => 40,
                'reach_distance' => 2503,
                'concatenated_description' => 'Comau NJ-40-2.5 - 40kg - 2503mm',
                'robot_base_height' => null,
                'reach_center_height' => null,
                'in_scope' => false,
            ],
            [
                'brand' => 'Comau',
                'model_number' => 'NJ-60-2.2',
                'payload_weight' => 60,
                'reach_distance' => 2258,
                'concatenated_description' => 'Comau NJ-60-2.2 - 60kg - 2258mm',
                'robot_base_height' => null,
                'reach_center_height' => null,
                'in_scope' => false,
            ],
            [
                'brand' => 'Comau',
                'model_number' => 'NJ-110-3.0',
                'payload_weight' => 110,
                'reach_distance' => 2980,
                'concatenated_description' => 'Comau NJ-110-3.0 - 110kg - 2980mm',
                'robot_base_height' => null,
                'reach_center_height' => null,
                'in_scope' => false,
            ],
            [
                'brand' => 'Denso',
                'model_number' => 'VL2500',
                'payload_weight' => 40,
                'reach_distance' => 2503,
                'concatenated_description' => 'Denso VL2500 - 40kg - 2503mm',
                'robot_base_height' => null,
                'reach_center_height' => null,
                'in_scope' => false,
            ],
            [
                'brand' => 'Fanuc',
                'model_number' => 'M20iD/12L',
                'payload_weight' => 12,
                'reach_distance' => 2272,
                'concatenated_description' => 'Fanuc M20iD/12L - 12kg - 2272mm',
                'robot_base_height' => '30.56',
                'reach_center_height' => '16.73',
                'in_scope' => true,
                'palletizer_module_id' => PalletizerModule::where('slug', '=', Str::slug('fanuc-m20id12l'))->first()->id,
            ],
            [
                'brand' => 'Fanuc',
                'model_number' => 'M-710iC/20M',
                'payload_weight' => 20,
                'reach_distance' => 2582,
                'concatenated_description' => 'Fanuc M-710iC/20M - 20kg - 2582mm',
                'robot_base_height' => '1.38',
                'reach_center_height' => '22.24',
                'in_scope' => true,
                'palletizer_module_id' => PalletizerModule::where('slug', '=', Str::slug('fanuc-m-710ic20m'))->first()->id,
            ],
            [
                'brand' => 'Fanuc',
                'model_number' => 'M-710iC/20L',
                'payload_weight' => 20,
                'reach_distance' => 3110,
                'concatenated_description' => 'Fanuc M-710iC/20L - 20kg - 3110mm',
                'robot_base_height' => '1.38',
                'reach_center_height' => '22.24',
                'in_scope' => true,
                'palletizer_module_id' => PalletizerModule::where('slug', '=', Str::slug('fanuc-m-710ic20l'))->first()->id,
            ],
            [
                'brand' => 'Fanuc',
                'model_number' => 'M20iD/25',
                'payload_weight' => 25,
                'reach_distance' => 1831,
                'concatenated_description' => 'Fanuc M20iD/25 - 25kg - 1831mm',
                'robot_base_height' => '30.56',
                'reach_center_height' => '16.73',
                'in_scope' => true,
                'palletizer_module_id' => PalletizerModule::where('slug', '=', Str::slug('fanuc-m20id25'))->first()->id,
            ],
            [
                'brand' => 'Fanuc',
                'model_number' => 'M20iB/25',
                'payload_weight' => 25,
                'reach_distance' => 1853,
                'concatenated_description' => 'Fanuc M20iB/25 - 25kg - 1853mm',
                'robot_base_height' => '30.56',
                'reach_center_height' => '25.59',
                'in_scope' => true,
                'palletizer_module_id' => PalletizerModule::where('slug', '=', Str::slug('fanuc-m20ib25'))->first()->id,
            ],
            [
                'brand' => 'Fanuc',
                'model_number' => 'CRX-25iA',
                'payload_weight' => 25,
                'reach_distance' => 1889,
                'concatenated_description' => 'Fanuc CRX-25iA - 25kg - 1889mm',
                'robot_base_height' => '30.56',
                'reach_center_height' => '14.57',
                'in_scope' => true,
                'palletizer_module_id' => PalletizerModule::where('slug', '=', Str::slug('fanuc-crx-25ia'))->first()->id,
            ],
            [
                'brand' => 'Fanuc',
                'model_number' => 'M20iD/35',
                'payload_weight' => 35,
                'reach_distance' => 1831,
                'concatenated_description' => 'Fanuc M20iD/35 - 35kg - 1831mm',
                'robot_base_height' => '30.56',
                'reach_center_height' => '16.73',
                'in_scope' => true,
                'palletizer_module_id' => PalletizerModule::where('slug', '=', Str::slug('fanuc-m20id35'))->first()->id,
            ],
            [
                'brand' => 'Fanuc',
                'model_number' => 'M-710iC/45M',
                'payload_weight' => 45,
                'reach_distance' => 2606,
                'concatenated_description' => 'Fanuc M-710iC/45M - 45kg - 2606mm',
                'robot_base_height' => '1.38',
                'reach_center_height' => '22.24',
                'in_scope' => true,
                'palletizer_module_id' => PalletizerModule::where('slug', '=', Str::slug('fanuc-m-710ic45m'))->first()->id,
            ],
            [
                'brand' => 'Fanuc',
                'model_number' => 'M-710iC/50',
                'payload_weight' => 50,
                'reach_distance' => 2050,
                'concatenated_description' => 'Fanuc M-710iC/50 - 50kg - 2050mm',
                'robot_base_height' => '30.56',
                'reach_center_height' => '22.24',
                'in_scope' => true,
                'palletizer_module_id' => PalletizerModule::where('slug', '=', Str::slug('fanuc-m-710ic50'))->first()->id,
            ],
            [
                'brand' => 'Fanuc',
                'model_number' => 'M-710iC/70',
                'payload_weight' => 70,
                'reach_distance' => 2050,
                'concatenated_description' => 'Fanuc M-710iC/70 - 70kg - 2050mm',
                'robot_base_height' => '30.56',
                'reach_center_height' => '22.24',
                'in_scope' => true,
                'palletizer_module_id' => PalletizerModule::where('slug', '=', Str::slug('fanuc-m-710ic70'))->first()->id,
            ],
            [
                'brand' => 'Fanuc',
                'model_number' => 'R-1000iA/80F',
                'payload_weight' => 80,
                'reach_distance' => 2230,
                'concatenated_description' => 'Fanuc R-1000iA/80F - 80kg - 2230mm',
                'robot_base_height' => '30.56',
                'reach_center_height' => '17.72',
                'in_scope' => true,
                'palletizer_module_id' => PalletizerModule::where('slug', '=', Str::slug('fanuc-r-1000ia80f'))->first()->id,
            ],
            [
                'brand' => 'Fanuc',
                'model_number' => 'R-1000iA/100F',
                'payload_weight' => 100,
                'reach_distance' => 2230,
                'concatenated_description' => 'Fanuc R-1000iA/100F - 100kg - 2230mm',
                'robot_base_height' => '30.56',
                'reach_center_height' => '17.72',
                'in_scope' => true,
                'palletizer_module_id' => PalletizerModule::where('slug', '=', Str::slug('fanuc-r-1000ia100f'))->first()->id,
            ],
            [
                'brand' => 'Fanuc',
                'model_number' => 'R-2000iD/100FH',
                'payload_weight' => 100,
                'reach_distance' => 2605,
                'concatenated_description' => 'Fanuc R-2000iD/100FH - 100kg - 2605mm',
                'robot_base_height' => '1.38',
                'reach_center_height' => '26.38',
                'in_scope' => true,
                'palletizer_module_id' => PalletizerModule::where('slug', '=', Str::slug('fanuc-r-2000id100fh'))->first()->id,
            ],
            [
                'brand' => 'Fanuc',
                'model_number' => 'R-2000iC/125L',
                'payload_weight' => 125,
                'reach_distance' => 3100,
                'concatenated_description' => 'Fanuc R-2000iC/125L - 125kg - 3100mm',
                'robot_base_height' => '1.38',
                'reach_center_height' => '26.38',
                'in_scope' => true,
                'palletizer_module_id' => PalletizerModule::where('slug', '=', Str::slug('fanuc-r-2000ic125l'))->first()->id,
            ],
            [
                'brand' => 'Kawasaki',
                'model_number' => 'RS010L',
                'payload_weight' => 20,
                'reach_distance' => 1925,
                'concatenated_description' => 'Kawasaki RS010L - 20kg - 1925mm',
                'robot_base_height' => '30.56',
                'reach_center_height' => '18.31',
                'in_scope' => true,
                'palletizer_module_id' => PalletizerModule::where('slug', '=', Str::slug('kawasaki-rs010l'))->first()->id,
            ],
            [
                'brand' => 'Kawasaki',
                'model_number' => 'RS030N',
                'payload_weight' => 30,
                'reach_distance' => 2100,
                'concatenated_description' => 'Kawasaki RS030N - 30kg - 2100mm',
                'robot_base_height' => '30.56',
                'reach_center_height' => '26.77',
                'in_scope' => true,
                'palletizer_module_id' => PalletizerModule::where('slug', '=', Str::slug('kawasaki-rs030n'))->first()->id,
            ],
            [
                'brand' => 'Kawasaki',
                'model_number' => 'RS050N',
                'payload_weight' => 50,
                'reach_distance' => 2100,
                'concatenated_description' => 'Kawasaki RS050N - 50kg - 2100mm',
                'robot_base_height' => '30.56',
                'reach_center_height' => '26.77',
                'in_scope' => true,
                'palletizer_module_id' => PalletizerModule::where('slug', '=', Str::slug('kawasaki-rs050n'))->first()->id,
            ],
            [
                'brand' => 'Kawasaki',
                'model_number' => 'RS080N',
                'payload_weight' => 80,
                'reach_distance' => 2100,
                'concatenated_description' => 'Kawasaki RS080N - 80kg - 2100mm',
                'robot_base_height' => '30.56',
                'reach_center_height' => '26.77',
                'in_scope' => true,
                'palletizer_module_id' => PalletizerModule::where('slug', '=', Str::slug('kawasaki-rs080n'))->first()->id,
            ],
            [
                'brand' => 'Kawasaki',
                'model_number' => 'BX100N',
                'payload_weight' => 100,
                'reach_distance' => 2200,
                'concatenated_description' => 'Kawasaki BX100N - 100kg - 2200mm',
                'robot_base_height' => '30.56',
                'reach_center_height' => '17.72',
                'in_scope' => true,
                'palletizer_module_id' => PalletizerModule::where('slug', '=', Str::slug('kawasaki-bx100n'))->first()->id,
            ],
            [
                'brand' => 'Yaskawa',
                'model_number' => 'HC20DTP',
                'payload_weight' => 20,
                'reach_distance' => 1700,
                'concatenated_description' => 'Yaskawa HC20DTP - 20kg - 1700mm',
                'robot_base_height' => '30.56',
                'reach_center_height' => '14.96',
                'in_scope' => true,
                'palletizer_module_id' => PalletizerModule::where('slug', '=', Str::slug('yaskawa-hc20dtp'))->first()->id,
            ],
            [
                'brand' => 'Yaskawa',
                'model_number' => 'GP50',
                'payload_weight' => 50,
                'reach_distance' => 2061,
                'concatenated_description' => 'Yaskawa GP50 - 50kg - 2061mm',
                'robot_base_height' => '30.56',
                'reach_center_height' => '21.26',
                'in_scope' => true,
                'palletizer_module_id' => PalletizerModule::where('slug', '=', Str::slug('yaskawa-gp50'))->first()->id,
            ],
            [
                'brand' => 'Yaskawa',
                'model_number' => 'GP70L',
                'payload_weight' => 70,
                'reach_distance' => 2732,
                'concatenated_description' => 'Yaskawa GP70L - 70kg - 2732mm',
                'robot_base_height' => '1.38',
                'reach_center_height' => '21.26',
                'in_scope' => true,
                'palletizer_module_id' => PalletizerModule::where('slug', '=', Str::slug('yaskawa-gp70l'))->first()->id,
            ],
            [
                'brand' => 'Yaskawa',
                'model_number' => 'GP88',
                'payload_weight' => 88,
                'reach_distance' => 2236,
                'concatenated_description' => 'Yaskawa GP88 - 88kg - 2236mm',
                'robot_base_height' => '30.56',
                'reach_center_height' => '21.26',
                'in_scope' => true,
                'palletizer_module_id' => PalletizerModule::where('slug', '=', Str::slug('yaskawa-gp88'))->first()->id,
            ],
            [
                'brand' => 'Yaskawa',
                'model_number' => 'GP110',
                'payload_weight' => 110,
                'reach_distance' => 2236,
                'concatenated_description' => 'Yaskawa GP110 - 110kg - 2236mm',
                'robot_base_height' => '30.56',
                'reach_center_height' => '21.26',
                'in_scope' => true,
                'palletizer_module_id' => PalletizerModule::where('slug', '=', Str::slug('yaskawa-gp110'))->first()->id,
            ],
        ];

        foreach ($data as $item) {
            RobotDetail::create($item);
        }

    }
}