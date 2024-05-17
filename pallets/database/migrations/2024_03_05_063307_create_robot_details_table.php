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
        Schema::create('robot_details', function (Blueprint $table) {
            $table->id();
            $table->string('brand');
            $table->string('model_number');
            $table->integer('palletizer_module_id')->nullable();
            $table->integer('payload_weight')->nullable();
            $table->integer('reach_distance')->nullable();
            $table->string('concatenated_description')->nullable();
            $table->float('robot_base_height', 100, 2)->nullable();
            $table->float('reach_center_height', 100, 2)->nullable();
            $table->boolean('in_scope');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('robot_details');
    }
};
