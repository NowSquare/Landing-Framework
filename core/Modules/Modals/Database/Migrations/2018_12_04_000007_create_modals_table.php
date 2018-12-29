<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModalsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('modals', function($table)
    {
      $table->bigIncrements('id');
      $table->integer('user_id')->unsigned()->nullable();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
      $table->bigInteger('funnel_id')->unsigned()->nullable();
      $table->foreign('funnel_id')->references('id')->on('funnels')->onDelete('cascade');
      $table->string('language', 5)->default('en');
      $table->string('timezone', 32)->default('UTC');
      $table->tinyInteger('variant')->unsigned()->default(1);
      $table->string('name', 200)->nullable();
      $table->string('local_domain', 64)->nullable();
      $table->string('domain', 200)->nullable();
      $table->boolean('active')->default(true);
      $table->dateTime('active_start')->nullable();
      $table->dateTime('active_end')->nullable();
      $table->json('active_week_days')->nullable();

      $table->text('url')->nullable();
      $table->mediumText('content')->nullable();

      $table->json('hosts')->nullable();
      $table->json('paths')->nullable();
      $table->json('referrer_hosts')->nullable();
      $table->json('referrer_paths')->nullable();
      $table->json('settings')->nullable();

      $table->integer('views')->unsigned()->default(0);
      $table->integer('conversions')->unsigned()->default(0);
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
    Schema::drop('modals');
  }
}
