<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeAndIsHiddenToBcPlans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bc_plans', function (Blueprint $table) {
            $table->string('plan_type', 16)->default(\Modules\User\Models\Plan::TYPE_RECURRING)->after('title');
            $table->boolean('is_hidden')->default(false)->after('update_user');
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
            $table->dropColumn(['type', 'is_hidden']);
        });
    }
}
