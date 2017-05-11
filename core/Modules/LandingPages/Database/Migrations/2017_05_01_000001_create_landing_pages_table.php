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
      $table->string('category', 32)->nullable();
      $table->string('name', 32);
      $table->string('favicon')->nullable();
      $table->string('local_domain', 64)->nullable();
      $table->string('domain', 200)->nullable();
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
      $table->boolean('show_in_menu')->default(true);
      $table->string('name', 32);
      $table->string('slug', 128)->nullable();
      $table->mediumText('content')->nullable();
      $table->mediumText('content_published')->nullable();
      $table->json('meta')->nullable();
      $table->json('meta_published')->nullable();
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
