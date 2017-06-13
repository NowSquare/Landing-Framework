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

    $funnel_id = Core\Secure::funnelId();

    if ($funnel_id == 0) {
      return \App::make('\Platform\Controllers\App\FunnelController')->showFunnels();
    }

    $sites = false;

    if (Gate::allows('limitation', 'landingpages.visible')) {
      $sites = \Modules\LandingPages\Http\Models\Site::where('user_id', Core\Secure::userId())->where('funnel_id', Core\Secure::funnelId())->select('landing_sites.*')->addSelect(\DB::raw('((landing_sites.conversions / landing_sites.visits) * 100) as conversion'))->orderBy('conversion', 'desc')->get();
    }

    if (Gate::allows('limitation', 'forms.visible')) {
      $forms = \Modules\Forms\Http\Models\Form::where('user_id', Core\Secure::userId())->where('funnel_id', Core\Secure::funnelId())->select('forms.*')->addSelect(\DB::raw('((forms.entries / forms.visits) * 100) as conversion'))->orderBy('entries', 'desc')->get();
    }

    return view('platform.dashboard.dashboard', compact('active_modules', 'sites', 'forms'));
  }
}