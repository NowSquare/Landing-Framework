<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('forms', function ($table) {
      $table->bigIncrements('id');
      $table->integer('user_id')->unsigned()->nullable();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->bigInteger('funnel_id')->unsigned()->nullable();
      $table->foreign('funnel_id')->references('id')->on('funnels')->onDelete('cascade');
      $table->tinyInteger('variant')->unsigned()->default(1);
      $table->boolean('active')->default(true);
      $table->string('name', 200)->nullable();
      $table->string('local_domain', 64)->nullable();
      $table->string('domain', 200)->nullable();
      $table->string('language', 5)->default('en');
      $table->string('timezone', 32)->default('UTC');
      $table->dateTime('last_followup')->nullable();
      $table->dateTime('last_response')->nullable();
      $table->bigInteger('visits')->unsigned()->default(0);
      $table->bigInteger('entries')->unsigned()->default(0);
      $table->integer('opens')->unsigned()->default(0);
      $table->integer('clicks')->unsigned()->default(0);
      $table->integer('drops')->unsigned()->default(0);
      $table->integer('bounces')->unsigned()->default(0);
      $table->integer('sent')->unsigned()->default(0);
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
    Schema::drop('forms');
  }
}
