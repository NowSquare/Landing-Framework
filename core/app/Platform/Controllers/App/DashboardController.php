<?php namespace Platform\Controllers\App;

use \Platform\Controllers\Core;
use Illuminate\Support\Facades\Gate;
use \Platform\Models\Analytics as ModelAnalytics;
use \Platform\Models\Location;
use \Platform\Models\Campaigns;

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
    $items = [];

    foreach ($modules as $module) {
      $namespace = $module->getLowerName();
      $enabled = config($namespace . '.enabled');
      $creatable = config($namespace . '.creatable');

      if ($enabled && $creatable && \Gate::allows('limitation', $namespace . '.visible')) {
        $items[] = [
          "icon" => config($namespace . '.icon'),
          "order" => config($namespace . '.order'),
          "name" => trans($namespace . '::global.module_name'),
          "desc" => trans($namespace . '::global.module_desc'),
          "url" => "#/" . $namespace . "/create"
        ];

      }
    }

    $items = array_values(array_sort($items, function ($value) {
      return $value['order'];
    }));
/*
    $items[] = [
      "img" => url('assets/images/items/landing-page.jpg'),
      "name" => "Coupon",
      "desc" => "With digital coupons you can offer your customers a great deal.",
      "url" => "#/coupon"
    ];
*/

    return view('platform.dashboard.dashboard', compact(
      'items'
    ));
  }
}