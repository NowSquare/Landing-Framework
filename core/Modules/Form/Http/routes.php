<?php

Route::group(['middleware' => 'web', 'prefix' => 'platform/form', 'namespace' => 'Modules\Form\Http\Controllers'], function()
{
  // Secured web routes
  Route::group(['middleware' => 'auth:web'], function () {

    //Route::get('/', 'FormController@index');
    Route::get('create', 'FormController@create');

  });
});
