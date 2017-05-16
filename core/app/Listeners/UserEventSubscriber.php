<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Auth;
use \Platform\Controllers\Core;
use Platform\Controllers\Helper;
use Carbon\Carbon;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class UserEventSubscriber {
  /**
   * Handle user login events.
   */
  public function onUserLogin($event) {
    // Check if user active
    if ($event->user->role != 'member' && $event->user->logins > 0 && $event->user->active == 0) {
      \Auth::guard('web')->logout();
    }

    // Check if reseller is active
    $reseller = \App\Reseller::where('id', $event->user->reseller_id)->where('active', 1)->first();
    if (empty($reseller)) {
      \Auth::guard('web')->logout();
    } 

    // Check if member active
    if ($event->user->role == 'member' && $event->user->active == 0) {
      \Auth::guard('member')->logout();
    }

    // Update user
    $event->user->logins = $event->user->logins + 1;
    $event->user->last_ip =  Helper\Client::ip();
    $event->user->last_login = Carbon::now();
    $event->user->save();

    // Create user tables, prefix with `x_` to have them grouped

    // Create user landing stats table if not exist
    $tbl_name = 'x_landing_stats_' . $event->user->id;

    if (! Schema::hasTable($tbl_name)) {
      Schema::create($tbl_name, function(Blueprint $table) {
        $table->bigIncrements('id');
        $table->bigInteger('landing_site_id')->unsigned();
        $table->foreign('landing_site_id')->references('id')->on('landing_sites')->onDelete('cascade');
        $table->bigInteger('landing_page_id')->unsigned();
        $table->foreign('landing_page_id')->references('id')->on('landing_pages')->onDelete('cascade');
        $table->char('fingerprint', 32)->nullable();
        $table->boolean('is_bot')->default(false);
        $table->string('ip', 40)->nullable();
        $table->string('language', 5)->nullable();
        $table->string('client_type', 32)->nullable();
        $table->string('client_name', 32)->nullable();
        $table->string('client_version', 32)->nullable();
        $table->string('client_engine', 32)->nullable();
        $table->string('client_engine_version', 32)->nullable();
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
    }

    // Create user form entries table if not exist
    $tbl_name = 'x_form_entries_' . $event->user->id;

    if (! Schema::hasTable($tbl_name)) {
      Schema::create($tbl_name, function(Blueprint $table) {
        $table->bigIncrements('id');
        $table->bigInteger('form_id')->unsigned();
        $table->foreign('form_id')->references('id')->on('forms')->onDelete('cascade');
        $table->string('email', 96)->nullable();
        $table->char('fingerprint', 32)->nullable();
        $table->boolean('is_bot')->default(false);
        $table->string('ip', 40)->nullable();
        $table->string('language', 5)->nullable();
        $table->string('client_type', 32)->nullable();
        $table->string('client_name', 32)->nullable();
        $table->string('client_version', 32)->nullable();
        $table->string('client_engine', 32)->nullable();
        $table->string('client_engine_version', 32)->nullable();
        $table->string('os_name', 32)->nullable();
        $table->string('os_version', 32)->nullable();
        $table->string('os_platform', 32)->nullable();
        $table->string('device', 12)->nullable();
        $table->string('brand', 32)->nullable();
        $table->string('model', 32)->nullable();
        $table->decimal('lat', 10, 8)->nullable();
        $table->decimal('lng', 11, 8)->nullable();
        $table->json('entry')->nullable();
        $table->json('meta')->nullable();
        $table->dateTime('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
      });
    }
  }

  /**
   * Handle user logout events.
   */
  public function onUserLogout($event) {
    // Log admin back in
    $sl = \Session::pull('logout', '');
    if($sl != '') {
      $qs = Core\Secure::string2array($sl);
      \Auth::loginUsingId($qs['user_id'], true);
      //return redirect('platform#/admin/users');
    }
  }

  /**
   * Handle user registration events.
   */
  public function onLogRegisteredUser($event) {
    $event->user->reseller_id = Core\Reseller::get()->id;
    $event->user->save();
  }

  /**
   * Register the listeners for the subscriber.
   *
   * @param  Illuminate\Events\Dispatcher  $events
   */
  public function subscribe($events) {
    $events->listen('Illuminate\Auth\Events\Login', 'App\Listeners\UserEventSubscriber@onUserLogin');
    $events->listen('Illuminate\Auth\Events\Logout', 'App\Listeners\UserEventSubscriber@onUserLogout');
    $events->listen('Illuminate\Auth\Events\Registered', 'App\Listeners\UserEventSubscriber@onLogRegisteredUser');
    //$events->listen('Illuminate\Auth\Events\Attempting', 'App\Listeners\UserEventSubscriber@onLogAuthenticationAttempt');
    //$events->listen('Illuminate\Auth\Events\Authenticated', 'App\Listeners\UserEventSubscriber@onLogAuthenticated');
    //$events->listen('Illuminate\Auth\Events\Lockout', 'App\Listeners\UserEventSubscriber@onLogLockout');
  }
}