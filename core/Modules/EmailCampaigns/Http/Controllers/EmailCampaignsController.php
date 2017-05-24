<?php

namespace Modules\EmailCampaigns\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use \Platform\Controllers\Core;
use Modules\EmailCampaigns\Http\Models;

class EmailCampaignsController extends Controller
{

    /**
     * Email campaigns backend main
     */
    public function index()
    {
      //$campaigns = Models\Site::where('user_id', Core\Secure::userId())->orderBy('created_at', 'desc')->get();
      $campaigns = [];
      //if (count($sites) == 0) {
      //  return $this->create();
      //} else {
        return view('emailcampaigns::overview', compact('campaigns'));
      //}
    }
}
