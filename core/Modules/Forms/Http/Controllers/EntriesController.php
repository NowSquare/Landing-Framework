<?php

namespace Modules\Forms\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use \Platform\Controllers\Core;
use Modules\Forms\Http\Models;

class EntriesController extends Controller
{
  /**
   * Entries
   */
  public function showEntries()
  {
    // Defaults
    $sl = request()->get('sl', '');
    $form_id = 0;
    $data_found = false;

    if ($sl != '') {
      $qs = Core\Secure::string2array($sl);
      $form_id = $qs['form_id'];
      $sl = rawurlencode($sl);
    }

    // Range
    $date_start = request()->get('start', date('Y-m-d', strtotime(' - 30 day')));
    $date_end = request()->get('end', date('Y-m-d'));

    $from =  $date_start . ' 00:00:00';
    $to = $date_end . ' 23:59:59';

    // All forms
    $forms = Models\Form::where('user_id', Core\Secure::userId())->orderBy('name', 'asc')->get();

    // This form
    $this_form = Models\Form::where('user_id', Core\Secure::userId())->where('id', $form_id)->first();

    // Get form columns
    $earliest_date = date('Y-m-d');

    if ($form_id > 0) {

      $tbl_name = 'x_form_entries_' . Core\Secure::userId();

      $Entry = new Models\Entry([]);
      $Entry->setTable($tbl_name);

      $columns['form'] = [];
      $columns['custom'] = [];

      $entries = $Entry->where('form_id', $form_id)->orderBy('created_at', 'asc')->first();

      if (! empty($entries)) {
        $data_found = true;
        $columns = $Entry->getColumns($form_id);
        $earliest_date = $entries->created_at->timezone(\Auth::user()->timezone)->format('Y-m-d');
      }
    }

    if ($earliest_date > $date_start || request()->get('start', '') == '') $date_start = $earliest_date;

    return view('forms::entries', compact(
      'data_found', 
      'earliest_date', 
      'date_start', 
      'date_end', 
      'forms', 
      'this_form', 
      'columns', 
      'form_id', 
      'sl'
    ));
  }

  /**
   * Export
   */

  public function getExport()
  {
    $type = request()->input('type', 'xls');
    if (! in_array($type, ['xls', 'xlsx', 'csv'])) $type = 'xls';

    $sl = request()->input('sl', '');

    if ($sl != '') {
      $qs = Core\Secure::string2array($sl);
      $form_id = $qs['form_id'];

      $form = Models\Form::where('user_id', Core\Secure::userId())->where('id', $form_id)->first();

      $filename = str_slug(Core\Reseller::get()->name . '-' . $form->name . '-' . date('Y-m-d h:i:s'));

      // Entry model
      $tbl_name = 'x_form_entries_' . Core\Secure::userId();

      $Entry = new Models\Entry([]);
      $Entry->setTable($tbl_name);

      $columns = $Entry->getColumns($form_id);

      // Columns
      $aColumn = [];
      $aColumn[] = "email as " . trans('global.email') . "";

      foreach($columns['form'] as $column) {

        $trans = $column;
        foreach (trans('global.form_fields') as $form_cat => $form_items) {
          $trans = str_replace($form_cat . '_', $form_cat . '.', $trans);
        }

        $trans = trans('global.form_fields.' . $trans);

        $aColumn[] = $column . " as " . $trans . "";
      }

      foreach($columns['custom'] as $column) {        
        $aColumn[] = \DB::raw('JSON_UNQUOTE(json_extract(entry, \'$."' . $column . '"\')) as "' . $column . '"');
      }

      $aColumn[] = "created_at as " . trans('global.created') . "";

      $entries = $Entry->where('form_id', $form_id)->select($aColumn)->orderBy('created_at', 'asc')->get();

      \Excel::create($filename, function($excel) use($entries) {
        $excel->sheet(trans('global.forms'), function($sheet) use($entries) {
          $sheet->fromArray($entries);
        });
      })->download($type);
    }
  }

