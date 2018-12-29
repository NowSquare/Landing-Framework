<?php

namespace Modules\Modals\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use \Platform\Controllers\Core;
use Modules\Modals\Http\Models;

class ModalsController extends Controller {
  /**
   * Overview
   */
  public function showModals() {
    $modals = Models\Modal::where('user_id', Core\Secure::userId())->where('funnel_id', Core\Secure::funnelId())->get();

    if ($modals->count() == 0) {
      return $this->showCreateModal(true);
    } else {
      return view('modals::overview', compact('modals'));
    }
  }

  /**
   * Create new
   */
  public function showCreateModal($first = false) {
    if (\Gate::allows('limitation', 'forms.visible')) {
      $forms = \Modules\Forms\Http\Models\Form::where('user_id', Core\Secure::userId())->where('funnel_id', Core\Secure::funnelId())->orderBy('name', 'asc')->get();
    } else {
      $forms = null;
    }

    if (\Gate::allows('limitation', 'landingpages.visible')) {
      $sites = \Modules\LandingPages\Http\Models\Site::where('user_id', Core\Secure::userId())->where('funnel_id', Core\Secure::funnelId())->orderBy('name', 'asc')->get();
    } else {
      $sites = null;
    }

    $reseller = Core\Reseller::get();

    return view('modals::create', compact('first', 'forms', 'sites', 'reseller'));
  }

  /**
   * Update modal
   */
  public function showEditModal() {
    $sl = request()->input('sl', '');

    if($sl != '') {
      $qs = Core\Secure::string2array($sl);
      $modal = Models\Modal::where('id', $qs['modal_id'])->where('user_id', '=', Core\Secure::userId())->first();

      if (\Gate::allows('limitation', 'forms.visible')) {
        $forms = \Modules\Forms\Http\Models\Form::where('user_id', Core\Secure::userId())->where('funnel_id', Core\Secure::funnelId())->orderBy('name', 'asc')->get();
      } else {
        $forms = null;
      }

      if (\Gate::allows('limitation', 'landingpages.visible')) {
        $sites = \Modules\LandingPages\Http\Models\Site::where('user_id', Core\Secure::userId())->where('funnel_id', Core\Secure::funnelId())->orderBy('name', 'asc')->get();
      } else {
        $sites = null;
      }

      return view('modals::edit', compact('sl', 'modal', 'forms', 'sites'));
    }
  }

  /**
   * Add / update modal
   */
  public function postModal() {
    $sl = request()->input('sl', NULL);
    $name = request()->input('name');
    $active = (boolean) request()->input('active', false);

    $url = request()->input('url', NULL);
    $trigger = request()->input('trigger', NULL);
    $scrollTop = request()->input('scrollTop', NULL);
    $delay = request()->input('delay', NULL);
    $ignoreAfterCloses = request()->input('ignoreAfterCloses', NULL);
    $allowedHosts = request()->input('allowedHosts', []);
    $allowedPaths = request()->input('allowedPaths', []);
    $position = request()->input('position', NULL);
    $shadow = (boolean) request()->input('shadow', false);
    $contentClasses = ($shadow) ? '-lm-shadow--8dp' : '';
    $width = request()->input('width', NULL);
    $height = request()->input('height', NULL);
    $backdropVisible = (boolean) request()->input('backdropVisible', false);
    $backdrop_color = request()->input('backdrop_color', NULL);
    $showLoader = (boolean) request()->input('showLoader', false);
    $loader_color = request()->input('loader_color', NULL);
    $close_color = request()->input('close_color', NULL);
    $closeBtnMargin = request()->input('closeBtnMargin', NULL);

    $settings = [
      'modalUrl' => $url,
      'trigger' => $trigger,
      'delay' => $delay,
      'scrollTop' => $scrollTop,
      'allowedHosts' => $allowedHosts,
      'allowedPaths' => $allowedPaths,
      'ignoreAfterCloses' => $ignoreAfterCloses,
      'backdropVisible' => $backdropVisible,
      'backdropBgColor' => $backdrop_color,
      'showLoader' => $showLoader,
      'loaderColor' => $loader_color,
      'closeBtnMargin' => $closeBtnMargin,
      'closeBtnColor' => $close_color,
      'contentPosition' => $position,
      'contentWidth' => $width,
      'contentHeight' => $height,
      'contentClasses' => $contentClasses
    ];

    if($sl != NULL) {
      $qs = Core\Secure::string2array($sl);
      $modal = Models\Modal::where('id', $qs['modal_id'])->where('user_id', '=', Core\Secure::userId())->first();
    } else {
      // Verify limit
      $modal_count = Models\Modal::where('user_id', '=', Core\Secure::userId())->count();
      $modal_count_limit = \Auth::user()->plan->limitations['modals']['max'];

      if ($modal_count >= $modal_count_limit) {
        return response()->json([
          'type' => 'error', 
          'msg' => trans('global.account_limit_reached'),
          'reset' => false
        ]);
      }

      $modal = new Models\Modal;
    }

    $modal->user_id = Core\Secure::userId();
    $modal->funnel_id = Core\Secure::funnelId();
    $modal->name = $name;
    $modal->active = $active;
    $modal->settings = $settings;

    if($modal->save()) {
      $response = array(
        'redir' => '#/modals'
      );
    } else {
      $response = array(
        'type' => 'error', 
        'msg' => $modal->errors()->first(),
        'reset' => false
      );
    }

    return response()->json($response);
  }

