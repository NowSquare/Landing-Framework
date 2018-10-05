<?php

Route::group(['middleware' => 'web', 'prefix' => 'ec', 'namespace' => 'Modules\EmailCampaigns\Http\Controllers'], function() {

  // Mail test
  Route::get('mail', 'EmailsController@sendEmail');

  // Confirm email address
  Route::get('confirm/{email_address}', 'EmailsController@confirmEmailTest');
  Route::get('confirm/{email_address}/{local_domain}/{entry_id}', 'EmailsController@confirmEmail');

  // Unsubscribe email address
  Route::get('unsubscribe/{email_address}', 'EmailsController@unsubscribeEmailTest');
  Route::get('unsubscribe/{email_address}/{local_domain}/{entry_id}', 'EmailsController@unsubscribeEmail');

  // Public routes
  Route::get('{local_domain}', 'EmailsController@showEmail');

  // Mailgun webhooks
  Route::post('mg/event', 'EmailsController@mgEvent');

  // Secured routes
  Route::group(['middleware' => 'auth:web'], function () {
    Route::get('edit/{local_domain}', 'EmailsController@editor');
  });

});

Route::group(['middleware' => ['web', 'funnel', 'limitation:emailcampaigns.visible'], 'prefix' => 'emailcampaigns', 'namespace' => 'Modules\EmailCampaigns\Http\Controllers'], function()
{
  // Secured routes
  Route::group(['middleware' => 'auth:web'], function () {
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
    Route::get('editor/modal/send-mailing', 'EmailsController@editorModalSendMailing');
    //Route::post('editor/send-mailing', 'EmailsController@editorPostSendMailing');
    Route::post('send-mailing', 'EmailsController@postSendMailing');
    Route::post('schedule-mailing', 'EmailsController@postScheduleMailing');
    Route::post('remove-schedule-mailing', 'EmailsController@postRemoveScheduleMailing');

    // Previews
    Route::get('preview/{template}', 'EmailsController@previewTemplate');

    // Email variables
    Route::get('emails/editor/variables', 'EmailsController@getEmailVariables');
  });
});
