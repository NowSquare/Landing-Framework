<?php namespace Platform\Controllers\Location;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

use \Platform\Controllers\Core;
use \Platform\Models\Location;
use \Platform\Models\Campaigns;
use \Platform\Models\Analytics;
use Illuminate\Http\Request;

class ApiCardController extends \App\Http\Controllers\Controller
{
  /*
  |--------------------------------------------------------------------------
  | Card Api Controller
  |--------------------------------------------------------------------------
  |
  | Card Api related logic
  |--------------------------------------------------------------------------
  */

  /**
   * Get cards list ordered on distance
   */

  public function getCards() {
    $preview = (boolean) request()->input('preview', false);
    $token = request()->input('token', NULL);
    $lat = request()->input('lat', env('GMAPS_DEFAULT_LAT'));
    $lng = request()->input('lng', env('GMAPS_DEFAULT_LNG'));
    $accuracy = request()->input('acc', 0);
    if ($accuracy > 1000) $accuracy = 1000;
    $timezone = request()->input('tz', 'UTC');
    $distance = request()->input('d', 1000 * 200); // Meters
    $categories = request()->input('c', ''); // Comma seperated category ids
    $categories = ($categories == '') ? [] : explode(',', $categories);
    $page = request()->input('p', 1);
    $length = request()->input('pl', 10);
    if ($length > 500) $length = 500;
    $start = ($page - 1) * $length;

    // Increase distance for demo or preview
    if(config('app.demo') || $preview) $distance = 1000 * 30000;
      
    //\DB::enableQueryLog();
    //dd(\DB::getQueryLog()); 

    if ($lat == 'undefined' || $lng == 'undefined') {
      return response()->json([[
        'sl' => '',
        'distance' => 0,
        'name' => 'Error processing request',
        'icon' => null,
        'description' => 'Pull list to refresh.',
        'location' => null
      ]]);
    }

    $apps = Campaigns\App::where('api_token', $token)->where('api_token', '<>', '')->where('active', 1)->whereNotNull('api_token')->get();

    if (empty($apps)) {
      return response()->json(['error' => 'Token not recognized']);
    }

    $found_cards = [];

    foreach ($apps as $app) {
      $campaigns = \DB::select('SELECT ac.campaign_id FROM app_campaigns ac LEFT JOIN campaigns c ON c.id = ac.campaign_id WHERE c.active = 1 AND ac.app_id = ' . $app->id . '');

      foreach($campaigns as $campaign) {

        $campaign = Campaigns\Campaign::where('id', $campaign->campaign_id)->first();

        $query = $campaign
          ->cards()
          ->where('active', 1)
          ->select(['cards.id', 'cards.name', 'cards.icon', 'cards.description'])
          ->distance($distance, $lat . ',' . $lng)
          ->orderBy('distance', 'asc')
          ->take($length * $page)
          ->skip(0); // $start

        // Filter categories
        if (! empty($categories)) {
          $query->whereHas('categories', function ($query) use ($categories) {
            $query->whereIn('category_id', $categories);
          });
        }

        $cards = $query->get();

        foreach($cards as $card) {
          $found_cards[] = [
            'sl' => Core\Secure::array2string(array('card_id' => $card->id)),
            'distance' => $card->distance,
            'name' => $card->name,
            'icon' => $card->icon,
            'description' => $card->description,
            'location' => $card->location
          ];
        }
      }
    }

    // Sort distance over all cards
    $found_cards = array_values(array_sort($found_cards, function ($value) {
      return $value['distance'];
    }));

    // Pagination (afterwards)
    $found_cards = array_slice($found_cards, $start, $length);

    return response()->json($found_cards);
  }

  /**
   * Get cards list ordered on distance
   */

  public function getCard() {
    $sl = request()->input('sl', NULL);
    $lat = request()->input('lat', env('GMAPS_DEFAULT_LAT'));
    $lng = request()->input('lng', env('GMAPS_DEFAULT_LNG'));
    $timezone = request()->input('tz', 'UTC');
    $device_uuid = request()->input('uuid', NULL);
    $model = request()->input('model', NULL);
    $platform = request()->input('platform', NULL);

    //\DB::enableQueryLog();
    //dd(\DB::getQueryLog()); 

    if ($sl != NULL) {
      $qs = Core\Secure::string2array($sl);
      $card = Location\Card::where('id', $qs['card_id'])->where('active', 1)->first();

      if (! empty($card)) {
        // Increment views
        $card->increment('views');

        if ($lat != 'undefined' && $lng != 'undefined') {
          // Add stat
          $stat = new Analytics\CardStat;

          $stat->card_id = $card->id;
          $stat->user_id = $card->user_id;
          $stat->ip = request()->ip();
          $stat->device_uuid = $device_uuid;
          $stat->model = $model;
          $stat->platform = $platform;
          $stat->lat = $lat;
          $stat->lng = $lng;

          $stat->save();
        }

        return response()->json([
          'id' => $card->id,
          'name' => $card->name,
          'icon' => $card->icon,
          'description' => $card->description,
          'content' => $card->content,
          'image' => $card->image,
          'lat' => $card->lat,
          'lng' => $card->lng,
          'zoom' => $card->zoom
        ]);
      }
    }
  }
}