  /**
   * Export
   */

  public function getExport() {
    $type = request()->input('type', 'xls');
    if (! in_array($type, ['xls', 'xlsx', 'csv'])) $type = 'xls';
    $filename = Core\Reseller::get()->name . '-' . str_slug(trans('modals::global.Modals')) . '-' . date('Y-m-d h:i:s');
    $modals = Models\Modal::where('Modals.user_id', Core\Secure::userId())->where('modals.funnel_id', '=', Core\Secure::funnelId())
      ->select(\DB::raw("
        modals.name as '" . trans('global.name') . "', 
        uuid as UUID,
        major as '" . trans('modals::global.major') . "', 
        minor as '" . trans('modals::global.minor') . "', 
        lat as '" . trans('modals::global.latitude') . "', 
        lng as '" . trans('modals::global.longitude') . "', 
        zoom as '" . trans('modals::global.zoom') . "', 
        active as '" . trans('global.active') . "', 
        modals.created_at as '" . trans('global.created') . "', 
        modals.updated_at as '" . trans('global.updated') . "'"))->get();

    \Excel::create($filename, function($excel) use($modals) {
      $excel->sheet(trans('modals::global.Modals'), function($sheet) use($modals) {
        $sheet->fromArray($modals);
      });
    })->download($type);
  }

  /**
   * Delete modal(s)
   */
  public function postDelete() {
    $sl = request()->input('sl', '');

    if(\Auth::check() && $sl != '') {
      $qs = Core\Secure::string2array($sl);

      $modal = Models\Modal::where('id', '=',  $qs['modal_id'])->where('user_id', '=',  Core\Secure::userId())->delete();
    } elseif (\Auth::check()) {
      foreach(request()->input('ids', array()) as $id) {
        $affected = Models\Modal::where('id', '=', $id)->where('user_id', '=',  Core\Secure::userId())->delete();
      }
    }

    return response()->json(array('result' => 'success'));
  }

  /**
   * Switch modal(s)
   */
  public function postSwitch() {
    if(\Auth::check()) {
      foreach(request()->input('ids', array()) as $id) {
        $current = Models\Modal::where('id', '=', $id)->first();
        $switch = ($current->active == 1) ? 0 : 1;
        $affected = Models\Modal::where('id', '=', $id)->where('user_id', '=',  Core\Secure::userId())->update(array('active' => $switch));
      }
    }

    return response()->json(array('result' => 'success'));
  }

  /**
   * Get modal list data
   */
  public function getModalData(Request $request) {
    $order_by = $request->input('order.0.column', 0);
    $order = $request->input('order.0.dir', 'asc');
    $search = $request->input('search.regex', '');
    $q = $request->input('search.value', '');
    $start = $request->input('start', 0);
    $draw = $request->input('draw', 1);
    $length = $request->input('length', 10);
    $data = array();    
    
    $aColumn = array('name', 'settings->modalUrl', 'settings->allowedHosts', 'settings->allowedPaths', 'active');
 
    if($q != '')
    {
      $count = Models\Modal::where(function ($query) {
          $query->where('user_id', '=', Core\Secure::userId());
          $query->where('funnel_id', '=', Core\Secure::funnelId());
        })->orderBy($aColumn[$order_by], $order)
        ->select(array('id', 'name', 'settings', 'active'))
        ->where(function ($query) use($q) {
          $query->orWhere('name', 'like', '%' . $q . '%');
          $query->orWhere('settings->modalUrl', 'like', '%' . $q . '%');
        })
        ->count();

      $oData = Models\Modal::where(function ($query) {
          $query->where('user_id', '=', Core\Secure::userId());
          $query->where('funnel_id', '=', Core\Secure::funnelId());
        })->orderBy($aColumn[$order_by], $order)
        ->select(array('id', 'name', 'settings', 'active'))
        ->where(function ($query) use($q) {
          $query->orWhere('name', 'like', '%' . $q . '%');
          $query->orWhere('settings->modalUrl', 'like', '%' . $q . '%');
        })
        ->take($length)->skip($start)->get();
    }
    else
    {
      $count = Models\Modal::where('user_id', '=', Core\Secure::userId())->where('funnel_id', Core\Secure::funnelId())->count();

      $oData = Models\Modal::where('user_id', '=', Core\Secure::userId())
        ->where('funnel_id', Core\Secure::funnelId())
        ->select(array('id', 'name', 'settings', 'active'))
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
      $allowedHosts = $row->settings['allowedHosts'] ?? [];
      $allowedHosts = (! empty($allowedHosts)) ? implode(', ', $allowedHosts) : trans('modals::global.all');
      $allowedPaths = $row->settings['allowedPaths'] ?? [];
      $allowedPaths = (! empty($allowedPaths)) ? implode(', ', $allowedPaths) : trans('modals::global.all');

      $script_src = url('modal/scripts.js?token=' . Core\Secure::staticHash($row->id));

      $data[] = array(
        'DT_RowId' => 'row_' . $row->id,
        'name' => $row->name,
        'settings->modalUrl' => $script_src, //$row->settings['modalUrl'] ?? null,
        'settings->allowedHosts' => $allowedHosts,
        'settings->allowedPaths' => $allowedPaths,
        'active' => $row->active,
        'sl' => Core\Secure::array2string(array('modal_id' => $row->id))
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
