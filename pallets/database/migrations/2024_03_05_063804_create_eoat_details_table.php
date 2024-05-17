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
        Schema::create('eoat_details', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('weight', 100, 2)->nullable();
            $table->float('z_height', 100, 2)->nullable();
            $table->float('y_offset', 100, 2)->nullable();
            $table->integer('palletizer_module_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eoat_details');
    }
};
