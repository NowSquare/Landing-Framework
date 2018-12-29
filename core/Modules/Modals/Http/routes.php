<?php
// Public modal routes
Route::group(['middleware' => 'cors', 'namespace' => 'Modules\Modals\Http\Controllers'], function () {
  Route::get('modal/get/{template?}/{id?}', 'ModalController@getModal');
  Route::get('modal/settings', 'ModalController@getModalSettings');
});

Route::group(['middleware' => ['web', 'funnel'], 'prefix' => 'modals', 'namespace' => 'Modules\Modals\Http\Controllers'], function() {


  // Secured web routes
  Route::group(['middleware' => 'auth:web'], function () {

    Route::get('/', 'ModalsController@showModals');
    Route::get('data', 'ModalsController@getModalData');
    Route::get('create', 'ModalsController@showCreateModal');
    Route::post('modal', 'ModalsController@postModal');
    Route::get('edit', 'ModalsController@showEditModal');
    Route::post('delete', 'ModalsController@postDelete');
    Route::post('switch', 'ModalsController@postSwitch');
    Route::get('export', 'ModalsController@getExport');

  });
});
