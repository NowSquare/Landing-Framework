<?php namespace Platform\Controllers\App;

use \Platform\Controllers\Core;

class AvangateController extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | Avangate Controller
   |--------------------------------------------------------------------------
   |
   | Avangate related logic
   |
   |--------------------------------------------------------------------------
   */

  /**
   * LCN (License Change Notification) Get
   * https://example.com/api/v1/avangate/lcn
   */

  public function getLcn()
  {
    // Array with product code (MWP001, etc.)
    $IPN_PCODE = request()->input('IPN_PCODE', []);

    $request = request()->all();
    $html = '';

    foreach ($request as $key => $val)
    {
      if (is_array($val)) $val = implode(', ', $val);
      $html .= $key . ': ' . $val . chr(13);
    }

    \Mail::raw($html, function ($message){
      $message->to(config('avangate.debug_mail'))->subject('[GET] Avangate LCN GET log');
    });
  }

  /**
   * LCN (License Change Notification) Posts
   * https://example.com/api/v1/avangate/lcn
   */

  public function postLcn()
  {
    // Avangate hash validation, get all POST parameters except HASH
    $request = request()->except('HASH');
    $remote_hash = request()->input('HASH', '');

    $date = date('YmdGis');
    $secret_key = \Config::get('avangate.key');

    $hmac_string = $this->array_to_string($request);
    $hash = $this->hmac($secret_key, $hmac_string);

    if ($remote_hash == $hash)
    {
      // It's a valid request
      $EXPIRATION_DATE = request()->input('EXPIRATION_DATE', '');
      $DISABLED = request()->input('DISABLED', '');
      $EXPIRED = request()->input('EXPIRED', '');
      $EMAIL = request()->input('EMAIL', '');
      $LICENSE_PRODUCT = request()->input('LICENSE_PRODUCT', '');
      $LICENSE_CODE = request()->input('LICENSE_CODE', '');
      $COUNTRY = request()->input('COUNTRY', '');
      $CITY = request()->input('CITY', '');

      $user_id = request()->input('EXTERNAL_CUSTOMER_REFERENCE', '');
      $remote_id = request()->input('AVANGATE_CUSTOMER_REFERENCE', '');

      $user_email = '-';
      if ($user_id != '' && $EXPIRED == 0)
      {
        $user = \App\User::where('id', $user_id)->first();
        if (! empty($user)) {
          $user_email = $user->email;
          $user->remote_id = $remote_id;
          $user->expires = $EXPIRATION_DATE;
          $plan = \App\Plan::where('remote_product_id1', $LICENSE_PRODUCT)->orWhere('remote_product_id2', $LICENSE_PRODUCT)->first();
          
          if (! empty($plan)) {
            $user->plan_id = $plan->id;
          }

          $user->save();
        }
      }

      $your_signature = $this->hmac($secret_key, $this->array_to_string([$LICENSE_CODE, $EXPIRATION_DATE, $date]));

      echo '<EPAYMENT>' . $date . '|' . $your_signature . '</EPAYMENT>';

      /**
       * Debug
       */

      if (config('avangate.debug_mail', '') != '') {
        $html = '';
        $html .= 'user email: ' . $user_email . chr(13);
  
        foreach ($request as $key => $val)
        {
          if (is_array($val)) $val = implode(', ', $val);
          $html .= $key . ': ' . $val . chr(13);
        }

        \Mail::raw($html, function ($message){
          $message->to(config('avangate.debug_mail'))->subject('[POST] Valid Avangate LCN log');
        });
      }
    }
  }

  /**
   * IPN Get
   * https://example.com/api/v1/avangate/ipn
   */

  public function getIpn()
  {
    // Array with product code (MWP001, etc.)
    $IPN_PCODE = request()->input('IPN_PCODE', []);

    $request = request()->input();
    
    /**
     * Debug
     */

    if (config('avangate.debug_mail', '') != '') {
      $html = '';

      foreach ($request as $key => $val)
      {
        if (is_array($val)) $val = implode(', ', $val);
        $html .= $key . ': ' . $val . chr(13);
      }

      \Mail::raw($html, function ($message){
        $message->to(config('avangate.debug_mail'))->subject('[GET] Avangate IPN log');
      });
    }
  }

  /**
   * IPN Posts
   * https://example.com/api/v1/avangate/ipn
   */

  public function postIpn()
  {
    // Avangate hash validation, get all POST parameters except HASH
    $request = request()->except('HASH');
    $remote_hash = request()->input('HASH', '');

    $date = date('YmdGis');
    $secret_key = \Config::get('avangate.key');

    $hmac_string = $this->array_to_string($request);
    $hash = $this->hmac($secret_key, $hmac_string);

    if ($remote_hash == $hash)
    {
      // It's a valid request
      $IPN_PID = request()->input('IPN_PID', '');
      $IPN_PNAME = request()->input('IPN_PNAME', '');
      $IPN_DATE = request()->input('IPN_DATE', '');
      $LICENSE_PRODUCT = request()->input('IPN_PID', '');

      $your_signature = $this->hmac($secret_key, $this->array_to_string([$IPN_PID, $IPN_PNAME, $IPN_DATE, $date]));

      echo '<EPAYMENT>' . $date . '|' . $your_signature . '</EPAYMENT>';

      /**
       * Debug
       */

      if (config('avangate.debug_mail', '') != '') {
        $html = '';

        foreach ($request as $key => $val)
        {
          if (is_array($val)) $val = 'ARRAY: ' . implode(', ', $val);
          $html .= $key . ': ' . $val . chr(13);
        }

        \Mail::raw($html, function ($message){
          $message->to(config('avangate.debug_mail'))->subject('[POST] Avangate IPN log');
        });
      }
    }
  }

  public static function hmac($key, $data){
    $b = 64; // byte length for md5
    if (strlen($key) > $b) {
     $key = pack('H*',md5($key));
    }
    $key  = str_pad($key, $b, chr(0x00));
    $ipad = str_pad('', $b, chr(0x36));
    $opad = str_pad('', $b, chr(0x5c));
    $k_ipad = $key ^ $ipad ;
    $k_opad = $key ^ $opad;
    return md5($k_opad  . pack('H*',md5($k_ipad . $data)));
  }

  public static function array_to_string($data){
    $return = '';
    
    if(!is_array($data)){
      $return	.= strlen($data).$data;
    }
    else{
      foreach($data as $val){
        if(!is_array($val)){
          $return	.= strlen($val).$val;
        } else {
          foreach($val as $val2){
            $return	.= strlen($val2).$val2;
          }
        }
      }		
    }
    return $return;
  }
}