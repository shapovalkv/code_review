<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCandidatesSeniorityLevel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bc_candidates', function (Blueprint $table) {
            if (!Schema::hasColumn('bc_candidates', 'seniority_level')) {
                $table->string('seniority_level')->nullable();
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
        Schema::dropColumns('bc_candidates', ['seniority_level']);
    }
}
