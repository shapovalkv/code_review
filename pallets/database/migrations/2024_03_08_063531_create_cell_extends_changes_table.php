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
        Schema::create('cell_extends_changes', function (Blueprint $table) {
            $table->id();
            $table->string('cell_editable_field');
            $table->string('cell_editable_from_module_id');
            $table->string('cell_editable_to_module_id');
            $table->string('cell_substitute_field');
            $table->string('cell_substitute_module_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cell_extends_changes');
    }
};
