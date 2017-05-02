<?php
namespace Platform\Controllers\Module;

use \Platform\Controllers\Core;
use \Platform\Models\Location;
use \Platform\Models\Campaigns;
use Illuminate\Http\Request;

class ModuleController extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | Module controller
   |--------------------------------------------------------------------------
   |
   | Module logic
   |
   */

  /**
   * Show new create
   */
  public function showNew() {

    $modules = \Module::enabled();
    $items = [];

    foreach ($modules as $module) {
      $namespace = $module->getLowerName();
      $enabled = config($namespace . '.enabled');
      $creatable = config($namespace . '.creatable');

      if ($enabled && $creatable) {
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
    return view('platform.modules.new', compact('items'));
  }

}