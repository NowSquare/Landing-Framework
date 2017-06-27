<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertiesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {

		Schema::create('property_types', function(Blueprint $table)
    {
      $table->increments('id');
      $table->string('name')->unique();
    });

		Schema::create('sales_types', function(Blueprint $table)
    {
      $table->increments('id');
      $table->string('name')->unique();
    });

    Schema::create('properties', function(Blueprint $table)
    {
      $table->bigIncrements('id');
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->integer('property_type_id')->nullable();
      $table->integer('property_sales_type_id')->nullable();
      $table->integer('county_id')->nullable();
      $table->integer('city_id')->nullable();

      $table->boolean('active')->default(true);
      $table->string('name', 100);
      $table->text('short_description')->nullable();
      $table->mediumText('full_description')->nullable();
      $table->integer('price')->unsigned();
      $table->datetime('last_price_change')->nullable();
      $table->integer('previous_price')->nullable()->unsigned();

      $table->string('construction_type', 16); // resale / new
      $table->string('address_1')->nullable();
      $table->string('address_2')->nullable();
      $table->string('address_3')->nullable();
      $table->string('zip')->nullable();
      $table->string('city')->nullable();
      $table->integer('built')->unsigned()->nullable();
      $table->integer('sq_ft')->unsigned()->nullable();
      $table->integer('sq_ft_plot')->unsigned()->nullable();
      $table->boolean('new')->default(0); // resale / new
      $table->boolean('business')->default(0);
      $table->tinyInteger('floor')->unsigned()->nullable();
      $table->tinyInteger('floors')->unsigned()->nullable();
      $table->tinyInteger('rooms')->unsigned()->nullable();
      $table->tinyInteger('beds')->nullable();
      $table->tinyInteger('baths')->nullable();
      $table->tinyInteger('car_spaces')->nullable();

      $table->boolean('pet_allowed')->default(0);
      $table->boolean('dishwasher')->default(0);
      $table->boolean('furnished')->default(0);
      $table->boolean('sold')->default(0);
      $table->datetime('sold_at')->nullable();
      $table->text('ext_url')->nullable();

      // Reference photo
      $table->string('photo_file_name')->nullable();
      $table->integer('photo_file_size')->nullable();
      $table->string('photo_content_type')->nullable();
      $table->timestamp('photo_updated_at')->nullable();

      $table->json('meta')->nullable();

      // Beacon interactions
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

      /**
       * Enter region
       */

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
       */

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
       */

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
       */

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
       */

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

      $table->tinyInteger('zoom')->nullable();
      $table->decimal('lat', 17, 14)->nullable();
      $table->decimal('lng', 18, 15)->nullable();

      $table->timestamps();
    });

    // Add lat/lng
    DB::statement('ALTER TABLE properties ADD location POINT' );

    Schema::create('property_surroundings', function(Blueprint $table)
    {
      $table->increments('id');
      $table->string('name', 32)->unique();
    });

    // Many-to-many relation
    Schema::create('property_surrounding', function(Blueprint $table)
    {
      $table->bigIncrements('id');
      $table->bigInteger('property_id')->unsigned();
      $table->integer('property_surrounding_id')->unsigned();
      $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
      $table->foreign('property_surrounding_id')->references('id')->on('property_surroundings')->onDelete('cascade');
    });

    Schema::create('property_features', function(Blueprint $table)
    {
      $table->increments('id');
      $table->string('name', 32)->unique();
    });

    // Many-to-many relation
    Schema::create('property_feature', function(Blueprint $table)
    {
      $table->bigIncrements('id');
      $table->bigInteger('property_id')->unsigned();
      $table->integer('property_feature_id')->unsigned();
      $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
      $table->foreign('property_feature_id')->references('id')->on('property_features')->onDelete('cascade');
    });

    Schema::create('property_garages', function(Blueprint $table)
    {
      $table->increments('id');
      $table->string('name', 32)->unique();
    });

    // Many-to-many relation
    Schema::create('property_garage', function(Blueprint $table)
    {
      $table->bigIncrements('id');
      $table->bigInteger('property_id')->unsigned();
      $table->integer('property_garage_id')->unsigned();
      $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
      $table->foreign('property_garage_id')->references('id')->on('property_garages')->onDelete('cascade');
    });

    Schema::create('property_photos', function(Blueprint $table)
    {
      $table->bigIncrements('id');
      $table->bigInteger('order');
      $table->integer('property_id')->unsigned();
      $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
      $table->string('name', 100)->nullable();

      // Photo
      $table->string('photo_file_name')->nullable();
      $table->integer('photo_file_size')->nullable();
      $table->string('photo_content_type')->nullable();
      $table->timestamp('photo_updated_at')->nullable();

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
    Schema::drop('property_photos');
    Schema::drop('properties');
    Schema::drop('sales_types');
    Schema::drop('house_types');
  }
}
