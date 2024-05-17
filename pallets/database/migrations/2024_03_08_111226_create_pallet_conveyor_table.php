<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('pallet_conveyor', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('palletizer_module_id');
            $table->boolean('is_pallet');
            $table->boolean('is_conveyor');
            $table->timestamps();

            $table->foreign('palletizer_module_id')->references('id')->on('palletizer_modules')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pallet_conveyor');
    }
};
