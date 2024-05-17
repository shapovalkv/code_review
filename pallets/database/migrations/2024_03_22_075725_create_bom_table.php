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
        Schema::create('bom', function (Blueprint $table) {
            $table->id();
            $table->integer('palletizer_module_id')->nullable();
            $table->string('part_number')->nullable();
            $table->string('description')->nullable();
            $table->integer('qty')->nullable();
            $table->string('in_assy')->nullable();
            $table->string('purchased_in_assy')->nullable();
            $table->boolean('in_boom')->nullable();
            $table->string('boom_category')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('vendor')->nullable();
            $table->float('price_each', 100, 10)->nullable();
            $table->float('quoted_price', 100, 10)->nullable();
            $table->float('price_all', 100, 10)->nullable();
            $table->longText('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bom');
    }
};
