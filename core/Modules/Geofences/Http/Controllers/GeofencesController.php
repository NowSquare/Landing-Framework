<?php

namespace Modules\Geofences\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use \Platform\Controllers\Core;
use Modules\Geofences\Http\Models;

class GeofencesController extends Controller
{
  /**
   * Show geofences
   */
  public function showGeofences()
  {
    $geofences = Models\Geofence::where('user_id', '=', Core\Secure::userId())->where('funnel_id', Core\Secure::funnelId())->get();

    if ($geofences->count() == 0) {
      return $this->showCreateGeofence(true);
    } else {
      return view('geofences::overview', compact('geofences'));
    }
  }

  /**
   * New geofence
   */
  public function showCreateGeofence($first = false)
  {
    return view('geofences::create', compact('first'));
  }

  /**
   * Update geofence
   */
  public function showEditGeofence()
  {
    $sl = request()->input('sl', '');

    if($sl != '') {
      $qs = Core\Secure::string2array($sl);
      $geofence = Models\Geofence::where('id', $qs['geofence_id'])->where('user_id', '=', Core\Secure::userId())->first();

      return view('geofences::edit', compact('sl', 'geofence'));
    }
  }

  /**
   * Add / update geofence
   */
  public function postGeofence()
  {
    $sl = request()->input('sl', NULL);
    $name = request()->input('name');
    $radius = request()->input('radius', NULL);
    $lat = request()->input('lat', NULL);
    $lng = request()->input('lng', NULL);
    $zoom = request()->input('zoom', NULL);
    $active = (boolean) request()->input('active', false);

    if($sl != NULL)
    {
      $qs = Core\Secure::string2array($sl);
      $geofence = Models\Geofence::where('id', $qs['geofence_id'])->where('user_id', '=', Core\Secure::userId())->first();
    }
    else
    {
      // Verify limit
      $geofence_count = Models\Geofence::where('user_id', '=', Core\Secure::userId())->count();
      $geofence_count_limit = \Auth::user()->plan->limitations['geofences']['max'];

      if ($geofence_count >= $geofence_count_limit) {
        return response()->json([
          'type' => 'error', 
          'msg' => trans('global.account_limit_reached'),
          'reset' => false
        ]);
      } elseif ($geofence_count >= 100) {
        return response()->json([
          'type' => 'error', 
          'msg' => trans('geofences::global.geofence_limit_reached'),
          'reset' => false
        ]);
      }
      $geofence = new Models\Geofence;
    }

    $geofence->user_id = Core\Secure::userId();
    $geofence->funnel_id = Core\Secure::funnelId();
    $geofence->name = $name;
    $geofence->radius = $radius;
    $geofence->lat = $lat;
    $geofence->lng = $lng;
    $geofence->zoom = $zoom;
    $geofence->active = $active;
    $geofence->setLocationAttribute($lng . ',' . $lat);

    if($geofence->save())
    {
      $response = array(
        'redir' => '#/geofences'
      );
    }
    else
    {
      $response = array(
        'type' => 'error', 
        'msg' => $geofence->errors()->first(),
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
    $filename = Core\Reseller::get()->name . '-' . str_slug(trans('geofences::global.geofences')) . '-' . date('Y-m-d h:i:s');

    $geofences = Models\Geofence::where('geofences.user_id', Core\Secure::userId())->where('geofences.funnel_id', '=', Core\Secure::funnelId())
      ->select(\DB::raw("
        geofences.name as '" . trans('global.name') . "', 
        lat as '" . trans('geofences::global.latitude') . "', 
        lng as '" . trans('geofences::global.longitude') . "', 
        radius as '" . trans('geofences::global.radius') . "', 
        zoom as '" . trans('geofences::global.zoom') . "', 
        active as '" . trans('global.active') . "', 
        geofences.created_at as '" . trans('global.created') . "', 
        geofences.updated_at as '" . trans('global.updated') . "'"))->get();

    \Excel::create($filename, function($excel) use($geofences) {
      $excel->sheet(trans('geofences::global.geofences'), function($sheet) use($geofences) {
        $sheet->fromArray($geofences);
      });
    })->download($type);
  }

  /**
   * Delete geofence(s)
   */
  public function postDelete()
  {
    $sl = request()->input('sl', '');

    if(\Auth::check() && $sl != '') {
      $qs = Core\Secure::string2array($sl);

      $geofence = Models\Geofence::where('id', '=',  $qs['geofence_id'])->where('user_id', '=',  Core\Secure::userId())->delete();
    } elseif (\Auth::check()) {
      foreach(request()->input('ids', array()) as $id) {
        $affected = Models\Geofence::where('id', '=', $id)->where('user_id', '=',  Core\Secure::userId())->delete();
      }
    }

    return response()->json(array('result' => 'success'));
  }

  /**
   * Switch geofence(s)
   */
  public function postSwitch()
  {
    if(\Auth::check())
    {
      foreach(request()->input('ids', array()) as $id)
      {
        $current = Models\Geofence::where('id', '=', $id)->first();
        $switch = ($current->active == 1) ? 0 : 1;
        $affected = Models\Geofence::where('id', '=', $id)->where('user_id', '=',  Core\Secure::userId())->update(array('active' => $switch));
      }
    }

    return response()->json(array('result' => 'success'));
  }

  /**
   * Get geofence list data
   */
  public function getGeofenceData(Request $request)
  {
    $order_by = $request->input('order.0.column', 0);
    $order = $request->input('order.0.dir', 'asc');
    $search = $request->input('search.regex', '');
    $q = $request->input('search.value', '');
    $start = $request->input('start', 0);
    $draw = $request->input('draw', 1);
    $length = $request->input('length', 10);
    $data = array();    
    
    $aColumn = array('geofences.name', 'geofences.lng', 'geofences.active');

    if($q != '')
    {
      $count = Models\Geofence::where(function ($query) {
          $query->where('geofences.user_id', '=', Core\Secure::userId());
          $query->where('geofences.funnel_id', '=', Core\Secure::funnelId());
        })
        ->orderBy($aColumn[$order_by], $order)
        ->select(array('geofences.id', 'geofences.name', 'geofences.radius', 'geofences.lng', 'geofences.lat', 'geofences.active'))
        ->where(function ($query) use($q) {
          $query->orWhere('geofences.name', 'like', '%' . $q . '%');
          $query->orWhere('geofences.radius', 'like', '%' . $q . '%');
          $query->orWhere('geofences.lng', 'like', '%' . $q . '%');
          $query->orWhere('geofences.lat', 'like', '%' . $q . '%');
        })
        ->count();

      $oData = Models\Geofence::where(function ($query) {
          $query->where('geofences.user_id', '=', Core\Secure::userId());
          $query->where('geofences.funnel_id', '=', Core\Secure::funnelId());
        })
        ->orderBy($aColumn[$order_by], $order)
        ->select(array('geofences.id', 'geofences.name', 'geofences.radius', 'geofences.lng', 'geofences.lat', 'geofences.active'))
        ->where(function ($query) use($q) {
          $query->orWhere('geofences.name', 'like', '%' . $q . '%');
          $query->orWhere('geofences.radius', 'like', '%' . $q . '%');
          $query->orWhere('geofences.lng', 'like', '%' . $q . '%');
          $query->orWhere('geofences.lat', 'like', '%' . $q . '%');
        })
        ->take($length)->skip($start)->get();
    }
    else
    {
      $count = Models\Geofence::where('geofences.user_id', '=', Core\Secure::userId())->where('funnel_id', Core\Secure::funnelId())->count();

      $oData = Models\Geofence::where('geofences.user_id', '=', Core\Secure::userId())
        ->where('funnel_id', Core\Secure::funnelId())
        ->select(array('geofences.id', 'geofences.name', 'geofences.radius', 'geofences.lng', 'geofences.lat', 'geofences.active'))
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
        'lng' => $row->lng,
        'lat' => $row->lat,
        'zoom' => $row->zoom,
        'radius' => $row->radius,
        'active' => $row->active,
        'sl' => Core\Secure::array2string(array('geofence_id' => $row->id))
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
