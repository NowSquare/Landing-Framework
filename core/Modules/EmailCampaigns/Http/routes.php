<?php

Route::group(['middleware' => 'web', 'prefix' => 'ec', 'namespace' => 'Modules\EmailCampaigns\Http\Controllers'], function() {

  // Public routes
  Route::get('{local_domain}', 'EmailCampaigns@homePage');

  // Secured routes
  Route::group(['middleware' => 'auth:web'], function () {
    Route::get('edit/{local_domain}', 'EmailCampaigns@editor');
  });

});

Route::group(['middleware' => 'web', 'prefix' => 'emailcampaigns', 'namespace' => 'Modules\EmailCampaigns\Http\Controllers'], function()
{
    Route::get('/', 'EmailCampaignsController@index');
    Route::get('create', 'EmailCampaignsController@create');
    Route::get('create/{category}', 'EmailCampaignsController@createCategory');
    Route::post('create', 'EmailCampaignsController@createForm');
});
