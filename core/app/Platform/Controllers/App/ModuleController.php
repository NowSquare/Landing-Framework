<?php namespace Platform\Controllers\App;

use \Platform\Controllers\Core;
use Illuminate\Http\Request;

class ModuleController extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | Module Controller
   |--------------------------------------------------------------------------
   |
   | Module related logic
   |--------------------------------------------------------------------------
   */

  /**
   * Show module management
   */
  public function showModules() {
    $modules = \Module::all();
    $items = [];

    foreach ($modules as $module) {
      $namespace = $module->getLowerName();
      $enabled = config($namespace . '.enabled', false);
      $in_plan = config($namespace . '.in_plan');

      $items[] = [
        "namespace" => $namespace,
        "enabled" => $enabled,
        "icon" => config($namespace . '.icon'),
        "order" => config($namespace . '.order'),
        "creatable" => config($namespace . '.creatable'),
        "in_plan_amount" => config($namespace . '.in_plan_amount'),
        "in_plan_default_amount" => config($namespace . '.in_plan_default_amount'),
        "extra_plan_config_boolean" => config($namespace . '.extra_plan_config_boolean'),
        "extra_plan_config_string" => config($namespace . '.extra_plan_config_string'),
        "order" => config($namespace . '.order'),
        "name" => ($enabled) ? trans($namespace . '::global.module_name') : $namespace,
        "desc" => trans($namespace . '::global.module_desc')
      ];
    }

    $items = array_values(array_sort($items, function ($value) {
      return $value['order'];
    }));

    return view('platform.admin.modules.modules', compact('items'));
  }

  /**
   * Switch module
   */
  public function switchModule() {
    if (config('app.demo')) {
      return response()->json([
        'type' => 'error',
        'reset' => false, 
        'msg' => "This is disabled in the demo"
      ]);
    }

    $sl = request()->input('sl', '');
    $checked = (bool) request()->input('checked', 0);

    if($sl != '') {
      $qs = Core\Secure::string2array($sl);
      $module = \Module::find($qs['namespace']);

      if ($checked) {
        $module->enable();
      } else {
        $module->disable();
      }
    }

    return response()->json([
      'type' => 'success'
    ]);
  }

}