<?php

namespace Modules\LandingPage\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class LandingPageController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('landingpage::index');
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
        "name" => trans('landingpage::global.business_services'),
        "desc" => trans('landingpage::global.business_services_desc'),
        "url" => "#/platform/landingpage/business_services"
      ];

      $items[] = [
        "icon" => 'chefavatar-food-grocery-kitchen-restaurant-drink.svg',
        "name" => trans('landingpage::global.food_drinks'),
        "desc" => trans('landingpage::global.food_drinks_desc'),
        "url" => "#/platform/landingpage/food_drink"
      ];

      $items[] = [
        "icon" => 'plan.svg',
        "name" => trans('landingpage::global.digital_tech'),
        "desc" => trans('landingpage::global.digital_tech_desc'),
        "url" => "#/platform/landingpage/digital_tech"
      ];

      $items[] = [
        "icon" => 'pictureprofile.svg',
        "name" => trans('landingpage::global.personal'),
        "desc" => trans('landingpage::global.personal_desc'),
        "url" => "#/platform/landingpage/personal"
      ];

      return view('landingpage::create', compact('items'));
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
        return view('landingpage::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('landingpage::edit');
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
