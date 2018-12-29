<?php

namespace Modules\Modals\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use \Platform\Controllers\Core;
use Modules\Modals\Http\Models;

class ModalController extends Controller
{
  /**
   * View modal
   */

  public function getModal($template = 'default', $id = null) {
    $locale = request()->get('locale', 'en');
    app()->setLocale($locale);
    return view('modals::modals.' . $template, compact('id'));
  }

  /**
   * Get modal JSON settings
   */

  public function getModalSettings() {
    $token = request()->get('token', null);
    $lang = request()->get('lang', null);
    $host = request()->get('host', null);
    $path = request()->get('path', null);

    $id = Core\Secure::staticHashDecode($token);

    if (is_numeric($id)) {
      $modal = Models\Modal::where('id', $id)->first();
      if ($modal !== null && $modal->active) {
        $url = $modal->settings['modalUrl'] ?? null;

        if ($url !== null) {
          $timeout = 10;
          $ch = curl_init();
          curl_setopt ( $ch, CURLOPT_URL, $url );
          curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
          curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
          $http_respond = curl_exec($ch);
          $http_respond = trim( strip_tags( $http_respond ) );
          $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
          curl_close($ch);

          if($http_code == 200 || $http_code == 302) {
            $settings = $modal->settings;
            return response()->json($settings);
          }
        }
      }
    }
  }
}
