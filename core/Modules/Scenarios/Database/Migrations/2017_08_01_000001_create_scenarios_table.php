<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScenariosTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('scenario_if', function(Blueprint $table)
    {
      $table->increments('id');
      $table->integer('sort')->unsigned();
      $table->string('name', 64);
      $table->boolean('active')->default(true);
    });

    Schema::create('scenario_then', function(Blueprint $table)
    {
      $table->increments('id');
      $table->integer('sort')->unsigned();
      $table->string('name', 64);
      $table->boolean('active')->default(true);
    });

    Schema::create('scenarios', function(Blueprint $table)
    {
      $table->bigIncrements('id');
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->bigInteger('funnel_id')->unsigned()->nullable();
      $table->foreign('funnel_id')->references('id')->on('funnels')->onDelete('cascade');
      $table->integer('scenario_if_id')->unsigned()->default(1);
      $table->foreign('scenario_if_id')->references('id')->on('scenario_if')->onDelete('cascade');
      $table->integer('scenario_then_id')->unsigned()->nullable();
      $table->foreign('scenario_then_id')->references('id')->on('scenario_then')->onDelete('cascade');
      $table->boolean('day_of_week_mo')->default(true);
      $table->boolean('day_of_week_tu')->default(true);
      $table->boolean('day_of_week_we')->default(true);
      $table->boolean('day_of_week_th')->default(true);
      $table->boolean('day_of_week_fr')->default(true);
      $table->boolean('day_of_week_sa')->default(true);
      $table->boolean('day_of_week_su')->default(true);
      $table->time('time_start')->nullable();
      $table->time('time_end')->nullable();
      $table->date('date_start')->nullable();
      $table->date('date_end')->nullable();
      $table->integer('frequency')->unsigned()->default(0);
      $table->integer('delay')->unsigned()->default(0);
      $table->text('notification_icon')->nullable();
      $table->string('notification_title', 200)->nullable();
      $table->text('notification_message')->nullable();
      $table->boolean('notification_vibrate')->default(true);
      $table->boolean('notification_sound')->default(true);
      $table->boolean('active')->default(true);
      $table->text('app_image')->nullable();
      $table->text('open_url')->nullable();
      $table->text('show_image')->nullable();
      $table->mediumText('template')->nullable();
      $table->integer('triggers')->unsigned()->default(0);
      $table->json('meta')->nullable();
      $table->timestamps();
    });

    // Creates the beacon_scenario (Many-to-Many relation) table
    Schema::create('beacon_scenario', function($table)
    {
      $table->bigIncrements('id')->unsigned();
      $table->bigInteger('beacon_id')->unsigned();
      $table->bigInteger('scenario_id')->unsigned();
      $table->foreign('beacon_id')->references('id')->on('beacons')->onDelete('cascade');
      $table->foreign('scenario_id')->references('id')->on('scenarios')->onDelete('cascade');
    });

    // Creates the geofence_scenario (Many-to-Many relation) table
    Schema::create('geofence_scenario', function($table)
    {
      $table->bigIncrements('id')->unsigned();
      $table->bigInteger('geofence_id')->unsigned();
      $table->bigInteger('scenario_id')->unsigned();
      $table->foreign('geofence_id')->references('id')->on('geofences')->onDelete('cascade');
      $table->foreign('scenario_id')->references('id')->on('scenarios')->onDelete('cascade');
    });

    Schema::create('interactions', function(Blueprint $table)
    {
      $table->bigIncrements('id');
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->bigInteger('funnel_id')->unsigned()->nullable();
      $table->foreign('funnel_id')->references('id')->on('funnels')->onDelete('cascade');
      $table->bigInteger('scenario_id')->unsigned()->nullable();
      $table->foreign('scenario_id')->references('id')->on('scenarios')->onDelete('set null');
      $table->bigInteger('geofence_id')->unsigned()->nullable();
      $table->foreign('geofence_id')->references('id')->on('geofences')->onDelete('cascade');
      $table->string('geofence', 64)->nullable();
      $table->bigInteger('beacon_id')->unsigned()->nullable();
      $table->foreign('beacon_id')->references('id')->on('beacons')->onDelete('set null');
      $table->string('beacon', 64)->nullable();
      $table->string('state', 16)->nullable();
      $table->uuid('device_uuid');
      $table->decimal('lat', 17, 14)->nullable();
      $table->decimal('lng', 18, 15)->nullable();
      $table->ipAddress('ip')->nullable();
      $table->string('model', 64)->nullable();
      $table->string('platform', 16)->nullable();
      $table->json('segment')->nullable();
      $table->json('extra')->nullable();
      $table->dateTime('created_at')->nullable();
    });

    Schema::create('dwelling_time', function(Blueprint $table)
    {
      $table->bigIncrements('id');
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->bigInteger('funnel_id')->unsigned()->nullable();
      $table->foreign('funnel_id')->references('id')->on('funnels')->onDelete('cascade');
      $table->bigInteger('geofence_id')->unsigned()->nullable();
      $table->foreign('geofence_id')->references('id')->on('geofences')->onDelete('set null');
      $table->string('geofence', 64)->nullable();
      $table->bigInteger('beacon_id')->unsigned()->nullable();
      $table->foreign('beacon_id')->references('id')->on('beacons')->onDelete('set null');
      $table->string('beacon', 64)->nullable();
      $table->string('state', 16)->nullable();
      $table->uuid('device_uuid');
      $table->decimal('lat', 17, 14)->nullable();
      $table->decimal('lng', 18, 15)->nullable();
      $table->ipAddress('ip')->nullable();
      $table->boolean('start')->nullable();
      $table->boolean('end')->nullable();
      $table->integer('dwelling_time')->unsigned()->nullable();
      $table->json('segment')->nullable();
      $table->json('meta')->nullable();
      $table->dateTime('created_at')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('dwelling_time');
    Schema::drop('interactions');
    Schema::drop('geofence_scenario');
    Schema::drop('beacon_scenario');
    Schema::drop('scenarios');
    Schema::drop('scenario_time');
    Schema::drop('scenario_day');
    Schema::drop('scenario_then');
    Schema::drop('scenario_if');

  }
}
