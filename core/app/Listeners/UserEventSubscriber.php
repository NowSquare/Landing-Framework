<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Auth;
use \Platform\Controllers\Core;
use Platform\Controllers\Helper;
use Carbon\Carbon;

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
    //if ($event->user->logins <= 1) {
    //  dispatch(new \App\Jobs\CreateUserTables($event->user->id));
    //}
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