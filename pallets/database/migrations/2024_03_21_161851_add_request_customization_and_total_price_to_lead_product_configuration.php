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
        Schema::table('lead_product_configurations', function (Blueprint $table) {
            $table->longText('request_customization')->after('robot_id')->nullable();
            $table->float('total_price', 255, 10)->nullable()->after('request_customization');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lead_product_configurations', function (Blueprint $table) {
            $table->dropColumn(['request_customization', 'total_price']);
        });
    }
};
