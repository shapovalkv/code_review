<?php

namespace Database\Seeders;

use App\Models\PalletizerModule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use stdClass;

class BOMTableSeader extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = base_path('database/seeders/bom1.xlsx');

        $data = Excel::toCollection(new stdClass(), $file)->first();

        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();

        foreach ($data as $key => $row) {
            if (is_string($row[0])) continue;

            if ($row[7] == "All"){
                $pal_id = 0;
            }elseif ($module = PalletizerModule::query()->where('slug', '=', Str::slug($row[7]))->first()){
                $pal_id = $module->id;
            } else {
                $pal_id = -1;
            }


            DB::table('bom')->insert([
                'palletizer_module_id' => $pal_id,
                'part_number' => $row[2],
                'description' => $row[3],
                'qty' => $row[4],
                'in_assy' => $row[5],
                'purchased_in_assy' => $row[6],
                'boom_category' => $row[9],
                'manufacturer' => $row[10],
                'vendor' => $row[11],
                'price_each' => $sheet->getCell('N'. ($key+1))->getCalculatedValue(),
                'price_all' => $sheet->getCell('O'. ($key+1))->getCalculatedValue(),
                'quoted_price' => $sheet->getCell('P'. ($key+1))->getCalculatedValue(),
                'notes' => $row[18],
            ]);
        }
    }
}
