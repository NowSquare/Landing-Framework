<?php 
namespace Modules\Scenarios\Http\Controllers;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use \Platform\Controllers\Core;
use Illuminate\Http\Request;
use Modules\Scenarios\Http\Models;

class ApiController extends \App\Http\Controllers\Controller
{
  /*
  |--------------------------------------------------------------------------
  | Api Controller
  |--------------------------------------------------------------------------
  |
  | Api related logic
  |--------------------------------------------------------------------------
  */

  /**
   * Post scenario interaction from app
   */

  public function postTrigger() {
    // Add interaction
    $type = request()->json('type', null);
    $type_id = request()->json('type_id', null); // Beacon or geofence id
    $device_uuid = request()->json('device_uuid', null);
    $scenario_id = request()->json('scenario_id', null);
    $lat = request()->json('lat', null);
    $lng = request()->json('lng', null);
    $model = request()->json('model', null);
    $platform = request()->json('platform', null);

    if ($device_uuid != null && $type != null && $scenario_id != null) {
      $scenario = Models\Scenario::where('id', '=', $scenario_id)->first();

      $state = ($scenario->scenario_if_id == 1) ? 'enter' : 'exit';

      // Inrement scenario triggers
      $scenario->increment('triggers');

      $interaction = new Models\Interaction;

      $interaction->user_id = $scenario->user_id;
      $interaction->funnel_id = $scenario->funnel_id;
      $interaction->device_uuid = $device_uuid;
      $interaction->model = $model;
      $interaction->platform = $platform;
      $interaction->ip = request()->ip();
      $interaction->lat = $lat;
      $interaction->lng = $lng;
      $interaction->scenario_id = $scenario_id;
      $interaction->state = $state;

      if ($type == 'beacon') {
        $beacon = \Modules\Beacons\Http\Models\Beacon::where('id', '=', $type_id)->first();
        $name = (! empty($beacon)) ? $beacon->name : null;

        $interaction->beacon_id = $type_id;
        $interaction->beacon = $name;

        // Increment beacon triggers
        $beacon->increment('triggers');

      } elseif ($type == 'geofence') {
        $geofence = \Modules\Geofences\Http\Models\Geofence::where('id', '=', $type_id)->first();
        $name = (! empty($geofence)) ? $geofence->name : null;

        $interaction->geofence_id = $type_id;
        $interaction->geofence = $name;

        // Increment geofence triggers
        $geofence->increment('triggers');
      }

      $interaction->save();

      return response()->json([true]);
    }
  }
  
