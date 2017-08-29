<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Auth;
use \Platform\Controllers\Core;
use Platform\Controllers\Helper;
use Carbon\Carbon;
use App\Notifications\SendEmail;

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
    // Get reseller
    $reseller = Core\Reseller::get();

    // Set expiration date, first check for default account
    //$default_plan = \App\Plan::where('reseller_id', $reseller->id)->where('active', 1)->where('default', 1)->first();
    $default_plan = \App\Plan::where('active', 1)->where('default', 1)->first();
    $trial_days = (! empty($default_plan) && is_numeric($default_plan->trial_days)) ? $default_plan->trial_days : 14;
    //$expires = Carbon::now()->addDays($trial_days);
    $trial_ends_at = Carbon::now()->addDays($trial_days);

    $event->user->reseller_id = $reseller->id;
    //$event->user->expires = $expires;
    $event->user->trial_ends_at = $trial_ends_at;
    $event->user->language = $reseller->default_language;
    $event->user->timezone = $reseller->default_timezone;
    $event->user->save();

    // Set language
    app()->setLocale($reseller->default_language);

    $mail_from = $reseller->mail_from_address;
    $mail_from_name = $reseller->mail_from_name;
    $subject = trans('global.new_user_subject', ['product_name' => $reseller->name]);
    $body_line1 = trans('global.new_user_mail_line1', ['product_name' => $reseller->name, 'trial_days' => $trial_days]);
    $body_line2 = trans('global.new_user_mail_line2', ['support_email' => $reseller->support_email]);
    $body_cta = trans('global.new_user_cta');
    $body_cta_link = url('login');

    $event->user->notify(new SendEmail($mail_from, $mail_from_name, $subject, $body_line1, $body_line2, $body_cta, $body_cta_link));
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