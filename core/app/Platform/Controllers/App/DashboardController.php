<?php namespace Platform\Controllers\App;

use \Platform\Controllers\Core;
use Illuminate\Support\Facades\Gate;
use \Platform\Models\Funnels;

class DashboardController extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | Dashboard Controller
   |--------------------------------------------------------------------------
   |
   | Dashboard related logic
   |--------------------------------------------------------------------------
   */

  /**
   * Dashboard
   */

  public function showDashboard() {
    $modules = \Module::enabled();
    $active_modules = [];

    foreach ($modules as $module) {
      $namespace = $module->getLowerName();
      $enabled = config($namespace . '.enabled');
      $creatable = config($namespace . '.creatable');

      if ($enabled && $creatable && \Gate::allows('limitation', $namespace . '.visible')) {
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

    /*if (count($funnels) == 0) {
      return \App::make('\Platform\Controllers\App\FunnelController')->showCreateFunnel();
    }*/

    return view('platform.dashboard.dashboard', compact('active_modules'));
  }
}