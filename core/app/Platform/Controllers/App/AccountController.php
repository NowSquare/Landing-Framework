<?php namespace Platform\Controllers\App;

use \Platform\Controllers\Core;

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

  public function showProfile() {
    $user = auth()->user();

    return view('platform.account.profile', compact('user'));
  }

  /**
   * Update profile
   */
  public function postProfile()
  {
    if (config('app.demo') && auth()->user()->id == 1) {
      return response()->json([
        'type' => 'error',
        'reset' => false, 
        'msg' => "This is disabled in the demo"
      ]);
    }

    $input = array(
      'timezone' => request()->input('timezone'),
      'name' => request()->input('name'),
      'email' => request()->input('email'),
      'new_password' => request()->input('new_password'),
      'current_password' => request()->input('current_password'),
      'language' => request()->input('language', config('app.fallback_locale'))
    );

    $rules = array(
      'name' => 'required|max:64',
      'email' => 'required|email|unique:users,email,' . auth()->user()->id,
      'new_password' => 'nullable|min:5|max:20',
      'timezone' => 'required',
      'current_password' => 'required'
    );

    $validator = \Validator::make($input, $rules);

    if($validator->fails())
    {
      $response = array(
        'type' => 'error', 
        'msg' => $validator->messages()->first()
      );
    }
    else
    {
      // Check password
      if(! \Hash::check($input['current_password'], auth()->user()->password))
      {
        return response()->json(array(
          'type' => 'error',
          'reset' => false, 
          'msg' => trans('global.incorrect_password')
        ));
      }

      $user = \App\User::find(auth()->user()->id);

      $user->name = $input['name'];
      $user->email = $input['email'];
      $user->timezone = $input['timezone'];
      $user->language = $input['language'];

      if($input['new_password'] != '')
      {
        $user->password = bcrypt($input['new_password']);
      }

      if($user->save())
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
          'msg' => $user->errors()->first()
        );
      }
    }
    return response()->json($response);
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
    } else {
      auth()->user()->avatar = $input['file'];
      auth()->user()->save();

      echo auth()->user()->avatar->url('default');
    }
  }

  /**
   * Delete avatar
   */
  public function postDeleteAvatar() {
    auth()->user()->avatar = STAPLER_NULL;
    auth()->user()->save();

    return response()->json(['src' => auth()->user()->getAvatar()]);
  }

  /**
   * Plan
   */

  public function showPlan() {
    $reseller = Core\Reseller::get();
    $user = auth()->user();

    if ($user->trial_ends_at != NULL || $user->expires != NULL) {
      $expiration_string = ($user->trial_ends_at != NULL) ? trans('global.trial_expires_in', ['datetime' => '<span data-moment="fromNowDateTime">' . $user->trial_ends_at->timezone($user->timezone)->format('Y-m-d H:i:s') . '</span>']) : trans('global.subscription_expires_in', ['datetime' => '<span data-moment="fromNowDateTime">' . $user->expires->timezone($user->timezone)->format('Y-m-d H:i:s') . '</span>']);
    } else {
      $expiration_string = '';
    }

    $plans = \Platform\Controllers\App\PlanController::getAllPlans();
    $all_plans = $plans['all_plans'];
    $annual_plans_exist = $plans['annual_plans_exist'];

    return view('platform.account.plan', compact('reseller', 'user', 'all_plans', 'annual_plans_exist', 'expiration_string'));
  }
}