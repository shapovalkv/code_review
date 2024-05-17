<?php

use Database\Seeders\JobPositionSeeder;
use Illuminate\Database\Migrations\Migration;

class JobPositionSedder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        (new JobPositionSeeder())->run();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
