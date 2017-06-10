<?php

Route::group(['middleware' => 'web', 'prefix' => 'lp', 'namespace' => 'Modules\LandingPages\Http\Controllers'], function() {

  // Create screenshots for all blocks
  Route::get('grab', 'LandingPagesController@createBlockScreenshots');

  // Public routes
  Route::get('{local_domain}', 'LandingPagesController@homePage');

  // Secured routes
  Route::group(['middleware' => 'auth:web'], function () {
    Route::get('edit/{local_domain}', 'LandingPagesController@editor');
  });
});

Route::group(['middleware' => ['web'], 'prefix' => 'landingpages', 'namespace' => 'Modules\LandingPages\Http\Controllers'], function() {
  Route::get('editor/block-preview', 'LandingPagesController@editorBlockPreview');
});

Route::group(['middleware' => ['web', 'funnel', 'limitation:landingpages.visible'], 'prefix' => 'landingpages', 'namespace' => 'Modules\LandingPages\Http\Controllers'], function() {

  // Secured routes
  Route::group(['middleware' => 'auth:web'], function () {

    Route::get('/', 'LandingPagesController@index');
    Route::get('create', 'LandingPagesController@create');
    Route::get('create/{category}', 'LandingPagesController@createCategory');
    Route::post('create', 'LandingPagesController@createPage');
    Route::post('save', 'LandingPagesController@savePage');
    Route::post('publish', 'LandingPagesController@publishPage');
    Route::post('unpublish', 'LandingPagesController@unpublishPage');
    Route::post('delete', 'LandingPagesController@deletePage');
    Route::get('editor', 'LandingPagesController@editorFrame');

    // Previews
    Route::get('preview/{template}', 'LandingPagesController@previewTemplate');
    Route::post('editor/block-html', 'LandingPagesController@editorBlockHtml');
    //Route::get('editor/block-preview', 'LandingPagesController@editorBlockPreview');

    // Pickers
    Route::get('editor/picker/button', 'LandingPagesController@editorPickerButton');

    // Editor modals
    Route::get('editor/modal/insert-block', 'LandingPagesController@editorModalInsertBlock');
    Route::get('editor/modal/insert-block-select', 'LandingPagesController@editorModalInsertBlockSelect');
    Route::get('editor/modal/background', 'LandingPagesController@editorModalBackground');
    Route::get('editor/modal/link', 'LandingPagesController@editorModalLink');
    Route::get('editor/modal/list', 'LandingPagesController@editorModalList');
    Route::get('editor/modal/form', 'LandingPagesController@editorModalForm');
    Route::get('editor/modal/image', 'LandingPagesController@editorModalImage');
    Route::get('editor/modal/video', 'LandingPagesController@editorModalVideo');
    Route::post('editor/parse-embed', 'LandingPagesController@editorParseEmbed');
    Route::get('editor/modal/icon', 'LandingPagesController@editorModalIcon');
    Route::get('editor/modal/qr', 'LandingPagesController@editorModalQr');
    Route::get('editor/modal/seo', 'LandingPagesController@editorModalSeo');
    Route::post('editor/seo', 'LandingPagesController@editorPostSeo');
    Route::get('editor/modal/domain', 'LandingPagesController@editorModalDomain');
    Route::post('editor/domain', 'LandingPagesController@editorPostDomain');

    // Analytics
    Route::get('analytics', 'AnalyticsController@showAnalytics');
    Route::post('analytics/stats/data', 'AnalyticsController@getStatData');
    Route::post('analytics/stats/range', 'AnalyticsController@getStatRange');

    // Edit html
    Route::group(['middleware' => ['auth:web', 'limitation:landingpages.edit_html']], function () {
      Route::get('source', 'LandingPagesController@sourceEditor');
    });

  });
});
