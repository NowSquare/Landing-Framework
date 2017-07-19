<?php namespace Platform\Controllers\App;

use \Platform\Controllers\Core;
use \Platform\Models\Funnels;

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
    // Get languages
    $languages = Core\Localization::getLanguagesArray();
    $current_language = strtoupper(trans('i18n.language_code'));

    // Get modules
    $modules = \Module::enabled();
    $active_modules = [];

    foreach ($modules as $module) {
      $namespace = $module->getLowerName();
      $enabled = config($namespace . '.enabled');
      $creatable = config($namespace . '.creatable');

      if ($enabled && \Gate::allows('limitation', $namespace . '.visible')) {
        $active_modules[] = [
          "namespace" => $namespace,
          "icon" => config($namespace . '.icon'),
          "order" => config($namespace . '.order'),
          "name" => trans($namespace . '::global.module_name'),
          "name_plural" => trans($namespace . '::global.module_name_plural'),
          "desc" => trans($namespace . '::global.module_desc'),
          "url" => "#/" . $namespace . "/create"
        ];

      }
    }

    $active_modules = array_values(array_sort($active_modules, function ($value) {
      return $value['order'];
    }));

    // Get funnels
    $funnels = Funnels\Funnel::where('user_id', Core\Secure::userId())->orderBy('name', 'asc')->get();
    $funnel_id = Core\Secure::funnelId();

    return view('platform.main', compact('languages', 'current_language', 'active_modules', 'funnels', 'funnel_id'));
  }

  /**
   * Login
   */

  public function login() {
    return view('app.auth.login');
  }

}