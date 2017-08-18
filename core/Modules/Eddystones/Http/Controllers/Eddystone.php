<?php

namespace Modules\Eddystones\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use \Platform\Controllers\Core;

class Eddystone extends Controller
{
    /**
     * List all beacons
     */
    public static function listBeacons($user_id = null)
    {
      // General config
      if ($user_id == null) $user_id = Core\Secure::userId();

      // Define scopes
      $scopes = ['https://www.googleapis.com/auth/userlocation.beacon.registry'];

      // Register client
      $client = new \Google_Client();
      $client->setAuthConfig(storage_path('app/google_keys/' . env('EDDYSTONE_CONFIG_JSON')));
      $client->setScopes($scopes);

      $proximitybeaconService = new \Google_Service_Proximitybeacon($client);

      // https://labs.ribot.co.uk/exploring-google-eddystone-with-the-proximity-beacon-api-bc9256c97e05
      // https://developers.google.com/beacons/proximity/reference/rest/v1beta1/beacons#Status
      $list_beacons = $proximitybeaconService->beacons->listBeacons([
        /*'q' => 'description:"user_"',
        property:"user_id=312" // 
        */
        //'q' => 'status:ACTIVE property:"user_id=' . $user_id . '"',
        'q' => 'property:"user_id=' . $user_id . '"',
        'pageSize' => '1000'
      ]);

      $beacons = $list_beacons->getBeacons();

      $count = (count($beacons) == 0) ? 0 : $list_beacons->getTotalCount(); 

      return [
        'count' => $count,
        'beacons' => $beacons
      ];
    }

    /**
     * Get beacon
     */
    public static function getBeacon($beacon_name)
    {
      // General config
      $user_id = Core\Secure::userId();
      $funnel_id = Core\Secure::funnelId();
      $error = '';

      // Define scopes
      $scopes = ['https://www.googleapis.com/auth/userlocation.beacon.registry'];

      // Register client
      $client = new \Google_Client();
      $client->setAuthConfig(storage_path('app/google_keys/' . env('EDDYSTONE_CONFIG_JSON')));
      $client->setScopes($scopes);

      $proximitybeaconService = new \Google_Service_Proximitybeacon($client);

      try {
        $beacon = $proximitybeaconService->beacons->get($beacon_name);
      } catch (\Exception $e) {
        $response = json_decode($e->getMessage());

        if (isset($response->error->message)) {
          $error = $response->error->message;
        } else {
          $error = 'Unknown error.';
        }
      }

      return [
        'error' => $error,
        'beacon' => $beacon
      ];
    }

    /**
     * Insert new beacon
     */
    public static function addBeacon($name, $namespace_id, $instance_id, $status)
    {
      // General config
      $user_id = Core\Secure::userId();
      $funnel_id = Core\Secure::funnelId();

      if (! ctype_xdigit($namespace_id)) {
        return [
          'error' => 'Namespace ID must be a hexadecimal string.'
        ];
      }

      if (! ctype_xdigit($instance_id)) {
        return [
          'error' => 'Instance ID must be a hexadecimal string.'
        ];
      }

      // Define scopes
      $scopes = ['https://www.googleapis.com/auth/userlocation.beacon.registry'];

      // Register client
      $client = new \Google_Client();
      $client->setAuthConfig(storage_path('app/google_keys/' . env('EDDYSTONE_CONFIG_JSON')));
      $client->setScopes($scopes);

      $proximitybeaconService = new \Google_Service_Proximitybeacon($client);

      $beacon_name = 'beacons/3!' . $namespace_id . $instance_id;
      $beacon_advertise_id = base64_encode(hex2bin($namespace_id . $instance_id));

      $error = '';

      $advertisedId = new \Google_Service_Proximitybeacon_AdvertisedId;

      $advertisedId->setType("EDDYSTONE");
      $advertisedId->setId($beacon_advertise_id);

      $new_beacon = new \Google_Service_Proximitybeacon_Beacon;

      $new_beacon->setAdvertisedId($advertisedId);
      $new_beacon->setDescription($name);
      $new_beacon->setStatus($status);

      //$new_beacon->description = $name;
      //$new_beacon->status = $status;

      // Add properties
      $new_beacon->setProperties([
        'user_id' => (string) $user_id,
        'funnel_id' => (string) $funnel_id
      ]);

      $result = null;

      try {
        $result = $proximitybeaconService->beacons->register($new_beacon);
      } catch (\Exception $e) {
        $response = json_decode($e->getMessage());
        if (isset($response->error->status) && $response->error->status == 'ALREADY_EXISTS') {
          $error = 'Beacon already exists.';
        } elseif (isset($response->error->message)) {
          $error = $response->error->message;
        } else {
          $error = 'Unknown error.';
        }
      }

      return [
        'error' => $error,
        'beacon' => $result
      ];
    }

