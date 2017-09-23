<?php

Route::group(['middleware' => ['web', 'funnel'], 'prefix' => 'geofences', 'namespace' => 'Modules\Geofences\Http\Controllers'], function()
{
  // Secured web routes
  Route::group(['middleware' => 'auth:web'], function () {
    Route::get('/', 'GeofencesController@showGeofences');
    Route::post('geofence', 'GeofencesController@postGeofence');
    Route::get('data', 'GeofencesController@getGeofenceData');
    Route::get('create', 'GeofencesController@showCreateGeofence');
    Route::get('edit', 'GeofencesController@showEditGeofence');
    Route::post('delete', 'GeofencesController@postDelete');
    Route::post('switch', 'GeofencesController@postSwitch');
    Route::get('export', 'GeofencesController@getExport');
  });
});
