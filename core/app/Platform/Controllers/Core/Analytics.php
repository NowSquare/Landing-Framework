<?php namespace Platform\Controllers\Core;

use \Platform\Controllers\Core;
use Illuminate\Http\Request;

class Analytics extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | Analytics Controller
   |--------------------------------------------------------------------------
   |
   | General  Analytics related logic
   |--------------------------------------------------------------------------
   */

  /**
   * Get date range
   * \Platform\Controllers\Core\Analytics::getRange($date_start, $date_end);
   */
  public static function getRange($strDateFrom, $strDateTo) {
    $aryRange = array();

    $iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
    $iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));

    if ($iDateTo >= $iDateFrom) {
      $d = ['y' => (int) date('Y', $iDateFrom), 'm' => (int) date('n', $iDateFrom), 'd' => (int) date('j', $iDateFrom)];
      $aryRange[date('Y-m-d', $iDateFrom)] = $d; // first entry
      while ($iDateFrom < $iDateTo) {
        $iDateFrom +=86400; // add 24 hours
        $d = ['y' => (int) date('Y', $iDateFrom), 'm' => (int) date('n', $iDateFrom), 'd' => (int) date('j', $iDateFrom)];
        $aryRange[date('Y-m-d', $iDateFrom)] = $d;
        //array_push($aryRange, $d);
      }
    }
    return $aryRange;
  }
}