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
        Schema::table('cad_models', function (Blueprint $table) {
            $table->string('rotate_axis')->default('y');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cad_models', function (Blueprint $table) {
            $table->dropColumn('rotate_axis');
        });
    }
};
