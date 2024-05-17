<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNeverSavedBeforeColumnToBcCandidatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bc_candidates', function (Blueprint $table) {
            $table->tinyInteger('never_saved_before')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bc_candidates', function (Blueprint $table) {
            $table->dropColumn('never_saved_before');
        });
    }
}
