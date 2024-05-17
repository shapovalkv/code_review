<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EquipmentAddMapsCoordinates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bc_equipments', function (Blueprint $table) {
            if (!Schema::hasColumn('bc_equipments', 'map_zoom')) {
                $table->integer('map_zoom')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropColumns('bc_equipments', ['map_zoom']);
    }
}
