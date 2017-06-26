<?php

namespace Modules\Beacons\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class BeaconsController extends Controller
{
    /**
     * Beacons backend main
     */
    public function index()
    {
      $order = request()->input('order', '');
      $cookie = null;

      if ($order != '') {
        $cookie = \Cookie::queue('ble_order', $order, 60 * 24 * 7 * 4 * 6);
        
      } else {
        $order = request()->cookie('ble_order', 'new_first');
      }

      switch($order) {
        case 'new_first': $order_column = 'created_at'; $order_by = 'desc'; break;
        case 'old_first': $order_column = 'created_at'; $order_by = 'asc'; break;
        case 'high_converting_first': $order_column = 'conversion'; $order_by = 'desc'; break;
        case 'low_converting_first': $order_column = 'conversion'; $order_by = 'asc'; break;
        case 'most_visited_first': $order_column = 'visits'; $order_by = 'desc'; break;
        case 'least_visited_first': $order_column = 'visits'; $order_by = 'asc'; break;
        default: $order_column = 'created_at'; $order_by = 'desc';
      }

      $beacons = [];

      if (count($sites) == 0) {
        return $this->create();
      } else {
        return view('beacons::overview', compact('beacons', 'order'))->withCookie($cookie);
      }
    }
}
