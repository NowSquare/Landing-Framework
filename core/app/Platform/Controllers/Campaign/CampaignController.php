<?php
namespace Platform\Controllers\Campaign;

use \Platform\Controllers\Core;
use \Platform\Models\Location;
use \Platform\Models\Campaigns;
use Illuminate\Http\Request;

class CampaignController extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | Campaign controller
   |--------------------------------------------------------------------------
   |
   | Campaign related logic
   |
   */

  /**
   * Show campaigns
   */
  public function showCampaigns()
  {
    return view('platform.campaigns.campaigns');
  }

  /**
   * New campaign
   */
  public function showNewCampaign()
  {
    $authUser = Core\Secure::user();
    $apps = Campaigns\App::where('user_id', '=', Core\Secure::userId())->orderBy('name', 'asc')->get();

    return view('platform.campaigns.campaign-new', compact('apps', 'authUser'));
  }

  /**
   * Update campaign
   */
  public function showEditCampaign()
  {
    $sl = request()->input('sl', '');

    if($sl != '') {
      $qs = Core\Secure::string2array($sl);
      $campaign = Campaigns\Campaign::where('id', $qs['campaign_id'])->where('user_id', '=', Core\Secure::userId())->first();
      $apps = Campaigns\App::where('user_id', '=', Core\Secure::userId())->orderBy('name', 'asc')->get();

      return view('platform.campaigns.campaign-edit', compact('sl', 'campaign', 'apps'));
    }
  }

  /**
   * Add / update campaign
   */
  public function postCampaign() {
    $authUser = Core\Secure::user();

    $sl = request()->input('sl', NULL);
    $name = request()->input('name');
    $apps = request()->input('apps', []);
    $timezone = request()->input('timezone', $authUser->timezone);
    $language = request()->input('language', $authUser->language);
    //$radius = request()->input('radius', NULL);
    //$lat = request()->input('lat', NULL);
    //$lng = request()->input('lng', NULL);
    //$zoom = request()->input('zoom', NULL);
    $active = (boolean) request()->input('active', false);

    if($sl != NULL) {
      $qs = Core\Secure::string2array($sl);
      $campaign = Campaigns\Campaign::where('id', $qs['campaign_id'])->where('user_id', '=', $authUser->id)->first();
    } else {
      // Verify limit
      $campaign_count = Campaigns\Campaign::where('user_id', '=', $authUser->id)->count();
      $campaign_count_limit = \Auth::user()->plan->limitations['mobile']['campaigns'];

      if ($campaign_count >= $campaign_count_limit) {
        return response()->json([
          'type' => 'error', 
          'msg' => trans('global.account_limit_reached'),
          'reset' => false
        ]);
      }

      $campaign = new Campaigns\Campaign;
    }

    $campaign->user_id = $authUser->id;
    $campaign->name = $name;
    $campaign->timezone = $timezone;
    $campaign->language = $language;
    //$campaign->radius = $radius;
    //$campaign->lat = $lat;
    //$campaign->lng = $lng;
    //$campaign->zoom = $zoom;
    $campaign->active = $active;
    //$campaign->setLocationAttribute($lng . ',' . $lat);

    if($campaign->save()) {
      $campaign->apps()->sync($apps);
      $response = ['redir' => '#/campaigns'];
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
    $filename = Core\Reseller::get()->name . '-' . str_slug(trans('global.campaigns')) . '-' . date('Y-m-d h:i:s');
    $campaigns = Campaigns\Campaign::where('campaigns.user_id', Core\Secure::userId())
      ->select(\DB::raw("
        name as '" . trans('global.name') . "', 
        active as '" . trans('global.active') . "', 
        created_at as '" . trans('global.created') . "', 
        updated_at as '" . trans('global.updated') . "'"))->get();

    \Excel::create($filename, function($excel) use($campaigns) {
      $excel->sheet(trans('global.campaigns'), function($sheet) use($campaigns) {
        $sheet->fromArray($campaigns);
      });
    })->download($type);
  }

  /**
   * Delete campaign(s)
   */
  public function postDelete()
  {
    $sl = request()->input('sl', '');

    if(\Auth::check() && $sl != '')
    {
      $qs = Core\Secure::string2array($sl);

      $campaign = Campaigns\Campaign::where('id', '=',  $qs['campaign_id'])->where('user_id', '=',  Core\Secure::userId())->delete();
    }
    elseif (\Auth::check())
    {
      foreach(request()->input('ids', array()) as $id)
      {
        $affected = Campaigns\Campaign::where('id', '=', $id)->where('user_id', '=',  Core\Secure::userId())->delete();
      }
    }

    return response()->json(array('result' => 'success'));
  }

  /**
   * Switch campaign(s)
   */
  public function postSwitch()
  {
    if(\Auth::check())
    {
      foreach(request()->input('ids', array()) as $id)
      {
        $current = Campaigns\Campaign::where('id', '=', $id)->first();
        $switch = ($current->active == 1) ? 0 : 1;
        $affected = Campaigns\Campaign::where('id', '=', $id)->where('user_id', '=',  Core\Secure::userId())->update(array('active' => $switch));
      }
    }

    return response()->json(array('result' => 'success'));
  }

  /**
   * Get campaign list data
   */
  public function getCampaignData(Request $request)
  {
    $order_by = $request->input('order.0.column', 0);
    $order = $request->input('order.0.dir', 'asc');
    $search = $request->input('search.regex', '');
    $q = $request->input('search.value', '');
    $start = $request->input('start', 0);
    $draw = $request->input('draw', 1);
    $length = $request->input('length', 10);
    $data = array();    
    
    $aColumn = array('campaigns.name', '', '', '', 'created_at', 'campaigns.active');

    if($q != '')
    {
      $count = Campaigns\Campaign::orderBy($aColumn[$order_by], $order)
        ->select(array('campaigns.id', 'campaigns.name', 'campaigns.active', 'campaigns.created_at'))
        ->where(function ($query) {
          $query->where('campaigns.user_id', '=', Core\Secure::userId());
        })
        ->where(function ($query) use($q) {
          $query->orWhere('campaigns.name', 'like', '%' . $q . '%');
        })
        ->count();

      $oData = Campaigns\Campaign::orderBy($aColumn[$order_by], $order)
        ->select(array('campaigns.id', 'campaigns.name', 'campaigns.active', 'campaigns.created_at'))
        ->where(function ($query) {
          $query->where('campaigns.user_id', '=', Core\Secure::userId());
        })
        ->where(function ($query) use($q) {
          $query->orWhere('campaigns.name', 'like', '%' . $q . '%');
        })
        ->take($length)->skip($start)->get();
    }
    else
    {
      $count = Campaigns\Campaign::where('campaigns.user_id', '=', Core\Secure::userId())->count();

      $oData = Campaigns\Campaign::where('campaigns.user_id', '=', Core\Secure::userId())
        ->select(array('campaigns.id', 'campaigns.name', 'campaigns.active', 'campaigns.created_at'))
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
      $app_array = $row->apps->pluck('name', 'id')->toArray();
      $apps = [];
      foreach ($app_array as $key => $val) {
        $apps[] = ['sl' => Core\Secure::array2string(array('app_id' => $key)), 'name' => $val];
      }

      $data[] = array(
        'DT_RowId' => 'row_' . $row->id,
        'name' => $row->name,
        'active' => $row->active,
        'apps' => $apps,
        'scenarios' => $row->scenarios->count(),
        'created_at' => $row->created_at->timezone(\Auth::user()->timezone)->format('Y-m-d H:i:s'),
        'sl' => Core\Secure::array2string(array('campaign_id' => $row->id))
        /*,
        'created_at' => $row->created_at->timezone(Auth::user()->timezone)->format(trans('global.dateformat_full'))*/
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