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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'social_id')) {
                $table->string('social_id', 255)->nullable()->after('last_name');
            }
            if (!Schema::hasColumn('users', 'social_type')) {
                $table->string('social_type', 255)->nullable()->after('social_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropColumns('users', ['social_id', 'social_type']);
    }
};
