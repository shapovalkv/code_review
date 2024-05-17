<?php

namespace Database\Seeders;

use App\Models\PalletConveyor;
use App\Models\PalletizerConfigurationSegment;
use App\Models\PalletizerModule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PalletizerModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ## MAIN PALLETIZER
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Main Palletizer Structure'))->first()->id, 'name' => 'Main Palletizer', 'cost' => 95343],

            ## ROBOT
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Robot'))->first()->id, 'name' => 'Fanuc M20iD/12L', 'cost' => 85019 ],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Robot'))->first()->id, 'name' => 'Fanuc M-710iC/20M', 'cost' => 93014 ],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Robot'))->first()->id, 'name' => 'Fanuc M-710iC/20L', 'cost' => 95914 ],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Robot'))->first()->id, 'name' => 'Fanuc M20iD/25', 'cost' => 79000 ],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Robot'))->first()->id, 'name' => 'Fanuc M20iB/25', 'cost' => 96645 ],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Robot'))->first()->id, 'name' => 'Fanuc CRX-25iA', 'cost' => 71020 ],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Robot'))->first()->id, 'name' => 'Fanuc M20iD/35', 'cost' => 83810 ],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Robot'))->first()->id, 'name' => 'Fanuc M-710iC/45M', 'cost' => 95214 ],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Robot'))->first()->id, 'name' => 'Fanuc M-710iC/50', 'cost' => 92716 ],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Robot'))->first()->id, 'name' => 'Fanuc M-710iC/70', 'cost' => 98316 ],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Robot'))->first()->id, 'name' => 'Fanuc R-1000iA/80F', 'cost' => 106330 ],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Robot'))->first()->id, 'name' => 'Fanuc R-1000iA/100F', 'cost' => 110930 ],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Robot'))->first()->id, 'name' => 'Fanuc R-2000iD/100FH', 'cost' => 119327  ],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Robot'))->first()->id, 'name' => 'Fanuc R-2000iC/125L', 'cost' => 118846 ],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Robot'))->first()->id, 'name' => 'Kawasaki RS010L', 'cost' => 45236 ],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Robot'))->first()->id, 'name' => 'Kawasaki RS030N', 'cost' => 55937 ],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Robot'))->first()->id, 'name' => 'Kawasaki RS050N', 'cost' => 56437 ],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Robot'))->first()->id, 'name' => 'Kawasaki RS080N', 'assembly_no' => 101289, 'cost' => 56955 ],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Robot'))->first()->id, 'name' => 'Kawasaki BX100N', 'cost' => 58937 ],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Robot'))->first()->id, 'name' => 'Yaskawa HC20DTP', 'cost' => 73075 ],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Robot'))->first()->id, 'name' => 'Yaskawa GP50', 'cost' => 89707 ],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Robot'))->first()->id, 'name' => 'Yaskawa GP70L', 'cost' => 99196 ],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Robot'))->first()->id, 'name' => 'Yaskawa GP88', 'cost' => 98306 ],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Robot'))->first()->id, 'name' => 'Yaskawa GP110', 'cost' => 99414 ],

            ## EAOT
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('EOAT'))->first()->id, 'name' => 'Vacuum Gripper Single',  'assembly_no' => 101283, 'cost' => 4824  ],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('EOAT'))->first()->id, 'name' => 'Vacuum Gripper Double', 'assembly_no' => 101284, 'cost' => 14033  ],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('EOAT'))->first()->id, 'name' => 'Fork Gripper', 'assembly_no' => 101289, 'cost' => 31177  ],

            ## PRODUCT INFEED
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Infeed Selection'))->first()->id, 'name' => 'Center Left Infeed', 'assembly_no' => 101315, 'cost' => 30410 ],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Infeed Selection'))->first()->id, 'name' => 'Center Right Infeed', 'assembly_no' => 101311, 'cost' => 30410 ],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Infeed Selection'))->first()->id, 'name' => 'Left Infeed', 'assembly_no' => 101316, 'cost' => 32315 ],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Infeed Selection'))->first()->id, 'name' => 'Right Infeed', 'assembly_no' => 101317, 'cost' => 32315 ],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Infeed Selection'))->first()->id, 'name' => 'Left Infeed Extended', 'assembly_no' => 101318, 'cost' => 33697 ],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Infeed Selection'))->first()->id, 'name' => 'Right Infeed Extended', 'assembly_no' => 101319, 'cost' => 33697 ],

            ## LEFT SIDE
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Left Side Pallet Position'))->first()->id, 'name' => 'Left Side Floor Pallet', 'assembly_no' => 101294, 'cost' => 16849 , 'is_pallet' => true, 'is_conveyor' => false],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Left Side Pallet Position'))->first()->id, 'name' => 'Left Side Floor Pallet - Left Infeed Extended', 'assembly_no' => 101299, 'cost' => 15719 , 'is_pallet' => true, 'is_conveyor' => false],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Left Side Pallet Position'))->first()->id, 'name' => 'Left Side Guarding Only', 'assembly_no' => 101295, 'cost' => 6594, 'is_pallet' => false, 'is_conveyor' => false],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Left Side Pallet Position'))->first()->id, 'name' => 'Left Side Guarding Only - Left Infeed', 'assembly_no' => 101301, 'cost' => 5777 , 'is_pallet' => false, 'is_conveyor' => false],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Left Side Pallet Position'))->first()->id, 'name' => 'Left Side Slip Sheet', 'assembly_no' => 101296, 'cost' => 17821 , 'is_pallet' => true, 'is_conveyor' => false],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Left Side Pallet Position'))->first()->id, 'name' => 'Left Side Slip Sheet - Left Infeed', 'assembly_no' => 101300, 'cost' => 17505 , 'is_pallet' => true, 'is_conveyor' => false],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Left Side Pallet Position'))->first()->id, 'name' => 'Left Side Straight Pallet Conveyor', 'assembly_no' => 101297, 'cost' => 36480 , 'is_pallet' => false, 'is_conveyor' => true],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Left Side Pallet Position'))->first()->id, 'name' => 'Left Side U-Turn Pallet Conveyor', 'assembly_no' => 101298, 'cost' => 52816 , 'is_pallet' => false, 'is_conveyor' => true],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Left Side Pallet Position'))->first()->id, 'name' => 'Left Side U-Turn Pallet Conveyor- Left Infeed Extended', 'assembly_no' => 101302, 'cost' => 50667 , 'is_pallet' => false, 'is_conveyor' => true],

            ## RIGHT SIDE
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Right Side Pallet Position'))->first()->id, 'name' => 'Right Side Floor Pallet', 'assembly_no' => 101320, 'cost' => 16849 , 'is_pallet' => true, 'is_conveyor' => false],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Right Side Pallet Position'))->first()->id, 'name' => 'Right Side Floor Pallet - Right Infeed Extended', 'assembly_no' => 101325, 'cost' => 15719 , 'is_pallet' => true, 'is_conveyor' => false],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Right Side Pallet Position'))->first()->id, 'name' => 'Right Side Guarding Only', 'assembly_no' => 101321, 'cost' => 6594 , 'is_pallet' => false, 'is_conveyor' => false],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Right Side Pallet Position'))->first()->id, 'name' => 'Right Side Guarding Only - Right Infeed', 'assembly_no' => 101327, 'cost' => 5777 , 'is_pallet' => false, 'is_conveyor' => false],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Right Side Pallet Position'))->first()->id, 'name' => 'Right Side Slip Sheet', 'assembly_no' => 101322, 'cost' => 17821 , 'is_pallet' => true, 'is_conveyor' => false],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Right Side Pallet Position'))->first()->id, 'name' => 'Right Side Slip Sheet - Right Infeed', 'assembly_no' => 101326, 'cost' => 17505 , 'is_pallet' => true, 'is_conveyor' => false],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Right Side Pallet Position'))->first()->id, 'name' => 'Right Side Straight Pallet Conveyor', 'assembly_no' => 101323, 'cost' => 36480 , 'is_pallet' => false, 'is_conveyor' => true],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Right Side Pallet Position'))->first()->id, 'name' => 'Right Side U-Turn Pallet Conveyor', 'assembly_no' => 101324, 'cost' => 52816 , 'is_pallet' => false, 'is_conveyor' => true],
            ['segment_id' => PalletizerConfigurationSegment::where('slug', '=', Str::slug('Right Side Pallet Position'))->first()->id, 'name' => 'Right Side U-Turn Pallet Conveyor - Right Infeed Extended', 'assembly_no' => 101328, 'cost' => 50667, 'is_pallet' => false, 'is_conveyor' => true]
        ];


        foreach ($data as $item) {
            $item['slug'] = Str::slug($item['name']);

            if (array_key_exists('is_pallet', $item) && array_key_exists('is_conveyor', $item)) {
                $palletConveyorData = [
                    'is_pallet' => $item['is_pallet'],
                    'is_conveyor' => $item['is_conveyor'],
                ];
                unset($item['is_pallet']);
                unset($item['is_conveyor']);

                $palletizerModule = PalletizerModule::create($item);

                PalletConveyor::create(['palletizer_module_id' => $palletizerModule->id, 'is_pallet' => $palletConveyorData['is_pallet'],  'is_conveyor' => $palletConveyorData['is_conveyor']]);
            } else {
                PalletizerModule::create($item);
            }
        }
    }
}
