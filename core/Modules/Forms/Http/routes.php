<?php

Route::group(['middleware' => 'web', 'prefix' => 'lp', 'namespace' => 'Modules\Forms\Http\Controllers'], function() {

  // Public routes
  Route::get('{local_domain}', 'FormsController@homePage');
});

Route::group(['middleware' => ['web', 'limitation:forms.visible'], 'prefix' => 'forms', 'namespace' => 'Modules\Forms\Http\Controllers'], function() {

  // Secured web routes
  Route::group(['middleware' => 'auth:web'], function () {

    Route::get('/', 'FormsController@index');
    Route::get('create', 'FormsController@create');
  });
});