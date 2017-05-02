<?php namespace Platform\Controllers\Members;

use \Platform\Controllers\Core;
use Illuminate\Http\Request;
use App\Notifications\MemberPasswordUpdated;

class MemberController extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | Members Controller
   |--------------------------------------------------------------------------
   |
   | Members related logic
   |--------------------------------------------------------------------------
   */

  /**
   * Members
   */

  public function showMembers()
  {
    return view('platform.members.members');
  }

  /**
   * Edit member
   */
  public function showEditMember()
  {
    $sl = request()->input('sl', '');

    if($sl != '') {
      $qs = Core\Secure::string2array($sl);
      $member = \Platform\Models\Members\Member::where('user_id', Core\Secure::userId())->where('id', $qs['member_id'])->first();

      return view('platform.members.member-edit', [
        'sl' => $sl,
        'member' => $member
      ]);
    }
  }

  /**
   * Upload avatar
   */
  public function postAvatar() {
    $input = array(
      'file' => \Request::file('file'),
      'extension'  => strtolower(\Request::file('file')->getClientOriginalExtension())
    );

    $rules = array(
      'file' => 'mimes:jpeg,gif,png',
      'extension'  => 'required|in:jpg,jpeg,png,gif'
    );

    $validator = \Validator::make($input, $rules);

    if($validator->fails()) {
       echo $validator->messages()->first();
       die();
    } else {
      $sl = request()->input('sl', NULL);
  
      if($sl != NULL) {
        $qs = Core\Secure::string2array($sl);

        $member = \Platform\Models\Members\Member::where('user_id', Core\Secure::userId())->where('id', $qs['member_id'])->first();
        $member->avatar = $input['file'];
        $member->save();

        echo $member->avatar->url('default');
      }
    }
  }

  /**
   * Delete avatar
   */
  public function postDeleteAvatar() {
    $sl = request()->input('sl', NULL);

    if($sl != NULL) {
      $qs = Core\Secure::string2array($sl);

      $member = \Platform\Models\Members\Member::where('user_id', Core\Secure::userId())->where('id', $qs['member_id'])->first();
      $member->avatar = STAPLER_NULL;
      $member->save();

      return response()->json(['src' => $member->getAvatar()]);
    }
  }

  /**
   * Add new member
   */
  public function postNewMember()
  {
    $input = array(
      'timezone' => request()->input('timezone'),
      'language' => request()->input('language'),
      'email' => request()->input('email'),
      'name' => request()->input('name'),
      'password' => request()->input('password'),
      'mail_login' => (bool) request()->input('mail_login', false),
      'active' => (bool) request()->input('active', false)
    );

    $rules = array(
      'email' => 'required|email|max:155|unique:members',
      'name' => 'required|max:64',
      'password' => 'required|min:6|max:32'
    );

    $validator = \Validator::make($input, $rules);

    if($validator->fails())
    {
      $response = array(
        'type' => 'error', 
        'reset' => false, 
        'msg' => $validator->messages()->first()
      );
    }
    else
    {
      $member = new \Platform\Models\Members\Member;

      $member->user_id = Core\Secure::userId();
      $member->name = $input['name'];
      $member->email = $input['email'];
      $member->language = $input['language'];
      $member->timezone = $input['timezone'];
      $member->active = $input['active'];
      if ($input['reseller_id'] > 0) $member->reseller_id = $input['reseller_id'];
      $member->password = bcrypt($input['password']);

      if($input['mail_login'])
      {
        // Send mail with credentials
        $reseller = Core\Reseller::get();

        $member->notify(new MemberCreated($input['password'], $reseller->url));

      }

      if($member->save())
      {
        $response = array(
          'type' => 'success',
          'redir' => '#/members'
        );
      }
      else
      {
        $response = array(
          'type' => 'error',
          'reset' => false, 
          'msg' => $member->errors()->first()
        );
      }
    }
    return response()->json($response);
  }

  /**
   * Save member changes
   */
  public function postMember()
  {
    $sl = request()->input('sl', '');

    if($sl != '')
    {
      $qs = Core\Secure::string2array($sl);

      $member = \Platform\Models\Members\Member::where('user_id', Core\Secure::userId())->where('id', $qs['member_id'])->first();

      $input = array(
        'timezone' => request()->input('timezone'),
        'language' => request()->input('language'),
        'email' => request()->input('email'),
        'name' => request()->input('name'),
        'new_password' => request()->input('new_password'),
        'active' => (bool) request()->input('active', false),
        'mail_login' => (bool) request()->input('mail_login', false)
      );

      $rules = array(
        'email' => 'required|email|unique:members,email,' . $qs['member_id'],
        'new_password' => 'min:5|max:32',
        'name' => 'required|max:64',
        'timezone' => 'required'
      );

      $validator = \Validator::make($input, $rules);

      if($validator->fails())
      {
        $response = array(
          'type' => 'error', 
          'reset' => false, 
          'msg' => $validator->messages()->first()
        );
      }
      else
      {
        $member->name = $input['name'];
        $member->email = $input['email'];
        $member->timezone = $input['timezone'];
        $member->language = $input['language'];
        $member->active = $input['active'];
  
        if($input['new_password'] != '')
        {
          $member->password = bcrypt($input['new_password']);

          if($input['mail_login'])
          {
            // Send mail with credentials
            $reseller = Core\Reseller::get();

            $member->notify(new MemberPasswordUpdated($input['new_password']));
          }
        }

        if($member->save())
        {
          $response = array(
            'type' => 'success',
            'reset' => false, 
            'msg' => trans('global.changes_saved')
          );
        }
        else
        {
          $response = array(
            'type' => 'error',
            'reset' => false, 
            'msg' => $member->errors()->first()
          );
        }
      }
      return response()->json($response);
    }
  }

  /**
   * Delete member
   */
  public function postMemberDelete()
  {
    $sl = request()->input('sl', '');

    if($sl != '')
    {
      $qs = Core\Secure::string2array($sl);
      $response = array('result' => 'success');

      $member = \Platform\Models\Members\Member::where('user_id', Core\Secure::userId())->where('id', '=',  $qs['member_id'])->first();

      if(! empty($member))
      {
        $member = \Platform\Models\Members\Member::where('id', '=',  $qs['member_id'])->forceDelete();
      }
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
    $filename = Core\Reseller::get()->name . '-' . str_slug(trans('global.members')) . '-' . date('Y-m-d h:i:s');
    $members = \Platform\Models\Members\Member::where('user_id', Core\Secure::userId())
      ->select(\DB::raw("
        name as '" . trans('global.name') . "', 
        email as '" . trans('global.email') . "', 
        logins as '" . trans('global.logins') . "', 
        last_ip as '" . trans('global.last_ip') . "', 
        last_login as '" . trans('global.last_login') . "', 
        created_at as '" . trans('global.created') . "'"))->get();

    \Excel::create($filename, function($excel) use($members) {
      $excel->sheet(trans('global.members'), function($sheet) use($members) {
        $sheet->fromArray($members);
      });
    })->download($type);
  }

  /**
   * Get member data
   */
  public function getMemberData(Request $request)
  {
    $order_by = $request->input('order.0.column', 0);
    $order = $request->input('order.0.dir', 'asc');
    $search = $request->input('search.regex', '');
    $q = $request->input('search.value', '');
    $start = $request->input('start', 0);
    $draw = $request->input('draw', 1);
    $length = $request->input('length', 10);
    $data = array();

    $aColumn = array('email', 'members.name', 'logins', 'last_login', 'members.created_at', 'members.active');

    if($q != '')
    {
      $count = \Platform\Models\Members\Member::leftJoin('resellers as r', 'r.id', '=', 'reseller_id')
        ->select(array('members.*'))
        ->where('members.user_id', '=', Core\Secure::userId())
        ->where(function ($query) use($q) {
          $query->orWhere('email', 'like', '%' . $q . '%')
          ->orWhere('role', 'like', '%' . $q . '%')
          ->orWhere('members.name', 'like', '%' . $q . '%');
        })
        ->count();

      $oData = \Platform\Models\Members\Member::orderBy($aColumn[$order_by], $order)
        ->leftJoin('resellers as r', 'r.id', '=', 'reseller_id')
        ->select(array('members.*'))
        ->where('members.user_id', '=', Core\Secure::userId())
        ->where(function ($query) use($q) {
          $query->orWhere('email', 'like', '%' . $q . '%')
          ->orWhere('role', 'like', '%' . $q . '%')
          ->orWhere('members.name', 'like', '%' . $q . '%');
        })
        ->take($length)->skip($start)->get();
    }
    else
    {
      $count = \Platform\Models\Members\Member::leftJoin('resellers as r', 'r.id', '=', 'reseller_id')->where('members.user_id', '=', Core\Secure::userId())->select(array('members.*'))->count();
      $oData = \Platform\Models\Members\Member::orderBy($aColumn[$order_by], $order)->leftJoin('resellers as r', 'r.id', '=', 'reseller_id')->where('members.user_id', '=', Core\Secure::userId())->select(array('members.*'))->take($length)->skip($start)->get();
    }

    if($length == -1) $length = $count;

    $recordsTotal = $count;
    $recordsFiltered = $count;

    foreach($oData as $row) {
      $expires = ($row->expires == NULL) ? '-' : $row->expires->format('Y-m-d');
      $last_login = ($row->last_login == NULL) ? '' : $row->last_login->timezone(\Auth::user()->timezone)->format('Y-m-d H:i:s');

      $data[] = array(
        'DT_RowId' => 'row_' . $row->id,
        'reseller' => ($row->reseller_name == '') ? '-' : $row->reseller_name,
        'name' => $row->name,
        'email' => $row->email,
        'logins' => $row->logins,
        'last_login' => $last_login,
        'active' => $row->active,
        'created_at' => $row->created_at->timezone(\Auth::user()->timezone)->format('Y-m-d H:i:s'),
        'sl' => Core\Secure::array2string(array('member_id' => $row->id))
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