<?php namespace Platform\Controllers\App;

use \Platform\Controllers\Core;
use Carbon\Carbon;

class StripeController extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | Stripe Controller
   |--------------------------------------------------------------------------
   |
   | Stripe related logic
   |
   |--------------------------------------------------------------------------
   */

  /**
   * Create Stripe customer if necessary 
   * and subscribe to plan.
   */

  public function postToken()
  {
    $plan_id = request()->input('plan_id', null);
    $stripe_plan_id = request()->input('stripe_plan_id', null);
    $token = request()->input('token', null);
    $email = request()->input('email', null);
    $type = request()->input('type', null);

    if ($token != null && auth()->check()) {
      $reseller = Core\Reseller::get();
      $stripe_secret = $reseller->stripe_secret;

      \Stripe\Stripe::setApiKey($stripe_secret);

      $user = \App\User::where('id', auth()->user()->id)->first();

      if (! empty($user)) {
        // Customer exists?
        if ($user->stripe_id == null) {
          // Create customer
          $customer = \Stripe\Customer::create(array(
            "description" => "Customer for " . $user->email,
            "source" => $token,
            "email" => $email
          ));

          $customer_stripe_id = $customer->id;

          $user->stripe_id = $customer_stripe_id;
          $user->save();

        } else {
          $customer_stripe_id = $user->stripe_id;
        }

        // Subscribe
        $subscription = \Stripe\Subscription::create([
          'customer' => $customer_stripe_id, 
          'items' => [
            [
              'plan' => $stripe_plan_id
            ]
          ]
        ]);

        if (isset($subscription->plan)) {
          $expires = Carbon::now()->addMonths(1);

          if (isset($subscription->plan->interval)) {
            $interval_count = (isset($subscription->plan->interval_count)) ? $subscription->plan->interval_count : 1;
            switch ($subscription->plan->interval) {
              case 'month': $expires = Carbon::now()->addMonths($interval_count); break;
              case 'year': $expires = Carbon::now()->addYears($interval_count); break;
            }
          }

          $user->trial_ends_at = null;
          $user->trial_ends_reminders_sent = 0;
          $user->expires_reminders_sent = 0;
          $user->plan_id = $plan_id;
          $user->expires = $expires;
          $user->save();
        }
      }
    }
  }

  /**
   * Stripe webhook
   */

  public function postWebhook()
  {
    $reseller = Core\Reseller::get();
    $stripe_secret = $reseller->stripe_secret;

    \Stripe\Stripe::setApiKey($stripe_secret);

    // Retrieve the request's body and parse it as JSON
    $input = @file_get_contents("php://input");
    $event_json = json_decode($input);

    // A customer is deleted in Stripe, set user to trial mode
    if ($event_json->type == 'customer.deleted') {
      $customer_stripe_id = $event_json->data->object->id;

      // Find matching user
      $user = \App\User::where('stripe_id', $customer_stripe_id)->first();

      if (! empty($user)) {
        $user->stripe_id = null;
        $user->trial_ends_reminders_sent = 0;
        $user->expires_reminders_sent = 0;
        $user->plan_id = null;
        $user->expires = Carbon::now()->addDays(14);
        $user->save();
      }
    }

    // A subscription is created for a customer in Stripe

    // Use invoice.payment_succeeded instead
    /*
    if ($event_json->type == 'customer.subscription.created') {
      $customer_stripe_id = $event_json->data->object->customer;
      $remote_product_id = $event_json->data->object->plan->id;

      // Find matching user
      $user = \App\User::where('stripe_id', $customer_stripe_id)->first();

      // Find matching plan
      $plan = \App\Plan::where('monthly_remote_product_id', $remote_product_id)->orWhere('annual_remote_product_id', $remote_product_id)->first();

      if (! empty($user) && ! empty($plan)) {

        $expires = Carbon::now()->addMonths(1);

        if (isset($event_json->data->object->plan->interval)) {
          $interval_count = (isset($event_json->data->object->plan->interval_count)) ? $event_json->data->object->plan->interval_count : 1;
          switch ($event_json->data->object->plan->interval) {
            case 'month': $expires = Carbon::now()->addMonths($interval_count); break;
            case 'year': $expires = Carbon::now()->addYears($interval_count); break;
          }
        }

        $user->trial_ends_reminders_sent = 0;
        $user->expires_reminders_sent = 0;
        $user->plan_id = $plan->id;
        $user->expires = $expires;
        $user->save();
      }
    }*/

    // The payment has succeeded, update the expiration date
    if ($event_json->type == 'invoice.payment_succeeded') {
      $customer_stripe_id = $event_json->data->object->customer;  
      $remote_product_id = $event_json->data->object->lines->data{0}->plan->id;

      // Find matching user
      $user = \App\User::where('stripe_id', $customer_stripe_id)->first();

      // Find matching plan
      $plan = \App\Plan::where('monthly_remote_product_id', $remote_product_id)->orWhere('annual_remote_product_id', $remote_product_id)->first();

      if (! empty($user) && ! empty($plan)) {

        $expires = Carbon::now()->addMonths(1);

        if (isset($event_json->data->object->lines->data{0}->plan->interval)) {
          $interval_count = (isset($event_json->data->object->lines->data{0}->plan->interval_count)) ? $event_json->data->object->lines->data{0}->plan->interval_count : 1;
          switch ($event_json->data->object->lines->data{0}->plan->interval) {
            case 'month': $expires = Carbon::now()->addMonths($interval_count); break;
            case 'year': $expires = Carbon::now()->addYears($interval_count); break;
          }
        }

        $user->trial_ends_reminders_sent = 0;
        $user->expires_reminders_sent = 0;
        $user->plan_id = $plan->id;
        $user->expires = $expires;
        $user->save();
      }
    }

    // A subscription is updated for a customer in Stripe
    if ($event_json->type == 'customer.subscription.updated') {
      $customer_stripe_id = $event_json->data->object->customer;
      $remote_product_id = $event_json->data->object->plan->id;
      $status = $event_json->data->object->status;

      if ($status != 'canceled' && $status != 'unpaid') {
        // Find matching user
        $user = \App\User::where('stripe_id', $customer_stripe_id)->first();

        // Find matching plan
        $plan = \App\Plan::where('monthly_remote_product_id', $remote_product_id)->orWhere('annual_remote_product_id', $remote_product_id)->first();

        if (! empty($user) && ! empty($plan)) {

          $expires = Carbon::now()->addMonths(1);

          if (isset($event_json->data->object->plan->interval)) {
            $interval_count = (isset($event_json->data->object->plan->interval_count)) ? $event_json->data->object->plan->interval_count : 1;
            switch ($event_json->data->object->plan->interval) {
              case 'month': $expires = Carbon::now()->addMonths($interval_count); break;
              case 'year': $expires = Carbon::now()->addYears($interval_count); break;
            }
          }

          $user->trial_ends_reminders_sent = 0;
          $user->expires_reminders_sent = 0;
          $user->plan_id = $plan->id;
          $user->expires = $expires;
          $user->save();
        }
      }
    }

    // A subscription is deleted for a customer in Stripe, set user to trial mode
    if ($event_json->type == 'customer.subscription.deleted') {
      $customer_stripe_id = $event_json->data->object->customer;

      // Find matching user
      $user = \App\User::where('stripe_id', $customer_stripe_id)->first();

      if (! empty($user)) {
        $user->trial_ends_reminders_sent = 0;
        $user->expires_reminders_sent = 0;
        $user->plan_id = null;
        $user->expires = Carbon::now()->addDays(14);
        $user->save();
      }
    }
  }
}