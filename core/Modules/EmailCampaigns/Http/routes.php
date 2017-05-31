<?php

Route::group(['middleware' => 'web', 'prefix' => 'ec', 'namespace' => 'Modules\EmailCampaigns\Http\Controllers'], function() {

  // Mail test
  Route::get('mail', 'EmailsController@sendEmail');

  // Confirm email address
  Route::get('confirm/{email_address}', 'EmailsController@confirmEmailTest');
  Route::get('confirm/{email_address}/{local_domain}', 'EmailsController@confirmEmail');

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
    Route::post('emails/delete', 'EmailsController@deleteEmail');

    Route::post('emails/save', 'EmailsController@saveEmail');
    Route::get('emails/editor', 'EmailsController@editorFrame');

    // Editor modals
    Route::get('editor/modal/settings', 'EmailsController@editorModalSettings');
    Route::post('editor/settings', 'EmailsController@editorPostSettings');
    Route::get('editor/modal/test-email', 'EmailsController@editorModalTestEmail');
    Route::post('editor/test-email', 'EmailsController@editorPostTestEmail');

    // Previews
    Route::get('preview/{template}', 'EmailsController@previewTemplate');

    // Email variables
    Route::get('emails/editor/variables', 'EmailsController@getEmailVariables');
});
