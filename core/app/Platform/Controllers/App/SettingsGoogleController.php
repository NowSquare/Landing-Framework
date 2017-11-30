<?php namespace Platform\Controllers\App;

use \Platform\Controllers\Core;
use Illuminate\Http\Request;

class SettingsGoogleController extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | Google settings Controller
   |--------------------------------------------------------------------------
   |
   | Module related logic
   |--------------------------------------------------------------------------
   */

  /**
   * Show settings
   */
  public function showSettings() {
    return view('platform.admin.settings.google');
  }

  /**
   * Post settings
   */
  public function postSettings() {
    if (config('app.demo')) {
      return response()->json([
        'type' => 'error',
        'reset' => false, 
        'msg' => "This is disabled in the demo"
      ]);
    }

    $input = array(
      'file' => request()->file('file'),
      'extension'  => strtolower(request()->file('file')->getClientOriginalExtension())
    );

    $rules = array(
      'file' => 'required|file',
      'extension'  => 'required|in:json'
    );

    $validator = \Validator::make($input, $rules);

    if($validator->fails()) {
      return response()->json([
        'type' => 'error',
        'reset' => false, 
        'msg' => $validator->messages()->first()
      ]);
    } else {
      $path = \Storage::putFileAs('google_keys', request()->file('file'), Core\Reseller::get()->id . '.json');
    }

    return response()->json([
      'type' => 'success', 
      'msg' => trans('global.upload_success')
    ]);
  }

}