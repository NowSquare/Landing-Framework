<?php

Route::group(['middleware' => 'web', 'prefix' => 'ec', 'namespace' => 'Modules\EmailCampaigns\Http\Controllers'], function() {

  // Public routes
  Route::get('{local_domain}', 'EmailCampaignsController@showEmail');

  // Secured routes
  Route::group(['middleware' => 'auth:web'], function () {
    Route::get('edit/{local_domain}', 'EmailCampaignsController@editor');
  });

});

Route::group(['middleware' => ['web', 'funnel', 'limitation:emailcampaigns.visible'], 'prefix' => 'emailcampaigns', 'namespace' => 'Modules\EmailCampaigns\Http\Controllers'], function()
{
    Route::get('/', 'EmailCampaignsController@index');
    Route::get('create', 'EmailCampaignsController@create');
    Route::get('create/{category}', 'EmailCampaignsController@createCategory');
    Route::post('create', 'EmailCampaignsController@createCampaign');

    Route::post('save', 'EmailCampaignsController@saveEmail');
    Route::post('publish', 'EmailCampaignsController@publishEmail');
    Route::post('unpublish', 'EmailCampaignsController@unpublishEmail');
    Route::post('delete', 'EmailCampaignsController@deleteEmail');
    Route::get('editor', 'EmailCampaignsController@editorFrame');

    // Previews
    Route::get('preview/{template}', 'EmailCampaignsController@previewTemplate');
});
