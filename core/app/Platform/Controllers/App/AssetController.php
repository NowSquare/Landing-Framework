<?php namespace Platform\Controllers\App;

class AssetController extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | Assets Controller
   |--------------------------------------------------------------------------
   |
   | Assets related logic
   |--------------------------------------------------------------------------
   */

  /**
   * App JavaScript
   */
  public function appJs()
  {
    $translation = trans('javascript');

    $js = '_lang=[];';
    foreach($translation as $key => $val)
    {
      $js .= '_lang["' . $key . '"]="' . $val . '";';
    }

    $response = \Response::make($js);
    $response->header('Content-Type', 'application/javascript');

    return $response;
  }

  /**
   * Public JavaScript
   */
  public function appJsPublic()
  {
    $translation = trans('javascript-public');

    $js = '_trans=[];';
    foreach($translation as $key => $val)
    {
      $js .= '_trans["' . $key . '"]="' . $val . '";';
    }

    $response = \Response::make($js);
    $response->header('Content-Type', 'application/javascript');

    return $response;
  }
}