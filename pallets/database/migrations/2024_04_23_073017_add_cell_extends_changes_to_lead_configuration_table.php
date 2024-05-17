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
            $table->integer('replaced_product_infeed_id')->nullable()->after('product_infeed_id');
            $table->integer('replaced_left_pallet_position_id')->nullable()->after('left_pallet_position_id');
            $table->integer('replaced_right_pallet_position_id')->nullable()->after('right_pallet_position_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lead_product_configurations', function (Blueprint $table) {
            $table->dropColumn(['replaced_product_infeed_id', 'replaced_left_pallet_position_id', 'replaced_right_pallet_position_id']);
        });
    }
};
