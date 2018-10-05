<?php 
namespace Modules\Scenarios\Http\Controllers;

use \Platform\Controllers\Core;
use \Platform\Models\Funnels;
use \Modules\Scenarios\Http\Models as Scenarios;
use \Modules\Beacons\Http\Models as Beacons;
use \Modules\Geofences\Http\Models as Geofences;
use Illuminate\Http\Request;

class AnalyticsController extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | Analytics Controller
   |--------------------------------------------------------------------------
   |
   | Analytics related logic
   |--------------------------------------------------------------------------
   */

  /**
   * Campaign Analytics
   */
  public function showAnalytics()
  {
    // Security link
    $sl = request()->get('sl', '');
    if ($sl == 'all') $sl = '';

    $data_found = false;

    $funnel_id = Core\Secure::funnelId();

    $funnel = Funnels\Funnel::where('user_id', Core\Secure::userId())
      ->where('id', $funnel_id)
      ->orderBy('created_at', 'asc')
      ->first();
/*
    if ($sl != '') {
      $qs = Core\Secure::string2array($sl);
      $funnel_id = $qs['funnel_id'];
      $sl = rawurlencode($sl);
    }
*/
    // Range
    $date_start = request()->get('start', date('Y-m-d', strtotime(' - 30 day')));
    $date_end = request()->get('end', date('Y-m-d'));

    $from =  $date_start . ' 00:00:00';
    $to = $date_end . ' 23:59:59';

    // Filter
    $filter = request()->get('filter', '');
    if ($filter != '') {
      $selected_beacons = [];
      $selected_geofences = [];

      $filter = json_decode($filter);

      if ($filter != null) {
        foreach ($filter as $place) {
          if (starts_with($place, 'b')) $selected_beacons[] = str_replace('b', '', $place);
          if (starts_with($place, 'g')) $selected_geofences[] = str_replace('g', '', $place);
        }
      }
    }

    /*
     |--------------------------------------------------------------------------
     | Get earliest date for funnel selection
     |--------------------------------------------------------------------------
     */
    $earliest_date = date('Y-m-d');

    // Get first interaction
    $interaction = Scenarios\Interaction::where('user_id', Core\Secure::userId())->where('funnel_id', $funnel_id)->orderBy('created_at', 'asc')->first();

    if (! empty($interaction)) {
      if ($interaction->created_at < $earliest_date) $earliest_date = $interaction->created_at;
    }

    /*
    if (! empty($funnel)) {
      if ($funnel->created_at < $earliest_date) $earliest_date = $funnel->created_at;
    }
    */

    // Get linked places
    if (! empty($funnel)) {
      $available_beacons = [];
      $available_geofences = [];

      /*
      foreach ($funnel->scenarios as $scenario) {
        foreach ($scenario->beacons as $beacon) {
          $available_beacons[] = $beacon->beacon_id;
        }
        foreach ($scenario->geofences as $geofence) {
          $available_geofences[] = $geofence->geofence_id;
        }
      }
      */

      $beacons = Beacons\Beacon::where('funnel_id', $funnel->id)->where('user_id', '=', Core\Secure::userId())->get();

      foreach ($beacons as $beacon) {
        $available_beacons[] = $beacon->id;
      }

      $geofences = Geofences\Geofence::where('funnel_id', $funnel->id)->where('user_id', '=', Core\Secure::userId())->get();

      foreach ($geofences as $geofence) {
        $available_geofences[] = $geofence->id;
      }
    }

    asort($available_beacons);
    asort($available_geofences);

    if ($filter == '') {
      $selected_beacons = $available_beacons;
      $selected_geofences = $available_geofences;
    }

    // Query details for selected places
    $beacons = Beacons\Beacon::whereIn('id', $selected_beacons)->where('user_id', '=', Core\Secure::userId())->orderBy('triggers', 'desc')->get();
    $geofences = Geofences\Geofence::whereIn('id', $selected_geofences)->where('user_id', '=', Core\Secure::userId())->orderBy('triggers', 'desc')->get();

    /*
     |--------------------------------------------------------------------------
     | Count interactions
     |--------------------------------------------------------------------------
     */

    // Interactions
    $stats_interactions = Scenarios\Interaction::where('user_id', Core\Secure::userId())
      ->select(\DB::raw('DATE(created_at) as date'), \DB::raw('count(id) as interactions'), 'beacon_id', 'geofence_id')
      ->where('funnel_id', $funnel_id)
      ->where('created_at', '>=', $from)
      ->where('created_at', '<=', $to)
      ->groupBy([\DB::raw('DATE(created_at)'), 'beacon_id', 'geofence_id'])
      ->get()
      ->toArray();

    // Create range for chart
    $main_chart_range = $this->getRange($date_start, $date_end);

    // Columns
    $chartJson['cols'] = [];

    $chartJson['cols'][] = [
      'label' => false,
      'type' => 'string'
      /*'type' => 'date'*/
    ];

    $chartJson['cols'][] = [
      'label' => trans('scenarios::global.interactions'),
      'type' => 'number'
    ];

    // Rows
    $min = 0;
    $max = 0;
    $chartJson['rows'] = [];

    // Merge stats with range
    foreach($main_chart_range as $date => $arr) {

      // Interactions
      $interactions = 0;
      $interactions = ($date < $earliest_date) ? NULL : 0;
      foreach($stats_interactions as $row) {
        if ($date == $row['date']) {
          if ($filter == '' || ($filter != '' && (in_array($row['beacon_id'], $selected_beacons) || in_array($row['geofence_id'], $selected_geofences)))) {
            $interactions += $row['interactions'];
            $data_found = true;
          }
          //break 1;
        }
      }

      $arr = array_merge(['interactions' => $interactions], $arr);
      $main_chart_range[$date] = $arr;

      if ($interactions < $min) $min = $interactions;
      if ($interactions > $max) $max = $interactions;

      $chartJson['rows'][] = [
        'c' => [
          ['v' => \Carbon\Carbon::parse($date)->timezone(\Auth::user()->timezone)->toFormattedDateString()],
          /*['v' => 'Date(' . $dArr['y'] . ', ' . $dArr['m'] . ', ' . $dArr['d'] . ')'],*/
          ['v' => $interactions]
        ]
      ];
    }

    $chartJson['vars'] = [
      'min' => $min,
      'max' => $max
    ];

    /*
     |--------------------------------------------------------------------------
     | Pie charts
     |--------------------------------------------------------------------------
     */

      /*
       |--------------------------------------------------------------------------
       | Platform
       |--------------------------------------------------------------------------
       */

    $segmentation_platform_interactions = Scenarios\Interaction::where('user_id', Core\Secure::userId())
      ->select('platform as name', \DB::raw('count(id) as total'))
      ->where('funnel_id', $funnel_id)
      ->where(function ($q) use($selected_beacons, $selected_geofences) {
        $q->orWhereIn('beacon_id', $selected_beacons)
          ->orWhereIn('geofence_id', $selected_geofences);
      })
      ->where('created_at', '>=', $from)
      ->where('created_at', '<=', $to)
      ->groupBy('platform')
      ->get()
      ->toArray();

      /*
       |--------------------------------------------------------------------------
       | Model
       |--------------------------------------------------------------------------
       */

    $segmentation_model_interactions = Scenarios\Interaction::where('user_id', Core\Secure::userId())
      ->select('model as name', \DB::raw('count(id) as total'))
      ->where('funnel_id', $funnel_id)
      ->where(function ($q) use($selected_beacons, $selected_geofences) {
        $q->orWhereIn('beacon_id', $selected_beacons)
          ->orWhereIn('geofence_id', $selected_geofences);
      })
      ->where('created_at', '>=', $from)
      ->where('created_at', '<=', $to)
      ->groupBy('model')
      ->get()
      ->toArray();

    /*
     |--------------------------------------------------------------------------
     | Platform
     |--------------------------------------------------------------------------
     */

    // Combine segments
    $segmentation_platform = [];
    foreach ($segmentation_platform_interactions as $segment) {
      if (isset($segmentation_platform[$segment['name']])) {
        $segmentation_platform[$segment['name']] += $segment['total'];
      } else {
        $segmentation_platform[$segment['name']] = $segment['total'];
      }
    }

    // Default values
    if (count($segmentation_platform) == 0) {
      $segmentation_platform['Android'] = 0;
      $segmentation_platform['iOS'] = 0;
    }

    /*
     |--------------------------------------------------------------------------
     | Model
     |--------------------------------------------------------------------------
     */

    // Combine segments
    $segmentation_model = [];
    foreach ($segmentation_model_interactions as $segment) {
      if (isset($segmentation_model[$segment['name']])) {
        $segmentation_model[$segment['name']] += $segment['total'];
      } else {
        $segmentation_model[$segment['name']] = $segment['total'];
      }
    }

    // Default values
    if (count($segmentation_model) == 0) {
      $segmentation_model[trans('global.device')] = 0;
    }

    /*
     |--------------------------------------------------------------------------
     | Heatmap
     |--------------------------------------------------------------------------
     */

    // Interactions
    $heatmap_interactions = [];
    if (! empty($funnel)) {
      $heatmap_interactions = Scenarios\Interaction::where('user_id', Core\Secure::userId())
        ->select('lat', 'lng', \DB::raw('count(id) as weight'))
        ->where('funnel_id', $funnel_id)
        ->where(function ($q) use($selected_beacons, $selected_geofences) {
          $q->orWhereIn('beacon_id', $selected_beacons)
            ->orWhereIn('geofence_id', $selected_geofences);
        })
        ->where('created_at', '>=', $from)
        ->where('created_at', '<=', $to)
        ->where('lat', '!=', null)
        ->where('lng', '!=', null)
        ->groupBy('lat')
        ->groupBy('lng')
        ->get()
        ->toArray();
    }

    $heatmap = [];

    foreach ($heatmap_interactions as $row) { $heatmap[] = ['lat' => $row['lat'], 'lng' => $row['lng'], 'weight' => $row['weight']]; } 

    return view('scenarios::analytics', compact(
      'sl', 
      'data_found', 
      'earliest_date', 
      'date_start', 
      'date_end', 
      'funnel', 
      'funnel_id', 
      'chartJson', 
      'heatmap', 
      'segmentation_platform', 
      'segmentation_model',  
      'selected_beacons', 
      'beacons', 
      'selected_geofences', 
      'geofences', 
      'location_groups'
    ));
  }

  /**
   * Get date range
   * \Modules\Scenarios\Http\Controllers\AnalyticsController::getRange($date_start, $date_end);
   */
  public static function getRange($strDateFrom, $strDateTo) {
    $aryRange = array();

    $iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
    $iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));

    if ($iDateTo >= $iDateFrom) {
      $d = ['y' => (int) date('Y', $iDateFrom), 'm' => (int) date('n', $iDateFrom), 'd' => (int) date('j', $iDateFrom)];
      $aryRange[date('Y-m-d', $iDateFrom)] = $d; // first entry
      while ($iDateFrom < $iDateTo) {
        $iDateFrom +=86400; // add 24 hours
        $d = ['y' => (int) date('Y', $iDateFrom), 'm' => (int) date('n', $iDateFrom), 'd' => (int) date('j', $iDateFrom)];
        $aryRange[date('Y-m-d', $iDateFrom)] = $d;
        //array_push($aryRange, $d);
      }
    }
    return $aryRange;
  }
}