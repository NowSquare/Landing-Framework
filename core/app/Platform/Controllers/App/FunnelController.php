<?php namespace Platform\Controllers\App;

use \Platform\Controllers\Core;
use Illuminate\Support\Facades\Gate;
use \Platform\Models\Funnels;

class FunnelController extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | Funnel Controller
   |--------------------------------------------------------------------------
   |
   | Funnel related logic
   |--------------------------------------------------------------------------
   */

  /**
   * Funnels
   */

  public function showFunnels() {

    // Get funnels
    $funnels = Funnels\Funnel::where('user_id', Core\Secure::userId())->orderBy('name', 'asc')->get();

    // Current funnel
    $funnel_id = Core\Secure::funnelId();

    return view('platform.funnels.funnels', compact('funnels', 'funnel_id'));
  }

  /**
   * Select funnel
   */

  public function selectFunnel() {
    $sl_funnel = request()->get('sl_funnel', '');

    if ($sl_funnel != '') {
      $qs = Core\Secure::string2array($sl_funnel);
      $funnel = Funnels\Funnel::where('user_id', Core\Secure::userId())->where('id', $qs['funnel_id'])->first();

      if (! empty($funnel)) {
        session(['funnel' => $sl_funnel]);

        $response = array(
          'type' => 'success',
          'redir' => 'reload'
        );
      } else {
        $response = array(
          'type' => 'error',
          'reset' => false, 
          'msg' => 'No funnel found'
        );
      }
    } else {
      $response = array(
        'type' => 'error',
        'reset' => false, 
        'msg' => 'No funnel found'
      );
    }
  
    return response()->json($response);    
  }

  /**
   * Create funnel
   */

  public function showCreateFunnel() {

    // Get funnels
    $funnels = Funnels\Funnel::where('user_id', Core\Secure::userId())->orderBy('name', 'asc')->first();

    return view('platform.funnels.funnel-new', compact('funnels'));
  }

  /**
   * Post create funnel
   */

  public function postCreateFunnel() {

    $name = request()->get('name', '');
    $name = substr($name, 0, 64);

    if ($name != '') {

      $funnel = new Funnels\Funnel;

      $funnel->name = $name;
      $funnel->user_id = auth()->user()->id;
      //$funnel->language = (auth()->check()) ? auth()->user()->language : Core\Reseller::get()->default_language;
      //$funnel->timezone = (auth()->check()) ? auth()->user()->timezone : Core\Reseller::get()->default_timezone;

      if($funnel->save()) {
        // Set funnel session
        $sl = Core\Secure::array2string(array('funnel_id' => $funnel->id));
        session(['funnel' => $sl]);

        $response = array(
          'type' => 'success',
          'redir' => '#/'
        );
      } else {
        $response = array(
          'type' => 'error',
          'reset' => false, 
          'msg' => $funnel->errors()->first()
        );
      }

    }
    return response()->json($response);
  }

  /**
   * Post edit funnel
   */

  public function postEditFunnel() {
    $sl_funnel = request()->get('sl', '');
    $name = request()->get('name', '');
    $name = substr($name, 0, 64);

    if ($sl_funnel != '' && $name != '') {
      $qs = Core\Secure::string2array($sl_funnel);
      $funnel = Funnels\Funnel::where('user_id', Core\Secure::userId())->where('id', $qs['funnel_id'])->first();

      $funnel->name = $name;

      if($funnel->save()) {
        $response = array(
          'type' => 'success',
          'redir' => 'reload'
        );
      } else {
        $response = array(
          'type' => 'error',
          'reset' => false, 
          'msg' => $funnel->errors()->first()
        );
      }

    } else {
      $response = array(
        'type' => 'error',
        'reset' => false, 
        'msg' => "Missing input"
      );
    }
    return response()->json($response);
  }

  /**
   * Post delete funnel
   */

  public function postDeleteFunnel() {
    $sl_funnel = request()->get('sl', '');

    if ($sl_funnel != '') {
      $qs = Core\Secure::string2array($sl_funnel);
      $funnel = Funnels\Funnel::where('user_id', Core\Secure::userId())->where('id', $qs['funnel_id'])->first();

      // Current funnel
      $funnel_id = Core\Secure::funnelId();

      if ($funnel_id == $funnel->id) {
        // Switch to other funnel if exists
        $funnel_new = Funnels\Funnel::where('user_id', Core\Secure::userId())->where('id', '<>', $qs['funnel_id'])->orderBy('name', 'asc')->first();
        if (! empty($funnel_new)) {
          // Switch session
          $sl = Core\Secure::array2string(array('funnel_id' => $funnel_new->id));
          session(['funnel' => $sl]);
        } else {
          // There are no other funnels, remove session
          session()->forget('funnel');
        }
      }

      // Delete funnel
      $funnel->forceDelete();

      $response = array(
        'type' => 'success',
        'redir' => 'reload'
      );
    } else {
      $response = array(
        'type' => 'error',
        'reset' => false, 
        'msg' => "Missing input"
      );
    }
    return response()->json($response);
  }
}