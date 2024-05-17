<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToPlans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bc_plans', function (Blueprint $table) {
            if (!Schema::hasColumn('bc_plans', 'plan_type')) {
                $table->string('plan_type', 16)->default(\Modules\User\Models\Plan::TYPE_RECURRING)->after('title');
            }
        });
        Schema::table('bc_plans', function (Blueprint $table) {
            if (!Schema::hasColumn('bc_plans', 'expiration_announcement_time')) {
                $table->integer('expiration_announcement_time')->nullable()->after('expiration_job_time');
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
        Schema::table('bc_plans', function (Blueprint $table) {
            $table->dropColumn(['type', 'expiration_announcement_time']);
        });
    }
}
