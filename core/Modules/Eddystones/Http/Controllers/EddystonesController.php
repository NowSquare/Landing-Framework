<?php

namespace Modules\Eddystones\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use \Platform\Controllers\Core;

class EddystonesController extends Controller
{
    /**
     * Eddystones backend main
     */
    public function showEddystones()
    {
      $eddystones = Eddystone::listBeacons();

      if ($eddystones['count'] == 0) {
        return $this->showCreateEddystone(true);
      } else {
        return view('eddystones::overview', compact('eddystones'));
      }
    }

    /**
     * Create new Eddystone
     */
    public function showCreateEddystone($first = false)
    {
      return view('eddystones::create', compact('first'));
    }

    /**
     * Edit Eddystone
     */
    public function showEditEddystone(Request $request)
    {
      $sl = $request->input('sl', '');

      if($sl != '') {
        $qs = Core\Secure::string2array($sl);

        $eddystone = Eddystone::getBeacon($qs['beaconName']);
        $attachments = Eddystone::getBeaconAttachments($qs['beaconName']);

        $browser_language = new \Sinergi\BrowserDetector\Language();
        $browser_language = substr($browser_language->getLanguage(), 0, 2);

        $languages = \Platform\Controllers\Core\Localization::getAllLanguages();

        $sites = \Modules\LandingPages\Http\Models\Site::where('user_id', Core\Secure::userId())->orderBy('funnel_id', 'asc')->orderBy('name', 'asc')->get();
        $forms = \Modules\Forms\Http\Models\Form::where('user_id', Core\Secure::userId())->orderBy('funnel_id', 'asc')->orderBy('name', 'asc')->get();

        return view('eddystones::edit', compact('sl', 'eddystone', 'attachments', 'languages', 'browser_language', 'sites', 'forms'));
      }
    }

    /**
     * Update post Eddystone
     */
    public function postEditEddystone(Request $request)
    {
      $sl = $request->input('sl', '');

      $name = $request->input('name', '');
      $active = (boolean) $request->input('active', false);

      if($sl != '') {
        $qs = Core\Secure::string2array($sl);

        $languages = $request->input('language', []);
        $notifications = $request->input('notification', []);
        $urls = $request->input('url', []);

        // Delete all attachments
        $result = Eddystone::batchDeleteAttachments($qs['beaconName']);

        // Add attachments
        $i = 0;
        foreach ($notifications as $notification) {
          $language = $languages[$i];
          $notification = $notifications[$i];
          $url = $urls[$i];

          if ($language != '' && $notification != '' && $url != '') {
            $result = Eddystone::createAttachment($qs['beaconName'], $language, $notification, $url);
          }
          $i++;
        }

        $status = ($active) ? 'ACTIVE' : 'INACTIVE';
        $result = Eddystone::update($qs['beaconName'], $name, $status);

        if (isset($result['error']) && $result['error'] != '') {
          return response()->json(['type' => 'error', 'reset' => false, 'msg' => $result['error']]);
        } else {
          return response()->json(['success' => true, 'redir' => '#/eddystones']);
        }
      }
    }

    /**
     * Post new Eddystone
     */
    public function postCreateEddystone(Request $request)
    {
      $name = $request->input('name', '');
      $namespace_id = $request->input('namespace_id', '');
      $instance_id = $request->input('instance_id', '');
      $active = (boolean) $request->input('active', false);

      $input = array(
        'name' => $name,
        'namespace_id' => $namespace_id,
        'instance_id' => $instance_id
      );

      $rules = array(
        'name' => 'required|max:127',
        'namespace_id' => 'required|size:20',
        'instance_id' => 'required|size:12'
      );

      $validator = \Validator::make($input, $rules);

      if($validator->fails()) {
        $response = array(
          'type' => 'error', 
          'reset' => false, 
          'msg' => $validator->messages()->first()
        );
        return response()->json($response);
      }

      $status = ($active) ? 'ACTIVE' : 'INACTIVE';

      $result = Eddystone::addBeacon($name, $namespace_id, $instance_id, $status);

      if (isset($result['error']) && $result['error'] != '') {
        return response()->json(['type' => 'error', 'reset' => false, 'msg' => $result['error']]);
      } else {
        $sl = \Platform\Controllers\Core\Secure::array2string(['beaconName' => $result['beacon']->getBeaconName()]);
        return response()->json(['success' => true, 'redir' => '#/eddystones/' . $sl]);
      }
    }

    /**
     * Delete Eddystone
     */
    public function postDeleteEddystone(Request $request) {
      
      $sl = $request->input('sl', '');

      if($sl != '') {
        $qs = Core\Secure::string2array($sl);

        $result = Eddystone::deleteBeacon($qs['beaconName']);

        if (isset($result['error']) && $result['error'] != '') {
          return response()->json(['type' => 'error', 'reset' => false, 'msg' => $result['error']]);
        } else {
          return response()->json(['result' => 'success']);
        }
      }
    }
}