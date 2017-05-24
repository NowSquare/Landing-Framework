<?php

Route::group(['middleware' => 'web', 'prefix' => 'emailcampaigns', 'namespace' => 'Modules\EmailCampaigns\Http\Controllers'], function()
{
    Route::get('/', 'EmailCampaignsController@index');
});
