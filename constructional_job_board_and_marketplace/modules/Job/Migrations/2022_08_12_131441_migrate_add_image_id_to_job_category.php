<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MigrateAddImageIdToJobCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bc_job_categories', function (Blueprint $table) {
            if (!Schema::hasColumn('bc_job_categories', 'image_id')) {
                $table->bigInteger('image_id')->nullable();
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
        Schema::dropColumns('bc_job_categories', ['image_id']);
    }
}
