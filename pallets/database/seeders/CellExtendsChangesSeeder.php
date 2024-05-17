<?php

namespace Database\Seeders;

use App\Models\CellExtendsChange;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CellExtendsChangesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [

            ## LEFT CHANGES
            [
                'cell_editable_field' => 'left_pallet_position_id',
                'cell_editable_from_module_id' => 37,
                'cell_editable_to_module_id' => 38,
                'cell_substitute_field' => 'left_pallet_position_id',
                'cell_substitute_module_id' => 37,
                'infeed_id' => 31
            ],
            [
                'cell_editable_field' => 'left_pallet_position_id',
                'cell_editable_from_module_id' => 39,
                'cell_editable_to_module_id' => 40,
                'cell_substitute_field' => 'left_pallet_position_id',
                'cell_substitute_module_id' => 39,
                'infeed_id' => 31
            ],

            ## RIGHT CHANGES
            [
                'cell_editable_field' => 'right_pallet_position_id',
                'cell_editable_from_module_id' => 46,
                'cell_editable_to_module_id' => 47,
                'cell_substitute_field' => 'right_pallet_position_id',
                'cell_substitute_module_id' => 46,
                'infeed_id' => 32
            ],
            [
                'cell_editable_field' => 'right_pallet_position_id',
                'cell_editable_from_module_id' => 48,
                'cell_editable_to_module_id' => 49,
                'cell_substitute_field' => 'right_pallet_position_id',
                'cell_substitute_module_id' => 48,
                'infeed_id' => 32
            ],

            ## LEFT Extended CHANGES
            [
                'cell_editable_field' => 'left_pallet_position_id',
                'cell_editable_from_module_id' => 35,
                'cell_editable_to_module_id' => 36,
                'cell_substitute_field' => 'left_pallet_position_id',
                'cell_substitute_module_id' => 35,
                'infeed_id' => 31
            ],
            [
                'cell_editable_field' => 'left_pallet_position_id',
                'cell_editable_from_module_id' => 42,
                'cell_editable_to_module_id' => 43,
                'cell_substitute_field' => 'left_pallet_position_id',
                'cell_substitute_module_id' => 42,
                'infeed_id' => 31
            ],

            ## RIGHT Extended CHANGES
            [
                'cell_editable_field' => 'right_pallet_position_id',
                'cell_editable_from_module_id' => 44,
                'cell_editable_to_module_id' => 45,
                'cell_substitute_field' => 'right_pallet_position_id',
                'cell_substitute_module_id' => 44,
                'infeed_id' => 32
            ],
            [
                'cell_editable_field' => 'right_pallet_position_id',
                'cell_editable_from_module_id' => 51,
                'cell_editable_to_module_id' => 52,
                'cell_substitute_field' => 'right_pallet_position_id',
                'cell_substitute_module_id' => 51,
                'infeed_id' => 32
            ],

            ## INFEED CHANGES
            [
                'cell_editable_field' => 'product_infeed_id',
                'cell_editable_from_module_id' => 31,
                'cell_editable_to_module_id' => 33,
                'cell_substitute_field' => 'left_pallet_position_id',
                'cell_substitute_module_id' => 35,
                'infeed_id' => 31
            ],
            [
                'cell_editable_field' => 'product_infeed_id',
                'cell_editable_from_module_id' => 31,
                'cell_editable_to_module_id' => 33,
                'cell_substitute_field' => 'left_pallet_position_id',
                'cell_substitute_module_id' => 42,
                'infeed_id' => 31
            ],
            [
                'cell_editable_field' => 'product_infeed_id',
                'cell_editable_from_module_id' => 32,
                'cell_editable_to_module_id' => 34,
                'cell_substitute_field' => 'right_pallet_position_id',
                'cell_substitute_module_id' => 44,
                'infeed_id' => 32
            ],
            [
                'cell_editable_field' => 'product_infeed_id',
                'cell_editable_from_module_id' => 32,
                'cell_editable_to_module_id' => 34,
                'cell_substitute_field' => 'right_pallet_position_id',
                'cell_substitute_module_id' => 51,
                'infeed_id' => 32
            ]
        ];

        foreach ($data as $item) {
            CellExtendsChange::create($item);
        }
    }
}
