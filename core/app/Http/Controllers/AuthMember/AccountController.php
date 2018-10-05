<?php namespace App\Http\Controllers\AuthMember;

use \Platform\Controllers\Core;
use Illuminate\Support\Facades\Gate;

class AccountController extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | Account Controller
   |--------------------------------------------------------------------------
   |
   | Account related logic
   |--------------------------------------------------------------------------
   */

  /**
   * Profile
   */

  public function showProfile()
  {
    return view('platform.members.auth.profile');
  }

  /**
   * Update member profile
   */
  public function postProfile()
  {

    $input = array(
      'email' => request()->input('email'),
      'name' => request()->input('name'),
      'new_password' => request()->input('new_password')
    );

    $rules = array(
      'email' => 'required|email|unique:members,email,' . \Auth::guard('member')->user()->id,
      'new_password' => 'min:5|max:32',
      'name' => 'required|max:64'
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
      $member = \Platform\Models\Members\Member::where('id', \Auth::guard('member')->user()->id)->first();

      $member->name = $input['name'];
      $member->email = $input['email'];

      if($input['new_password'] != '')
      {
        $member->password = bcrypt($input['new_password']);
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