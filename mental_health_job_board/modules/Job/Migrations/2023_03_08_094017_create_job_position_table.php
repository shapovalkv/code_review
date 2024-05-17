<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobPositionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bc_job_positions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',255)->nullable();
            $table->text('description')->nullable();
            $table->string('slug',255)->nullable();
            $table->string('status',50)->nullable();
            $table->bigInteger('origin_id')->nullable();

            $table->nestedSet();

            $table->integer('create_user')->nullable();
            $table->integer('update_user')->nullable();
            $table->softDeletes();

            $table->timestamps();
        });

        Schema::create('bc_job_position_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('origin_id')->unsigned();
            $table->string('locale')->index();

            $table->string('name',255)->nullable();
            $table->text('content')->nullable();

            $table->integer('create_user')->nullable();
            $table->integer('update_user')->nullable();
            $table->timestamps();
        });

        Schema::table('bc_jobs', function (Blueprint $table) {
            if (Schema::hasColumn('bc_jobs', 'w2_california')) {
                $table->renameColumn('w2_california', 'position_id');
            }
            if (Schema::hasColumn('bc_jobs', 'position_id')) {
                $table->integer('position_id')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bc_job_positions');
        Schema::dropIfExists('bc_job_positions_translations');
        Schema::dropColumns('bc_jobs', ['w2_california']);

        Schema::table('bc_jobs', function (Blueprint $table) {
            if (Schema::hasColumn('bc_jobs', 'position_id')) {
                $table->renameColumn('position_id', 'w2_california');
            }
            if (Schema::hasColumn('bc_jobs', 'w2_california')) {
                $table->boolean('w2_california')->nullable()->change();
            }
        });
    }
}

