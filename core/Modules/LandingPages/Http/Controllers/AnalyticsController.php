<?php

namespace Modules\LandingPages\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use \Platform\Controllers\Core;
use Modules\LandingPages\Http\Models;

class AnalyticsController extends Controller
{

  /**
   * Analytics
   */
  public function showAnalytics()
  {
    // Defaults
    $sl = request()->get('sl', '');
    $landing_page_id = 0;
    $data_found = false;

    if ($sl != '') {
      $qs = Core\Secure::string2array($sl);
      $landing_page_id = $qs['landing_page_id'];
      $sl = rawurlencode($sl);
    }

    // Range
    $date_start = request()->get('start', date('Y-m-d', strtotime(' - 30 day')));
    $date_end = request()->get('end', date('Y-m-d'));

    //$from =  $date_start . ' 00:00:00';
    //$to = $date_end . ' 23:59:59';
    $from = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date_start . ' 00:00:00', \Auth::user()->timezone)->tz('UTC')->format('Y-m-d H:i:s');
    $to = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date_end . ' 23:59:59', \Auth::user()->timezone)->tz('UTC')->format('Y-m-d H:i:s');

    // All landing page sites
    $sites = Models\Site::where('user_id', Core\Secure::userId())->orderBy('name', 'asc')->get();

    // This page
    $this_page = Models\Page::where('user_id', Core\Secure::userId())->where('id', $landing_page_id)->first();
    $site_id = $this_page->site->id;

    // Get earliest date
    $earliest_date = date('Y-m-d');

    if ($landing_page_id > 0) {

      $tbl_name = 'x_landing_stats_' . Core\Secure::userId();

      $Stat = new Models\Stat([]);
      $Stat->setTable($tbl_name);

      $Stat = $Stat->where('landing_page_id', $landing_page_id)->orderBy('created_at', 'asc')->first();

      if (! empty($Stat)) {
        $data_found = true;
        $earliest_date = $Stat->created_at->timezone(\Auth::user()->timezone)->format('Y-m-d');
      }
    }

    if ($earliest_date > $date_start) $date_start = $earliest_date;

    return view('landingpages::analytics', compact(
      'data_found', 
      'earliest_date', 
      'date_start', 
      'date_end', 
      'sites', 
      'this_page', 
      'site_id',
      'landing_page_id', 
      'sl'
    ));
  }

  /**
   * Get stats data
   */
  public function getStatData(Request $request)
  {
    // Form id
    $sl = request()->get('sl', '');

    if ($sl == '') {
      return '';
    }

    $qs = Core\Secure::string2array($sl);
    $landing_page_id = $qs['landing_page_id'];

    // Date
    $date_start = request()->get('date_start', date('Y-m-d', strtotime(' - 30 day')));
    $date_end = request()->get('date_end', date('Y-m-d'));

    $from =  $date_start . ' 00:00:00';
    $to = $date_end . ' 23:59:59';

    // Datatables
    $order_by = $request->input('order.0.column', 0);
    $order = $request->input('order.0.dir', 'asc');
    $search = $request->input('search.regex', '');
    $q = $request->input('search.value', '');
    $start = $request->input('start', 0);
    $draw = $request->input('draw', 1);
    $length = $request->input('length', 10);
    $data = array();    

    // Stat model
    $tbl_name = 'x_landing_stats_' . Core\Secure::userId();

    $Stat = new Models\Stat([]);
    $Stat->setTable($tbl_name);

    // Columns
    $aColumn = [];
    $aColumn[] = 'language';
    $aColumn[] = 'client_name';
    $aColumn[] = 'client_version';
    $aColumn[] = 'os_name';
    $aColumn[] = 'os_version';
    $aColumn[] = 'brand';
    $aColumn[] = 'model';
    $aColumn[] = 'created_at';

    if($q != '') {

      $count = $Stat->orderBy($aColumn[$order_by], $order)
        ->where(function ($query) use($landing_page_id, $from, $to) {
          $query->where('landing_page_id', $landing_page_id);
          $query->where('created_at', '>=', $from);
          $query->where('created_at', '<=', $to);
          $query->where('is_bot', 0);
        })
        ->where(function ($query) use($q, $aColumn) {
          foreach ($aColumn as $column) {
            $query->orWhere($column, 'like', '%' . $q . '%');
          }
        })
        ->count();

      $oData = $Stat->orderBy($aColumn[$order_by], $order)
        ->where(function ($query) use($landing_page_id, $from, $to) {
          $query->where('landing_page_id', $landing_page_id);
          $query->where('created_at', '>=', $from);
          $query->where('created_at', '<=', $to);
          $query->where('is_bot', 0);
        })
        ->where(function ($query) use($q, $aColumn) {
          foreach ($aColumn as $column) {
            $query->orWhere($column, 'like', '%' . $q . '%');
          }
        })
        ->take($length)->skip($start)->get();

    } else {

      $count = $Stat->where('landing_page_id', $landing_page_id)
        ->where('created_at', '>=', $from)
        ->where('created_at', '<=', $to)
        ->where('is_bot', 0)
        ->count();

      $oData = $Stat->where('landing_page_id', $landing_page_id)
        ->where('created_at', '>=', $from)
        ->where('created_at', '<=', $to)
        ->where('is_bot', 0)
        ->orderBy($aColumn[$order_by], $order)
        ->take($length)
        ->skip($start)
        ->get();
    }

    if($length == -1) $length = $count;

    $recordsTotal = $count;
    $recordsFiltered = $count;

    foreach($oData as $row) {
      $columns['DT_RowId'] = 'row_' . $row->id;
      $columns['language'] = $row->language;
      $columns['client_name'] = $row->client_name;
      $columns['client_version'] = $row->client_version;
      $columns['os_name'] = $row->os_name;
      $columns['os_version'] = $row->os_version;
      $columns['brand'] = $row->brand;
      $columns['model'] = $row->model;
      $columns['created_at'] = $row->created_at->timezone(\Auth::user()->timezone)->format('Y-m-d H:i:s');
      $columns['sl'] = Core\Secure::array2string(['landing_site_id' => $row->landing_site_id, 'landing_page_id' => $row->landing_page_id, 'stat_id' => $row->id]);

      $data[] = $columns;
    }

    $response = array(
      'draw' => $draw,
      'recordsTotal' => $recordsTotal,
      'recordsFiltered' => $recordsFiltered,
      'data' => $data
    );

    return response()->json($response);
  }

  /**
   * Get stats data
   */
  public function getStatRange(Request $request)
  {
    // Form id
    $sl = request()->get('sl', '');

    if ($sl == '') {
      return '';
    }

    $qs = Core\Secure::string2array($sl);
    $landing_page_id = $qs['landing_page_id'];

    // Date
    $date_start = request()->get('date_start', date('Y-m-d', strtotime(' - 30 day')));
    $date_end = request()->get('date_end', date('Y-m-d'));

    $from =  $date_start . ' 00:00:00';
    $to = $date_end . ' 23:59:59';

    // Stat model
    $tbl_name = 'x_landing_stats_' . Core\Secure::userId();

    $Stat = new Models\Stat([]);
    $Stat->setTable($tbl_name);

    $stats_visits = $Stat->where('landing_page_id', $landing_page_id)
      ->select(\DB::raw('DATE(created_at) as date'), \DB::raw('count(id) as visits'))
      ->where('created_at', '>=', $from)
      ->where('created_at', '<=', $to)
      ->where('is_bot', 0)
      ->groupBy([\DB::raw('DATE(created_at)')])
      ->get();

    $main_chart_range = \Platform\Controllers\Core\Analytics::getRange($date_start, $date_end);

    // Columns
    $response['cols'] = [];

    $response['cols'][] = [
      'label' => false,
      'type' => 'string'
      /*'type' => 'date'*/
    ];

    $response['cols'][] = [
      'label' => trans('global.visits'),
      'type' => 'number'
    ];

    // Rows
    $min = 0;
    $max = 0;
    $response['rows'] = [];

    foreach ($main_chart_range as $date => $dArr) {

      //$visits = ($date < $earliest_date) ? NULL : 0;
      $visits = 0;

      foreach($stats_visits as $row) {
        // $row->created_at->timezone(\Auth::user()->timezone)->format('Y-m-d')
        if ($date == $row->date) {
          $visits = $row->visits;
          if ($visits < $min) $min = $visits;
          if ($visits > $max) $max = $visits;
          break 1;
        }
      }

      $response['rows'][] = [
        'c' => [
          ['v' => \Carbon\Carbon::parse($date)->timezone(\Auth::user()->timezone)->toFormattedDateString()],
          /*['v' => 'Date(' . $dArr['y'] . ', ' . $dArr['m'] . ', ' . $dArr['d'] . ')'],*/
          ['v' => $visits]
        ]
      ];

    }

    $response['vars'] = [
      'min' => $min,
      'max' => $max
    ];

    return response()->json($response);
  }
}
