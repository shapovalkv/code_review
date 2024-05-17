<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMapZoomToCampanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bc_companies', function (Blueprint $table) {
            if (!Schema::hasColumn('bc_companies', 'map_zoom')) {
                $table->integer('map_zoom')->nullable()->after('map_lng');
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
        Schema::dropColumns('bc_companies', ['map_zoom']);
    }
}
