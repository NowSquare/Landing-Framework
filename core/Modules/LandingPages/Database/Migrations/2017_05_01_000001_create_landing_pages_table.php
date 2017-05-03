<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
      $table->bigInteger('order');
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->boolean('active')->default(true);
      $table->string('name', 128);
      $table->string('favicon')->nullable();
      $table->string('local_domain', 255)->nullable();
      $table->string('domain', 255)->nullable();
      $table->string('language', 5)->default('en');
      $table->string('timezone', 32)->default('UTC');
      $table->text('robots')->nullable();
      $table->json('meta')->nullable();
    });

    Schema::create('landing_pages', function(Blueprint $table)
    {
      $table->bigIncrements('id');
      $table->bigInteger('left');
      $table->bigInteger('right');
      $table->bigInteger('parent');
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->boolean('active')->default(true);
      $table->string('name', 128);
      $table->string('slug', 255)->nullable();
      $table->mediumText('content')->nullable();
      $table->mediumText('content_published')->nullable();
      $table->json('meta')->nullable();
      $table->json('meta_published')->nullable();
    });

    Schema::create('landing_stats', function(Blueprint $table)
    {
      $table->bigIncrements('id');
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->bigInteger('landing_site_id')->unsigned();
      $table->foreign('landing_site_id')->references('id')->on('landing_sites')->onDelete('cascade');
      $table->bigInteger('landing_page_id')->unsigned();
      $table->foreign('landing_page_id')->references('id')->on('landing_pages')->onDelete('cascade');
      $table->string('ip', 40)->nullable();
      $table->uuid('device_uuid')->nullable();
      $table->string('platform', 16)->nullable();
      $table->string('model', 32)->nullable();
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
    Schema::drop('landing_stats');
    Schema::drop('landing_pages');
    Schema::drop('landing_sites');
  }
}
