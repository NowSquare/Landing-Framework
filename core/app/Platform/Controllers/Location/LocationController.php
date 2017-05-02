<?php
namespace Platform\Controllers\Location;

use \Platform\Controllers\Core;
use \Platform\Models\Location;
use Illuminate\Http\Request;

class LocationController extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | Location controller
   |--------------------------------------------------------------------------
   |
   | Location related logic
   |
   */

  /**
   * Add location group
   */
  public function postLocationGroup()
  {
    $location_group = new Location\LocationGroup;

    $location_group->user_id = Core\Secure::userId();
    $location_group->name = request()->input('inputValue', NULL);

    $location_group->save();

    return response()->json(array('id' => $location_group->id));
  }
}