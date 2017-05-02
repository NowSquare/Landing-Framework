<?php
namespace Platform\Controllers\Location;

use \Platform\Controllers\Core;
use \Platform\Models\Location;
use \Platform\Models\Campaigns;
use \Platform\Models\Categories;
use Illuminate\Http\Request;

class CardController extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | Card controller
   |--------------------------------------------------------------------------
   |
   | Card related logic
   |
   */

  /**
   * Show cards
   */
  public function showCards()
  {
    $cards = Location\Card::where('user_id', '=', Core\Secure::userId())->orderBy('name', 'asc');

    return view('platform.cards.cards', array(
      'cards' => $cards
    ));
  }

  /**
   * New card
   */
  public function showNewCard()
  {
    // Get all categories, campaigns, gefences, beacons and groups / locations
    $categories = Categories\Category::where('reseller_id', '=', Core\Secure::resellerId())->orderBy('order', 'asc')->get();
    $campaigns = Campaigns\Campaign::where('user_id', '=', Core\Secure::userId())->orderBy('name', 'asc')->get();
    $geofences = Location\Geofence::where('user_id', '=', Core\Secure::userId())->where('location_group_id', NULL)->orderBy('name', 'asc')->get();
    $beacons = Location\Beacon::where('user_id', '=', Core\Secure::userId())->where('location_group_id', NULL)->orderBy('name', 'asc')->get();
    $location_groups = Location\LocationGroup::where('user_id', '=', Core\Secure::userId())->orderBy('name', 'asc')->get();

    return view('platform.cards.card-new', compact('categories', 'campaigns', 'geofences', 'beacons', 'location_groups'));
  }

  /**
   * Update card
   */
  public function showEditCard()
  {
    $sl = request()->input('sl', '');

    if($sl != '') {
      $qs = Core\Secure::string2array($sl);
      $card = Location\Card::where('id', $qs['card_id'])->where('user_id', '=', Core\Secure::userId())->first();

      // Get linked objects
      $selected_categories = $card->categories->pluck('id')->toArray();
      $selected_campaigns = $card->campaigns->pluck('campaign_id')->toArray();
      $selected_beacons = $card->beacons->pluck('beacon_id')->toArray();
      $selected_geofences = $card->geofences->pluck('geofence_id')->toArray();

      // Get all categories, campaigns, gefences, beacons and groups / locations
      $categories = Categories\Category::where('reseller_id', '=', Core\Secure::resellerId())->orderBy('order', 'asc')->get();
      $campaigns = Campaigns\Campaign::where('user_id', '=', Core\Secure::userId())->orderBy('name', 'asc')->get();
      $geofences = Location\Geofence::where('user_id', '=', Core\Secure::userId())->where('location_group_id', NULL)->orderBy('name', 'asc')->get();
      $beacons = Location\Beacon::where('user_id', '=', Core\Secure::userId())->where('location_group_id', NULL)->orderBy('name', 'asc')->get();
      $location_groups = Location\LocationGroup::where('user_id', '=', Core\Secure::userId())->orderBy('name', 'asc')->get();

      return view('platform.cards.card-edit', compact('sl', 'card', 'categories', 'selected_categories', 'campaigns', 'selected_campaigns', 'beacons', 'selected_beacons', 'geofences', 'selected_geofences', 'location_groups'));
    }
  }

  /**
   * Add / update card
   */
  public function postCard()
  {
    $sl = request()->input('sl', NULL);
    $name = request()->input('name');
    $description = request()->input('description', NULL);
    $content = request()->input('content', NULL);
    $icon = request()->input('icon', NULL);
    $image = request()->input('image', NULL);
    $lat = request()->input('lat', NULL);
    $lng = request()->input('lng', NULL);
    $zoom = request()->input('zoom', NULL);
    $active = (boolean) request()->input('active', false);
    $campaigns = request()->input('campaigns', '');
    if ($campaigns == '') $campaigns = [];
    $categories = request()->input('categories', '');
    if ($categories == '') $categories = [];
    $places = request()->input('places', '');

    if($sl != NULL)
    {
      $qs = Core\Secure::string2array($sl);
      $card = Location\Card::where('id', $qs['card_id'])->where('user_id', '=', Core\Secure::userId())->first();
    }
    else
    {
      // Verify limit
      $card_count = Location\Card::where('user_id', '=', Core\Secure::userId())->count();
      $card_count_limit = \Auth::user()->plan->limitations['mobile']['cards'];

      if ($card_count >= $card_count_limit) {
        return response()->json([
          'type' => 'error', 
          'msg' => trans('global.account_limit_reached'),
          'reset' => false
        ]);
      }

      $card = new Location\Card;
    }

    $card->user_id = Core\Secure::userId();
    $card->name = $name;
    $card->description = $description;
    $card->content = $content;
    $card->icon = $icon;
    $card->image = $image;
    $card->lat = $lat;
    $card->lng = $lng;
    $card->zoom = $zoom;
    $card->active = $active;
    $card->setLocationAttribute($lng . ',' . $lat);

    if($card->save())
    {
      $geofences = array();
      $beacons = array();

      if ($places != '') {
        foreach($places as $place) {
          if (starts_with($place, 'geofence')) {
            $id = str_replace('geofence', '', $place);
            array_push($geofences, $id); 
          }
  
          if (starts_with($place, 'beacon')) {
            $id = str_replace('beacon', '', $place);
            array_push($beacons, $id); 
          }
        }
      }

      $card->geofences()->sync($geofences);
      $card->beacons()->sync($beacons);

      $card->campaigns()->sync($campaigns);
      $card->categories()->sync($categories);

      $response = array(
        'redir' => '#/cards'
      );
    }
    else
    {
      $response = array(
        'type' => 'error', 
        'msg' => $card->errors()->first(),
        'reset' => false
      );
    }

    return response()->json($response);
  }

  /**
   * Export
   */

  public function getExport()
  {
    $type = request()->input('type', 'xls');
    if (! in_array($type, ['xls', 'xlsx', 'csv'])) $type = 'xls';
    $filename = Core\Reseller::get()->name . '-' . str_slug(trans('global.cards')) . '-' . date('Y-m-d h:i:s');
    $cards = Location\Card::where('cards.user_id', Core\Secure::userId())
      ->select(\DB::raw("
        cards.name as '" . trans('global.name') . "', 
        cards.description as '" . trans('global.description') . "', 
        cards.content as '" . trans('global.content') . "', 
        lat as '" . trans('global.latitude') . "', 
        lng as '" . trans('global.longitude') . "', 
        zoom as '" . trans('global.zoom') . "', 
        active as '" . trans('global.active') . "', 
        cards.created_at as '" . trans('global.created') . "', 
        cards.updated_at as '" . trans('global.updated') . "'"))->get();

    \Excel::create($filename, function($excel) use($cards) {
      $excel->sheet(trans('global.cards'), function($sheet) use($cards) {
        $sheet->fromArray($cards);
      });
    })->download($type);
  }

  /**
   * Delete card(s)
   */
  public function postDelete()
  {
    $sl = request()->input('sl', '');

    if(\Auth::check() && $sl != '')
    {
      $qs = Core\Secure::string2array($sl);
      $card = Location\Card::where('id', '=',  $qs['card_id'])->where('user_id', '=',  Core\Secure::userId())->delete();
    }
    elseif (\Auth::check())
    {
      foreach(request()->input('ids', array()) as $id)
      {
        $affected = Location\Card::where('id', '=', $id)->where('user_id', '=',  Core\Secure::userId())->delete();
      }
    }

    return response()->json(array('result' => 'success'));
  }

  /**
   * Switch card(s)
   */
  public function postSwitch()
  {
    if(\Auth::check())
    {
      foreach(request()->input('ids', array()) as $id)
      {
        $current = Location\Card::where('id', '=', $id)->first();
        $switch = ($current->active == 1) ? 0 : 1;
        $affected = Location\Card::where('id', '=', $id)->where('user_id', '=',  Core\Secure::userId())->update(array('active' => $switch));
      }
    }

    return response()->json(array('result' => 'success'));
  }

  /**
   * Get card list data
   */
  public function getCardData(Request $request)
  {
    $order_by = $request->input('order.0.column', 0);
    $order = $request->input('order.0.dir', 'asc');
    $search = $request->input('search.regex', '');
    $q = $request->input('search.value', '');
    $start = $request->input('start', 0);
    $draw = $request->input('draw', 1);
    $length = $request->input('length', 10);
    $data = array();    
    
    $aColumn = array('cards.name', '', 'cards.lng', 'cards.created_at', 'cards.active');

    if($q != '')
    {
      $count = Location\Card::orderBy($aColumn[$order_by], $order)
        ->where(function ($query) {
          $query->where('cards.user_id', '=', Core\Secure::userId());
        })
        ->where(function ($query) use($q) {
          $query->orWhere('cards.name', 'like', '%' . $q . '%');
          $query->orWhere('cards.title', 'like', '%' . $q . '%');
          $query->orWhere('cards.description', 'like', '%' . $q . '%');
          $query->orWhere('cards.radius', 'like', '%' . $q . '%');
          $query->orWhere('cards.lng', 'like', '%' . $q . '%');
          $query->orWhere('cards.lat', 'like', '%' . $q . '%');
        })
        ->count();

      $oData = Location\Card::orderBy($aColumn[$order_by], $order)
        ->where(function ($query) {
          $query->where('cards.user_id', '=', Core\Secure::userId());
        })
        ->where(function ($query) use($q) {
          $query->orWhere('cards.name', 'like', '%' . $q . '%');
          $query->orWhere('cards.title', 'like', '%' . $q . '%');
          $query->orWhere('cards.description', 'like', '%' . $q . '%');
          $query->orWhere('cards.radius', 'like', '%' . $q . '%');
          $query->orWhere('cards.lng', 'like', '%' . $q . '%');
          $query->orWhere('cards.lat', 'like', '%' . $q . '%');
        })
        ->take($length)->skip($start)->get();
    }
    else
    {
      $count = Location\Card::where('cards.user_id', '=', Core\Secure::userId())->count();

      $oData = Location\Card::where('cards.user_id', '=', Core\Secure::userId())
        ->orderBy($aColumn[$order_by], $order)
        ->take($length)
        ->skip($start)
        ->get();
    }

    if($length == -1) $length = $count;

    $recordsTotal = $count;
    $recordsFiltered = $count;

    foreach($oData as $row)
    {
      $campaigns_array = $row->campaigns->pluck('name', 'campaign_id')->toArray();
      $campaigns = [];
      foreach ($campaigns_array as $key => $val) {
        $campaigns[] = ['sl' => Core\Secure::array2string(array('campaign_id' => $key)), 'name' => $val];
      }

      $data[] = array(
        'DT_RowId' => 'row_' . $row->id,
        'campaigns' => $campaigns,
        'name' => $row->name,
        /*'description' => $row->description,*/
        'lng' => $row->lng,
        'lat' => $row->lat,
        'zoom' => $row->zoom,
        'active' => $row->active,
        'created_at' => $row->created_at->timezone(\Auth::user()->timezone)->format('Y-m-d H:i:s'),
        'sl' => Core\Secure::array2string(array('card_id' => $row->id))
      );
    }

    $response = array(
      'draw' => $draw,
      'recordsTotal' => $recordsTotal,
      'recordsFiltered' => $recordsFiltered,
      'data' => $data
    );

    echo json_encode($response);
  }
}