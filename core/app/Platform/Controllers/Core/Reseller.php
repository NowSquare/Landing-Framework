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
  public static function get() {
    if (\Auth::check()) {
      $reseller = \App\Reseller::find(\Auth::user()->reseller_id);
    } else {
      $reseller = \App\Reseller::where('domain', \Request::getHost())->first();
      
      if (! $reseller) {
        $reseller = \App\Reseller::where('domain', '*')->first();
      }
    }
    
    if ($reseller) {
      $reseller->url = ($reseller->domain == '*') ? url('/') : 'http://' . $reseller->domain;
      
      if ($reseller->favicon == NULL) $reseller->favicon = url('favicon.ico');
      if ($reseller->logo == NULL) $reseller->logo = url('assets/images/branding/logo-hori-light.svg');
      if ($reseller->logo_square == NULL) $reseller->logo_square = url('assets/images/branding/logo-square.svg');
      if ($reseller->page_title == NULL) $reseller->page_title = $reseller->name;
    } else {
      $reseller = new \stdClass;
      $reseller->url = url('/');
      $reseller->name = '';
      $reseller->active = false;
      $reseller->favicon = url('favicon.ico');
      $reseller->logo = url('assets/images/branding/logo-hori.svg');
      $reseller->logo_square = url('assets/images/branding/logo-square.svg');
      $reseller->page_title = 'Not found';
    }
    
    return $reseller;
  }
}