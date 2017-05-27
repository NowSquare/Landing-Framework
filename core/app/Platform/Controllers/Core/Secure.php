<?php namespace Platform\Controllers\Core;

class Secure extends \App\Http\Controllers\Controller {

  /**
   * Get funnel id - \App\Core\Secure::funnelId()
   */

  public static function funnelId()
  {
    if (auth()->check()) {
      $sl_funnel = session('funnel', '');
      if ($sl_funnel != '') {
        $qs = Secure::string2array($sl_funnel);
        return $qs['funnel_id'];
      } else {
        return 0;
      }
    } else {
      return 0;
    }
  }

  /**
   * Get account holder id - \App\Core\Secure::userId()
   */

  public static function userId()
  {
    if (auth()->check()) {
      return (auth()->user()->parent_id != NULL) ? auth()->user()->parent_id : auth()->user()->id;
    } else {
      return 0;
    }
  }

  /**
   * Get user reseller id - \App\Core\Secure::resellerId()
   */

  public static function resellerId()
  {
    if (auth()->check()) {
      return auth()->user()->reseller_id;
    } else {
      return 0;
    }
  }

  /**
   * Get account holder user - \App\Core\Secure::user()
   */

  public static function user()
  {
    return (auth()->user()->parent_id != NULL) ? \App\User::find(auth()->user()->parent_id) : auth()->user();
  }

  /**
   * Array to encrypted string - $sl = \App\Core\Secure::array2string(array('id' => 1))
   */

  public static function array2string($array)
  {
    $string = http_build_query($array);
    $string =  \Crypt::encrypt($string);
    return rawurlencode($string);
  }

  /**
   * Encrypted string to array - $sl = \App\Core\Secure::string2array($sl)
   */

  public static function string2array($string)
  {
    try {
      $string = rawurldecode($string);
      $string = \Crypt::decrypt($string);
    }
    catch(\Illuminate\Encryption\DecryptException  $e)
    {
      echo 'Decrypt Error';
      die();
    }

    parse_str($string, $array);
    return $array;
  }

  /**
   * Short hash ONLY for numbers, for example user_id to create upload directory. $hash = \App\Core\Secure::staticHash(1)
   */

  public static function staticHash($number, $obfuscate = false)
  {
    $hashids = new \Hashids\Hashids(\Config::get('app.key'));

    if ($obfuscate) {
      $number = $number * intval(config()->get('system.obfuscator_prefix'));
    }

    $string = $hashids->encode($number);
    return $string;
  }

  /**
   * Decode hash. $number = \App\Core\Secure::staticHashDecode($hash)
   */

  public static function staticHashDecode($hash, $obfuscate = false)
  {
    $hashids = new \Hashids\Hashids(\Config::get('app.key'));
    $number = $hashids->decode($hash);
    if (isset($number[0])) {
      $number = $number[0];

      if ($obfuscate) {
        $number = intval($number) / intval(config()->get('system.obfuscator_prefix'));
      }
    } else {
      $number = false;
    }

    return $number;
  }
}