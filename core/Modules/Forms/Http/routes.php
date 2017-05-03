<?php

Route::group(['middleware' => 'web', 'prefix' => 'forms', 'namespace' => 'Modules\Forms\Http\Controllers'], function()
{
  // Secured web routes
  Route::group(['middleware' => 'auth:web'], function () {

    //Route::get('/', 'FormsController@index');
    Route::get('create', 'FormsController@create');

  });
});
