<?php
// Public landing page
Route::get('lp/{local_domain}', 'Modules\LandingPages\Http\Controllers\LandingPagesController@homePage');

Route::group(['middleware' => 'web', 'prefix' => 'landingpages', 'namespace' => 'Modules\LandingPages\Http\Controllers'], function() {

  // Secured web routes
  Route::group(['middleware' => 'auth:web'], function () {

    Route::get('create', 'LandingPagesController@create');
    Route::get('editor', 'LandingPagesController@editor');

    // Editor modals
    Route::get('editor/modal/insert-block', 'LandingPagesController@editorModalInsertBlock');
    Route::get('editor/modal/background', 'LandingPagesController@editorModalBackground');
    Route::get('editor/modal/link', 'LandingPagesController@editorModalLink');
    Route::get('editor/modal/list', 'LandingPagesController@editorModalList');
    Route::get('editor/modal/image', 'LandingPagesController@editorModalImage');
    Route::get('editor/modal/icon', 'LandingPagesController@editorModalIcon');

  });
});
