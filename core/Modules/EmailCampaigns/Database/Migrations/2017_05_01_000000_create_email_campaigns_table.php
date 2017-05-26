<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailCampaignsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('email_campaigns', function ($table) {
      $table->bigIncrements('id');
      $table->integer('user_id')->unsigned()->nullable();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->bigInteger('campaign_id')->unsigned()->nullable();
      $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
      $table->string('type', 32)->nullable();
      $table->boolean('active')->default(true);
      $table->string('name', 200)->nullable();
      $table->string('language', 5)->default('en');
      $table->string('timezone', 32)->default('UTC');
      $table->dateTime('last_followup')->nullable();
      $table->dateTime('last_response')->nullable();
      $table->bigInteger('opens')->unsigned()->default(0);
      $table->bigInteger('clicks')->unsigned()->default(0);
      $table->json('meta')->nullable();
      $table->timestamps();
    });

    Schema::create('emails', function ($table) {
      $table->bigIncrements('id');
      $table->bigInteger('parent_id')->unsigned()->nullable();
      $table->bigInteger('_lft')->unsigned()->default(0);
      $table->bigInteger('_rgt')->unsigned()->default(0);
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->bigInteger('email_campaign_id')->unsigned();
      $table->foreign('email_campaign_id')->references('id')->on('email_campaigns')->onDelete('cascade');
      $table->bigInteger('form_id')->unsigned()->nullable();
      $table->foreign('form_id')->references('id')->on('forms')->onDelete('cascade')->nullable();
      $table->string('template', 48)->nullable();
      $table->string('type', 32)->nullable();
      $table->string('local_domain', 64)->nullable();
      $table->tinyInteger('variant')->unsigned()->default(1);
      $table->boolean('active')->default(true);
      $table->string('name', 200)->nullable();
      $table->string('language', 5)->default('en');
      $table->string('timezone', 32)->default('UTC');
      $table->dateTime('last_followup')->nullable();
      $table->dateTime('last_response')->nullable();
      $table->bigInteger('opens')->unsigned()->default(0);
      $table->bigInteger('clicks')->unsigned()->default(0);
      $table->integer('send_after_days')->nullable();
      $table->time('send_time')->nullable();
      $table->boolean('only_send_when_opened')->default(false);
      $table->boolean('only_send_when_clicked')->default(false);
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
    Schema::drop('emails');
    Schema::drop('email_campaigns');
  }
}
