<?php

Route::group(['middleware' => 'web', 'prefix' => 'beacons', 'namespace' => 'Modules\Beacons\Http\Controllers'], function()
{
    Route::get('/', 'BeaconsController@index');
});