    /**
     * Update beacon
     */
    public static function update($beacon_name, $name, $status)
    {
      // General config
      $user_id = Core\Secure::userId();
      $funnel_id = Core\Secure::funnelId();

      // Define scopes
      $scopes = ['https://www.googleapis.com/auth/userlocation.beacon.registry'];

      // Register client
      $client = new \Google_Client();
      $client->setAuthConfig(storage_path('app/google_keys/' . env('EDDYSTONE_CONFIG_JSON')));
      $client->setScopes($scopes);

      $proximitybeaconService = new \Google_Service_Proximitybeacon($client);

      $error = '';

      $new_beacon = new \Google_Service_Proximitybeacon_Beacon;

      //$new_beacon->setAdvertisedId($advertisedId);
      $new_beacon->setDescription($name);

      // Update status
      if ($status == 'ACTIVE') {
        $proximitybeaconService->beacons->activate($beacon_name);
      } else {
        $proximitybeaconService->beacons->deactivate($beacon_name);
      }
      $new_beacon->setStatus($status);

      // Add properties
      $new_beacon->setProperties([
        'user_id' => (string) $user_id,
        'funnel_id' => (string) $funnel_id
      ]);

      try {
        $result = $proximitybeaconService->beacons->update($beacon_name, $new_beacon);
      } catch (\Exception $e) {
        $response = json_decode($e->getMessage());
        if (isset($response->error->message)) {
          $error = $response->error->message;
        } else {
          $error = 'Unknown error.';
        }
      }

      return [
        'error' => $error
      ];
    }

    /**
     * Delete beacon
     */
    public static function deleteBeacon($beacon_name)
    {
      // Define scopes
      $scopes = ['https://www.googleapis.com/auth/userlocation.beacon.registry'];

      // Register client
      $client = new \Google_Client();
      $client->setAuthConfig(storage_path('app/google_keys/' . env('EDDYSTONE_CONFIG_JSON')));
      $client->setScopes($scopes);

      $proximitybeaconService = new \Google_Service_Proximitybeacon($client);

      $error = '';

      try {
        $result = $proximitybeaconService->beacons->delete($beacon_name);
      } catch (\Exception $e) {
        $response = json_decode($e->getMessage());

        if (isset($response->error->message)) {
          $error = $response->error->message;
        } else {
          $error = 'Unknown error.';
        }
      }

      return [
        'error' => $error
      ];
    }

