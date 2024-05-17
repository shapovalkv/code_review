<?php

namespace Database\Seeders;

use App\Models\CellGripperRequirement;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PalletizerConfigurationsSegmentSeeder::class);
        $this->call(PalletizerModuleSeeder::class);
        $this->call(ProductTypeSeeder::class);
        $this->call(CellGripperRequirementSeeder::class);
        $this->call(CellExtendsChangesSeeder::class);
        $this->call(EAOTDetailsSeeder::class);
        $this->call(RobotDetailsSeeder::class);
        $this->call(CadModelSeeder::class);
        $this->call(ProductTypeImgSeeder::class);
        $this->call(MeasurementSystemSeeder::class);
//        $this->call(BOMTableSeader::class);
    }
}
