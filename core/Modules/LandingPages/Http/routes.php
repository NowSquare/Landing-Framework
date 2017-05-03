<?php
// Public landing page
Route::get('lp/{local_domain}', 'Modules\LandingPages\Http\Controllers\LandingPagesController@homePage');

Route::group(['middleware' => 'web', 'prefix' => 'landingpages', 'namespace' => 'Modules\LandingPages\Http\Controllers'], function() {

  // Secured web routes
  Route::group(['middleware' => 'auth:web'], function () {

    Route::get('create', 'LandingPagesController@create');
    Route::get('editor', 'LandingPagesController@editor');

    Route::get('editor/modal/background', 'LandingPagesController@editorModalBackground');

  });
});
