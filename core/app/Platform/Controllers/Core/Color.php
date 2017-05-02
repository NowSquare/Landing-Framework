<?php
namespace Platform\Controllers\Core;

class Color extends \App\Http\Controllers\Controller {
  
  /*
  |--------------------------------------------------------------------------
  | Color Controller
  |--------------------------------------------------------------------------
  |
  | Color related logic
  |--------------------------------------------------------------------------
  */

  /**
   * \Platform\Controllers\Core\Color::hex2rgb('#ff0000');
   * Returns rgb array
   */
  public static function hex2rgb($hex) {
     $hex = str_replace("#", "", $hex);
  
     if(strlen($hex) == 3) {
        $r = hexdec(substr($hex,0,1).substr($hex,0,1));
        $g = hexdec(substr($hex,1,1).substr($hex,1,1));
        $b = hexdec(substr($hex,2,1).substr($hex,2,1));
     } else {
        $r = hexdec(substr($hex,0,2));
        $g = hexdec(substr($hex,2,2));
        $b = hexdec(substr($hex,4,2));
     }
     $rgb = array($r, $g, $b);
     //return implode(",", $rgb); // returns the rgb values separated by commas
     return $rgb; // returns an array with the rgb values
  }
}