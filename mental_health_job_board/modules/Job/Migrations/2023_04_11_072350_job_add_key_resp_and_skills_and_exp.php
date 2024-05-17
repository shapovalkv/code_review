<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class JobAddKeyRespAndSkillsAndExp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bc_jobs', function (Blueprint $table) {
            if (!Schema::hasColumn('bc_jobs', 'key_responsibilities')) {
                $table->text('key_responsibilities')->nullable();
            }
            if (!Schema::hasColumn('bc_jobs', 'skills_and_exp')) {
                $table->text('skills_and_exp')->nullable();
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
        Schema::dropColumns('bc_jobs', ['key_responsibilities', 'skills_and_exp']);
    }
}
