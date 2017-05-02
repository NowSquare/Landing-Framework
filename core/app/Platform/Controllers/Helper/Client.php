<?php namespace Platform\Controllers\Helper;

class Client extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | Client Controller
   |--------------------------------------------------------------------------
   |
   | Client helper functions
   |--------------------------------------------------------------------------
   */

  /**
   * \Platform\Controllers\Controllers\Helper\Client::ip();
   */

  public static function ip() {
    $headers = array ('HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'HTTP_VIA', 'HTTP_X_COMING_FROM', 'HTTP_COMING_FROM', 'HTTP_CLIENT_IP' );
 
    foreach ( $headers as $header ) {
      if (isset ( $_SERVER [$header]  )) {
      
        if (($pos = strpos ( $_SERVER [$header], ',' )) != false) {
          $ip = substr ( $_SERVER [$header], 0, $pos );
        } else {
          $ip = $_SERVER [$header];
        }
        $ipnum = ip2long ( $ip );
        if ($ipnum !== - 1 && $ipnum !== false && (long2ip ( $ipnum ) === $ip)) {
          if (($ipnum - 184549375) && // Not in 10.0.0.0/8
          ($ipnum  - 1407188993) && // Not in 172.16.0.0/12
          ($ipnum  - 1062666241)) // Not in 192.168.0.0/16
          if (($pos = strpos ( $_SERVER [$header], ',' )) != false) {
            $ip = substr ( $_SERVER [$header], 0, $pos );
          } else {
            $ip = $_SERVER [$header];
          }
          return $ip;
        }
      }
      
    }
    return (isset($_SERVER ['REMOTE_ADDR'])) ? $_SERVER ['REMOTE_ADDR'] : '127.0.0.1';
  }
}