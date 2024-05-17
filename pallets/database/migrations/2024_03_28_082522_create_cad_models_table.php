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
        Schema::create('cad_models', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('path');
            $table->unsignedBigInteger('cad_modelable_id');
            $table->string('cad_modelable_type');
            $table->float('position_x',6)->default(0);
            $table->float('position_y',6)->default(0);
            $table->float('position_z',6)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cad_models');
    }
};
