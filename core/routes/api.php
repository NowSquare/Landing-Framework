<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

/*
 |--------------------------------------------------------------------------
 | JSON web token secured routes
 |--------------------------------------------------------------------------
 */
Route::group(['middleware' => 'jwt.auth'], function () {

  // Scenario board
  Route::post('scenario/scenario', '\Platform\Controllers\Location\ScenarioController@postScenario');
  Route::post('scenario/update-scenario', '\Platform\Controllers\Location\ScenarioController@postUpdateScenario');
  Route::post('scenario/update-scenario-places', '\Platform\Controllers\Location\ScenarioController@postUpdateScenarioPlaces');
  Route::post('scenario/delete-scenario', '\Platform\Controllers\Location\ScenarioController@postDeleteScenario');
});

/*
 |--------------------------------------------------------------------------
 | Remote API v1
 |--------------------------------------------------------------------------
 */
Route::group(['prefix' => 'v1'], function() {

  // Proximity
  Route::get('remote', '\Platform\Controllers\Location\ApiController@getApp');
  Route::post('remote', '\Platform\Controllers\Location\ApiController@getApp');
  Route::post('remote/scenario', '\Platform\Controllers\Location\ApiController@postScenario');
  Route::get('remote/categories', '\Platform\Controllers\Categories\ApiCategoryController@getCategories');
  Route::get('remote/cards', '\Platform\Controllers\Location\ApiCardController@getCards');
  //Route::get('remote/card', '\Platform\Controllers\Location\ApiCardController@getCard');
  Route::post('remote/card', '\Platform\Controllers\Location\ApiCardController@getCard');

  // Avangate
  Route::get('avangate/lcn', '\Platform\Controllers\App\AvangateController@getLcn');
  Route::post('avangate/lcn', '\Platform\Controllers\App\AvangateController@postLcn');
  Route::get('avangate/ipn', '\Platform\Controllers\App\AvangateController@getIpn');
  Route::post('avangate/ipn', '\Platform\Controllers\App\AvangateController@postIpn');
  Route::get('avangate/teams', '\Platform\Controllers\App\AvangateController@getTeams');
});