    /**
     * Get all attachments (Nearby Notifications) of beacon
     */
    public static function getBeaconAttachments($beacon_name)
    {
      // Define scopes
      $scopes = ['https://www.googleapis.com/auth/userlocation.beacon.registry'];

      // Register client
      $client = new \Google_Client();
      $client->setAuthConfig(storage_path('app/google_keys/' . env('EDDYSTONE_CONFIG_JSON')));
      $client->setScopes($scopes);

      $proximitybeaconService = new \Google_Service_Proximitybeacon($client);

      // Attachments (filter Nearby Notifications with 'namespacedType')
      $attachments = $proximitybeaconService->beacons_attachments->listBeaconsAttachments($beacon_name, [
        'namespacedType' => 'com.google.nearby/*'
      ])->getAttachments();

      $return = [];

      foreach ($attachments as $attachment) {
        $attachment_name = $attachment->getAttachmentName();
        //$created_at = \Carbon\Carbon::parse($attachment->getCreationTimeMs())->timezone(\Auth::user()->timezone)->format('Y-m-d H:i:s');
        $data = json_decode(base64_decode($attachment->getData()));
        $language = $attachment->getNamespacedType();
        $language = explode('/', $language)[1];

        $return[] = [
          'language' => $language,
          'notification' => $data->title,
          'url' => $data->url, 
          'targeting' => [
            'anyOfDaysOfWeek' => (isset($data->targeting->anyOfDaysOfWeek)) ? $data->targeting->anyOfDaysOfWeek : [1,2,3,4,5,6,7]
          ]
        ];
      }
      return $return;
    }

    /**
     * Batch delete attachments
     */
    public static function batchDeleteAttachments($beacon_name)
    {
      // Define scopes
      $scopes = ['https://www.googleapis.com/auth/userlocation.beacon.registry'];

      // Register client
      $client = new \Google_Client();
      $client->setAuthConfig(storage_path('app/google_keys/' . env('EDDYSTONE_CONFIG_JSON')));
      $client->setScopes($scopes);

      $proximitybeaconService = new \Google_Service_Proximitybeacon($client);

      $error = '';

      try {
        $result = $proximitybeaconService->beacons_attachments->batchDelete($beacon_name);
      } catch (\Exception $e) {
        $response = json_decode($e->getMessage());

        if (isset($response->error->message)) {
          $error = $response->error->message;
        } else {
          $error = 'Unknown error.';
        }
      }

      return [
        'error' => $error
      ];
    }

    /**
     * Create attachment
     */
    public static function createAttachment($beacon_name, $language, $title, $url, $days_of_week = [1,2,3,4,5,6,7])
    {
      // Define scopes
      $scopes = ['https://www.googleapis.com/auth/userlocation.beacon.registry'];

      // Register client
      $client = new \Google_Client();
      $client->setAuthConfig(storage_path('app/google_keys/' . env('EDDYSTONE_CONFIG_JSON')));
      $client->setScopes($scopes);

      $proximitybeaconService = new \Google_Service_Proximitybeacon($client);

      $error = '';

      //$startDate = '2017-06-22';
      //$endDate = '2017-07-12';
      //$startTimeOfDay = '14:40';
      //$endTimeOfDay = '14:40';
      //$anyOfDaysOfWeek = [1,2,3,4,5,6,7];

      $data = base64_encode(json_encode([
        'title' => substr($title, 0, 40), 
        'url' => $url, 
        'targeting' => [
          'anyOfDaysOfWeek' => $days_of_week
        ]
      ]));
      /*
      $data = base64_encode(json_encode([
        'title' => $title, 
        'url' => $url, 
        'targeting' => [
          'startDate' => '2017-06-22',
          'endDate' => '2017-07-12',
          'startTimeOfDay' => '14:40',
          'endTimeOfDay' => '17:40',
          'anyOfDaysOfWeek' => [1,2,3,4,5,6,7]
        ]
      ]));*/

      // New attachment
      $attachment = new \Google_Service_Proximitybeacon_BeaconAttachment;
      $attachment->setNamespacedType('com.google.nearby/' . $language);
      $attachment->setData($data);

      // Add attachment to beacon
      try {
        $attachment = $proximitybeaconService->beacons_attachments->create($beacon_name, $attachment);
      } catch (\Exception $e) {
        $response = json_decode($e->getMessage());

        if (isset($response->error->message)) {
          $error = $response->error->message;
        } else {
          $error = 'Unknown error.';
        }
      }

      return [
        'error' => $error
      ];
    }
}
