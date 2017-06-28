<?php
if (env('REAL_ESTATE', false)) {
  Route::group(['middleware' => 'web', 'namespace' => 'Modules\Properties\Http\Controllers'], function() {

    // Public routes
    Route::get('', 'PropertiesController@homePage');
    Route::get('locale', 'PropertiesController@locale');

    // Secured routes
    Route::group(['middleware' => 'auth:web'], function () {
      Route::get('edit/{local_domain}', 'PropertiesController@editor');
    });
  });

  Route::group(['middleware' => ['web'], 'prefix' => 'properties', 'namespace' => 'Modules\Properties\Http\Controllers'], function() {
    Route::get('editor/block-preview', 'PropertiesController@editorBlockPreview');
  });

  Route::group(['middleware' => ['web', 'funnel', 'limitation:properties.visible'], 'prefix' => 'properties', 'namespace' => 'Modules\Properties\Http\Controllers'], function() {

    // Secured routes
    Route::group(['middleware' => 'auth:web'], function () {

      Route::get('/', 'PropertiesController@index');

      // Edit html
      /*
      Route::group(['middleware' => ['auth:web', 'limitation:properties.edit_html']], function () {
        Route::get('source', 'PropertiesController@sourceEditor');
        Route::post('source', 'PropertiesController@postSourceEditor');
      });
  */
    });
  });
}