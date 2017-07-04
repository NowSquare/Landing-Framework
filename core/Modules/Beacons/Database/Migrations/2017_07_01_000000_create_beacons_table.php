<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBeaconsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
/*
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

    Schema::create('scenario_day', function(Blueprint $table)
    {
      $table->increments('id');
      $table->integer('sort')->unsigned();
      $table->string('name', 64);
      $table->boolean('active')->default(true);
    });

    Schema::create('scenario_time', function(Blueprint $table)
    {
      $table->increments('id');
      $table->integer('sort')->unsigned();
      $table->string('name', 64);
      $table->boolean('active')->default(true);
    });
* /
    Schema::create('location_groups', function($table)
    {
      $table->bigIncrements('id')->unsigned();
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->string('name', 64);
      $table->json('settings')->nullable();
    });
/*
    Schema::create('scenarios', function(Blueprint $table)
    {
      $table->bigIncrements('id');
      $table->bigInteger('funnel_id')->unsigned()->nullable();
      $table->foreign('funnel_id')->references('id')->on('funnels')->onDelete('cascade');
      $table->integer('scenario_if_id')->unsigned()->default(1);
      $table->foreign('scenario_if_id')->references('id')->on('scenario_if')->onDelete('cascade');
      $table->integer('scenario_then_id')->unsigned()->nullable();
      $table->foreign('scenario_then_id')->references('id')->on('scenario_then')->onDelete('cascade');
      $table->integer('scenario_day_id')->unsigned()->default(1);
      $table->foreign('scenario_day_id')->references('id')->on('scenario_day')->onDelete('cascade');
      $table->integer('scenario_time_id')->unsigned()->default(1);
      $table->foreign('scenario_time_id')->references('id')->on('scenario_time')->onDelete('cascade');  
      $table->time('time_start')->nullable();
      $table->time('time_end')->nullable();
      $table->date('date_start')->nullable();
      $table->date('date_end')->nullable();
      $table->integer('frequency')->unsigned()->default(0);
      $table->integer('delay')->unsigned()->default(0);
      $table->text('notification')->nullable();
      $table->boolean('active')->default(true);
      $table->text('open_url')->nullable();
      $table->integer('triggers')->unsigned()->default(0);
      $table->json('meta')->nullable();

      // Image
      $table->string('image_file_name')->nullable();
      $table->integer('image_file_size')->nullable();
      $table->string('image_content_type')->nullable();
      $table->timestamp('image_updated_at')->nullable();

      $table->timestamps();
    });
* /
    Schema::create('beacon_uuids', function(Blueprint $table)
    {
      $table->increments('id');
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->uuid('uuid');
    });

    Schema::create('beacons', function(Blueprint $table)
    {
      $table->bigIncrements('id');
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->bigInteger('funnel_id')->unsigned()->nullable();
      $table->foreign('funnel_id')->references('id')->on('funnels')->onDelete('cascade');
      $table->bigInteger('location_group_id')->unsigned()->nullable();
      $table->foreign('location_group_id')->references('id')->on('location_groups');
      $table->boolean('active')->default(true);
      $table->string('name', 64);
      $table->text('description')->nullable();
      $table->uuid('uuid')->nullable();
      $table->bigInteger('major')->nullable()->unsigned();
      $table->bigInteger('minor')->nullable()->unsigned();
      $table->integer('hits')->unsigned()->default(0);
      $table->integer('monitor_hits')->unsigned()->default(0);
      $table->integer('monitor_enter_hits')->unsigned()->default(0);
      $table->integer('monitor_exit_hits')->unsigned()->default(0);
      $table->integer('range_hits')->unsigned()->default(0);
      $table->integer('triggers')->unsigned()->default(0);
      $table->integer('monitor_triggers')->unsigned()->default(0);
      $table->integer('range_triggers')->unsigned()->default(0);
      $table->json('meta')->nullable();

      // Interactions

      /**
       * Enter region
       * /

      // Enter region interaction
      $table->bigInteger('monitor_enter_region_id')->unsigned()->nullable();
      $table->string('monitor_enter_region_table')->nullable();
      $table->text('monitor_enter_region_notification_title')->nullable();
      $table->string('monitor_enter_region_notification_icon', 250)->nullable();
      $table->text('monitor_enter_region_notification')->nullable();

      // Enter region timing
      $table->integer('monitor_enter_frequency')->unsigned()->default(0);
      $table->integer('monitor_enter_delay')->unsigned()->default(0);
      $table->integer('monitor_enter_stop_interactions_after_n_triggers')->unsigned()->default(0);
      $table->time('monitor_enter_region_time_start')->nullable();
      $table->time('monitor_enter_region_time_end')->nullable();
      $table->date('monitor_enter_region_date_start')->nullable();
      $table->date('monitor_enter_region_date_end')->nullable();

      /**
       * Exit region
       * /

      // Exit region interaction
      $table->bigInteger('monitor_exit_region_id')->unsigned()->nullable();
      $table->string('monitor_exit_region_table')->nullable();
      $table->text('monitor_exit_region_notification_title')->nullable();
      $table->string('monitor_exit_region_notification_icon', 250)->nullable();
      $table->text('monitor_exit_region_notification')->nullable();

      // Exit region timing
      $table->integer('monitor_exit_frequency')->unsigned()->default(0);
      $table->integer('monitor_exit_delay')->unsigned()->default(0);
      $table->integer('monitor_exit_stop_interactions_after_n_triggers')->unsigned()->default(0);
      $table->time('monitor_exit_region_time_start')->nullable();
      $table->time('monitor_exit_region_time_end')->nullable();
      $table->date('monitor_exit_region_date_start')->nullable();
      $table->date('monitor_exit_region_date_end')->nullable();

      /**
       * Range immediate
       * /

      // Range immediate interaction
      $table->bigInteger('range_immediate_id')->unsigned()->nullable();
      $table->string('range_immediate_table')->nullable();

      // Range immediate timing
      $table->integer('range_immediate_frequency')->unsigned()->default(0);
      $table->integer('range_immediate_delay')->unsigned()->default(0);
      $table->integer('range_immediate_stop_interactions_after_n_triggers')->unsigned()->default(0);
      $table->time('range_immediate_region_time_start')->nullable();
      $table->time('range_immediate_region_time_end')->nullable();
      $table->date('range_immediate_region_date_start')->nullable();
      $table->date('range_immediate_region_date_end')->nullable();

      /**
       * Range near
       * /

      // Range near interaction
      $table->bigInteger('range_near_id')->unsigned()->nullable();
      $table->string('range_near_table')->nullable();

      // Range near timing
      $table->integer('range_near_frequency')->unsigned()->default(0);
      $table->integer('range_near_delay')->unsigned()->default(0);
      $table->integer('range_near_stop_interactions_after_n_triggers')->unsigned()->default(0);
      $table->time('range_near_region_time_start')->nullable();
      $table->time('range_near_region_time_end')->nullable();
      $table->date('range_near_region_date_start')->nullable();
      $table->date('range_near_region_date_end')->nullable();

      /**
       * Range far
       * /

      // Range far interaction

      $table->bigInteger('range_far_id')->unsigned()->nullable();
      $table->string('range_far_table')->nullable();

      // Range far timing
      $table->integer('range_far_frequency')->unsigned()->default(0);
      $table->integer('range_far_delay')->unsigned()->default(0);
      $table->integer('range_far_stop_interactions_after_n_triggers')->unsigned()->default(0);
      $table->time('range_far_region_time_start')->nullable();
      $table->time('range_far_region_time_end')->nullable();
      $table->date('range_far_region_date_start')->nullable();
      $table->date('range_far_region_date_end')->nullable();


      // Generic timing (interaction specific scenarios override these)
      $table->integer('frequency')->unsigned()->default(0);
      $table->integer('delay')->unsigned()->default(0);
      $table->integer('stop_interactions_after_n_triggers')->unsigned()->default(0);
      $table->time('time_start')->nullable();
      $table->time('time_end')->nullable();
      $table->date('date_start')->nullable();
      $table->date('date_end')->nullable();

      // Reference photo
      $table->string('photo_file_name')->nullable();
      $table->integer('photo_file_size')->nullable();
      $table->string('photo_content_type')->nullable();
      $table->timestamp('photo_updated_at')->nullable();

      $table->tinyInteger('zoom')->nullable();
      $table->decimal('lat', 17, 14)->nullable();
      $table->decimal('lng', 18, 15)->nullable();

      $table->timestamps();
    });

    // Add lat/lng
    DB::statement('ALTER TABLE beacons ADD location POINT' );

    // Creates the beacon_scenario (Many-to-Many relation) table
    /*
    Schema::create('beacon_scenario', function($table)
    {
      $table->bigIncrements('id')->unsigned();
      $table->bigInteger('beacon_id')->unsigned();
      $table->bigInteger('scenario_id')->unsigned();
      $table->foreign('beacon_id')->references('id')->on('beacons')->onDelete('cascade');
      $table->foreign('scenario_id')->references('id')->on('scenarios')->onDelete('cascade');
    });
    * /
    Schema::create('beacon_interactions', function(Blueprint $table)
    {
      $table->bigIncrements('id');
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->bigInteger('funnel_id')->unsigned()->nullable();
      $table->foreign('funnel_id')->references('id')->on('funnels')->onDelete('cascade');
      //$table->bigInteger('scenario_id')->unsigned()->nullable();
      //$table->foreign('scenario_id')->references('id')->on('scenarios')->onDelete('set null');
      $table->bigInteger('beacon_id')->unsigned();
      $table->foreign('beacon_id')->references('id')->on('beacons')->onDelete('cascade');
      $table->string('beacon', 64)->nullable();
      $table->string('state', 32)->nullable();
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

    Schema::create('beacon_dwelling_time', function(Blueprint $table)
    {
      $table->bigIncrements('id');
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->bigInteger('funnel_id')->unsigned()->nullable();
      $table->foreign('funnel_id')->references('id')->on('funnels')->onDelete('cascade');
      $table->bigInteger('beacon_id')->unsigned()->nullable();
      $table->foreign('beacon_id')->references('id')->on('beacons')->onDelete('set null');
      $table->string('beacon', 64)->nullable();
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

    Schema::create('beacon_visits', function(Blueprint $table)
    {
      $table->bigIncrements('id');
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->bigInteger('funnel_id')->unsigned()->nullable();
      $table->foreign('funnel_id')->references('id')->on('funnels')->onDelete('cascade');
      $table->uuid('device_uuid');
      $table->ipAddress('ip')->nullable();
      $table->string('model', 64)->nullable();
      $table->string('platform', 16)->nullable();
      $table->dateTime('created_at')->nullable();
    });
    *///
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    /*
    Schema::drop('beacon_visits');
    Schema::drop('beacon_dwelling_time');
    Schema::drop('beacon_interactions');
    Schema::drop('beacon_scenario');
    Schema::drop('beacons');
    Schema::drop('beacon_uuids');
    Schema::drop('scenarios');
    Schema::drop('location_groups');
    Schema::drop('scenario_time');
    Schema::drop('scenario_day');
    Schema::drop('scenario_then');
    Schema::drop('scenario_if');
    */
  }
}
