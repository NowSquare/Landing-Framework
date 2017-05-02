<?php

Route::group(['middleware' => 'web', 'prefix' => 'platform/landingpage', 'namespace' => 'Modules\LandingPage\Http\Controllers'], function()
{
  // Secured web routes
  Route::group(['middleware' => 'auth:web'], function () {

    Route::get('create', 'LandingPageController@create');

  });
});
