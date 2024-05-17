<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OldCompanyMgrationAndRefactoringCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $companies = Modules\Company\Models\Company::query()->get();
        foreach ($companies as $company){
            if (!empty($company->location_id) && !empty($company->map_lat) && !empty($company->map_lng) && !empty($company->map_zoom)){
                DB::table('bc_company_offices_locations')->insert([
                    'company_id' => $company->id,
                    'location_id' => $company->location_id,
                    'is_main' => 1,
                    'map_lat' => $company->map_lat,
                    'map_lng' => $company->map_lng,
                    'map_zoom' => 3,
                ]);
            }
        }

//        Schema::table('bc_companies', function (Blueprint $table) {
//            if (Schema::hasColumn('bc_companies', 'location_id')) {
//                $table->dropColumn('location_id');
//            }
//            if (Schema::hasColumn('bc_companies', 'map_lat')) {
//                $table->dropColumn('map_lat');
//            }
//            if (Schema::hasColumn('bc_companies', 'map_lng')) {
//                $table->dropColumn('map_lng');
//            }
//            if (Schema::hasColumn('bc_companies', 'map_zoom')) {
//                $table->dropColumn('map_zoom');
//            }
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('bc_companies', function (Blueprint $table) {
//            $table->bigInteger('location_id')->nullable()->after('status');
//            $table->string('map_lat',30)->nullable()->after('location_id');
//            $table->string('map_lng',30)->nullable()->after('map_lat');
//            $table->integer('map_zoom')->after('map_lng');
//        });
    }
}
