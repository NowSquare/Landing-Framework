<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Kalnoy\Nestedset\NestedSet;

class CreateLandingPagesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('landing_sites', function(Blueprint $table)
    {
      $table->bigIncrements('id');
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->bigInteger('funnel_id')->unsigned()->nullable();
      $table->foreign('funnel_id')->references('id')->on('funnels')->onDelete('cascade');
      $table->boolean('active')->default(true);
      $table->string('name', 64);
      $table->string('local_domain', 64)->nullable();
      $table->string('domain', 200)->nullable();
      $table->string('language', 5)->default('en');
      $table->string('timezone', 32)->default('UTC');
      $table->bigInteger('visits')->unsigned()->default(0);
      $table->bigInteger('conversions')->unsigned()->default(0);
      $table->json('meta')->nullable();
      $table->timestamps();
    });

    Schema::create('landing_pages', function(Blueprint $table)
    {
      $table->bigIncrements('id');
      $table->bigInteger('parent_id')->unsigned()->nullable();
      $table->bigInteger('_lft')->unsigned()->default(0);
      $table->bigInteger('_rgt')->unsigned()->default(0);
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->bigInteger('landing_site_id')->unsigned();
      $table->foreign('landing_site_id')->references('id')->on('landing_sites')->onDelete('cascade');
      $table->tinyInteger('variant')->unsigned()->default(1);
      $table->boolean('show_in_menu')->default(true);
      $table->string('name', 64);
      $table->string('template', 48)->nullable();
      $table->string('slug', 128)->nullable();
      $table->bigInteger('visits')->unsigned()->default(0);
      $table->bigInteger('conversions')->unsigned()->default(0);
      $table->json('meta')->nullable();
      $table->timestamps();
    });

    Schema::create('landing_stats', function(Blueprint $table) {
      $table->bigIncrements('id');
      $table->bigInteger('landing_site_id')->unsigned();
      $table->foreign('landing_site_id')->references('id')->on('landing_sites')->onDelete('cascade');
      $table->bigInteger('landing_page_id')->unsigned();
      $table->foreign('landing_page_id')->references('id')->on('landing_pages')->onDelete('cascade');
      $table->char('fingerprint', 32)->nullable();
      $table->bigInteger('views')->unsigned()->default(1);
      $table->boolean('is_bot')->default(false);
      $table->string('ip', 40)->nullable();
      $table->string('language', 5)->nullable();
      $table->string('client_type', 32)->nullable();
      $table->string('client_name', 32)->nullable();
      $table->string('client_version', 32)->nullable();
      $table->string('os_name', 32)->nullable();
      $table->string('os_version', 32)->nullable();
      $table->string('os_platform', 32)->nullable();
      $table->string('device', 12)->nullable();
      $table->string('brand', 32)->nullable();
      $table->string('model', 32)->nullable();
      $table->string('bot_name', 32)->nullable();
      $table->string('bot_category', 32)->nullable();
      $table->string('bot_url', 200)->nullable();
      $table->string('bot_producer_name', 48)->nullable();
      $table->string('bot_producer_url', 128)->nullable();
      $table->decimal('lat', 10, 8)->nullable();
      $table->decimal('lng', 11, 8)->nullable();
      $table->json('meta')->nullable();
      $table->dateTime('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('landing_pages');
    Schema::drop('landing_sites');
  }
}
