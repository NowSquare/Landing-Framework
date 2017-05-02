<?php namespace Platform\Controllers\App;

use \Platform\Controllers\Core;

class MainController extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | Main Controller
   |--------------------------------------------------------------------------
   |
   | Main back end related logic
   |
   |--------------------------------------------------------------------------
   */

  /**
   * Main layout
   */

  public function main() {
    $languages = Core\Localization::getLanguagesArray();
    $current_language = strtoupper(trans('i18n.language_code'));

    return view('platform.main', compact('languages', 'current_language'));
  }

  /**
   * Login
   */

  public function login() {
    return view('app.auth.login');
  }

}