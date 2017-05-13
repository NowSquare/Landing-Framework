<?php

Route::group(['middleware' => 'web', 'prefix' => 'lp', 'namespace' => 'Modules\LandingPages\Http\Controllers'], function() {

  // Public landing page
  Route::get('{local_domain}', 'LandingPagesController@homePage');
});

Route::group(['middleware' => ['web', 'limitation:landingpages.visible'], 'prefix' => 'landingpages', 'namespace' => 'Modules\LandingPages\Http\Controllers'], function() {

  // Secured web routes
  Route::group(['middleware' => 'auth:web'], function () {

    Route::get('/', 'LandingPagesController@index');
    Route::get('create', 'LandingPagesController@create');
    Route::get('create/{category}', 'LandingPagesController@createCategory');
    Route::get('edit', 'LandingPagesController@editor');
    Route::get('editor', 'LandingPagesController@editorFrame');

    // Previews
    Route::get('preview/{template}', 'LandingPagesController@previewTemplate');
    Route::get('editor/block-preview', 'LandingPagesController@editorBlockPreview');

    // Pickers
    Route::get('editor/picker/button', 'LandingPagesController@editorPickerButton');

    // Editor modals
    Route::get('editor/modal/insert-block', 'LandingPagesController@editorModalInsertBlock');
    Route::get('editor/modal/insert-block-select', 'LandingPagesController@editorModalInsertBlockSelect');
    Route::get('editor/modal/background', 'LandingPagesController@editorModalBackground');
    Route::get('editor/modal/link', 'LandingPagesController@editorModalLink');
    Route::get('editor/modal/list', 'LandingPagesController@editorModalList');
    Route::get('editor/modal/image', 'LandingPagesController@editorModalImage');
    Route::get('editor/modal/icon', 'LandingPagesController@editorModalIcon');

  });
});
