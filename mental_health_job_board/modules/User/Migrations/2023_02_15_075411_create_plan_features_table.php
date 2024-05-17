<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bc_plan_features', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('plan_id')->unsigned();
            $table->string('slug');
            $table->string('name');
            $table->string('value')->nullable();
            $table->timestamps();
            $table->softDeletes();
            // Indexes
            $table->unique(['plan_id', 'slug']);
            $table->foreign('plan_id')->references('id')->on('bc_plans')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bc_plan_features');
    }
}
