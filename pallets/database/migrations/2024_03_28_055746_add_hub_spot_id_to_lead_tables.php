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
        Schema::table('leads', function (Blueprint $table) {
            $table->string('hs_contact_id')->nullable()->after('local_distributor');
        });

        Schema::table('lead_product_configurations', function (Blueprint $table) {
            $table->string('hs_custom_object_palletizer_id')->nullable()->after('total_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['hs_contact_id']);
        });

        Schema::table('lead_product_configurations', function (Blueprint $table) {
            $table->dropColumn(['hs_custom_object_palletizer_id']);
        });
    }
};
