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
      $table->integer('user_id')->unsigned()->nullable();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->bigInteger('funnel_id')->unsigned()->nullable();
      $table->foreign('funnel_id')->references('id')->on('funnels')->onDelete('cascade');
      $table->tinyInteger('variant')->unsigned()->default(1);
      $table->boolean('active')->default(true);
      $table->string('name', 200)->nullable();
      $table->string('local_domain', 64)->nullable();
      $table->string('domain', 200)->nullable();
      $table->string('language', 5)->default('en');
      $table->string('timezone', 32)->default('UTC');
      $table->dateTime('last_followup')->nullable();
      $table->dateTime('last_response')->nullable();
      $table->bigInteger('visits')->unsigned()->default(0);
      $table->bigInteger('entries')->unsigned()->default(0);
      $table->integer('opens')->unsigned()->default(0);
      $table->integer('clicks')->unsigned()->default(0);
      $table->integer('drops')->unsigned()->default(0);
      $table->integer('bounces')->unsigned()->default(0);
      $table->integer('sent')->unsigned()->default(0);
      $table->json('meta')->nullable();
      $table->timestamps();
    });

    Schema::create('form_stats', function(Blueprint $table) {
      $table->bigIncrements('id');
      $table->bigInteger('form_id')->unsigned();
      $table->foreign('form_id')->references('id')->on('forms')->onDelete('cascade');
      $table->bigInteger('landing_site_id')->unsigned()->nullable();
      $table->foreign('landing_site_id')->references('id')->on('landing_sites')->onDelete('SET NULL');
      $table->bigInteger('landing_page_id')->unsigned()->nullable();
      $table->foreign('landing_page_id')->references('id')->on('landing_pages')->onDelete('SET NULL');
      $table->char('fingerprint', 32)->nullable();
      $table->bigInteger('views')->unsigned()->default(1);
      $table->boolean('is_bot')->default(false);
      $table->string('ip', 40)->nullable();
      $table->string('language', 5)->nullable();
      $table->string('client_type', 32)->nullable();
      $table->string('client_name', 32)->nullable();
      $table->string('client_version', 32)->nullable();
      $table->string('os_name', 32)->nullable();
      $table->string('os_version', 32)->nullable();
      $table->string('os_platform', 32)->nullable();
      $table->string('device', 12)->nullable();
      $table->string('brand', 32)->nullable();
      $table->string('model', 32)->nullable();
      $table->string('bot_name', 32)->nullable();
      $table->string('bot_category', 32)->nullable();
      $table->string('bot_url', 200)->nullable();
      $table->string('bot_producer_name', 48)->nullable();
      $table->string('bot_producer_url', 128)->nullable();
      $table->decimal('lat', 10, 8)->nullable();
      $table->decimal('lng', 11, 8)->nullable();
      $table->json('meta')->nullable();
      $table->dateTime('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
    });

    Schema::create('form_entries', function(Blueprint $table) {
      $table->bigIncrements('id');
      $table->bigInteger('form_id')->unsigned();
      $table->foreign('form_id')->references('id')->on('forms')->onDelete('cascade');
      $table->bigInteger('landing_site_id')->unsigned()->nullable();
      $table->foreign('landing_site_id')->references('id')->on('landing_sites')->onDelete('SET NULL');
      $table->bigInteger('landing_page_id')->unsigned()->nullable();
      $table->foreign('landing_page_id')->references('id')->on('landing_pages')->onDelete('SET NULL');
      $table->boolean('confirmed')->default(false);
      $table->dateTime('confirmed_at')->nullable();
      $table->dateTime('unsubscribed_at')->nullable();
      $table->boolean('delivered')->default(false);
      $table->boolean('dropped')->default(false);
      $table->boolean('bounced')->default(false);
      $table->integer('clicks')->unsigned()->default(0);
      $table->integer('opens')->unsigned()->default(0);
      $table->integer('sent')->unsigned()->default(0);
      $table->dateTime('last_open')->nullable();
      $table->dateTime('last_click')->nullable();
      $table->dateTime('last_send')->nullable();
      $table->char('fingerprint', 32)->nullable();
      $table->string('ip', 40)->nullable();
      $table->string('language', 5)->nullable();
      $table->string('client_type', 32)->nullable();
      $table->string('client_name', 32)->nullable();
      $table->string('client_version', 32)->nullable();
      $table->string('os_name', 32)->nullable();
      $table->string('os_version', 32)->nullable();
      $table->string('os_platform', 32)->nullable();
      $table->string('device', 12)->nullable();
      $table->string('brand', 32)->nullable();
      $table->string('model', 32)->nullable();
      $table->decimal('lat', 10, 8)->nullable();
      $table->decimal('lng', 11, 8)->nullable();

      $table->string('email', 96);
      $table->string('personal_first_name', 250)->nullable();
      $table->string('personal_last_name', 250)->nullable();
      $table->string('personal_name', 250)->nullable();
      $table->tinyInteger('personal_gender')->unsigned()->nullable();
      $table->tinyInteger('personal_title')->unsigned()->nullable();
      $table->string('personal_impressum', 250)->nullable();
      $table->date('personal_birthday')->nullable();
      $table->string('personal_website', 250)->nullable();
      $table->string('personal_address1', 250)->nullable();
      $table->string('personal_address2', 250)->nullable();
      $table->string('personal_street', 250)->nullable();
      $table->string('personal_house_number', 15)->nullable();
      $table->string('personal_phone', 20)->nullable();
      $table->string('personal_mobile', 20)->nullable();
      $table->string('personal_fax', 20)->nullable();
      $table->string('personal_postal', 20)->nullable();
      $table->string('personal_city', 64)->nullable();
      $table->string('personal_state', 64)->nullable();
      $table->string('personal_country', 64)->nullable();
      $table->string('business_company', 64)->nullable();
      $table->string('business_job_title', 32)->nullable();
      $table->string('business_website', 250)->nullable();
      $table->string('business_email', 96)->nullable();
      $table->string('business_address1', 250)->nullable();
      $table->string('business_address2', 250)->nullable();
      $table->string('business_street', 250)->nullable();
      $table->string('business_house_number', 15)->nullable();
      $table->string('business_phone', 20)->nullable();
      $table->string('business_mobile', 20)->nullable();
      $table->string('business_fax', 20)->nullable();
      $table->string('business_postal', 20)->nullable();
      $table->string('business_city', 64)->nullable();
      $table->string('business_state', 64)->nullable();
      $table->string('business_country', 64)->nullable();
      $table->date('booking_date')->nullable();
      $table->date('booking_start_date')->nullable();
      $table->date('booking_end_date')->nullable();
      $table->time('booking_time')->nullable();
      $table->time('booking_start_time')->nullable();
      $table->time('booking_end_time')->nullable();
      $table->dateTime('booking_date_time')->nullable();
      $table->dateTime('booking_start_date_time')->nullable();
      $table->dateTime('booking_end_date_time')->nullable();

      $table->json('entry')->nullable();
      $table->json('meta')->nullable();
      $table->dateTime('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('form_entries');
    Schema::drop('form_stats');
    Schema::drop('forms');
  }
}
