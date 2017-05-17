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
      $table->bigInteger('campaign_id')->unsigned()->nullable();
      $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
      $table->boolean('active')->default(true);
      $table->string('name', 200);
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
      $table->string('name', 200);
      $table->string('template', 48)->nullable();
      $table->string('type', 32)->nullable();
      $table->string('slug', 128)->nullable();
      $table->bigInteger('visits')->unsigned()->default(0);
      $table->bigInteger('conversions')->unsigned()->default(0);
      $table->json('meta')->nullable();
      $table->timestamps();
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
