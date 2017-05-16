<?php

/*
 |--------------------------------------------------------------------------
 | Globals
 |--------------------------------------------------------------------------
 */

$url_parts = parse_url(URL::current());

/*
 |--------------------------------------------------------------------------
 | Check for reseller or custom domain
 |--------------------------------------------------------------------------
 */

$reseller = \Platform\Controllers\Core\Reseller::get();

if ($reseller !== false) {
	$domain = str_replace('www.', '', $url_parts['host']);

  $custom_site = \Modules\LandingPages\Http\Models\Site::where('domain', $domain)
		->orWhere('domain', 'www.' . $domain)
		->first();

} else {
	$custom_site = array();
}

/*
 |--------------------------------------------------------------------------
 | Front end website
 |--------------------------------------------------------------------------
 */

Route::get('/', function() use($url_parts, $custom_site, $reseller) {
  if (! empty($custom_site)) {
    // Naked or www domain?
    if (substr($custom_site->domain, 0, 4) == 'www.' && substr($url_parts['host'], 0, 4) != 'www.') {
      return \Redirect::to($url_parts['scheme'] . '://' . $custom_site->domain, 301);
    } elseif (substr($custom_site->domain, 0, 4) != 'www.' && substr($url_parts['host'], 0, 4) == 'www.') {
      return \Redirect::to($url_parts['scheme'] . '://' . $custom_site->domain, 301);
    }
    // Page
    App::setLocale($custom_site->language);

    return App::make('\Modules\LandingPages\Http\Controllers\LandingPagesController')->homePage($custom_site->local_domain);
  } else {

    // Public website
    return App::make('\Platform\Controllers\Website\WebsiteController')->home();
  }
});

/*
 |--------------------------------------------------------------------------
 | Platform routes
 |--------------------------------------------------------------------------
 */

// JavaScript language vars
Route::get('assets/javascript', '\Platform\Controllers\App\AssetController@appJs');

// Secured web routes
Route::group(['middleware' => 'auth:web'], function () {

  // Main layout
  Route::get('platform', '\Platform\Controllers\App\MainController@main')->name('main');

  /*
   |--------------------------------------------------------------------------
   | Partials
   |--------------------------------------------------------------------------
   */

  // Dashboard
  Route::get('platform/dashboard', '\Platform\Controllers\App\DashboardController@showDashboard');

  // Profile
  Route::get('platform/profile', '\Platform\Controllers\App\AccountController@showProfile');
  Route::post('platform/profile', '\Platform\Controllers\App\AccountController@postProfile');
  Route::post('platform/profile-avatar', '\Platform\Controllers\App\AccountController@postAvatar');
  Route::post('platform/profile-avatar-delete', '\Platform\Controllers\App\AccountController@postDeleteAvatar');

  // Temp -----------------------------------------------
  Route::get('platform/landing/editor', '\Platform\Controllers\Landing\PagesController@showEditor');

  
  // -----------------------------------------------------------------
  // Plan limitation account.plan_visible
  Route::group(['middleware' => 'limitation:account.plan_visible'], function () {

    // Plan
    Route::get('platform/plan', '\Platform\Controllers\App\AccountController@showPlan');
  });

  // -----------------------------------------------------------------
  // Plan limitation online.visible
  Route::group(['middleware' => 'limitation:online.visible'], function () {

    // -----------------------------------------------------------------
    // Plan limitation online.members_visible
    Route::group(['middleware' => 'limitation:online.members_visible'], function () {

      // Members
      Route::get('platform/members', '\Platform\Controllers\Members\MemberController@showMembers');
      Route::get('platform/members/export', '\Platform\Controllers\Members\MemberController@getExport');
      Route::get('platform/members/data', '\Platform\Controllers\Members\MemberController@getMemberData');
      Route::get('platform/member/edit', '\Platform\Controllers\Members\MemberController@showEditMember');
      Route::post('platform/member/update', '\Platform\Controllers\Members\MemberController@postMember');
      Route::post('platform/member/delete', '\Platform\Controllers\Members\MemberController@postMemberDelete');
      Route::post('platform/member/upload-avatar', '\Platform\Controllers\Members\MemberController@postAvatar');
      Route::post('platform/member/delete-avatar', '\Platform\Controllers\Members\MemberController@postDeleteAvatar');
    });
  });


  // -----------------------------------------------------------------
  // Plan limitation media.visible
  Route::group(['middleware' => 'limitation:media.visible'], function () {

    // Media
    Route::get('platform/media/browser', '\Platform\Controllers\App\MediaController@showBrowser');
    Route::get('platform/media/picker', '\Platform\Controllers\App\MediaController@showPicker');
    Route::any('elfinder/connector', '\Barryvdh\Elfinder\ElfinderController@showConnector');
    Route::get('elfinder/tinymce', '\Platform\Controllers\App\MediaController@showTinyMCE');
  });

  // For owners
  Route::group(['middleware' => 'role:owner'], function () {

    // Reseller management
    Route::get('platform/admin/resellers', '\Platform\Controllers\App\ResellerController@showResellers');
    Route::get('platform/admin/resellers/data', '\Platform\Controllers\App\ResellerController@getResellerData');
    Route::get('platform/admin/reseller/new', '\Platform\Controllers\App\ResellerController@showNewReseller');
    Route::post('platform/admin/reseller/new', '\Platform\Controllers\App\ResellerController@postNewReseller');
    Route::get('platform/admin/reseller/edit', '\Platform\Controllers\App\ResellerController@showEditReseller');
    Route::post('platform/admin/reseller/update', '\Platform\Controllers\App\ResellerController@postReseller');
    Route::post('platform/admin/reseller/delete', '\Platform\Controllers\App\ResellerController@postResellerDelete');
  });

  // For owners and admins
  Route::group(['middleware' => 'role:owner,reseller,admin'], function () {

    // User management
    Route::get('platform/admin/users', '\Platform\Controllers\App\UserController@showUsers');
    Route::get('platform/admin/users/data', '\Platform\Controllers\App\UserController@getUserData');
    Route::get('platform/admin/user/new', '\Platform\Controllers\App\UserController@showNewUser');
    Route::post('platform/admin/user/new', '\Platform\Controllers\App\UserController@postNewUser');
    Route::get('platform/admin/user/edit', '\Platform\Controllers\App\UserController@showEditUser');
    Route::post('platform/admin/user/update', '\Platform\Controllers\App\UserController@postUser');
    Route::post('platform/admin/user/delete', '\Platform\Controllers\App\UserController@postUserDelete');
    Route::post('platform/admin/user/upload-avatar', '\Platform\Controllers\App\UserController@postAvatar');
    Route::post('platform/admin/user/delete-avatar', '\Platform\Controllers\App\UserController@postDeleteAvatar');
    Route::get('platform/admin/user/login-as/{sl}', '\Platform\Controllers\App\UserController@getLoginAs');

    // Plan management
    Route::get('platform/admin/plans', '\Platform\Controllers\App\PlanController@showPlans');
    Route::get('platform/admin/plans/data', '\Platform\Controllers\App\PlanController@getPlanData');
    Route::get('platform/admin/plan/new', '\Platform\Controllers\App\PlanController@showNewPlan');
    Route::post('platform/admin/plan/new', '\Platform\Controllers\App\PlanController@postNewPlan');
    Route::get('platform/admin/plan/edit', '\Platform\Controllers\App\PlanController@showEditPlan');
    Route::post('platform/admin/plan/update', '\Platform\Controllers\App\PlanController@postPlan');
    Route::post('platform/admin/plan/delete', '\Platform\Controllers\App\PlanController@postPlanDelete');
    Route::post('platform/admin/plan/order', '\Platform\Controllers\App\PlanController@postPlanOrder');

    // Software apps
    Route::get('platform/admin/software/apps', '\Platform\Controllers\Software\AppController@showApps');
    Route::get('platform/admin/software/apps/data', '\Platform\Controllers\Software\AppController@getAppData');
    Route::get('platform/admin/software/app/new', '\Platform\Controllers\Software\AppController@showNewApp');
    Route::post('platform/admin/software/app/new', '\Platform\Controllers\Software\AppController@postApp');
    Route::get('platform/admin/software/app/edit', '\Platform\Controllers\Software\AppController@showEditApp');
    Route::post('platform/admin/software/app/update', '\Platform\Controllers\Software\AppController@postApp');
    Route::post('platform/admin/software/app/delete', '\Platform\Controllers\Software\AppController@postAppDelete');
    Route::post('platform/admin/software/app/order', '\Platform\Controllers\Software\AppController@postAppOrder');
  });
});

