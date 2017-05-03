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
    // Default values
    $card_views_difference = 0;
    $card_views_today = 0;
    $card_views_yesterday = 0;
    $interaction_difference = 0;
    $interactions_today = 0;
    $interactions_yesterday = 0;
    $android_difference = 0;
    $android_today = 0;
    $android_yesterday = 0;
    $ios_difference = 0;
    $ios_today = 0;
    $ios_yesterday = 0;
    $app_count = 0;
    $app_count_limit = 0;
    $app_count_perc = 0;
    $campaign_count = 0;
    $campaign_count_limit = 0;
    $campaign_count_perc = 0;
    $beacon_count = 0;
    $beacon_count_limit = 0;
    $beacon_count_perc = 0;
    $geofence_count = 0;
    $geofence_count_limit = 0;
    $geofence_count_perc = 0;
    $campaign_count = 0;
    $campaign_count_limit = 0;
    $campaign_count_perc = 0;

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

    // Only execute queries when user has access to mobile features
    if (Gate::allows('limitation', 'mobile.visible')) {
    }

    return view('platform.dashboard.dashboard', compact(
      'card_views_difference', 
      'card_views_today', 
      'card_views_yesterday', 
      'interaction_difference', 
      'interactions_today', 
      'interactions_yesterday', 
      'android_difference', 
      'android_today', 
      'android_yesterday', 
      'ios_difference', 
      'ios_today', 
      'ios_yesterday', 
      'app_count', 
      'app_count_limit', 
      'app_count_perc', 
      'campaign_count', 
      'campaign_count_limit', 
      'campaign_count_perc', 
      'beacon_count', 
      'beacon_count_limit', 
      'beacon_count_perc', 
      'geofence_count', 
      'geofence_count_limit', 
      'geofence_count_perc',
      'items'
    ));
  }
}