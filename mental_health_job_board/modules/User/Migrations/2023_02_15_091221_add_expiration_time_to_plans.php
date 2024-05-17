<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExpirationTimeToPlans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bc_plans', function (Blueprint $table) {
            if (!Schema::hasColumn('bc_plans', 'expiration_job_time')) {
                $table->string('expiration_job_time')->nullable();
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
        Schema::dropColumns('bc_plans', ['expiration_job_time']);
    }
}
