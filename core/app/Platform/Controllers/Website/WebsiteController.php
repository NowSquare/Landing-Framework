<?php namespace Platform\Controllers\Website;

class WebsiteController extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | Website Controller
   |--------------------------------------------------------------------------
   |
   | Website related logic
   |--------------------------------------------------------------------------
   */

  /**
   * Index
   */

  public function home()
  {
    return view('website.home');
  }

}