/*
 |--------------------------------------------------------------------------
 | Auth Platform
 |--------------------------------------------------------------------------
 */

// Login Routes
Route::get('login', ['as' => 'login', 'uses' => 'Auth\LoginController@showLoginForm']);
Route::post('login', ['as' => 'login.post', 'uses' => 'Auth\LoginController@login']);
Route::post('logout', ['as' => 'logout', 'uses' => 'Auth\LoginController@logout']);
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

// Registration Routes
if (\Config::get('auth.allow_registration', true)) {
  Route::get('register', ['as' => 'register', 'uses' => 'Auth\RegisterController@showRegistrationForm']);
  Route::post('register', ['as' => 'register.post', 'uses' => 'Auth\RegisterController@register']);
}

// Password Reset Routes
Route::get('password/reset', ['as' => 'password.reset', 'uses' => 'Auth\ForgotPasswordController@showLinkRequestForm']);
Route::post('password/email', ['as' => 'password.email', 'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail']);
Route::get('password/reset/{token}', ['as' => 'password.reset.token', 'uses' => 'Auth\ResetPasswordController@showResetForm']);
Route::post('password/reset', ['as' => 'password.reset.post', 'uses' => 'Auth\ResetPasswordController@reset']);

/*
 |--------------------------------------------------------------------------
 | Auth Members
 |--------------------------------------------------------------------------
 */

//Member Login
Route::get('member/login', 'AuthMember\LoginController@showLoginForm');
Route::post('member/login', 'AuthMember\LoginController@login');
Route::post('member/logout', 'AuthMember\LoginController@logout');
Route::get('member/logout', 'AuthMember\LoginController@logout');

//Member Register
Route::get('member/register', 'AuthMember\RegisterController@showRegistrationForm');
Route::post('member/register', 'AuthMember\RegisterController@register');

//Member Passwords
Route::post('member/password/email', 'AuthMember\ForgotPasswordController@sendResetLinkEmail');
Route::post('member/password/reset', 'AuthMember\ResetPasswordController@reset');
Route::get('member/password/reset', 'AuthMember\ForgotPasswordController@showLinkRequestForm');
Route::get('member/password/reset/{token}', 'AuthMember\ResetPasswordController@showResetForm');

// Reset everything
Route::get('reset/{key}', '\Platform\Controllers\App\InstallationController@reset');
?>