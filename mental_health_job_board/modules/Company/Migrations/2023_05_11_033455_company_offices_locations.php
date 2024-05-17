<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CompanyOfficesLocations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bc_company_offices_locations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id');
            $table->integer('is_main')->nullable();
            $table->bigInteger('location_id')->nullable();
            $table->string('map_lat', 30)->nullable();
            $table->string('map_lng', 30)->nullable();
            $table->integer('map_zoom')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bc_company_offices_locations');
    }
}
