<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExperienceTypeToJob extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bc_jobs', function (Blueprint $table) {
            if (!Schema::hasColumn('bc_jobs', 'experience_type')) {
                $table->string('experience_type', 255)->nullable()->after('experience');
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
        Schema::dropColumns('bc_jobs', ['experience_type']);
    }
}
