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
      $email_campaigns = Models\EmailCampaign::where('user_id', Core\Secure::userId())->orderBy('created_at', 'desc')->get();

      if (count($email_campaigns) == 0) {
        return $this->create();
      } else {
        return view('emailcampaigns::overview', compact('email_campaigns'));
      }
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
      $categories = FunctionsController::getCategories();

      return view('emailcampaigns::create', compact('categories'));
    }

    /**
     * Create a new campaign step 2
     */
    public function createCategory($category)
    {
      $templates = FunctionsController::getTemplatesByCategory($category);

      return view('emailcampaigns::create-category', compact('category', 'templates'));
    }

}
