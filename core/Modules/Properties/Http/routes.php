<?php

Route::group(['middleware' => 'web', 'prefix' => 'properties', 'namespace' => 'Modules\Properties\Http\Controllers'], function()
{
    Route::get('/', 'PropertiesController@index');
});
