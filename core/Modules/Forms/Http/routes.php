<?php

Route::group(['middleware' => 'web', 'prefix' => 'f', 'namespace' => 'Modules\Forms\Http\Controllers'], function() {

  // Public routes
  Route::get('{local_domain}', 'FormsController@homePage');

  // Form post
  Route::post('post', 'FormsController@formPost');

  // Secured routes
  Route::group(['middleware' => 'auth:web'], function () {
    Route::get('edit/{local_domain}', 'FormsController@editor');
  });

});

Route::group(['middleware' => ['web', 'funnel', 'limitation:forms.visible'], 'prefix' => 'forms', 'namespace' => 'Modules\Forms\Http\Controllers'], function() {

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

    // Editor modals
    Route::get('editor/modal/settings', 'FormsController@editorModalSettings');
    Route::post('editor/settings', 'FormsController@editorPostSettings');
    Route::get('editor/modal/seo', 'FormsController@editorModalSeo');
    Route::post('editor/seo', 'FormsController@editorPostSeo');
    Route::get('editor/modal/design', 'FormsController@editorModalDesign');
    Route::post('editor/design', 'FormsController@editorPostDesign');

    // Entries
    Route::get('entries', 'EntriesController@showEntries');

    Route::post('entry/delete', 'EntriesController@postDelete');
    //Route::get('entries/data', 'EntriesController@getData');
    Route::post('entries/data', 'EntriesController@getData');
    Route::get('entries/export', 'EntriesController@getExport'); 
  });
});