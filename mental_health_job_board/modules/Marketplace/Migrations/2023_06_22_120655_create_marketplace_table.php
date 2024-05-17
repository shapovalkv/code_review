<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarketplaceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bc_marketplaces', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 255)->nullable();
            $table->string('slug')->charset('utf8')->unique();
            $table->text('content')->nullable();
            $table->date('announcement_date')->nullable();
            $table->integer('image_id')->nullable();
            $table->integer('banner_image_id')->nullable();
            $table->tinyInteger('is_featured')->nullable();
            $table->string('gallery', 255)->nullable();
            $table->string('video_url', 255)->nullable();
            $table->string('map_lng', 30)->nullable();
            $table->string('map_lat', 20)->nullable();
            $table->integer('map_zoom')->nullable();
            $table->bigInteger('location_id')->nullable();

            $table->bigInteger('cat_id')->nullable();

            $table->string('status', 30)->nullable();
            $table->string('announcement_status', 30)->nullable();
            $table->string('website', 255)->nullable();

            $table->bigInteger('company_id')->nullable();
            $table->bigInteger('create_user')->nullable();
            $table->bigInteger('update_user')->nullable();
            $table->dateTime('expiration_date')->nullable();

            $table->bigInteger('author_id')->nullable();

            $table->index(['status', 'author_id']);

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('bc_marketplace_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('origin_id')->unsigned();
            $table->string('locale')->index();
            $table->string('title', 255)->nullable();
            $table->text('content')->nullable();
            $table->text('packages')->nullable();
            $table->text('package_compare')->nullable();

            $table->text('faqs')->nullable();
            $table->text('requirements')->nullable();

            $table->bigInteger('create_user')->nullable();
            $table->bigInteger('update_user')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('bc_marketplace_tags', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('tag_id')->nullable();
            $table->integer('target_id')->nullable();

            $table->bigInteger('create_user')->nullable();
            $table->bigInteger('update_user')->nullable();
            $table->timestamps();
        });

        Schema::create('bc_marketplace_cat', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name', 255)->nullable();
            $table->text('content')->nullable();
            $table->string('slug')->nullable()->unique();
            $table->string('status', 50)->nullable();
            $table->bigInteger('image_id')->nullable();
            $table->text('faqs')->nullable();
            $table->bigInteger('news_cat_id')->nullable();
            $table->nestedSet();

            $table->integer('create_user')->nullable();
            $table->integer('update_user')->nullable();
            $table->softDeletes();

            $table->timestamps();
        });
        Schema::create('bc_marketplace_cat_types', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name', 255)->nullable();
            $table->text('content')->nullable();
            $table->string('slug')->nullable()->unique();
            $table->string('status', 50)->nullable();
            $table->bigInteger('image_id')->nullable();
            $table->bigInteger('cat_id')->nullable();
            $table->text('cat_children')->nullable();
            $table->nestedSet();

            $table->integer('create_user')->nullable();
            $table->integer('update_user')->nullable();
            $table->softDeletes();

            $table->timestamps();
        });

        Schema::create('bc_marketplace_cat_trans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('origin_id')->nullable();
            $table->string('locale', 10)->nullable();

            $table->string('name', 255)->nullable();
            $table->text('content')->nullable();

            $table->integer('create_user')->nullable();
            $table->integer('update_user')->nullable();
            $table->unique(['origin_id', 'locale']);
            $table->timestamps();
        });

        Schema::create('bc_marketplace_cat_type_trans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('origin_id')->nullable();
            $table->string('locale', 10)->nullable();

            $table->string('name', 255)->nullable();
            $table->text('content')->nullable();

            $table->integer('create_user')->nullable();
            $table->integer('update_user')->nullable();
            $table->unique(['origin_id', 'locale']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *9*
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bc_marketplaces');
        Schema::dropIfExists('bc_marketplace_translations');
        Schema::dropIfExists('bc_marketplace_term');
        Schema::dropIfExists('bc_marketplace_tags');
        Schema::dropIfExists('bc_marketplace_cat');
        Schema::dropIfExists('bc_marketplace_cat_trans');
        Schema::dropIfExists('bc_marketplace_cat_types');
        Schema::dropIfExists('bc_marketplace_cat_type_trans');
    }
}
