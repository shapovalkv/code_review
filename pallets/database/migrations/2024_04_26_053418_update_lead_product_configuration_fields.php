<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lead_product_configurations', function (Blueprint $table) {
            $table->float('product_length', 100, 2)->nullable()->change();
            $table->float('product_width', 100, 2)->nullable()->change();
            $table->float('product_weight', 100, 2)->nullable()->change();
            $table->float('pallet_length', 100, 2)->nullable()->change();
            $table->float('pallet_width', 100, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lead_product_configurations', function (Blueprint $table) {
            $table->integer('product_length')->nullable()->change();
            $table->integer('product_width')->nullable()->change();
            $table->integer('product_weight')->nullable()->change();
            $table->integer('pallet_length')->nullable()->change();
            $table->integer('pallet_width')->nullable()->change();
        });
    }
};
