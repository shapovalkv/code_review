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
        Schema::table('cell_extends_changes', function (Blueprint $table) {
            $table->integer('infeed_id')->nullable()->after('cell_substitute_module_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cell_extends_changes', function (Blueprint $table) {
            $table->dropColumn('infeed_id');
        });
    }
};
