<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGallaryToCampanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bc_companies', function (Blueprint $table) {
            if (!Schema::hasColumn('bc_companies', 'gallery')) {
                $table->string('gallery', 255)->nullable();
            }
            if (!Schema::hasColumn('bc_companies', 'video_url')) {
                $table->string('video_url', 255)->nullable();
            }
            if (!Schema::hasColumn('bc_companies', 'video_cover_id')) {
                $table->bigInteger('video_cover_id')->nullable();
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
        Schema::dropColumns('bc_companies', ['video_url', 'gallery', 'video_cover_id']);
    }
}
