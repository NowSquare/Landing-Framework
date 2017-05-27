<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFunnelsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('funnels', function($table)
    {
      $table->bigIncrements('id');
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->string('name', 64);
      $table->text('description')->nullable();
      $table->string('api_token', 60)->nullable()->unique();
      $table->dateTime('date_start')->nullable();
      $table->dateTime('date_end')->nullable();
      $table->string('language', 5)->nullable();
      $table->string('timezone', 32)->nullable();
      $table->boolean('active')->default(true);
      $table->json('segment')->nullable();
      $table->json('settings')->nullable();
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
    Schema::drop('funnels');
  }
}
