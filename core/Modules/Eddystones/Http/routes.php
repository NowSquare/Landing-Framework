<?php

Route::group(['middleware' => ['web', 'funnel', 'limitation:eddystones.visible'], 'prefix' => 'eddystones', 'namespace' => 'Modules\Eddystones\Http\Controllers'], function()
{
    Route::get('/', 'EddystonesController@showEddystones');
    Route::get('/create', 'EddystonesController@showCreateEddystone');
    Route::post('/create', 'EddystonesController@postCreateEddystone');
    Route::post('/delete', 'EddystonesController@postDeleteEddystone');
    Route::get('/edit', 'EddystonesController@showEditEddystone');
    Route::post('/edit', 'EddystonesController@postEditEddystone');
});