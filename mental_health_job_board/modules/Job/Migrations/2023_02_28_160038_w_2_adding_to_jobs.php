<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class W2AddingToJobs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bc_jobs', function (Blueprint $table) {
            if (!Schema::hasColumn('bc_jobs', 'w2_california')) {
                $table->boolean('w2_california')->nullable();
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
        Schema::dropColumns('bc_jobs', ['w2_california']);
    }
}
