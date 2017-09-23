<?php

namespace Modules\Scenarios\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use \Platform\Controllers\Core;
use Modules\Scenarios\Http\Models;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ScenariosController extends Controller
{
  /*
   |--------------------------------------------------------------------------
   | Scenario Controller
   |--------------------------------------------------------------------------
   |
   | Scenario related logic
   |--------------------------------------------------------------------------
   */

  /**
   * Show scenario editor
   */

  public function showEditScenarios() {
    // Create a JWT token for API calls
    $jwt_token = JWTAuth::fromUser(auth()->user());

    $funnel_id = Core\Secure::funnelId();
    $funnel = \Platform\Models\Funnels\Funnel::where('id', $funnel_id)->first();

    $scenarios = Models\Scenario::where('user_id', '=', Core\Secure::userId())->where('funnel_id', Core\Secure::funnelId())->orderBy('id', 'asc')->get();

    // Get all beacons and groups / locations
    $geofences = \Modules\Geofences\Http\Models\Geofence::where('user_id', '=', Core\Secure::userId())->where('active', '=', 1)->where('funnel_id', Core\Secure::funnelId())->orderBy('name', 'asc')->get();
    $beacons = \Modules\Beacons\Http\Models\Beacon::where('user_id', '=', Core\Secure::userId())->where('active', '=', 1)->where('funnel_id', Core\Secure::funnelId())->orderBy('name', 'asc')->get();

    // Get scenario statements
    $scenario_if = Models\ScenarioIf::all();
    $scenario_then = Models\ScenarioThen::where('active', 1)->orderBy('sort', 'asc')->get();

    if (! empty($scenarios)) {
      return view('scenarios::scenarios', compact('jwt_token', 'funnel', 'scenarios', 'geofences', 'beacons', 'scenario_if', 'scenario_then'));
    }
  }

  /**
   * Save new scenario
   */
  public function postScenario() {
    $funnel_id = request()->input('funnel_id', 0);
    $scenarios = Models\Scenario::where('user_id', '=', Core\Secure::userId())->where('funnel_id', $funnel_id)->orderBy('id', 'asc')->get();

    if(! empty($scenarios) && $funnel_id > 0) {
      // Verify limit
      /*
      $scenario_count = $scenarios->count();
      $scenario_count_limit = \Auth::user()->plan->limitations['mobile']['scenarios_per_campaign'];

      if ($scenario_count >= $scenario_count_limit) {
        return response()->json([
          'result' => 'error', 
          'result_msg' => trans('global.account_limit_reached')
        ]);
      }
      */
      $scenario = new Models\Scenario;
      $scenario->user_id = Core\Secure::userId();
      $scenario->funnel_id = $funnel_id;
    }

    if($scenario->save()) {
      $sl = Core\Secure::array2string(['scenario_id' => $scenario->id]);
      $response = [
        'result' => 'success', 
        'sl' => $sl
      ];
    } else {
      $response = [
        'result' => 'error', 
        'result_msg' => $scenario->errors()->first()
      ];
    }

    return response()->json($response);
  }

  /**
   * Update scenario
   */
  public function postUpdateScenario() {
    $name = request()->input('name', '');
    $value = request()->input('value', '');
    if($value == '') $value = NULL;
    $sl = request()->input('sl', '');

    $qs = Core\Secure::string2array($sl);
    $scenario = Models\Scenario::where('user_id', '=', Core\Secure::userId())->where('id', $qs['scenario_id'])->first();

    if(! empty($scenario)) {
      if($name == 'scenario-if') {
        $scenario->scenario_if_id = $value;
      } elseif($name == 'scenario-then') {
        $scenario->scenario_then_id = $value;
      } elseif($name == 'days-of-week') {
        if (isset($value) && is_array($value)) {
          $scenario->day_of_week_mo = false;
          $scenario->day_of_week_tu = false;
          $scenario->day_of_week_we = false;
          $scenario->day_of_week_th = false;
          $scenario->day_of_week_fr = false;
          $scenario->day_of_week_sa = false;
          $scenario->day_of_week_su = false;

          foreach ($value as $day) {
            $scenario->{'day_of_week_' . $day} = true;
          }
        } else {
          $scenario->day_of_week_mo = true;
          $scenario->day_of_week_tu = true;
          $scenario->day_of_week_we = true;
          $scenario->day_of_week_th = true;
          $scenario->day_of_week_fr = true;
          $scenario->day_of_week_sa = true;
          $scenario->day_of_week_su = true;
        }

      } elseif($name == 'datepicker-range') {
        $date_start = request()->input('date_start', '');
        $date_end = request()->input('date_end', '');

        if($date_start == '') $date_start = NULL;
        $scenario->date_start = $date_start;

        if($date_end == '') $date_end = NULL;
        $scenario->date_end = $date_end;
      } elseif($name == 'time-range-start') {
        if($value == '') $value = NULL;
        $scenario->time_start = $value;
      } elseif($name == 'time-range-end') {
        if($value == '') $value = NULL;
        $scenario->time_end = $value;
      } elseif($name == 'notification') {
        $notification_title = request()->input('title', '');
        $scenario->notification_title = $notification_title;
        $scenario->notification_message = $value;
      } elseif($name == 'app_image') {
        $scenario->app_image = $value;
      } elseif($name == 'open_url') {
        $scenario->open_url = $value;
      } elseif($name == 'template') {
        $scenario->template = $value;
      } elseif($name == 'show_image') {
        $scenario->show_image = $value;
      } elseif($name == 'config') {
        $frequency = request()->input('frequency', '');
        $scenario->frequency = $frequency;

        $delay = request()->input('delay', '');
        $scenario->delay = $delay;
      }

      $scenario->save();
    }

    return response()->json(['result' => 'success']);
  }

  /**
   * Update scenario beacons
   */
  public function postUpdateScenarioPlaces() {
    $places = request()->input('places', '');
    $sl = request()->input('sl', '');
    $qs = Core\Secure::string2array($sl);
    $scenario = Models\Scenario::where('user_id', '=', Core\Secure::userId())->where('id', $qs['scenario_id'])->first();

    if(! empty($scenario)) {
      $geofences = array();
      $beacons = array();

      if ($places != '') {
        foreach($places as $place) {
          if (starts_with($place, 'geofence')) {
            $id = str_replace('geofence', '', $place);
            array_push($geofences, $id); 
          }
  
          if (starts_with($place, 'beacon')) {
            $id = str_replace('beacon', '', $place);
            array_push($beacons, $id); 
          }
        }
      }

      $scenario->geofences()->sync($geofences);
      $scenario->beacons()->sync($beacons);
    }

    return response()->json(['result' => 'success']);
  }

  /**
   * Delete scenario
   */
  public function postDeleteScenario() {
    $sl = request()->input('sl', '');
    $qs = Core\Secure::string2array($sl);

    $scenario = Models\Scenario::where('user_id', '=', Core\Secure::userId())->where('id', $qs['scenario_id'])->first();
    if(! empty($scenario)) $scenario->forceDelete();

    return response()->json(['result' => 'success']);
  }

  /**
   * WYSIWYG editor
   */
  public function showTemplateEditor() {
    $i = request()->input('i', '');

    return view('scenarios::edit-template', compact('i'));
  }

  /**
   * QR
   */
  public function showQr() {
    $funnel_id = Core\Secure::funnelId();
    $funnel = \Platform\Models\Funnels\Funnel::where('id', $funnel_id)->first();

    $funnel_token = $funnel->api_token;
    $account_token = auth()->user()->api_token;

    return view('scenarios::qr', compact('funnel_token', 'account_token'));
  }

  /**
   * QR
   */
  public function showAppRequired() {

    $reseller = \Platform\Controllers\Core\Reseller::get();

    return view('scenarios::app-required', compact('reseller'));
  }

}
