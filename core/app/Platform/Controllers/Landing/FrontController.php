<?php
namespace Platform\Controllers\Landing;

use \Platform\Controllers\Core;
use \Platform\Models\Location;
use \Platform\Models\Campaigns;
use Illuminate\Http\Request;

class FrontController extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | Landing Pages front-end controller
   |--------------------------------------------------------------------------
   |
   | Landing Pages front-end logic
   |
   */

  /**
   * Show page
   */
  public function showPage()
  {
    return view('landing-pages.front');
  }

}