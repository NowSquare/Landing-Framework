<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShortUrlsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('short_urls', function($table)
    {
      $table->bigIncrements('id');
      $table->integer('user_id')->unsigned()->nullable();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
      $table->text('url');
      $table->string('short_url', 128)->unique();
      $table->integer('hits')->unsigned()->default(0);
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
    Schema::drop('short_urls');
  }
}
