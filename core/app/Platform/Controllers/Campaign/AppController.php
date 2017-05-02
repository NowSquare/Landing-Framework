<?php
namespace Platform\Controllers\Campaign;

use \Platform\Controllers\Core;
use \Platform\Models\Location;
use \Platform\Models\Campaigns;
use Illuminate\Http\Request;

class AppController extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | App controller
   |--------------------------------------------------------------------------
   |
   | App related logic
   |
   */

  /**
   * Show apps
   */
  public function showApps()
  {
    return view('platform.campaigns.apps');
  }

  /**
   * New app
   */
  public function showNewApp()
  {
    return view('platform.campaigns.app-new');
  }

  /**
   * Update app
   */
  public function showEditApp()
  {
    $sl = request()->input('sl', '');

    if($sl != '') {
      $qs = Core\Secure::string2array($sl);
      $app = Campaigns\App::where('id', $qs['app_id'])->where('user_id', '=', Core\Secure::userId())->first();

      return view('platform.campaigns.app-edit', compact('sl', 'app'));
    }
  }

  /**
   * Add / update app
   */
  public function postApp(Request $request) {
    $authUser = Core\Secure::user();

    $sl = request()->input('sl', NULL);
    $name = request()->input('name');
    $api_token = request()->input('api_token', '');
    $active = (boolean) request()->input('active', false);

    $validator = \Validator::make($request->all(), [
      'name' => 'required|max:32',
      'api_token' => 'required|min:60|max:60'
    ]);

    if ($validator->fails()) {
      return response()->json([
        'type' => 'error', 
        'msg' => $validator->errors()->first(),
        'reset' => false
      ]);
    }

    if($sl != NULL) {
      $qs = Core\Secure::string2array($sl);
      $app = Campaigns\App::where('id', $qs['app_id'])->where('user_id', '=', $authUser->id)->first();
    } else {
      // Verify limit
      $app_count = Campaigns\App::where('user_id', '=', $authUser->id)->count();
      $app_count_limit = \Auth::user()->plan->limitations['mobile']['apps'];

      if ($app_count >= $app_count_limit) {
        return response()->json([
          'type' => 'error', 
          'msg' => trans('global.account_limit_reached'),
          'reset' => false
        ]);
      }

      $app = new Campaigns\App;
    }

    if ($api_token == '') $api_token = str_random(60);

    $app->user_id = $authUser->id;
    $app->name = $name;
    $app->api_token = $api_token;
    $app->active = $active;

    if($app->save()) {
      $response = ['redir' => '#/campaign/apps'];
    } else {
      $response = [
        'type' => 'error', 
        'msg' => $app->errors()->first(),
        'reset' => false
      ];
    }

    return response()->json($response);
  }

  /**
   * Export
   */

  public function getExport()
  {
    $type = request()->input('type', 'xls');
    if (! in_array($type, ['xls', 'xlsx', 'csv'])) $type = 'xls';
    $filename = Core\Reseller::get()->name . '-' . str_slug(trans('global.apps')) . '-' . date('Y-m-d h:i:s');
    $apps = Campaigns\App::where('campaign_apps.user_id', Core\Secure::userId())
      ->select(\DB::raw("
        name as '" . trans('global.name') . "', 
        api_token as '" . trans('global.api_token') . "', 
        created_at as '" . trans('global.created') . "', 
        updated_at as '" . trans('global.updated') . "'"))->get();

    \Excel::create($filename, function($excel) use($apps) {
      $excel->sheet(trans('global.apps'), function($sheet) use($apps) {
        $sheet->fromArray($apps);
      });
    })->download($type);
  }

  /**
   * Delete app(s)
   */
  public function postDelete()
  {
    $sl = request()->input('sl', '');

    if(\Auth::check() && $sl != '')
    {
      $qs = Core\Secure::string2array($sl);

      $app = Campaigns\App::where('id', '=',  $qs['app_id'])->where('user_id', '=',  Core\Secure::userId())->delete();
    }
    elseif (\Auth::check())
    {
      foreach(request()->input('ids', array()) as $id)
      {
        $affected = Campaigns\App::where('id', '=', $id)->where('user_id', '=',  Core\Secure::userId())->delete();
      }
    }

    return response()->json(array('result' => 'success'));
  }

  /**
   * Switch app(s)
   */
  public function postSwitch()
  {
    if(\Auth::check())
    {
      foreach(request()->input('ids', array()) as $id)
      {
        $current = Campaigns\App::where('id', '=', $id)->first();
        $switch = ($current->active == 1) ? 0 : 1;
        $affected = Campaigns\App::where('id', '=', $id)->where('user_id', '=',  Core\Secure::userId())->update(array('active' => $switch));
      }
    }

    return response()->json(array('result' => 'success'));
  }

  /**
   * Get app list data
   */
  public function getAppData(Request $request)
  {
    $order_by = $request->input('order.0.column', 0);
    $order = $request->input('order.0.dir', 'asc');
    $search = $request->input('search.regex', '');
    $q = $request->input('search.value', '');
    $start = $request->input('start', 0);
    $draw = $request->input('draw', 1);
    $length = $request->input('length', 10);
    $data = array();    
    
    $aColumn = array('campaign_apps.name', 'campaign_apps.api_token', 'created_at', 'campaign_apps.active');

    if($q != '')
    {
      $count = Campaigns\App::orderBy($aColumn[$order_by], $order)
        ->select(array('campaign_apps.id', 'campaign_apps.name', 'campaign_apps.api_token', 'campaign_apps.active', 'campaign_apps.created_at'))
        ->where(function ($query) {
          $query->where('campaign_apps.user_id', '=', Core\Secure::userId());
        })
        ->where(function ($query) use($q) {
          $query->orWhere('campaign_apps.name', 'like', '%' . $q . '%');
          $query->orWhere('campaign_apps.api_token', 'like', '%' . $q . '%');
        })
        ->count();

      $oData = Campaigns\App::orderBy($aColumn[$order_by], $order)
        ->select(array('campaign_apps.id', 'campaign_apps.name', 'campaign_apps.api_token', 'campaign_apps.active', 'campaign_apps.created_at'))
        ->where(function ($query) {
          $query->where('campaign_apps.user_id', '=', Core\Secure::userId());
        })
        ->where(function ($query) use($q) {
          $query->orWhere('campaign_apps.name', 'like', '%' . $q . '%');
          $query->orWhere('campaign_apps.api_token', 'like', '%' . $q . '%');
        })
        ->take($length)->skip($start)->get();
    }
    else
    {
      $count = Campaigns\App::where('campaign_apps.user_id', '=', Core\Secure::userId())->count();

      $oData = Campaigns\App::where('campaign_apps.user_id', '=', Core\Secure::userId())
        ->select(array('campaign_apps.id', 'campaign_apps.name', 'campaign_apps.api_token', 'campaign_apps.active', 'campaign_apps.created_at'))
        ->orderBy($aColumn[$order_by], $order)
        ->take($length)
        ->skip($start)
        ->get();
    }

    if($length == -1) $length = $count;

    $recordsTotal = $count;
    $recordsFiltered = $count;

    foreach($oData as $row)
    {
      $data[] = array(
        'DT_RowId' => 'row_' . $row->id,
        'name' => $row->name,
        'active' => $row->active,
        'api_token' => $row->api_token,
        'created_at' => $row->created_at->timezone(\Auth::user()->timezone)->format('Y-m-d H:i:s'),
        'sl' => Core\Secure::array2string(array('app_id' => $row->id))
      );
    }

    $response = array(
      'draw' => $draw,
      'recordsTotal' => $recordsTotal,
      'recordsFiltered' => $recordsFiltered,
      'data' => $data
    );

    echo json_encode($response);
  }
}