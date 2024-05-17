<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Company\Models\CompanyOffices;

class OldCompanyZoomMgrationToCompanyOffices extends Migration
{
    public function up()
    {
        CompanyOffices::query()->delete();
        $companies = Modules\Company\Models\Company::query()->get();
        foreach ($companies as $company){
            if (!empty($company->location_id)){
                DB::table('bc_company_offices_locations')->insert([
                    'company_id' => $company->id,
                    'location_id' => $company->location_id,
                    'is_main' => 1,
                    'map_lat' => $company->map_lat,
                    'map_lng' => $company->map_lng,
                    'map_zoom' => empty($company->map_zoom) ? 12 : $company->map_zoom,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
