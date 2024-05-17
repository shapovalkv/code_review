<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class JobInvite extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bc_job_candidates', function (Blueprint $table) {
            if (!Schema::hasColumn('bc_job_candidates', 'w2_california')) {
                $table->bigInteger('initiator_id')->nullable();
            }
            if (Schema::hasColumn('bc_job_candidates', 'cv_id')) {
                $table->bigInteger('cv_id')->nullable()->change();
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
        Schema::dropColumns('bc_job_candidates', ['initiator_id']);

        Schema::table('bc_jobs', function (Blueprint $table) {
            if (Schema::hasColumn('bc_job_candidates', 'cv_id')) {
                $table->bigInteger('cv_id')->change();
            }
        });
    }
}
