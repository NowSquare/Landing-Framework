<?php

Route::group(['middleware' => ['web', 'funnel'], 'prefix' => 'beacons', 'namespace' => 'Modules\Beacons\Http\Controllers'], function()
{
  // Secured web routes
  Route::group(['middleware' => 'auth:web'], function () {

    Route::get('/', 'BeaconsController@showBeacons');
    Route::get('data', 'BeaconsController@getBeaconData');
    Route::get('create', 'BeaconsController@showCreateBeacon');
    Route::post('beacon', 'BeaconsController@postBeacon');
    Route::get('edit', 'BeaconsController@showEditBeacon');
    Route::post('delete', 'BeaconsController@postDelete');
    Route::post('switch', 'BeaconsController@postSwitch');
    Route::get('export', 'BeaconsController@getExport');
    Route::post('beacon-uuid', 'BeaconsController@postBeaconUuid');

  });
});
