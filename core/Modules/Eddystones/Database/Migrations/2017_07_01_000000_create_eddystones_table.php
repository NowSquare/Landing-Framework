<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEddystonesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    /*
    Schema::create('eddystones', function(Blueprint $table)
    {
      $table->bigIncrements('id');
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->bigInteger('funnel_id')->unsigned()->nullable();
      $table->foreign('funnel_id')->references('id')->on('funnels')->onDelete('cascade');
      $table->string('status', 16);
      $table->string('description', 200);
      $table->string('namespace', 32);
      $table->string('instance', 16);
      $table->integer('hits')->unsigned()->default(0);
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

    Schema::create('eddystone_attachments', function(Blueprint $table)
    {
      $table->bigIncrements('id');
      $table->bigInteger('eddystone_id')->unsigned();
      $table->foreign('eddystone_id')->references('id')->on('eddystones')->onDelete('cascade');
      $table->char('language', 2);
      $table->string('text', 50);
      $table->string('type', 16)->nullable();
      $table->string('url', 250);
      $table->json('data')->nullable();
      $table->timestamps();
    });
    */
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    //Schema::drop('eddystone_attachments');
    //Schema::drop('eddystones');
  }
}
