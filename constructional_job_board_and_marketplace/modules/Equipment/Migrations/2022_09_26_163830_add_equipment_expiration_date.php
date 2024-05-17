<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEquipmentExpirationDate extends Migration
{
    public function up()
    {
        Schema::table('bc_equipments', function (Blueprint $table) {
            if (!Schema::hasColumn('bc_equipments', 'expiration_date')) {
                $table->dateTime('expiration_date')->nullable();
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
        Schema::dropColumns('bc_equipments', ['expiration_date']);
    }
}
