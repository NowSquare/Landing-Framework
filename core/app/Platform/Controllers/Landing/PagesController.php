<?php
namespace Platform\Controllers\Landing;

use \Platform\Controllers\Core;
use \Platform\Models\Location;
use \Platform\Models\Campaigns;
use Illuminate\Http\Request;

class PagesController extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | Landing Pages controller
   |--------------------------------------------------------------------------
   |
   | Landing Pages logic
   |
   */

  /**
   * Show editor
   */
  public function showEditor()
  {
    return view('platform.landing.editor');
  }

}