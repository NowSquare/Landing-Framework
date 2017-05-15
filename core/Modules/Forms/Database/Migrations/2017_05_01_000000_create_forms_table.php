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
      $table->bigInteger('order');
      $table->integer('user_id')->unsigned()->nullable();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->boolean('active')->default(true);
      $table->string('category', 32)->nullable();
      $table->string('name', 48)->nullable();
      $table->string('language', 5)->default('en');
      $table->string('timezone', 32)->default('UTC');
      $table->dateTime('last_followup')->nullable();
      $table->dateTime('last_response')->nullable();
      $table->json('meta')->nullable();
      $table->json('meta_published')->nullable();
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
