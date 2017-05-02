<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {

    Schema::create('members', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('reseller_id')->unsigned()->nullable();
      $table->foreign('reseller_id')->references('id')->on('resellers')->onDelete('set null');
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->string('namespace', 32)->nullable();
      $table->string('role', 20)->default('member');
      $table->string('name', 64)->nullable();
      $table->string('email');
      $table->string('password', 60)->nullable();
      $table->boolean('confirmed')->default(false);
      $table->string('confirmation_code')->nullable();
      $table->string('api_token', 60)->nullable()->unique();
      $table->boolean('active')->default(true);
      $table->string('nickname', 64)->nullable();
      $table->timestamp('birthday')->nullable();
      $table->string('gender', 1)->nullable();
      $table->string('phone', 20)->nullable();
      $table->string('city', 32)->nullable();
      $table->string('country', 32)->nullable();
      $table->string('language', 5)->default('en');
      $table->string('timezone', 32)->default('UTC');
      $table->integer('logins')->default(0)->unsigned();
      $table->ipAddress('last_ip')->nullable();
      $table->dateTime('last_login')->nullable();

      // Avatar
      $table->string('avatar')->nullable();
      $table->string('avatar_file_name')->nullable();
      $table->integer('avatar_file_size')->nullable();
      $table->string('avatar_content_type')->nullable();
      $table->timestamp('avatar_updated_at')->nullable();

      // Stripe
      $table->string('stripe_id')->nullable();

      // Braintree
      $table->string('braintree_id')->nullable();
      $table->string('paypal_email')->nullable();

      // General
      $table->string('card_brand')->nullable();
      $table->string('card_last_four')->nullable();
      $table->timestamp('trial_ends_at')->nullable();

      $table->json('settings')->nullable();
      $table->rememberToken();
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
    Schema::drop('members');
  }
}
