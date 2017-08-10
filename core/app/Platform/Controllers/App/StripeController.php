<?php namespace Platform\Controllers\App;

use \Platform\Controllers\Core;

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

      // Customer exists?
      if (auth()->user()->stripe_id == null) {
        // Create customer
        $customer = \Stripe\Customer::create(array(
          "description" => "Customer for " . auth()->user()->email,
          "source" => $token,
          "email" => $email
        ));

        $customer_stripe_id = $customer->id;

        auth()->user()->stripe_id = $customer_stripe_id;
        auth()->user()->save();

      } else {
        $customer_stripe_id = auth()->user()->stripe_id;
      }

      // Get Stripe plan
      //$plan = \Stripe\Plan::retrieve($stripe_plan_id);

      $subscription = \Stripe\Subscription::create([
        'customer' => $customer_stripe_id, 
        'items' => [
          [
            'plan' => $stripe_plan_id
          ]
        ]
      ]);

      $html = '';
      $html .= auth()->user()->id . '<br>';
      $html .= $plan_id;

      \Mail::raw($html, function ($message){
        $message->to(config('avangate.debug_mail'))->subject('Stripe test mail');
      });

    }
  }

  /**
   * Stripe webhook
   */

  public function postWebhook()
  {
    $request = request()->all();

    $html = '';

    foreach ($request as $key => $val)
    {
      if (is_array($val)) $val = implode(', ', $val);
      $html .= $key . ': ' . $val . chr(13);
    }

    \Mail::raw($html, function ($message){
      $message->to(config('avangate.debug_mail'))->subject('Stripe webhook test');
    });

  }
}