<?php

namespace Modules\LandingPages\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use \Platform\Controllers\Core;
use Modules\LandingPages\Http\Models;

class LandingPagesController extends Controller
{
    /**
     * Landing page home
     */
    public function homePage($local_domain, $edit = false)
    {
        return view('landingpages::index');
    }

    /**
     * Landing page editor
     */
    public function editor()
    {
      $sl = request()->input('sl', '');

      if($sl != '') {
        $qs = Core\Secure::string2array($sl);
        //$landing_site = Pages::where('user_id', Core\Secure::userId())->where('id', $qs['landing_site_id'])->first();

        return view('landingpages::editor');
      }
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
      $items = [];

      $items[] = [
        "icon" => 'presentation.svg',
        "name" => trans('landingpages::global.business_services'),
        "desc" => trans('landingpages::global.business_services_desc'),
        "url" => "#/platform/landingpages/business_services"
      ];

      $items[] = [
        "icon" => 'chefavatar-food-grocery-kitchen-restaurant-drink.svg',
        "name" => trans('landingpages::global.food_drinks'),
        "desc" => trans('landingpages::global.food_drinks_desc'),
        "url" => "#/platform/landingpages/food_drink"
      ];

      $items[] = [
        "icon" => 'plan.svg',
        "name" => trans('landingpages::global.digital_tech'),
        "desc" => trans('landingpages::global.digital_tech_desc'),
        "url" => "#/platform/landingpages/digital_tech"
      ];

      $items[] = [
        "icon" => 'pictureprofile.svg',
        "name" => trans('landingpages::global.personal'),
        "desc" => trans('landingpages::global.personal_desc'),
        "url" => "#/platform/landingpages/personal"
      ];

      return view('landingpages::create', compact('items'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('landingpages::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('landingpages::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}
