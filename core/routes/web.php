<?php

//Route::get('test-expiration', function() {
  //return App::make('\Platform\Controllers\App\UserController')->checkExpiringTrials();
  //return App::make('\Platform\Controllers\App\UserController')->checkExpiredAccounts();
//});

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
Route::get('assets/translations', '\Platform\Controllers\App\AssetController@appJsPublic');

// Thumbnails
Route::get('platform/thumbnail', '\Platform\Controllers\Core\Thumb@getNail');

// Secured web routes
Route::group(['middleware' => 'auth:web'], function () {

  // Main layout
  Route::get('platform', '\Platform\Controllers\App\MainController@main')->name('main');

  // Change language
  Route::post('platform/language', '\Platform\Controllers\Core\Localization@postSetLanguage');

  // Stripe
  Route::post('platform/stripe/token', '\Platform\Controllers\App\StripeController@postToken');

  /*
   |--------------------------------------------------------------------------
   | Partials
   |--------------------------------------------------------------------------
   */

  // Dashboard
  Route::get('platform/dashboard', '\Platform\Controllers\App\DashboardController@showDashboard');

  // Funnels
  Route::get('platform/funnels', '\Platform\Controllers\App\FunnelController@showFunnels');
  Route::post('platform/funnels/select', '\Platform\Controllers\App\FunnelController@selectFunnel');
  //Route::get('platform/funnels/new', '\Platform\Controllers\App\FunnelController@showCreateFunnel');
  Route::post('platform/funnels/new', '\Platform\Controllers\App\FunnelController@postCreateFunnel');
  //Route::get('platform/funnels/edit', '\Platform\Controllers\App\FunnelController@showEditFunnel');
  Route::post('platform/funnels/edit', '\Platform\Controllers\App\FunnelController@postEditFunnel');
  Route::post('platform/funnels/delete', '\Platform\Controllers\App\FunnelController@postDeleteFunnel');

  // Profile
  Route::get('platform/profile', '\Platform\Controllers\App\AccountController@showProfile');
  Route::post('platform/profile', '\Platform\Controllers\App\AccountController@postProfile');
  Route::post('platform/profile-avatar', '\Platform\Controllers\App\AccountController@postAvatar');
  Route::post('platform/profile-avatar-delete', '\Platform\Controllers\App\AccountController@postDeleteAvatar');
  
  // -----------------------------------------------------------------
  // Plan limitation account.plan_visible
  Route::group(['middleware' => 'limitation:account.plan_visible'], function () {

    // Plan
    Route::get('platform/plan', '\Platform\Controllers\App\AccountController@showPlan');
  });

  // -----------------------------------------------------------------
  // Plan limitation media.visible
  Route::group(['middleware' => 'limitation:media.visible'], function () {

    // Media
    Route::get('platform/media/browser', '\Platform\Controllers\App\MediaController@showBrowser');
    Route::get('platform/media/picker', '\Platform\Controllers\App\MediaController@showPicker');
    Route::get('platform/media/picker/{input_id}/{callback?}', '\Platform\Controllers\App\MediaController@popUp');
    Route::any('elfinder/connector', '\Barryvdh\Elfinder\ElfinderController@showConnector');
    Route::get('elfinder/tinymce', '\Platform\Controllers\App\MediaController@showTinyMCE');
  });

  // For owners and admins
  Route::group(['middleware' => 'role:owner,reseller,admin'], function () {

    // User management
    Route::get('platform/admin/users', '\Platform\Controllers\App\UserController@showUsers');
    Route::get('platform/admin/users/data', '\Platform\Controllers\App\UserController@getUserData');
    Route::get('platform/admin/user/edit', '\Platform\Controllers\App\UserController@showEditUser');
    Route::post('platform/admin/user/update', '\Platform\Controllers\App\UserController@postUser');
    Route::post('platform/admin/user/delete', '\Platform\Controllers\App\UserController@postUserDelete');
    Route::post('platform/admin/user/upload-avatar', '\Platform\Controllers\App\UserController@postAvatar');
    Route::post('platform/admin/user/delete-avatar', '\Platform\Controllers\App\UserController@postDeleteAvatar');
    Route::get('platform/admin/user/login-as/{sl}', '\Platform\Controllers\App\UserController@getLoginAs');
  });

  // For owners only
  Route::group(['middleware' => 'role:owner'], function () {

    // Reseller management
    Route::get('platform/admin/resellers', '\Platform\Controllers\App\ResellerController@showResellers');
    Route::get('platform/admin/resellers/data', '\Platform\Controllers\App\ResellerController@getResellerData');
    Route::get('platform/admin/reseller/new', '\Platform\Controllers\App\ResellerController@showNewReseller');
    Route::post('platform/admin/reseller/new', '\Platform\Controllers\App\ResellerController@postNewReseller');
    Route::get('platform/admin/reseller/edit', '\Platform\Controllers\App\ResellerController@showEditReseller');
    Route::post('platform/admin/reseller/update', '\Platform\Controllers\App\ResellerController@postReseller');
    Route::post('platform/admin/reseller/delete', '\Platform\Controllers\App\ResellerController@postResellerDelete');

    // New user is owner only
    Route::get('platform/admin/user/new', '\Platform\Controllers\App\UserController@showNewUser');
    Route::post('platform/admin/user/new', '\Platform\Controllers\App\UserController@postNewUser');

    // Settings
    Route::get('platform/admin/settings', '\Platform\Controllers\App\SettingsController@showSettings');

    // Modules
    Route::get('platform/admin/modules', '\Platform\Controllers\App\ModuleController@showModules')->name('modules');
    Route::post('platform/admin/modules/switch', '\Platform\Controllers\App\ModuleController@switchModule');

    // Plan management
    Route::get('platform/admin/plans', '\Platform\Controllers\App\PlanController@showPlans');
    Route::get('platform/admin/plans/data', '\Platform\Controllers\App\PlanController@getPlanData');
    Route::get('platform/admin/plan/new', '\Platform\Controllers\App\PlanController@showNewPlan');
    Route::post('platform/admin/plan/new', '\Platform\Controllers\App\PlanController@postNewPlan');
    Route::get('platform/admin/plan/edit', '\Platform\Controllers\App\PlanController@showEditPlan');
    Route::post('platform/admin/plan/update', '\Platform\Controllers\App\PlanController@postPlan');
    Route::post('platform/admin/plan/delete', '\Platform\Controllers\App\PlanController@postPlanDelete');
    Route::post('platform/admin/plan/order', '\Platform\Controllers\App\PlanController@postPlanOrder');
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

// Reset everything
Route::get('reset/{key}', '\Platform\Controllers\App\InstallationController@reset');
?>