<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

/*
 |--------------------------------------------------------------------------
 | Remote API v1
 |--------------------------------------------------------------------------
 */
Route::group(['prefix' => 'v1'], function() {

  // Avangate
  Route::get('avangate/lcn', '\Platform\Controllers\App\AvangateController@getLcn');
  Route::post('avangate/lcn', '\Platform\Controllers\App\AvangateController@postLcn');
  Route::get('avangate/ipn', '\Platform\Controllers\App\AvangateController@getIpn');
  Route::post('avangate/ipn', '\Platform\Controllers\App\AvangateController@postIpn');

  // Stripe
  Route::post('stripe/webhook', '\Platform\Controllers\App\StripeController@postWebhook');
});