  /**
   * API response
   */
  public static function getApiResponse($type = '')
  {
    $take_distance_into_account = false;

    $preview = (boolean) request()->input('preview', false);
    $token = request()->input('token', null);
    $timezone = request()->input('tz', 'UTC');
    $lat = request()->input('lat', env('GMAPS_DEFAULT_LAT'));
    $lng = request()->input('lng', env('GMAPS_DEFAULT_LNG'));
    $accuracy = request()->input('acc', 0);

    if ($accuracy > 1000) $accuracy = 1000;
    if ($preview) $accuracy = 1000 * 30000;

    $distance_beacons = 100 + $accuracy;
    $distance_geofences = 50000 + $accuracy;

    //\DB::enableQueryLog();
    //dd(\DB::getQueryLog());

    $reseller = Core\Reseller::get();
    $meta_name = $reseller->name;
    $meta_logo = $reseller->logo_square;
    $meta_bar_color = "#b224ef";
    $meta_bar_text_color = "#ffffff";
    $meta_text_color = "#333333";
    $meta_bg_color = "#eeeeee";
    $meta_text = "";

    if ($token == null) {
      die('No token provided');
      //$funnels = \Platform\Models\Funnels\Funnel::get();
    } else {
      if ($type == 'reseller') {
        $reseller = \App\Reseller::where('api_token', $token)->first();
        if (empty($reseller)) {
          return response()->json(['error' => 'Token not recognized']);
        }
        $users = \App\User::where('reseller_id', $reseller->id)->pluck('id')->toArray();
        $funnels = \Platform\Models\Funnels\Funnel::whereIn('user_id', $users)->get();
      } elseif ($type == 'account') {
        $user = \App\User::where('api_token', $token)->first();
        $funnels = \Platform\Models\Funnels\Funnel::where('user_id', $user->id)->get();
      } elseif ($type == 'funnel') {
        $funnels = \Platform\Models\Funnels\Funnel::where('api_token', $token)->get();
        $meta_name = $funnels[0]->name;
      }
    }

    if (empty($funnels)) {
      return response()->json(['error' => 'Token not recognized']);
    }

    $found_geofences = [];
    $found_beacons = [];
    $available_geofences = [];
    $available_beacons = [];
    $available_scenarios = [];
    $count_beacon = 0;
    $count_geofence = 0;

    $user = \App\User::where('id', $funnels[0]->user_id)->first();

    foreach ($funnels as $funnel) {
      $scenarios = $funnel->scenarios()->whereNotNull('scenario_then_id')->get();

      foreach($scenarios as $scenario) {
        $scenario_beacons = [];
        if ($take_distance_into_account) {
          $beacons = $scenario
            ->beacons()
            ->distance($distance_beacons, $lat . ',' . $lng)
            ->orderBy('distance', 'asc')
            ->take(20)
            ->skip(0)
            ->get();
        } else {
          $beacons = $scenario
            ->beacons()
            ->take(20)
            ->skip(0)
            ->get();
        }

        foreach($beacons as $beacon) {
          if ($beacon->active == 1 && !in_array($beacon->beacon_id, $scenario_beacons)) {
            array_push($scenario_beacons, $beacon->beacon_id);
          }

          if ($beacon->active == 1 && !in_array($beacon->beacon_id, $found_beacons)) {
            $available_beacons[$count_beacon] = array(
              'id' => $beacon->beacon_id,
              'identifier' => $beacon->name,
              'uuid' => $beacon->uuid,
              'major' => $beacon->major,
              'minor' => $beacon->minor,
              'lat' => $beacon->lat,
              'lng' => $beacon->lng,
            );
            array_push($found_beacons, $beacon->beacon_id);
            $count_beacon++;
          }
        }

        $scenario_geofences = [];
        if ($take_distance_into_account) {
          $geofences = $scenario
            ->geofences()
            ->distance($distance_geofences, $lat . ',' . $lng)
            ->orderBy('distance', 'asc')
            ->take(100)
            ->skip(0)
            ->get();
        } else {
          $geofences = $scenario
            ->geofences()
            ->take(100)
            ->skip(0)
            ->get();
        }

        foreach($geofences as $geofence) {
          if ($geofence->active == 1 && !in_array($geofence->geofence_id, $scenario_geofences)) {
            array_push($scenario_geofences, $geofence->geofence_id);
          }

          if ($geofence->active == 1 && !in_array($geofence->geofence_id, $found_geofences)) {
            $available_geofences[$count_geofence] = array(
              'id' => $geofence->geofence_id,
              'identifier' => $geofence->name,
              'lat' => $geofence->lat,
              'lng' => $geofence->lng,
              'radius' => $geofence->radius
            );
            array_push($found_geofences, $geofence->geofence_id);
            $count_geofence++;
          }
        }

        // Check if scenario has (valid) output
        $scenario_has_output = true;

        switch ($scenario->scenario_then_id) {

          // show_image
          case 2:
            if ($scenario->show_image == '') $scenario_has_output = false;
            break;

          // show_template
          case 3:
            if ($scenario->template == null) $scenario_has_output = false;
            break;

          // open_url
          case 4:
            if ($scenario->open_url == '') $scenario_has_output = false;
            break;
        }

        // Check if notification is required
        switch ($scenario->scenario_if_id) {
          case 1:
          case 2:
            if ($scenario->notification_message == '') $scenario_has_output = false;
            break;
        }

        if ($scenario->app_image != '' && $scenario_has_output && $scenario->active == 1 && $scenario->scenario_then_id != null && (! empty($scenario_beacons) || ! empty($scenario_geofences))) {

          // Set scenario_then_id because some scenarios merge
          $scenario_then_id = $scenario->scenario_then_id;
          $open_url = $scenario->open_url;
          $open_url = ($scenario->scenario_then_id == 3 && $scenario->template != null) ? url('/scenarios/view/template/' . Core\Secure::staticHash($scenario->id, true)) : $open_url;
          $open_url = ($scenario->scenario_then_id == 2 && $scenario->show_image != null) ? url('/scenarios/view/image/' . Core\Secure::staticHash($scenario->id, true)) : $open_url;

          // Default date/time
          $day_time['mon'] = ((bool) $scenario->day_of_week_mo) ? ['00:00:00-23:59:59'] : [];
          $day_time['tue'] = ((bool) $scenario->day_of_week_tu) ? ['00:00:00-23:59:59'] : [];
          $day_time['wed'] = ((bool) $scenario->day_of_week_we) ? ['00:00:00-23:59:59'] : [];
          $day_time['thu'] = ((bool) $scenario->day_of_week_th) ? ['00:00:00-23:59:59'] : [];
          $day_time['fri'] = ((bool) $scenario->day_of_week_fr) ? ['00:00:00-23:59:59'] : [];
          $day_time['sat'] = ((bool) $scenario->day_of_week_sa) ? ['00:00:00-23:59:59'] : [];
          $day_time['sun'] = ((bool) $scenario->day_of_week_su) ? ['00:00:00-23:59:59'] : [];

          // Translate date and time to general timezone
          if ($user->timezone != $timezone) {
            if ($scenario->date_start != null) {
              $date = \Carbon\Carbon::createFromFormat('Y-m-d', $scenario->date_start, $user->timezone);
              $scenario->date_start = $date->setTimezone('UTC')->format('Y-m-d');
            }

            if ($scenario->date_end != null) {
              $date = \Carbon\Carbon::createFromFormat('Y-m-d', $scenario->date_end, $user->timezone);
              $scenario->date_end = $date->setTimezone('UTC')->format('Y-m-d');
            }

            if ($scenario->time_start != null) {
              $date = \Carbon\Carbon::createFromFormat('H:i:s', $scenario->time_start, $user->timezone);
              $scenario->time_start = $date->setTimezone('UTC')->format('H:i:s');
            }

            if ($scenario->time_end != null) {
              $date = \Carbon\Carbon::createFromFormat('H:i:s', $scenario->time_end, $user->timezone);
              $scenario->time_end = $date->setTimezone('UTC')->format('H:i:s');
            }
          }

          if ($scenario->time_start != null && $scenario->time_end != null) {
            $day_time['mon'] = ((bool) $scenario->day_of_week_mo) ? [$scenario->time_start . '-' . $scenario->time_end] : [];
            $day_time['tue'] = ((bool) $scenario->day_of_week_tu) ? [$scenario->time_start . '-' . $scenario->time_end] : [];
            $day_time['wed'] = ((bool) $scenario->day_of_week_we) ? [$scenario->time_start . '-' . $scenario->time_end] : [];
            $day_time['thu'] = ((bool) $scenario->day_of_week_th) ? [$scenario->time_start . '-' . $scenario->time_end] : [];
            $day_time['fri'] = ((bool) $scenario->day_of_week_fr) ? [$scenario->time_start . '-' . $scenario->time_end] : [];
            $day_time['sat'] = ((bool) $scenario->day_of_week_sa) ? [$scenario->time_start . '-' . $scenario->time_end] : [];
            $day_time['sun'] = ((bool) $scenario->day_of_week_su) ? [$scenario->time_start . '-' . $scenario->time_end] : [];
          }

          if ($scenario->time_start != null && $scenario->time_end == null) {
            $day_time['mon'] = ((bool) $scenario->day_of_week_mo) ? [$scenario->time_start . '-23:59:59'] : [];
            $day_time['tue'] = ((bool) $scenario->day_of_week_tu) ? [$scenario->time_start . '-23:59:59'] : [];
            $day_time['wed'] = ((bool) $scenario->day_of_week_we) ? [$scenario->time_start . '-23:59:59'] : [];
            $day_time['thu'] = ((bool) $scenario->day_of_week_th) ? [$scenario->time_start . '-23:59:59'] : [];
            $day_time['fri'] = ((bool) $scenario->day_of_week_fr) ? [$scenario->time_start . '-23:59:59'] : [];
            $day_time['sat'] = ((bool) $scenario->day_of_week_sa) ? [$scenario->time_start . '-23:59:59'] : [];
            $day_time['sun'] = ((bool) $scenario->day_of_week_su) ? [$scenario->time_start . '-23:59:59'] : [];
          }

          if ($scenario->time_start == null && $scenario->time_end != null) {
            $day_time['mon'] = ((bool) $scenario->day_of_week_mo) ? ['00:00:00-' . $scenario->time_end] : [];
            $day_time['tue'] = ((bool) $scenario->day_of_week_tu) ? ['00:00:00-' . $scenario->time_end] : [];
            $day_time['wed'] = ((bool) $scenario->day_of_week_we) ? ['00:00:00-' . $scenario->time_end] : [];
            $day_time['thu'] = ((bool) $scenario->day_of_week_th) ? ['00:00:00-' . $scenario->time_end] : [];
            $day_time['fri'] = ((bool) $scenario->day_of_week_fr) ? ['00:00:00-' . $scenario->time_end] : [];
            $day_time['sat'] = ((bool) $scenario->day_of_week_sa) ? ['00:00:00-' . $scenario->time_end] : [];
            $day_time['sun'] = ((bool) $scenario->day_of_week_su) ? ['00:00:00-' . $scenario->time_end] : [];
          }

          $available_scenarios[] = array(
            'id' => $scenario->id,
            'scenario_if_id' => $scenario->scenario_if_id,
            'scenario_then_id' => $scenario_then_id,
            'day_time' => $day_time,
            'time_start' => $scenario->time_start,
            'time_end' => $scenario->time_end,
            'date_start' => $scenario->date_start,
            'date_end' => $scenario->date_end,
            'frequency' => $scenario->frequency,
            'delay' => $scenario->delay,
            'notification_title' => str_replace('%', '%%', $scenario->notification_title),
            'notification_message' => str_replace('%', '%%', $scenario->notification_message),
            'app_image' => $scenario->app_image,
            'open_url' => $open_url,
            'geofences' => $scenario_geofences,
            'beacons' => $scenario_beacons
          );
        }
      }
    }

    $response = array(
      'meta' => [
        'timezone' => $timezone
      ],
      'geofences' => $available_geofences,
      'beacons' => $available_beacons,
      'scenarios' => $available_scenarios
    );

    return $response;
  }
}