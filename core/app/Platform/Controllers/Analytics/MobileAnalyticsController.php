<?php namespace Platform\Controllers\Analytics;

use \Platform\Controllers\Core;
use \Platform\Controllers\Analytics;
use \Platform\Models\Analytics as ModelAnalytics;
use \Platform\Models\Location;
use \Platform\Models\Campaigns;
use \Platform\Models\Categories;
use Illuminate\Http\Request;

class MobileAnalyticsController extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | Campaign Analytics Controller
   |--------------------------------------------------------------------------
   |
   | Campaign Analytics related logic
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

    $campaign_id = '';

    if ($sl != '') {
      $qs = Core\Secure::string2array($sl);
      $campaign_id = $qs['campaign_id'];
      $sl = rawurlencode($sl);
    }

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
     | Selected campaign(s)
     |--------------------------------------------------------------------------
     */

    /*
     |--------------------------------------------------------------------------
     | Campaigns
     |--------------------------------------------------------------------------
     */
    $campaigns = Campaigns\Campaign::where('user_id', Core\Secure::userId())
      ->where('active', 1)
      ->orderBy('created_at', 'asc')
      ->get();

    /*
     |--------------------------------------------------------------------------
     | Get earliest date for campaign selection
     |--------------------------------------------------------------------------
     */
    $earliest_date = date('Y-m-d');

    // Get earliest date of card
    if (is_numeric($campaign_id)) {

      $card = \DB::select("select DATE(created_at) as date
        from `cards` 
        where `user_id` = :user_id 
        and exists (select * from `campaigns` inner join `campaign_card` on `campaigns`.`id` = `campaign_card`.`campaign_id` where `campaign_card`.`campaign_id` in (:campaign_id)) 
        order by date asc
        limit 1", 
      [
        'user_id' => Core\Secure::userId(),
        'campaign_id' => $campaign_id
      ]);

    } elseif(is_array($campaign_id)) {
      $campaign_ids = implode(',', $campaign_id);

      $card = \DB::select("select DATE(created_at) as date
        from `cards` 
        where `user_id` = :user_id 
        and exists (select * from `campaigns` inner join `campaign_card` on `campaigns`.`id` = `campaign_card`.`campaign_id` where `campaign_card`.`campaign_id` in (:campaign_ids)) 
        order by date asc
        limit 1", 
      [
        'user_id' => Core\Secure::userId(),
        'campaign_ids' => $campaign_ids
      ]);
    }  else {
      $card = \DB::select("select DATE(created_at) as date
        from `cards` 
        where `user_id` = :user_id 
        order by date asc
        limit 1", 
      [
        'user_id' => Core\Secure::userId()
      ]);
    }

    if (count($card) > 0) {
      if ($card[0]->date < $earliest_date) $earliest_date = $card[0]->date;
    }

    // Get earliest date of campaign
    if (is_numeric($campaign_id)) {
      $campaign = Campaigns\Campaign::where('user_id', Core\Secure::userId())
        ->select(\DB::raw('DATE(created_at) as date'))
        ->where('id', $campaign_id)
        ->orderBy('date', 'asc')
        ->first();
    } elseif(is_array($campaign_id)) {
      $campaign = Campaigns\Campaign::where('user_id', Core\Secure::userId())
        ->select(\DB::raw('DATE(created_at) as date'))
        ->whereIn('id', $campaign_id)
        ->orderBy('date', 'asc')
        ->first();
    } else {
      $campaign = Campaigns\Campaign::where('user_id', Core\Secure::userId())
        ->select(\DB::raw('DATE(created_at) as date'))
        ->orderBy('date', 'asc')
        ->first();
    }

    if (! empty($campaign)) {
      if ($campaign->date < $earliest_date) $earliest_date = $campaign->date;
    }

    // Get linked places
    if (is_numeric($campaign_id)) {
      $campaign = Campaigns\Campaign::where('user_id', Core\Secure::userId())->where('id', $campaign_id)->first();
      $available_beacons = [];
      $available_geofences = [];

      foreach ($campaign->scenarios as $scenario) {
        foreach ($scenario->beacons as $beacon) {
          $available_beacons[] = $beacon->id;
        }
        foreach ($scenario->geofences as $geofence) {
          $available_geofences[] = $geofence->id;
        }
      }

    } elseif(is_array($campaign_id)) {
      // ToDo
    } else {
      $campaigns = Campaigns\Campaign::where('user_id', Core\Secure::userId())->get();
      $available_beacons = [];
      $available_geofences = [];

      foreach ($campaigns as $campaign) {
        foreach ($campaign->scenarios as $scenario) {
          foreach ($scenario->beacons as $beacon) {
            $available_beacons[] = $beacon->id;
          }
          foreach ($scenario->geofences as $geofence) {
            $available_geofences[] = $geofence->id;
          }
        }
      }
    }

    asort($available_beacons);
    asort($available_geofences);

    if ($filter == '') {
      $selected_beacons = $available_beacons;
      $selected_geofences = $available_geofences;
    }

    // Get all gefences and beacons without group and all locations 
    $available_beacons_wo_group = Location\Beacon::whereIn('id', $available_beacons)->where('user_id', '=', Core\Secure::userId())->where('location_group_id', NULL)->orderBy('name', 'asc')->get();
    $available_geofences_wo_group = Location\Geofence::whereIn('id', $available_geofences)->where('user_id', '=', Core\Secure::userId())->where('location_group_id', NULL)->orderBy('name', 'asc')->get();
    $location_groups = Location\LocationGroup::where('user_id', '=', Core\Secure::userId())->orderBy('name', 'asc')->get();

    // Query details for selected places
    $beacons = Location\Beacon::whereIn('id', $selected_beacons)->where('user_id', '=', Core\Secure::userId())->where('created_at', '>=', $from)->where('created_at', '<=', $to)->orderBy('triggers', 'desc')->get();
    $geofences = Location\Geofence::whereIn('id', $selected_geofences)->where('user_id', '=', Core\Secure::userId())->where('created_at', '>=', $from)->where('created_at', '<=', $to)->orderBy('triggers', 'desc')->get();

    if (count($beacons) == 0) {
      $beacons[0] = new \stdClass;
      $beacons[0]->name = '';
      $beacons[0]->triggers = 0;
    }

    if (count($geofences) == 0) {
      $geofences[0] = new \stdClass;
      $geofences[0]->name = '';
      $geofences[0]->triggers = 0;
    }

    /*
     |--------------------------------------------------------------------------
     | Count views and interactions
     |--------------------------------------------------------------------------
     */

    // Card views
    // Raw query because of this issue: https://github.com/laravel/framework/issues/18523

    // This is not working because of bug above:
    /*
    $stats_card_views = ModelAnalytics\CardStat::where('user_id', Core\Secure::userId())
      ->whereHas('campaigns', function($query) use ($campaign_id) { 
        $query->whereIn('campaign_card.campaign_id', [$campaign_id]);     
      })
      ->select(\DB::raw('DATE(created_at) as date'), \DB::raw('count(id) as views'))
      ->where('created_at', '>=', $from)
      ->where('created_at', '<=', $to)
      ->groupBy([\DB::raw('DATE(created_at)')])
      ->get()
      ->toArray();
    */

    if (is_numeric($campaign_id)) {
      $stats_card_views = \DB::select("select DATE(created_at) as date, count(id) as views 
        from `card_stats` 
        where `user_id` = :user_id 
        and exists (select * from `campaigns` inner join `campaign_card` on `campaigns`.`id` = `campaign_card`.`campaign_id` where `campaign_card`.`campaign_id` in (:campaign_id)) 
        and `created_at` >= :from and `created_at` <= :to 
        group by DATE(created_at)", 
      [
        'user_id' => Core\Secure::userId(),
        'campaign_id' => $campaign_id,
        'from' => $from,
        'to' => $to
      ]);
    } elseif(is_array($campaign_id)) {
      $campaign_ids = implode(',', $campaign_id);
      $stats_card_views = \DB::select("select DATE(created_at) as date, count(id) as views 
        from `card_stats` 
        where `user_id` = :user_id 
        and exists (select * from `campaigns` inner join `campaign_card` on `campaigns`.`id` = `campaign_card`.`campaign_id` where `campaign_card`.`campaign_id` in (:campaign_ids)) 
        and `created_at` >= :from and `created_at` <= :to 
        group by DATE(created_at)", 
      [
        'user_id' => Core\Secure::userId(),
        'campaign_ids' => $campaign_ids,
        'from' => $from,
        'to' => $to
      ]);
    }  else {
      $stats_card_views = \DB::select("select DATE(created_at) as date, count(id) as views 
        from `card_stats` 
        where `user_id` = :user_id 
        and `created_at` >= :from and `created_at` <= :to 
        group by DATE(created_at)", 
      [
        'user_id' => Core\Secure::userId(),
        'from' => $from,
        'to' => $to
      ]);
    }

    // Interactions
    if (is_numeric($campaign_id)) {
      $stats_interactions = Location\Interaction::where('user_id', Core\Secure::userId())
        ->select(\DB::raw('DATE(created_at) as date'), \DB::raw('count(id) as interactions'), 'beacon_id', 'geofence_id')
        ->where('campaign_id', $campaign_id)
        ->where('created_at', '>=', $from)
        ->where('created_at', '<=', $to)
        ->groupBy([\DB::raw('DATE(created_at)'), 'beacon_id', 'geofence_id'])
        ->get()
        ->toArray();
    } elseif(is_array($campaign_id)) {
      $stats_interactions = Location\Interaction::where('user_id', Core\Secure::userId())
        ->select(\DB::raw('DATE(created_at) as date'), \DB::raw('count(id) as interactions'), 'beacon_id', 'geofence_id')
        ->whereIn('campaign_id', $campaign_id)
        ->where('created_at', '>=', $from)
        ->where('created_at', '<=', $to)
        ->groupBy([\DB::raw('DATE(created_at)'), 'beacon_id', 'geofence_id'])
        ->get()
        ->toArray();
    } else {
      $stats_interactions = Location\Interaction::where('user_id', Core\Secure::userId())
        ->select(\DB::raw('DATE(created_at) as date'), \DB::raw('count(id) as interactions'), 'beacon_id', 'geofence_id')
        ->where('created_at', '>=', $from)
        ->where('created_at', '<=', $to)
        ->groupBy([\DB::raw('DATE(created_at)'), 'beacon_id', 'geofence_id'])
        ->get()
        ->toArray();
    }

    // Create range for chart
    $main_chart_range = Analytics\AnalyticsController::getRange($date_start, $date_end);

    // Merge stats with range
    foreach($main_chart_range as $date => $arr) {
      // Views
      $views = ($date < $earliest_date) ? NULL : 0;
      foreach($stats_card_views as $row) {
        if ($date == $row->date) {
          $views = $row->views;
          break 1;
        }
      }

      $arr = array_merge(['views' => $views], $arr);

      // Interactions
      $interactions = 0;
      $interactions = ($date < $earliest_date) ? NULL : 0;
      foreach($stats_interactions as $row) {
        if ($date == $row['date']) {
          if ($filter == '' || ($filter != '' && (in_array($row['beacon_id'], $selected_beacons) || in_array($row['geofence_id'], $selected_geofences)))) {
            $interactions += $row['interactions'];
          }
          //break 1;
        }
      }

      $arr = array_merge(['interactions' => $interactions], $arr);
      $main_chart_range[$date] = $arr;
    }

    /*
     |--------------------------------------------------------------------------
     | Pie charts
     |--------------------------------------------------------------------------
     */

    if (is_numeric($campaign_id)) {

      /*
       |--------------------------------------------------------------------------
       | Platform
       |--------------------------------------------------------------------------
       */

      $segmentation_platform_interactions = Location\Interaction::where('user_id', Core\Secure::userId())
        ->select('platform as name', \DB::raw('count(id) as total'))
        ->where('campaign_id', $campaign_id)
        ->where(function ($q) use($selected_beacons, $selected_geofences) {
          $q->orWhereIn('beacon_id', $selected_beacons)
            ->orWhereIn('geofence_id', $selected_geofences);
        })
        ->where('created_at', '>=', $from)
        ->where('created_at', '<=', $to)
        ->groupBy('platform')
        ->get()
        ->toArray();

      $segmentation_platform_cards = \DB::select("select platform as name, count(id) as total 
        from `card_stats` 
        where `user_id` = :user_id 
        and exists (select * from `campaigns` inner join `campaign_card` on `campaigns`.`id` = `campaign_card`.`campaign_id` where `campaign_card`.`campaign_id` in (:campaign_id)) 
        and `created_at` >= :from and `created_at` <= :to 
        group by `platform`", 
      [
        'user_id' => Core\Secure::userId(),
        'campaign_id' => $campaign_id,
        'from' => $from,
        'to' => $to
      ]);

      /*
       |--------------------------------------------------------------------------
       | Model
       |--------------------------------------------------------------------------
       */

      $segmentation_model_interactions = Location\Interaction::where('user_id', Core\Secure::userId())
        ->select('model as name', \DB::raw('count(id) as total'))
        ->where('campaign_id', $campaign_id)
        ->where(function ($q) use($selected_beacons, $selected_geofences) {
          $q->orWhereIn('beacon_id', $selected_beacons)
            ->orWhereIn('geofence_id', $selected_geofences);
        })
        ->where('created_at', '>=', $from)
        ->where('created_at', '<=', $to)
        ->groupBy('model')
        ->get()
        ->toArray();

      $segmentation_model_cards = \DB::select("select model as name, count(id) as total 
        from `card_stats` 
        where `user_id` = :user_id 
        and exists (select * from `campaigns` inner join `campaign_card` on `campaigns`.`id` = `campaign_card`.`campaign_id` where `campaign_card`.`campaign_id` in (:campaign_id)) 
        and `created_at` >= :from and `created_at` <= :to 
        group by `model`", 
      [
        'user_id' => Core\Secure::userId(),
        'campaign_id' => $campaign_id,
        'from' => $from,
        'to' => $to
      ]);

    } elseif(is_array($campaign_id)) {

      /*
       |--------------------------------------------------------------------------
       | Platform
       |--------------------------------------------------------------------------
       */

      $segmentation_platform_interactions = Location\Interaction::where('user_id', Core\Secure::userId())
        ->select('platform as name', \DB::raw('count(id) as total'))
        ->whereIn('campaign_id', $campaign_id)
        ->where(function ($q) use($selected_beacons, $selected_geofences) {
          $q->orWhereIn('beacon_id', $selected_beacons)
            ->orWhereIn('geofence_id', $selected_geofences);
        })
        ->where('created_at', '>=', $from)
        ->where('created_at', '<=', $to)
        ->groupBy('platform')
        ->get()
        ->toArray();

      $campaign_ids = implode(',', $campaign_id);
      $segmentation_platform_cards = \DB::select("select platform as name, count(id) as total 
        from `card_stats` 
        where `user_id` = :user_id 
        and exists (select * from `campaigns` inner join `campaign_card` on `campaigns`.`id` = `campaign_card`.`campaign_id` where `campaign_card`.`campaign_id` in (:campaign_ids)) 
        and `created_at` >= :from and `created_at` <= :to 
        group by `platform`", 
      [
        'user_id' => Core\Secure::userId(),
        'campaign_ids' => $campaign_ids,
        'from' => $from,
        'to' => $to
      ]);

      /*
       |--------------------------------------------------------------------------
       | Model
       |--------------------------------------------------------------------------
       */

      $segmentation_model_interactions = Location\Interaction::where('user_id', Core\Secure::userId())
        ->select('model as name', \DB::raw('count(id) as total'))
        ->whereIn('campaign_id', $campaign_id)
        ->where(function ($q) use($selected_beacons, $selected_geofences) {
          $q->orWhereIn('beacon_id', $selected_beacons)
            ->orWhereIn('geofence_id', $selected_geofences);
        })
        ->where('created_at', '>=', $from)
        ->where('created_at', '<=', $to)
        ->groupBy('model')
        ->get()
        ->toArray();

      $campaign_ids = implode(',', $campaign_id);
      $segmentation_model_cards = \DB::select("select model as name, count(id) as total 
        from `card_stats` 
        where `user_id` = :user_id 
        and exists (select * from `campaigns` inner join `campaign_card` on `campaigns`.`id` = `campaign_card`.`campaign_id` where `campaign_card`.`campaign_id` in (:campaign_ids)) 
        and `created_at` >= :from and `created_at` <= :to 
        group by `model`", 
      [
        'user_id' => Core\Secure::userId(),
        'campaign_ids' => $campaign_ids,
        'from' => $from,
        'to' => $to
      ]);

    } else {

      /*
       |--------------------------------------------------------------------------
       | Platform
       |--------------------------------------------------------------------------
       */

      $segmentation_platform_interactions = Location\Interaction::where('user_id', Core\Secure::userId())
        ->select('platform as name', \DB::raw('count(id) as total'))
        ->where(function ($q) use($selected_beacons, $selected_geofences) {
          $q->orWhereIn('beacon_id', $selected_beacons)
            ->orWhereIn('geofence_id', $selected_geofences);
        })
        ->where('created_at', '>=', $from)
        ->where('created_at', '<=', $to)
        ->groupBy('platform')
        ->get()
        ->toArray();

      $segmentation_platform_cards = \DB::select("select platform as name, count(id) as total 
        from `card_stats` 
        where `user_id` = :user_id 
        and `created_at` >= :from and `created_at` <= :to 
        group by `platform`", 
      [
        'user_id' => Core\Secure::userId(),
        'from' => $from,
        'to' => $to
      ]);

      /*
       |--------------------------------------------------------------------------
       | Model
       |--------------------------------------------------------------------------
       */

      $segmentation_model_interactions = Location\Interaction::where('user_id', Core\Secure::userId())
        ->select('model as name', \DB::raw('count(id) as total'))
        ->where(function ($q) use($selected_beacons, $selected_geofences) {
          $q->orWhereIn('beacon_id', $selected_beacons)
            ->orWhereIn('geofence_id', $selected_geofences);
        })
        ->where('created_at', '>=', $from)
        ->where('created_at', '<=', $to)
        ->groupBy('model')
        ->orderBy('total', 'desc')
        ->get()
        ->toArray();

      $segmentation_model_cards = \DB::select("select model as name, count(id) as total 
        from `card_stats` 
        where `user_id` = :user_id 
        and `created_at` >= :from and `created_at` <= :to 
        group by `model`", 
      [
        'user_id' => Core\Secure::userId(),
        'from' => $from,
        'to' => $to
      ]);

    }

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

    // Card views
    // Raw query because of this issue: https://github.com/laravel/framework/issues/18523
    $heatmap_card_views = [];
    if (is_numeric($campaign_id)) {
      $heatmap_card_views = \DB::select("select lat, lng, count(id) as weight 
        from `card_stats` 
        where `user_id` = :user_id 
        and not isNull(lat) and not isNull(lng)
        and exists (select * from `campaigns` inner join `campaign_card` on `campaigns`.`id` = `campaign_card`.`campaign_id` where `campaign_card`.`campaign_id` in (:campaign_id)) 
        and `created_at` >= :from and `created_at` <= :to 
        group by lat, lng", 
      [
        'user_id' => Core\Secure::userId(),
        'campaign_id' => $campaign_id,
        'from' => $from,
        'to' => $to
      ]);
    } elseif(is_array($campaign_id)) {
      $campaign_ids = implode(',', $campaign_id);
      $heatmap_card_views = \DB::select("select lat, lng, count(id) as weight 
        from `card_stats` 
        where `user_id` = :user_id 
        and not isNull(lat) and not isNull(lng)
        and exists (select * from `campaigns` inner join `campaign_card` on `campaigns`.`id` = `campaign_card`.`campaign_id` where `campaign_card`.`campaign_id` in (:campaign_ids)) 
        and `created_at` >= :from and `created_at` <= :to 
        group by lat, lng", 
      [
        'user_id' => Core\Secure::userId(),
        'campaign_ids' => $campaign_ids,
        'from' => $from,
        'to' => $to
      ]);
    }  else {
      $heatmap_card_views = \DB::select("select lat, lng, count(id) as weight 
        from `card_stats` 
        where `user_id` = :user_id 
        and not isNull(lat) and not isNull(lng)
        and `created_at` >= :from and `created_at` <= :to 
        group by lat, lng", 
      [
        'user_id' => Core\Secure::userId(),
        'from' => $from,
        'to' => $to
      ]);
    }

    // Interactions
    $heatmap_interactions = [];
    if (is_numeric($campaign_id)) {
      $heatmap_interactions = Location\Interaction::where('user_id', Core\Secure::userId())
        ->select('lat', 'lng', \DB::raw('count(id) as weight'))
        ->where('campaign_id', $campaign_id)
        ->where(function ($q) use($selected_beacons, $selected_geofences) {
          $q->orWhereIn('beacon_id', $selected_beacons)
            ->orWhereIn('geofence_id', $selected_geofences);
        })
        ->where('created_at', '>=', $from)
        ->where('created_at', '<=', $to)
        ->groupBy('lat')
        ->groupBy('lng')
        ->get()
        ->toArray();
    } elseif(is_array($campaign_id)) {
      $heatmap_interactions = Location\Interaction::where('user_id', Core\Secure::userId())
        ->select('lat', 'lng', \DB::raw('count(id) as weight'))
        ->whereIn('campaign_id', $campaign_id)
        ->where(function ($q) use($selected_beacons, $selected_geofences) {
          $q->orWhereIn('beacon_id', $selected_beacons)
            ->orWhereIn('geofence_id', $selected_geofences);
        })
        ->where('created_at', '>=', $from)
        ->where('created_at', '<=', $to)
        ->groupBy('lat')
        ->groupBy('lng')
        ->get()
        ->toArray();
    } else {
      $heatmap_interactions = Location\Interaction::where('user_id', Core\Secure::userId())
        ->select('lat', 'lng', \DB::raw('count(id) as weight'))
        ->where(function ($q) use($selected_beacons, $selected_geofences) {
          $q->orWhereIn('beacon_id', $selected_beacons)
            ->orWhereIn('geofence_id', $selected_geofences);
        })
        ->where('created_at', '>=', $from)
        ->where('created_at', '<=', $to)
        ->whereNotNull('lat')->whereNotNull('lng')
        ->groupBy('lat')->groupBy('lng')
        ->get()
        ->toArray();
    }

    $heatmap = [];
    
    foreach ($heatmap_card_views as $row) { $heatmap[] = ['lat' => $row->lat, 'lng' => $row->lng, 'weight' => $row->weight]; } 
    foreach ($heatmap_interactions as $row) { $heatmap[] = ['lat' => $row['lat'], 'lng' => $row['lng'], 'weight' => $row['weight']]; } 

    return view('platform.analytics.mobile-analytics', compact(
      'sl', 
      'earliest_date', 
      'date_start', 
      'date_end', 
      'campaigns', 
      'campaign_id', 
      'main_chart_range', 
      'heatmap', 
      'segmentation_platform', 
      'segmentation_model', 
      'categories', 
      'available_categories', 
      'campaigns', 
      'available_campaigns', 
      'available_beacons_wo_group', 
      'available_beacons', 
      'selected_beacons', 
      'beacons', 
      'available_geofences_wo_group', 
      'available_geofences', 
      'selected_geofences', 
      'geofences', 
      'location_groups'
    ));
  }
}