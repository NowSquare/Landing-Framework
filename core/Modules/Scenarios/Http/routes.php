<?php
Route::group(['middleware' => ['web'], 'prefix' => 'scenarios', 'namespace' => 'Modules\Scenarios\Http\Controllers'], function()
{
  // View scenario assets
  Route::get('view/image/{hash}', '\Modules\Scenarios\Http\Controllers\ViewController@showImage');
  Route::get('view/template/{hash}', '\Modules\Scenarios\Http\Controllers\ViewController@showTemplate');
});

Route::group(['middleware' => ['web', 'funnel'], 'prefix' => 'scenarios', 'namespace' => 'Modules\Scenarios\Http\Controllers'], function()
{

  // Secured routes
  Route::group(['middleware' => 'auth:web'], function () {

    // Scenario editor
    Route::get('/', 'ScenariosController@showEditScenarios');

    // Analytics
    Route::get('analytics', 'AnalyticsController@showAnalytics');

    // WYSIWYG editor
    Route::get('edit/template', 'ScenariosController@showTemplateEditor');

    // QR
    Route::get('qr', 'ScenariosController@showQr');

    // App required modal
    Route::get('app-required', 'ScenariosController@showAppRequired');
  });

});

/*
 |--------------------------------------------------------------------------
 | Public API
 |--------------------------------------------------------------------------
 */

Route::group(['middleware' => 'web', 'prefix' => 'api/v1/scenarios', 'namespace' => 'Modules\Scenarios\Http\Controllers'], function()
{
    // Post interaction from app
    Route::post('trigger', 'ApiController@postTrigger');

    // Scenario API
    Route::get('/', function() { return App::make('\Modules\Scenarios\Http\Controllers\ApiController')->getApiResponse(); });
    Route::get('account', function() { return App::make('\Modules\Scenarios\Http\Controllers\ApiController')->getApiResponse('account'); });
    Route::get('funnel', function() { return App::make('\Modules\Scenarios\Http\Controllers\ApiController')->getApiResponse('funnel'); });
});

/*
 |--------------------------------------------------------------------------
 | JSON web token secured routes
 |--------------------------------------------------------------------------
 */
Route::group(['middleware' => 'jwt.auth', 'prefix' => 'scenarios', 'namespace' => 'Modules\Scenarios\Http\Controllers'], function () {

  // Scenarios
  Route::post('scenario', 'ScenariosController@postScenario');
  Route::post('update-scenario', 'ScenariosController@postUpdateScenario');
  Route::post('update-scenario-places', 'ScenariosController@postUpdateScenarioPlaces');
  Route::post('delete-scenario', 'ScenariosController@postDeleteScenario');
});