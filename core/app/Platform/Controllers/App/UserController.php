<?php namespace Platform\Controllers\App;

use \Platform\Controllers\Core;
use Illuminate\Http\Request;
use App\Notifications\PasswordUpdated;
use App\Notifications\UserCreated;
use Illuminate\Support\Facades\Schema;
use App\Notifications\SendEmail;

class UserController extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | User Controller
   |--------------------------------------------------------------------------
   |
   | User related logic
   |--------------------------------------------------------------------------
   */

  /**
   * Check for accounts that have been expired
   */
  public static function checkExpiredAccounts()
  {
    $now = \Carbon\Carbon::now()->tz('UTC')->format('Y-m-d H:i:s');
    $yesterday = \Carbon\Carbon::now()->addDays(-1)->tz('UTC')->format('Y-m-d H:i:s');
    $two_days_ago = \Carbon\Carbon::now()->addDays(-2)->tz('UTC')->format('Y-m-d H:i:s');
    $three_days_ago = \Carbon\Carbon::now()->addDays(-3)->tz('UTC')->format('Y-m-d H:i:s');
    $three_days_before_expiration = \Carbon\Carbon::now()->addDays(-12)->tz('UTC')->format('Y-m-d H:i:s');
    $two_weeks_ago = \Carbon\Carbon::now()->addDays(-15)->tz('UTC')->format('Y-m-d H:i:s'); // Two weeks + one day

    // Account expired yesterday
    $users = \App\User::where('active', true)
      ->whereNull('is_reseller_id')
      ->where('expires', '<', $yesterday)
      ->where('expires', '>', $two_days_ago)
      ->where('expires_reminders_sent', 0)
      ->get();

    foreach ($users as $user) {
      $user->expires_reminders_sent = 1;
      $user->save();
      //echo 'Your account expired yesterday';

      // Set language
      app()->setLocale($user->language);

      // Get reseller
      $reseller = Core\Reseller::get($user->reseller_id);

      $mail_from = $reseller->mail_from_address;
      $mail_from_name = $reseller->mail_from_name;
      $subject = trans('global.account_expired_yesterday_subject', ['product_name' => $reseller->name]);
      $body_line1 = trans('global.account_expired_yesterday_mail_line1', ['product_name' => $reseller->name]);
      $body_line2 = trans('global.account_expired_yesterday_mail_line2', ['product_name' => $reseller->name]);
      $body_cta = trans('global.account_expired_yesterday_cta');
      $body_cta_link = url('login');

      $user->notify(new SendEmail($mail_from, $mail_from_name, $subject, $body_line1, $body_line2, $body_cta, $body_cta_link));
    }

    // Account deleted in 3 days
    $users = \App\User::where('active', true)
      ->whereNull('is_reseller_id')
      ->where('expires', '<', $three_days_before_expiration)
      ->where('expires', '>', $two_weeks_ago)
      ->where('expires_reminders_sent', 1)
      ->get();

    foreach ($users as $user) {
      $user->expires_reminders_sent = 2;
      $user->save();
      //echo 'Your account is deleted in 3 days';

      // Set language
      app()->setLocale($user->language);

      // Get reseller
      $reseller = Core\Reseller::get($user->reseller_id);

      $mail_from = $reseller->mail_from_address;
      $mail_from_name = $reseller->mail_from_name;
      $subject = trans('global.account_deleted_in_3_days_subject', ['product_name' => $reseller->name]);
      $body_line1 = trans('global.account_deleted_in_3_days_mail_line1', ['product_name' => $reseller->name]);
      $body_line2 = trans('global.account_deleted_in_3_days_mail_line2', ['product_name' => $reseller->name]);
      $body_cta = trans('global.account_deleted_in_3_days_cta');
      $body_cta_link = url('login');

      $user->notify(new SendEmail($mail_from, $mail_from_name, $subject, $body_line1, $body_line2, $body_cta, $body_cta_link));
    }

    // Account deleted
    $users = \App\User::where('active', true)
      ->whereNull('is_reseller_id')
      ->where('expires', '<', $two_weeks_ago)
      ->where('expires_reminders_sent', 2)
      ->get();

    foreach ($users as $user) {

      // User hash
      $user_hash = Core\Secure::staticHash($user->id);

      // Delete uploads
      $user_upload_dir = public_path() . '/public/uploads/' . $user_hash;
      \File::deleteDirectory($user_upload_dir);

      // Delete landing page files
      \Storage::disk('public')->deleteDirectory('/landingpages/site/' . $user_hash);

      // Delete form files
      \Storage::disk('public')->deleteDirectory('/forms/form/' . $user_hash);

      // Delete email files
      \Storage::disk('public')->deleteDirectory('/emails/email/' . $user_hash);

      // Delete Eddystones
      $eddystones = \Modules\Eddystones\Http\Controllers\Eddystone::listBeacons($user->id);

      foreach($eddystones['beacons'] as $eddystone) {
        $beaconName = $eddystone->getBeaconName();
        $response = \Modules\Eddystones\Http\Controllers\Eddystone::deleteBeacon($beaconName);
      }

      //echo 'Your account has been deleted';

      // Set language
      app()->setLocale($user->language);

      // Get reseller
      $reseller = Core\Reseller::get($user->reseller_id);

      $mail_from = $reseller->mail_from_address;
      $mail_from_name = $reseller->mail_from_name;
      $subject = trans('global.account_deleted_subject', ['product_name' => $reseller->name]);
      $body_line1 = trans('global.account_deleted_mail_line1', ['product_name' => $reseller->name]);
      $body_line2 = trans('global.account_deleted_mail_line2', ['product_name' => $reseller->name]);
      $body_cta = trans('global.account_deleted_cta');
      $body_cta_link = url('register');

      $user->notify(new SendEmail($mail_from, $mail_from_name, $subject, $body_line1, $body_line2, $body_cta, $body_cta_link));

      // Delete user
      $user->forceDelete();
    }
  }

  /**
   * Check if there are trials expiring and/or ending
   */
  public static function checkExpiringTrials()
  {
    $now = \Carbon\Carbon::now()->tz('UTC')->format('Y-m-d H:i:s');
    $tomorrow = \Carbon\Carbon::now()->addDays(1)->tz('UTC')->format('Y-m-d H:i:s');
    $in_two_days = \Carbon\Carbon::now()->addDays(2)->tz('UTC')->format('Y-m-d H:i:s');
    $in_three_days = \Carbon\Carbon::now()->addDays(3)->tz('UTC')->format('Y-m-d H:i:s');

    // Trial ends in 3 days
    $users = \App\User::where('active', true)
      ->whereNull('is_reseller_id')
      ->where('trial_ends_at', '>', $in_two_days)
      ->where('trial_ends_at', '<', $in_three_days)
      ->where('trial_ends_reminders_sent', 0)
      ->get();

    foreach ($users as $user) {
      $user->trial_ends_reminders_sent = 1;
      $user->save();
      //echo 'Your trial ends in 3 days';

      // Set language
      app()->setLocale($user->language);

      // Get reseller
      $reseller = Core\Reseller::get($user->reseller_id);

      $mail_from = $reseller->mail_from_address;
      $mail_from_name = $reseller->mail_from_name;
      $subject = trans('global.trial_ends_in_3_days_subject', ['product_name' => $reseller->name]);
      $body_line1 = trans('global.trial_ends_in_3_days_mail_line1', ['product_name' => $reseller->name]);
      $body_line2 = trans('global.trial_ends_in_3_days_mail_line2', ['product_name' => $reseller->name]);
      $body_cta = trans('global.trial_ends_in_3_days_cta');
      $body_cta_link = url('login');

      $user->notify(new SendEmail($mail_from, $mail_from_name, $subject, $body_line1, $body_line2, $body_cta, $body_cta_link));
    }

    // Trial ends in 1 day
    $users = \App\User::where('active', true)
      ->whereNull('is_reseller_id')
      ->where('trial_ends_at', '>', $now)
      ->where('trial_ends_at', '<', $tomorrow)
      ->where('trial_ends_reminders_sent', 1)
      ->get();

    foreach ($users as $user) {
      $user->trial_ends_reminders_sent = 2;
      $user->save();
      //echo 'Your trial ends tomorrow';

      // Set language
      app()->setLocale($user->language);

      // Get reseller
      $reseller = Core\Reseller::get($user->reseller_id);

      $mail_from = $reseller->mail_from_address;
      $mail_from_name = $reseller->mail_from_name;
      $subject = trans('global.trial_ends_tomorrow_subject', ['product_name' => $reseller->name]);
      $body_line1 = trans('global.trial_ends_tomorrow_mail_line1', ['product_name' => $reseller->name]);
      $body_line2 = trans('global.trial_ends_tomorrow_mail_line2', ['product_name' => $reseller->name]);
      $body_cta = trans('global.trial_ends_tomorrow_cta');
      $body_cta_link = url('login');

      $user->notify(new SendEmail($mail_from, $mail_from_name, $subject, $body_line1, $body_line2, $body_cta, $body_cta_link));
    }

    // Trial has ended
    $users = \App\User::where('active', true)
      ->whereNull('is_reseller_id')
      ->where('trial_ends_at', '<', $now)
      ->where('trial_ends_reminders_sent', 2)
      ->get();

    foreach ($users as $user) {

      // User hash
      $user_hash = Core\Secure::staticHash($user->id);

      // Delete uploads
      $user_upload_dir = public_path() . '/public/uploads/' . $user_hash;
      \File::deleteDirectory($user_upload_dir);

      // Delete landing page files
      \Storage::disk('public')->deleteDirectory('/landingpages/site/' . $user_hash);

      // Delete form files
      \Storage::disk('public')->deleteDirectory('/forms/form/' . $user_hash);

      // Delete email files
      \Storage::disk('public')->deleteDirectory('/emails/email/' . $user_hash);

      // Delete Eddystones
      $eddystones = \Modules\Eddystones\Http\Controllers\Eddystone::listBeacons($user->id);

      foreach($eddystones['beacons'] as $eddystone) {
        $beaconName = $eddystone->getBeaconName();
        $response = \Modules\Eddystones\Http\Controllers\Eddystone::deleteBeacon($beaconName);
      }

      // Set language
      app()->setLocale($user->language);

      // Get reseller
      $reseller = Core\Reseller::get($user->reseller_id);

      $mail_from = $reseller->mail_from_address;
      $mail_from_name = $reseller->mail_from_name;
      $subject = trans('global.trial_has_ended_subject', ['product_name' => $reseller->name]);
      $body_line1 = trans('global.trial_has_ended_mail_line1', ['product_name' => $reseller->name]);
      $body_line2 = trans('global.trial_has_ended_mail_line2', ['product_name' => $reseller->name]);
      $body_cta = trans('global.trial_has_ended_cta');
      $body_cta_link = url('register');

      $user->notify(new SendEmail($mail_from, $mail_from_name, $subject, $body_line1, $body_line2, $body_cta, $body_cta_link));

      // Delete user
      $user->forceDelete();

      //echo 'Sad to see you go!';
    }

  }

  /**
   * User management
   */
  public function showUsers()
  {
    $users = \App\User::orderBy('name')->get();

    return view('platform.admin.users.users', compact('users'));
  }

  /**
   * New user
   */
  public function showNewUser()
  {
    if (\Gate::allows('owner-management')) {
      $resellers = \App\Reseller::orderBy('name')->get();
    } else {
      $resellers = null;
    }

    if (\Gate::allows('admin-management')) {
      $plans = \App\Plan::select([\DB::raw('CONCAT(resellers.name, " - ", plans.name) AS name'), 'plans.id', 'resellers.active as reseller_active', 'plans.active as plan_active'])->leftjoin('resellers', 'resellers.id', '=', 'plans.reseller_id')->orderBy('resellers.name', 'ASC')->orderBy('plans.order', 'ASC')->get();

      $plans_list = [];
      if (count($plans) > 0) {
        foreach ($plans as $plan) {
          $suffix = '';
          if ($plan->plan_active != 1 && $plan->id > 1) $suffix .= ' [inactive]';
          if ($plan->reseller_active != 1) $suffix .= ' [inactive reseller]';
          $plans_list[$plan->id] = $plan->name . $suffix;
        }
      }
    } else {
      $plans = null;
    }

    return view('platform.admin.users.user-new', compact('resellers', 'plans', 'plans_list'));
  }

  /**
   * Edit user
   */
  public function showEditUser()
  {
    $sl = request()->input('sl', '');

    if($sl != '') {
      $qs = Core\Secure::string2array($sl);
      $user = \App\User::where('id', $qs['user_id'])->first();

      if (\Gate::allows('owner-management')) {
        $resellers = \App\Reseller::orderBy('name')->get();
      } else {
        $resellers = null;
      }

      if (\Gate::allows('admin-management')) {
        $plans = \App\Plan::select([\DB::raw('CONCAT(resellers.name, " - ", plans.name) AS name'), 'plans.id', 'resellers.active as reseller_active', 'plans.active as plan_active'])->leftjoin('resellers', 'resellers.id', '=', 'plans.reseller_id')->orderBy('resellers.name', 'ASC')->orderBy('plans.order', 'ASC')->get();

        $plans_list = [];
        if (count($plans) > 0) {
          foreach ($plans as $plan) {
            $suffix = '';
            if ($plan->plan_active != 1 && $plan->id > 1) $suffix .= ' [inactive]';
            if ($plan->reseller_active != 1) $suffix .= ' [inactive reseller]';
            $plans_list[$plan->id] = $plan->name . $suffix;
          }
        }
      } else {
        $plans = null;
      }

      return view('platform.admin.users.user-edit', compact('sl', 'user', 'resellers', 'plans', 'plans_list'));
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
        $data = Core\Secure::string2array($sl);
        $user_id = $data['user_id'];
      } else {
        $user_id = \Auth::user()->id;
      }

      $user = \App\User::find($user_id);
      $user->avatar = $input['file'];
      $user->save();

      echo $user->avatar->url('default');
    }
  }

  /**
   * Delete avatar
   */
  public function postDeleteAvatar() {
    $sl = request()->input('sl', NULL);

    if($sl != NULL) {
      $data = Core\Secure::string2array($sl);
      $user_id = $data['user_id'];
    } else {
      $user_id = \Auth::user()->id;
    }

    $user = \App\User::find($user_id);
    $user->avatar = STAPLER_NULL;
    $user->save();

    return response()->json(['src' => $user->getAvatar()]);
  }

  /**
   * Add new user
   */
  public function postNewUser()
  {
    $input = array(
      'timezone' => request()->input('timezone'),
      'language' => request()->input('language'),
      'email' => request()->input('email'),
      'name' => request()->input('name'),
      'password' => request()->input('password'),
      'mail_login' => (bool) request()->input('mail_login', false),
      'active' => (bool) request()->input('active', false),
      'role' =>request()->input('role'),
      'plan_id' =>request()->input('plan_id', null),
      'reseller_id' =>request()->input('reseller_id', Core\Reseller::get()->id)
    );

    $rules = array(
      'email' => 'required|email|max:155|unique:users',
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
      $user = new \App\User;

      $user->plan_id = (is_numeric($input['plan_id'])) ? $input['plan_id'] : null;
      $user->name = $input['name'];
      $user->email = $input['email'];
      $user->api_token = str_random(60);
      $user->language = $input['language'];
      $user->timezone = $input['timezone'];
      $user->active = $input['active'];
      $user->role = $input['role'];
      $user->password = bcrypt($input['password']);

      if (\Gate::allows('owner-management')) {
        $user->reseller_id = $input['reseller_id'];
      } else {
        $user->reseller_id = Core\Reseller::get()->id;
      }

      if($input['mail_login'])
      {
        // Send mail with credentials
        $reseller = Core\Reseller::get();

        $user->notify(new UserCreated($input['password'], $reseller->url));

      }

      if($user->save())
      {
        $response = array(
          'type' => 'success',
          'redir' => '#/admin/users'
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
   * Save user changes
   */
  public function postUser()
  {
    $sl = request()->input('sl', '');

    if($sl != '')
    {
      $qs = Core\Secure::string2array($sl);

      if (config('app.demo') && $qs['user_id'] == 1) {
        return response()->json([
          'type' => 'error',
          'reset' => false, 
          'msg' => "This is disabled in the demo"
        ]);
      }

      $user = \App\User::find($qs['user_id']);

      $input = array(
        'timezone' => request()->input('timezone'),
        'language' => request()->input('language'),
        'email' => request()->input('email'),
        'name' => request()->input('name'),
        'new_password' => request()->input('new_password'),
        'active' => (bool) request()->input('active', false),
        'mail_login' => (bool) request()->input('mail_login', false),
        'role' =>request()->input('role'),
        'plan_id' =>request()->input('plan_id', NULL),
        'reseller_id' =>request()->input('reseller_id', NULL)
      );

      $rules = array(
        'email' => 'required|email|unique:users,email,' . $qs['user_id'],
        'new_password' => 'nullable|min:5|max:32',
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
        $user->name = $input['name'];
        $user->email = $input['email'];
        $user->timezone = $input['timezone'];
        $user->language = $input['language'];

        if ($qs['user_id'] > 1 && array_has(trans('global.user_roles'), $user->role)) 
        {
          $user->plan_id = (is_numeric($input['plan_id'])) ? $input['plan_id'] : null;
          $user->active = $input['active'];
          $user->role = $input['role'];

          if (\Gate::allows('owner-management')) {
            $user->reseller_id = $input['reseller_id'];
          }
        }

        if($input['new_password'] != '')
        {
          $user->password = bcrypt($input['new_password']);

          if($input['mail_login'])
          {
            // Send mail with credentials
            $reseller = Core\Reseller::get();

            $user->notify(new PasswordUpdated($input['new_password']));
          }
        }

        if($user->save())
        {
          $response = array(
            'type' => 'success',
            'redir' => '#/admin/users'/*
            'type' => 'success',
            'reset' => false, 
            'msg' => trans('global.changes_saved')*/
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
  }

  /**
   * Login as user
   */
  public function getLoginAs($sl)
  {
    if($sl != '')
    {
      $qs = Core\Secure::string2array($sl);
      $user = \App\User::find($qs['user_id']);

      if ($user->reseller_id != NULL)
      {
        // Set session to redirect to in case of logout
        $logout = Core\Secure::array2string(['user_id' => \Auth::user()->id]);
        \Session::put('logout', $logout);

        \Auth::loginUsingId($qs['user_id']);

        return redirect('platform');
      }
    }
  }

  /**
   * Delete user
   */
  public function postUserDelete()
  {
    if (! \Gate::allows('owner-management')) return;

    $sl = request()->input('sl', '');

    if($sl != '')
    {
      $qs = Core\Secure::string2array($sl);
      $response = array('result' => 'success');

      if (config('app.demo') && $qs['user_id'] == 1) {
        return response()->json([
          'type' => 'error',
          'reset' => false, 
          'msg' => "This is disabled in the demo"
        ]);
      }

      $user = \App\User::where('id', '>',  1)->whereNull('is_reseller_id')->where('id', '=',  $qs['user_id'])->first();

      if(! empty($user))
      {
        $user = \App\User::where('id', '=',  $qs['user_id'])->forceDelete();

        $user_id_hash = Core\Secure::staticHash($qs['user_id']);

        // Delete user uploads
        $user_dir = public_path() . '/uploads/' . $user_id_hash;
        \File::deleteDirectory($user_dir);

        // Delete user public storate
        \Storage::disk('public')->delete('landingpages/site/' . $user_id_hash);

        $user_dir = public_path() . '/uploads/' . $user_id_hash;
        \File::deleteDirectory($user_dir);

        // Delete user landing stats table if exist
        $tbl_name = 'x_landing_stats_' . $qs['user_id'];
        Schema::dropIfExists($tbl_name);

        // Delete user form entries table if exist
        $tbl_name = 'x_form_entries_' . $qs['user_id'];
        Schema::dropIfExists($tbl_name);
      }
      else
      {
        $response = array('msg' => trans('global.cant_delete_owner'));
      }
    }
    return response()->json($response);
  }

  /**
   * Get user data
   */
  public function getUserData(Request $request)
  {
    $sql_reseller = "1=1";
    $sql_role = "1=1";

    if (! \Gate::allows('owner-management')) {
      $reseller_id = Core\Reseller::get()->id;
      $sql_reseller = "reseller_id = " . $reseller_id;
    }

    if (\Auth::user()->role == 'admin')
    {
      $sql_role = "role <> 'admin' AND role <> 'owner'";
    }

    $order_by = $request->input('order.0.column', 0);
    $order = $request->input('order.0.dir', 'asc');
    $search = $request->input('search.regex', '');
    $q = $request->input('search.value', '');
    $start = $request->input('start', 0);
    $draw = $request->input('draw', 1);
    $length = $request->input('length', 10);
    $data = array();

    if (\Gate::allows('owner-management')) {
      $aColumn = array('reseller_name', 'name', 'email', 'role', 'logins', 'last_login', 'users.trial_ends_at', 'plan', 'users.expires', 'users.created_at', 'users.active');
    } else {      
      $aColumn = array('name', 'email', 'role', 'logins', 'last_login', 'users.trial_ends_at', 'plan', 'users.expires', 'users.created_at', 'users.active');
    }

    if($q != '')
    {
      $count = \App\User::leftJoin('resellers as r', 'r.id', '=', 'reseller_id')
        ->select(array('users.*', 'r.name as reseller_name', 'r.favicon as favicon'))
        ->whereRaw($sql_reseller)->whereRaw($sql_role)
        ->where('parent_id', '=', NULL)
        ->where(function ($query) use($q) {
          $query->orWhere('email', 'like', '%' . $q . '%')
          ->orWhere('role', 'like', '%' . $q . '%')
          ->orWhere('r.name', 'like', '%' . $q . '%')
          ->orWhere('users.name', 'like', '%' . $q . '%');
        })
        ->count();

      $oData = \App\User::orderBy($aColumn[$order_by], $order)
        ->leftJoin('resellers as r', 'r.id', '=', 'reseller_id')
        ->select(array('users.*', 'r.name as reseller_name', 'r.favicon as favicon'))
        ->whereRaw($sql_reseller)->whereRaw($sql_role)
        ->where('parent_id', '=', NULL)
        ->where(function ($query) use($q) {
          $query->orWhere('email', 'like', '%' . $q . '%')
          ->orWhere('role', 'like', '%' . $q . '%')
          ->orWhere('r.name', 'like', '%' . $q . '%')
          ->orWhere('users.name', 'like', '%' . $q . '%');
        })
        ->take($length)->skip($start)->get();
    }
    else
    {
      $count = \App\User::leftJoin('resellers as r', 'r.id', '=', 'reseller_id')->whereRaw($sql_reseller)->whereRaw($sql_role)->where('parent_id', '=', NULL)->select(array('users.*', 'r.name as reseller_name', 'r.favicon as favicon'))->count();
      $oData = \App\User::orderBy($aColumn[$order_by], $order)->leftJoin('resellers as r', 'r.id', '=', 'reseller_id')->whereRaw($sql_reseller)->whereRaw($sql_role)->where('parent_id', '=', NULL)->select(array('users.*', 'r.name as reseller_name', 'r.favicon as favicon'))->take($length)->skip($start)->get();
    }

    if($length == -1) $length = $count;

    $recordsTotal = $count;
    $recordsFiltered = $count;

    foreach($oData as $row) {
      $expires = ($row->expires == NULL) ? '-' : $row->expires->format('Y-m-d');
      $last_login = ($row->last_login == NULL) ? '' : $row->last_login->timezone(\Auth::user()->timezone)->format('Y-m-d H:i:s');
      $trial_ends_at = ($row->trial_ends_at == NULL) ? '-' : $row->trial_ends_at->timezone(\Auth::user()->timezone)->format('Y-m-d H:i:s');
      $expires = ($row->expires == NULL) ? '-' : $row->expires->timezone(\Auth::user()->timezone)->format('Y-m-d H:i:s');
      $undeletable = ($row->id == 1) ? 1 : 0;

      $plan = ($row->plan == null) ? trans('global.free') : $row->plan->name;
      if ($row->plan_id == 1) $plan .= ' <i class="fa fa-lock" aria-hidden="true"></i>';

      if (\Gate::allows('owner-management')) {
        $favicon = ($row->favicon == null) ? url('favicon.ico') : $row->favicon;
        $reseller = ($row->reseller_name == '') ? '-' : $row->reseller_name;
      } else {
        $favicon = '-';
        $reseller = '-';
      }

      $data[] = array(
        'DT_RowId' => 'row_' . $row->id,
        'reseller' => $reseller,
        'favicon' => $favicon,
        'name' => $row->name,
        'plan' => $plan,
        'email' => $row->email,
        'role_name' => $row->role,
        'role' => trans('global.roles.' . $row->role),
        'logins' => $row->logins,
        'last_login' => $last_login,
        'trial_ends_at' => $trial_ends_at,
        'expires' => $expires,
        'active' => $row->active,
        'created_at' => $row->created_at->timezone(\Auth::user()->timezone)->format('Y-m-d H:i:s'),
        'sl' => Core\Secure::array2string(array('user_id' => $row->id)),
        'undeletable' => $undeletable
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