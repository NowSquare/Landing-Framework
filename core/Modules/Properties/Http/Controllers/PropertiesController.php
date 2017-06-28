<?php

namespace Modules\Properties\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class PropertiesController extends Controller
{
    /**
     * Properties backend main
     */
    public function index()
    {
      $order = request()->input('order', '');
      $cookie = null;

      if ($order != '') {
        $cookie = \Cookie::queue('p_order', $order, 60 * 24 * 7 * 4 * 6);
        
      } else {
        $order = request()->cookie('p_order', 'new_first');
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

      $properties = Models\Property::where('user_id', Core\Secure::userId())->where('funnel_id', Core\Secure::funnelId())->orderBy($order_column, $order_by)->get();

      if (count($properties) == 0) {
        return $this->create();
      } else {
        return view('properties::overview', compact('properties', 'order'))->withCookie($cookie);
      }
    }

    /**
     * Create a new property select type
     */
    public function create()
    {
      $types = Models\PropertyType::where('user_id', Core\Secure::userId())->where('funnel_id', Core\Secure::funnelId())->orderBy('name', 'asc')->get();

      return view('properties::create', compact('types'));
    }

    /**
     * Create a new property
     */
    public function createCategory($category)
    {
      $templates = FunctionsController::getTemplatesByCategory($category);

      return view('properties::create-category', compact('category', 'templates'));
    }
}
