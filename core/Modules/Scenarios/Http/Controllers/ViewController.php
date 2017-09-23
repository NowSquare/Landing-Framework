<?php 
namespace Modules\Scenarios\Http\Controllers;

use \Platform\Controllers\Core;
use Illuminate\Http\Request;
use Modules\Scenarios\Http\Models;

class ViewController extends \App\Http\Controllers\Controller
{
  /*
  |--------------------------------------------------------------------------
  | Remote Controller
  |--------------------------------------------------------------------------
  |
  | Remote Controller
  |--------------------------------------------------------------------------
  */

  /**
   * Show template
   */

  public function showTemplate($hash)
  {
    if($hash != '') {
      $scenario_id =Core\Secure::staticHashDecode($hash, true);
      $scenario = Models\Scenario::where('id', $scenario_id)->first();

      if (! empty($scenario)) {
        return view('scenarios::view-template', [
          'template' => $scenario->template
        ]);
      }
    }
  }

  /**
   * Show image
   */

  public function showImage($hash)
  {
    if($hash != '') {
      $scenario_id =Core\Secure::staticHashDecode($hash, true);
      $scenario = Models\Scenario::where('id', $scenario_id)->first();

      if (! empty($scenario)) {
        $show_image = ($scenario->show_image != NULL) ? url($scenario->show_image) : NULL;

        return view('scenarios::view-image', [
          'image' => $scenario->show_image
        ]);
      }
    }
  }
}