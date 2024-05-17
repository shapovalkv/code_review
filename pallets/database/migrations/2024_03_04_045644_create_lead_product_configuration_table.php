<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lead_product_configurations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lead_id')->nullable();
            $table->foreign('lead_id')->references('id')->on('leads');
            $table->integer('gripper_id')->nullable();
            $table->integer('product_infeed_id')->nullable();
            $table->integer('left_pallet_position_id')->nullable();
            $table->integer('right_pallet_position_id')->nullable();
            $table->integer('system_pallet_height')->nullable();
            $table->string('product_name')->nullable();
            $table->integer('product_type_id')->nullable();
            $table->integer('product_length')->nullable();
            $table->integer('product_width')->nullable();
            $table->float('product_height', 100, 2)->nullable();
            $table->integer('product_weight')->nullable();
            $table->float('product_infeed_rate', 100, 2)->nullable();
            $table->integer('pallet_length')->nullable();
            $table->integer('pallet_width')->nullable();
            $table->float('pallet_height', 100, 2)->nullable();
            $table->integer('robot_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_product_configurations');
    }
};