  /**
   * Delete
   */
  public function postDelete()
  {
    $sl = request()->input('sl', '');

    // Entry model
    $tbl_name = 'x_form_entries_' . Core\Secure::userId();

    $Entry = new Models\Entry([]);
    $Entry->setTable($tbl_name);

    if(\Auth::check() && $sl != '') {
      $qs = Core\Secure::string2array($sl);
      $entry = $Entry->where('id', '=',  $qs['entry_id'])->delete();

      // Decrement
      Models\Form::whereId($qs['form_id'])->decrement('conversions');

    } elseif (\Auth::check()) {
      foreach(request()->input('ids', array()) as $id) {
        $affected = $Entry->where('id', '=', $id)->delete();

        // Decrement
        Models\Form::whereId($qs['form_id'])->decrement('conversions');
      }
    }

    return response()->json(array('result' => 'success'));
  }

  /**
   * Get data
   */
  public function getData(Request $request)
  {
    // Form id
    $sl = request()->get('sl', '');

    if ($sl == '') {
      return '';
    }

    $qs = Core\Secure::string2array($sl);
    $form_id = $qs['form_id'];

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

    // Entry model
    $tbl_name = 'x_form_entries_' . Core\Secure::userId();

    $Entry = new Models\Entry([]);
    $Entry->setTable($tbl_name);

    $columns = $Entry->getColumns($form_id);

    // Columns
    $aFormColumns = [];
    $aCustomColumns = [];
    $aColumn = [];
    $aColumn[] = 'email';

    foreach($columns['form'] as $column) {
      $aColumn[] = $column;
      $aFormColumns[] = $column;
    }

    foreach($columns['custom'] as $column) {
      $aColumn[] = 'entry->' . $column;
      $aCustomColumns[] = $column;
    }

    $aColumn[] = 'created_at';

    if($q != '') {

      $count = $Entry->orderBy($aColumn[$order_by], $order)
        ->where(function ($query) use($form_id, $from, $to) {
          $query->where('form_id', $form_id);
          $query->where('created_at', '>=', $from);
          $query->where('created_at', '<=', $to);
        })
        ->where(function ($query) use($q, $aFormColumns, $aCustomColumns) {
          $query->orWhere('email', 'like', '%' . $q . '%');

          foreach($aFormColumns as $column) {
            $query->orWhere($column, 'like', '%' . $q . '%');
          }

          foreach($aCustomColumns as $column) {
            $query->orWhere('entry->' . $column, 'like', '%' . $q . '%');
          }
        })
        ->count();

      $oData = $Entry->orderBy($aColumn[$order_by], $order)
        ->where(function ($query) use($form_id, $from, $to) {
          $query->where('form_id', $form_id);
          $query->where('created_at', '>=', $from);
          $query->where('created_at', '<=', $to);
        })
        ->where(function ($query) use($q, $aFormColumns, $aCustomColumns) {
          $query->orWhere('email', 'like', '%' . $q . '%');

          foreach($aFormColumns as $column) {
            $query->orWhere($column, 'like', '%' . $q . '%');
          }

          foreach($aCustomColumns as $column) {
            $query->orWhere('entry->' . $column, 'like', '%' . $q . '%');
          }
        })
        ->take($length)->skip($start)->get();

    } else {

      $count = $Entry->where('form_id', $form_id)
        ->where('created_at', '>=', $from)
        ->where('created_at', '<=', $to)
        ->count();

      $oData = $Entry->where('form_id', $form_id)
        ->where('created_at', '>=', $from)
        ->where('created_at', '<=', $to)
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
      $columns['email'] = $row->email;
      $columns['created_at'] = $row->created_at->timezone(\Auth::user()->timezone)->format('Y-m-d H:i:s');
      $columns['sl'] = Core\Secure::array2string(['form_id' => $form_id, 'entry_id' => $row->id]);

      foreach($aFormColumns as $column) {
        $value = $row->{$column};

        // Parse special fields
        if ($column == 'personal_gender') {
          $value = ($row->{$column} == 0) ? trans('global.form_fields_gender.male') : trans('global.form_fields_gender.female');
        }

        if ($column == 'personal_title') {
          $value = ($row->{$column} == 0) ? trans('global.form_fields_title.mr') : trans('global.form_fields_title.ms');
        }

        $columns[$column] = \Illuminate\Support\Str::limit(strip_tags($value), 20, '...');
      }

      foreach($aCustomColumns as $column) {
        $columns[$column] = \Illuminate\Support\Str::limit(strip_tags($row->entry[$column]), 20, '...');
      }

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
}
