<?php

namespace Modules\Beacons\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use \Platform\Controllers\Core;
use Modules\Beacons\Http\Models;

class BeaconsController extends Controller
{
  /**
   * Overview
   */
  public function showBeacons()
  {
    $beacons = Models\Beacon::where('user_id', Core\Secure::userId())->where('funnel_id', Core\Secure::funnelId())->get();

    if ($beacons->count() == 0) {
      return $this->showCreateBeacon(true);
    } else {
      return view('beacons::overview', compact('beacons'));
    }
  }

  /**
   * Create new
   */
  public function showCreateBeacon($first = false)
  {
    // Get all uuids
    $uuids_add = Models\BeaconUuid::where('user_id', '=', Core\Secure::userId())->orderBy('uuid', 'asc')->select('uuid')->get()->mapWithKeys_v2(function ($item) {
      return [$item['uuid'] => $item['uuid']];
    })->toArray();

    $uuids[''] = ['' => '&nbsp;'];
    $uuids['NEW'] = '+ ' . trans('beacons::global.add_new_uuid');

    if (count($uuids_add) > 0) $uuids[trans('beacons::global.existing')] = $uuids_add;

    $uuids[trans('beacons::global.vendors')] = trans('beacons::global.beacon_vendor_uuids');

    return view('beacons::create', compact('first', 'uuids'));
  }

  /**
   * Update beacon
   */
  public function showEditBeacon()
  {
    $sl = request()->input('sl', '');

    if($sl != '') {
      $qs = Core\Secure::string2array($sl);
      $beacon = Models\Beacon::where('id', $qs['beacon_id'])->where('user_id', '=', Core\Secure::userId())->first();

      // Get all uuids
      $uuids_add = Models\BeaconUuid::where('user_id', '=', Core\Secure::userId())->orderBy('uuid', 'asc')->select('uuid')->get()->mapWithKeys_v2(function ($item) {
        return [$item['uuid'] => $item['uuid']];
      })->toArray();

      $uuids[''] = ['' => '&nbsp;'];
      $uuids['NEW'] = '+ ' . trans('beacons::global.add_new_uuid');

      if (count($uuids_add) > 0) $uuids[trans('beacons::global.existing')] = $uuids_add;

      $uuids[trans('beacons::global.vendors')] = trans('beacons::global.beacon_vendor_uuids');

      return view('beacons::edit', compact('sl', 'beacon', 'uuids'));
    }
  }

  /**
   * Add beacon UUID
   */
  public function postBeaconUuid()
  {
    $beacon_uuid = new Models\BeaconUuid;

    $beacon_uuid->user_id = Core\Secure::userId();
    $beacon_uuid->uuid = request()->input('inputValue', NULL);

    $beacon_uuid->save();

    return response()->json(array('id' => $beacon_uuid->uuid));
  }

