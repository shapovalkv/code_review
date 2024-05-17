<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CandidateMinMaxSalary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bc_candidates', function (Blueprint $table) {
            if (!Schema::hasColumn('bc_candidates', 'expected_salary_max')) {
                $table->string('expected_salary_max')->nullable();
            }
            if (Schema::hasColumn('bc_candidates', 'expected_salary')) {
                $table->renameColumn('expected_salary', 'expected_salary_min');
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
        Schema::dropColumns('bc_candidates', ['expected_salary_max', 'expected_salary_min']);
    }
}
