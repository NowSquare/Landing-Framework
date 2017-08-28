<?php
namespace Platform\Controllers\Core;

class Reseller extends \App\Http\Controllers\Controller {
  
  /*
  |--------------------------------------------------------------------------
  | Reseller Controller
  |--------------------------------------------------------------------------
  |
  | Reseller related logic
  |--------------------------------------------------------------------------
  */

  /**
   * \Platform\Controllers\Core\Reseller::get();
   * Returns current reseller data
   */
  public static function get($reseller_id = null) {
    if ($reseller_id == null) {
      if (\Auth::check()) {
        $reseller = \App\Reseller::find(\Auth::user()->reseller_id);
      } else {
        $host = \Request::getHost();
        $host = (starts_with($host, 'www.')) ? substr($host, 4, strlen($host) - 4) : $host;

        $reseller = \App\Reseller::where('domain', $host)
          ->orWhere('domain', 'www.' . $host)
          ->first();

        if (! $reseller) {
          $reseller = \App\Reseller::where('domain', '*')->first();
        }
      }
    } else {
      $reseller = \App\Reseller::where('id', $reseller_id)->first();
    }
    
    if ($reseller) {
      $reseller->url = ($reseller->domain == '*') ? url('/') : 'http://' . $reseller->domain;
      
      if ($reseller->favicon == null) $reseller->favicon = url('assets/branding/favicon.svg');
      if ($reseller->logo == null) $reseller->logo = url('assets/branding/logo-hori-light.svg');
      if ($reseller->logo_square == null) $reseller->logo_square = url('assets/branding/logo-square.svg');
      if ($reseller->page_title == null) $reseller->page_title = $reseller->name;
      if ($reseller->mail_from_address == null) $reseller->mail_from_address = env('MAIL_FROM_ADDRESS');
      if ($reseller->mail_from_name == null) $reseller->mail_from_name = env('MAIL_FROM_NAME');
      if ($reseller->avangate_key == null) $reseller->avangate_key = config()->get('avangate.key');
      if ($reseller->stripe_key == null) $reseller->stripe_key = env('STRIPE_KEY', null);
      if ($reseller->stripe_secret == null) $reseller->stripe_secret = env('STRIPE_SECRET', null);
    } else {
      $reseller = new \stdClass;
      $reseller->url = url('/');
      $reseller->name = env('APP_NAME', config()->get('app.name'));
      $reseller->active = false;
      $reseller->favicon = url('assets/branding/favicon.svg');
      $reseller->logo = url('assets/branding/icon-light.svg');
      $reseller->logo_square = url('assets/branding/square.svg');
      $reseller->page_title = 'Not found';
    }
    
    return $reseller;
  }
}