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
    public function showCampaigns()
    {
      $order = request()->input('order', '');
      $cookie = null;

      if ($order != '') {
        $cookie = \Cookie::queue('ec_order', $order, 60 * 24 * 7 * 4 * 6);
        
      } else {
        $order = request()->cookie('ec_order', 'new_first');
      }

      switch($order) {
        case 'new_first': $order_column = 'created_at'; $order_by = 'desc'; break;
        case 'old_first': $order_column = 'created_at'; $order_by = 'asc'; break;
        default: $order_column = 'created_at'; $order_by = 'desc';
      }

      $email_campaigns = Models\EmailCampaign::where('user_id', Core\Secure::userId())->where('funnel_id', Core\Secure::funnelId())->orderBy($order_column, $order_by)->get();

      if (count($email_campaigns) == 0) {
        return $this->showCreateCampaign();
      } else {
        $categories = FunctionsController::getCampaignCategories();

        return view('emailcampaigns::campaigns', compact('email_campaigns', 'categories', 'order'))->withCookie($cookie);
      }
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function showCreateCampaign()
    {
      $categories = FunctionsController::getCampaignCategories();

      return view('emailcampaigns::campaign-create', compact('categories'));
    }

    /**
     * Create a email campaign and return redir url
     */
    public function postCreateCampaign(Request $request)
    {
      $name = $request->input('name', '');
      $category = $request->input('category', '');

      $input = array(
        'name' => $name
      );

      $rules = array(
        'name' => 'required|max:64'
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

      // Verify limit - deprecated: only check emails
      /*
      $current_count = Models\EmailCampaign::where('user_id', '=', Core\Secure::userId())->count();
      $current_count_limit = \Auth::user()->plan->limitations['emailcampaigns']['max'];

      if ($current_count > $current_count_limit) {
        return response()->json([
          'type' => 'error', 
          'msg' => trans('global.account_limit_reached'),
          'reset' => false
        ]);
      }
      */

      $email_campaign = FunctionsController::createCampaign($name, $category);

      $redir = ($email_campaign !== false) ? Core\Secure::array2string(['email_campaign_id' => $email_campaign->id]) : '#';

      return response()->json(['redir' => $redir]);
    }

    /**
     * Edit campaign
     */
    public function showEditCampaign()
    {
      $sl = request()->input('sl', '');

      if($sl != '') {
        $qs = Core\Secure::string2array($sl);
        $sl = urlencode($sl);

        $email_campaign_id = $qs['email_campaign_id'];
        $email_campaign = Models\EmailCampaign::where('user_id', Core\Secure::userId())->where('id', $email_campaign_id)->first();

        // All campaigns
        $email_campaigns = Models\EmailCampaign::where('user_id', Core\Secure::userId())->where('funnel_id', Core\Secure::funnelId())->orderBy('name', 'asc')->get();

        return view('emailcampaigns::campaign-edit', compact('sl', 'email_campaigns', 'email_campaign'));
      }
    }

    /**
     * Post update campaign
     */
    public function postUpdateCampaign()
    {
      $sl = request()->input('sl', '');
      $name = request()->get('name', '');

      $input = array(
        'name' => $name
      );

      $rules = array(
        'name' => 'required|max:64'
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

      if($sl != '') {
        $qs = Core\Secure::string2array($sl);
        $email_campaign_id = $qs['email_campaign_id'];
        $email_campaign = Models\EmailCampaign::where('user_id', Core\Secure::userId())->where('id', $email_campaign_id)->first();

        $email_campaign->name = $name;

        $email_campaign->save();

        $response = array(
          'type' => 'success',
          'redir' => '#/emailcampaigns'
        );

        return response()->json($response);
      }
    }

    /**
     * Create a new campaign step 2
     
    public function createCategory($category)
    {
      $templates = FunctionsController::getTemplatesByCategory($category);

      return view('emailcampaigns::create-category', compact('category', 'templates'));
    }
*/
    /**
     * Delete campaign
     */
    public function deleteCampaign()
    {
      $sl = request()->input('sl', '');

      if($sl != '') {
        $qs = Core\Secure::string2array($sl);
        $campaign_id = $qs['email_campaign_id'];
        if (is_numeric($campaign_id)) {
          // Delete records
          Models\EmailCampaign::where('user_id', Core\Secure::userId())->where('id', $campaign_id)->delete();

          // Delete records
          //$tbl_name = 'x_email_mailings_' . Core\Secure::userId();
          //\DB::table($tbl_name)->where('email_campaign_id', $campaign_id)->delete();

          // Delete files
          $storage_root = 'emails/email/' . Core\Secure::staticHash(Core\Secure::userId()) . '/' . Core\Secure::staticHash($campaign_id, true);
          \Storage::disk('public')->deleteDirectory($storage_root);

          return response()->json(['success' => true]);
        }
      }
    }
}
