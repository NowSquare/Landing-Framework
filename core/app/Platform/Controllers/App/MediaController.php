<?php namespace Platform\Controllers\App;

use \Platform\Controllers\Core;

class MediaController extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | Media Controller
   |--------------------------------------------------------------------------
   |
   | Media related logic
   |--------------------------------------------------------------------------
   */

  /**
   * Media
   */
  public function showBrowser()
  {
    return view('platform.media.browser', [
      'locale' => \App::getLocale()
    ]);
  }

  /**
   * File picker
   */
  public function showPicker()
  {
    $id = request()->input('id');
    $preview = request()->input('preview');

    return view('platform.media.picker', [
      'locale' => \App::getLocale(),
      'id' => $id,
      'preview' => $preview
    ]);
  }

  /**
   * Load elFinder TinyMCE
   */
  public function showTinyMCE()
  {
    return \View::make('platform.media.tinymce4');
  }
}