  /**
   * Add / update beacon
   */
  public function postBeacon()
  {
    $sl = request()->input('sl', NULL);
    $name = request()->input('name');
    $lat = request()->input('lat', NULL);
    $lng = request()->input('lng', NULL);
    $zoom = request()->input('zoom', NULL);
    $uuid = request()->input('uuid', NULL);
    $major = request()->input('major', NULL);
    $minor = request()->input('minor', NULL);
    $active = (boolean) request()->input('active', false);

    if($sl != NULL)
    {
      $qs = Core\Secure::string2array($sl);
      $beacon = Models\Beacon::where('id', $qs['beacon_id'])->where('user_id', '=', Core\Secure::userId())->first();
    }
    else
    {
      // Verify limit
      $beacon_count = Models\Beacon::where('user_id', '=', Core\Secure::userId())->count();
      $beacon_count_limit = \Auth::user()->plan->limitations['beacons']['max'];

      if ($beacon_count >= $beacon_count_limit) {
        return response()->json([
          'type' => 'error', 
          'msg' => trans('global.account_limit_reached'),
          'reset' => false
        ]);
      }

      $beacon = new Models\Beacon;
    }

    $beacon->user_id = Core\Secure::userId();
    $beacon->funnel_id = Core\Secure::funnelId();
    $beacon->name = $name;
    $beacon->uuid = $uuid;
    $beacon->major = $major;
    $beacon->minor = $minor;
    $beacon->lat = $lat;
    $beacon->lng = $lng;
    $beacon->zoom = $zoom;
    $beacon->active = $active;
    $beacon->setLocationAttribute($lng . ',' . $lat);

    if($beacon->save())
    {
      $response = array(
        'redir' => '#/beacons'
      );
    }
    else
    {
      $response = array(
        'type' => 'error', 
        'msg' => $beacon->errors()->first(),
        'reset' => false
      );
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
    $filename = Core\Reseller::get()->name . '-' . str_slug(trans('beacons::global.beacons')) . '-' . date('Y-m-d h:i:s');
    $beacons = Models\Beacon::where('beacons.user_id', Core\Secure::userId())->where('beacons.funnel_id', '=', Core\Secure::funnelId())
      ->select(\DB::raw("
        beacons.name as '" . trans('global.name') . "', 
        uuid as UUID,
        major as '" . trans('beacons::global.major') . "', 
        minor as '" . trans('beacons::global.minor') . "', 
        lat as '" . trans('beacons::global.latitude') . "', 
        lng as '" . trans('beacons::global.longitude') . "', 
        zoom as '" . trans('beacons::global.zoom') . "', 
        active as '" . trans('global.active') . "', 
        beacons.created_at as '" . trans('global.created') . "', 
        beacons.updated_at as '" . trans('global.updated') . "'"))->get();

    \Excel::create($filename, function($excel) use($beacons) {
      $excel->sheet(trans('geofences::global.beacons'), function($sheet) use($beacons) {
        $sheet->fromArray($beacons);
      });
    })->download($type);
  }

  /**
   * Delete beacon(s)
   */
  public function postDelete()
  {
    $sl = request()->input('sl', '');

    if(\Auth::check() && $sl != '') {
      $qs = Core\Secure::string2array($sl);

      $beacon = Models\Beacon::where('id', '=',  $qs['beacon_id'])->where('user_id', '=',  Core\Secure::userId())->delete();
    } elseif (\Auth::check()) {
      foreach(request()->input('ids', array()) as $id) {
        $affected = Models\Beacon::where('id', '=', $id)->where('user_id', '=',  Core\Secure::userId())->delete();
      }
    }

    return response()->json(array('result' => 'success'));
  }

  /**
   * Switch beacon(s)
   */
  public function postSwitch()
  {
    if(\Auth::check()) {
      foreach(request()->input('ids', array()) as $id) {
        $current = Models\Beacon::where('id', '=', $id)->first();
        $switch = ($current->active == 1) ? 0 : 1;
        $affected = Models\Beacon::where('id', '=', $id)->where('user_id', '=',  Core\Secure::userId())->update(array('active' => $switch));
      }
    }

    return response()->json(array('result' => 'success'));
  }

  /**
   * Get beacon list data
   */
  public function getBeaconData(Request $request)
  {
    $order_by = $request->input('order.0.column', 0);
    $order = $request->input('order.0.dir', 'asc');
    $search = $request->input('search.regex', '');
    $q = $request->input('search.value', '');
    $start = $request->input('start', 0);
    $draw = $request->input('draw', 1);
    $length = $request->input('length', 10);
    $data = array();    
    
    $aColumn = array('beacons.name', 'beacons.uuid', 'beacons.major', 'beacons.minor', 'beacons.active');

    if($q != '')
    {
      $count = Models\Beacon::where(function ($query) {
          $query->where('beacons.user_id', '=', Core\Secure::userId());
          $query->where('beacons.funnel_id', '=', Core\Secure::funnelId());
        })->orderBy($aColumn[$order_by], $order)
        ->select(array('beacons.id', 'beacons.name', 'beacons.uuid', 'beacons.major', 'beacons.minor', 'beacons.lat', 'beacons.lng', 'beacons.zoom', 'beacons.active'))
        ->where(function ($query) use($q) {
          $query->orWhere('beacons.name', 'like', '%' . $q . '%');
          $query->orWhere('beacons.uuid', 'like', '%' . $q . '%');
          $query->orWhere('beacons.major', 'like', '%' . $q . '%');
          $query->orWhere('beacons.minor', 'like', '%' . $q . '%');
          $query->orWhere('bg.name', 'like', '%' . $q . '%');
        })
        ->count();

      $oData = Models\Beacon::where(function ($query) {
          $query->where('beacons.user_id', '=', Core\Secure::userId());
          $query->where('beacons.funnel_id', '=', Core\Secure::funnelId());
        })->orderBy($aColumn[$order_by], $order)
        ->select(array('beacons.id', 'beacons.name', 'beacons.uuid', 'beacons.major', 'beacons.minor', 'beacons.lat', 'beacons.lng', 'beacons.zoom', 'beacons.active'))
        ->where(function ($query) use($q) {
          $query->orWhere('beacons.name', 'like', '%' . $q . '%');
          $query->orWhere('beacons.uuid', 'like', '%' . $q . '%');
          $query->orWhere('beacons.major', 'like', '%' . $q . '%');
          $query->orWhere('beacons.minor', 'like', '%' . $q . '%');
        })
        ->take($length)->skip($start)->get();
    }
    else
    {
      $count = Models\Beacon::where('beacons.user_id', '=', Core\Secure::userId())->where('funnel_id', Core\Secure::funnelId())->count();

      $oData = Models\Beacon::where('beacons.user_id', '=', Core\Secure::userId())
        ->where('funnel_id', Core\Secure::funnelId())
        ->select(array('beacons.id', 'beacons.name', 'beacons.uuid', 'beacons.major', 'beacons.minor', 'beacons.lat', 'beacons.lng', 'beacons.zoom', 'beacons.active'))
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
        'uuid' => $row->uuid,
        'major' => ($row->major === NULL) ? '-' : $row->major,
        'minor' => ($row->minor === NULL) ? '-' : $row->minor,
        'lng' => $row->lng,
        'lat' => $row->lat,
        'zoom' => $row->zoom,
        'active' => $row->active,
        'sl' => Core\Secure::array2string(array('beacon_id' => $row->id))
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
