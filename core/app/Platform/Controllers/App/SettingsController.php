<?php namespace Platform\Controllers\App;

use \Platform\Controllers\Core;
use Illuminate\Http\Request;

class SettingsController extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | Settings Controller
   |--------------------------------------------------------------------------
   |
   | Settings related logic
   |--------------------------------------------------------------------------
   */

  /**
   * Show settings home
   */
  public function showSettings() {

    return view('platform.admin.settings.settings');
  }


}