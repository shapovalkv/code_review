<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVideoCoverIdToMarketplace extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bc_marketplaces', function (Blueprint $table) {
            if (!Schema::hasColumn('bc_marketplaces', 'video_cover_image_id')) {
                $table->bigInteger('video_cover_image_id')->nullable()->after('video_url');
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
        Schema::dropColumns('bc_marketplaces', ['video_cover_image_id']);
    }
}
