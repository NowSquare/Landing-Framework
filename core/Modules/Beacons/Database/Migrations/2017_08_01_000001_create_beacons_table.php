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
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('beacons');
    Schema::drop('beacon_uuids');

  }
}
