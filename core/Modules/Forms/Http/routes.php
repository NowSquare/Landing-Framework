<?php

Route::group(['middleware' => 'web', 'prefix' => 'f', 'namespace' => 'Modules\Forms\Http\Controllers'], function() {

  // Public routes
  Route::get('{local_domain}', 'FormsController@homePage');

  // Secured routes
  Route::group(['middleware' => 'auth:web'], function () {
    Route::get('edit/{local_domain}', 'FormsController@editor');
  });

});

Route::group(['middleware' => ['web', 'limitation:forms.visible'], 'prefix' => 'forms', 'namespace' => 'Modules\Forms\Http\Controllers'], function() {

  // Secured web routes
  Route::group(['middleware' => 'auth:web'], function () {

    Route::get('/', 'FormsController@index');
    Route::get('create', 'FormsController@create');
    Route::get('create/{category}', 'FormsController@createCategory');
    Route::post('create', 'FormsController@createForm');
    Route::post('save', 'FormsController@saveForm');
    Route::post('publish', 'FormsController@publishForm');
    Route::post('unpublish', 'FormsController@unpublishForm');
    Route::post('delete', 'FormsController@deleteForm');
    Route::get('editor', 'FormsController@editorFrame');

    // Previews
    Route::get('preview/{template}', 'FormsController@previewTemplate');
  });
});