<?php

Route::group(['middleware' => 'web', 'prefix' => 'ec', 'namespace' => 'Modules\EmailCampaigns\Http\Controllers'], function() {

  // Mail test
  Route::get('mail', 'EmailsController@sendEmail');

  // Public routes
  Route::get('{local_domain}', 'EmailsController@showEmail');

  // Secured routes
  Route::group(['middleware' => 'auth:web'], function () {
    Route::get('edit/{local_domain}', 'EmailsController@editor');
  });

});

Route::group(['middleware' => ['web', 'funnel', 'limitation:emailcampaigns.visible'], 'prefix' => 'emailcampaigns', 'namespace' => 'Modules\EmailCampaigns\Http\Controllers'], function()
{
    Route::get('/', 'EmailCampaignsController@showCampaigns');
    Route::get('create', 'EmailCampaignsController@showCreateCampaign');
    Route::post('create', 'EmailCampaignsController@postCreateCampaign');
    Route::get('edit', 'EmailCampaignsController@showEditCampaign');
    Route::post('update', 'EmailCampaignsController@postUpdateCampaign');
    Route::post('delete', 'EmailCampaignsController@deleteCampaign');

    Route::get('emails', 'EmailsController@showEmails');
    Route::get('emails/create', 'EmailsController@showCreateEmail');
    Route::get('emails/create/{category}', 'EmailsController@showSelectTemplate');
    Route::post('emails/create', 'EmailsController@postCreateEmail');

    Route::post('emails/save', 'EmailsController@saveEmail');
    Route::get('emails/editor', 'EmailsController@editorFrame');

    // Previews
    Route::get('preview/{template}', 'EmailsController@previewTemplate');
});
