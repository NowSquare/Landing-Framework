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
    $user = \Auth::user();

    return view('platform.account.profile', compact('user'));
  }

  /**
   * Update profile
   */
  public function postProfile()
  {
    if (config('app.demo') && \Auth::user()->id == 1) {
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
      'email' => 'required|email|unique:users,email,' . \Auth::user()->id,
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
      if(! \Hash::check($input['current_password'], \Auth::user()->password))
      {
        return response()->json(array(
          'type' => 'error',
          'reset' => false, 
          'msg' => trans('global.incorrect_password')
        ));
      }

      $user = \App\User::find(\Auth::user()->id);

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
      \Auth::user()->avatar = $input['file'];
      \Auth::user()->save();

      echo \Auth::user()->avatar->url('default');
    }
  }

  /**
   * Delete avatar
   */
  public function postDeleteAvatar() {
    \Auth::user()->avatar = STAPLER_NULL;
    \Auth::user()->save();

    return response()->json(['src' => \Auth::user()->getAvatar()]);
  }

  /**
   * Plan
   */

  public function showPlan() {
    $user = \Auth::user();
    $plans = \App\Plan::where('reseller_id', Core\Reseller::get()->id)->where('active', 1)->where('default', 0)->orderBy('order', 'asc')->get();
    $default_plan = \App\Plan::where('reseller_id', Core\Reseller::get()->id)->where('active', 1)->where('default', 1)->first();

    $modules = \Module::enabled();
    $items = [];

    foreach ($modules as $module) {
      $namespace = $module->getLowerName();
      $enabled = config($namespace . '.enabled');
      $in_plan = config($namespace . '.in_plan');

      if ($enabled && $in_plan) {
        $items[] = [
          "namespace" => $namespace,
          "icon" => config($namespace . '.icon'),
          "order" => config($namespace . '.order'),
          "creatable" => config($namespace . '.creatable'),
          "in_free_plan" => config($namespace . '.in_free_plan'),
          "in_free_plan_default_amount" => config($namespace . '.in_free_plan_default_amount'),
          "in_plan_amount" => config($namespace . '.in_plan_amount'),
          "in_plan_default_amount" => config($namespace . '.in_plan_default_amount'),
          "extra_plan_config_boolean" => config($namespace . '.extra_plan_config_boolean'),
          "extra_plan_config_string" => config($namespace . '.extra_plan_config_string'),
          "order" => config($namespace . '.order'),
          "name" => trans($namespace . '::global.module_name'),
          "desc" => trans($namespace . '::global.module_desc')
        ];
      }
    }

    $items = array_values(array_sort($items, function ($value) {
      return $value['order'];
    }));

    return view('platform.account.plan', compact('user', 'plans', 'default_plan', 'items'));
  }
}