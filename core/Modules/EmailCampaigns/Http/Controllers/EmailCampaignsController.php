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
     * Email page
     */
    public function showEmail($local_domain)
    {
      if(isset($local_domain) && $local_domain != '') {

        $preview = (boolean) request()->input('preview', false);
        $email_id = Core\Secure::staticHashDecode($local_domain, true);

        if (is_numeric($email_id)) {

          $variant = 1;

          if ($preview) {
            $email = Models\Email::where('user_id', Core\Secure::userId())->where('id', $email_id)->first();

            if (! empty($email)) {
              $view = 'public.emails::' . Core\Secure::staticHash($email->user_id) . '.' . Core\Secure::staticHash($email->email_campaign_id, true) . '.' . $local_domain . '.' . $variant . '.index';
            } else {
              return response()->view('errors.unpublished', ['msg' => trans('global.email_not_published')], 404);
            }
          } else {
            $email = Models\Email::where('id', $email_id)->first();

            if (! empty($email)) {
              $view = 'public.emails::' . Core\Secure::staticHash($email->user_id) . '.' . Core\Secure::staticHash($email->email_campaign_id, true) . '.' . $local_domain . '.' . $variant . '.published.index';
            } else {
              return response()->view('errors.unpublished', ['msg' => trans('global.email_not_published')], 404);
            }
          }

          try {
            $template = view($view);
          } catch(\Exception $e) {
            return response()->view('errors.unpublished', ['msg' => trans('global.email_not_published')], 404);
          }

          // Stats
          if (! $preview && Core\Secure::userId() != $email->user_id) {
            //FunctionsController::addStat($email);
          }

          libxml_use_internal_errors(true);
          $dom = \phpQuery::newDocumentHTML($template);
          \phpQuery::selectDocument($dom);

          // Add sl + translations
          $sl = Core\Secure::array2string(['email_id' => $email_id]);
          pq('head')->find('script[src]:first')->before(PHP_EOL . '<script src="' . url('assets/translations?lang=' . $email->language) . '"></script>');
          pq('head')->find('script[src]:first')->before(PHP_EOL . '<script>var sl_e = "' . $sl . '";</script>');

          // Beautify html
          $html = Core\Parser::beautifyHtml($dom);

          return $html;

        } else {
          return response()->view('errors.404', ['msg' => trans('global.page_not_published')], 404);
        }
      } else {
        return response()->view('errors.404', ['msg' => trans('global.page_not_published')], 404);
      }
    }

    /**
     * Email campaigns backend main
     */
    public function index()
    {
      $email_campaigns = Models\EmailCampaign::where('user_id', Core\Secure::userId())->where('funnel_id', Core\Secure::funnelId())->orderBy('created_at', 'desc')->get();

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

    /**
     * Create a email campaign and return redir url
     */
    public function createCampaign(Request $request)
    {
      $template = $request->input('template', '');
      $name = $request->input('name', '');

      // Verify limit
      $current_count = Models\EmailCampaign::where('user_id', '=', Core\Secure::userId())->count();
      $current_count_limit = \Auth::user()->plan->limitations['emailcampaigns']['max'];

      if ($current_count >= $current_count_limit) {
        return response()->json([
          'type' => 'error', 
          'msg' => trans('global.account_limit_reached'),
          'reset' => false
        ]);
      }

      $email = FunctionsController::createCampaign($template, $name);

      $redir = ($email !== false) ? Core\Secure::array2string(['email_id' => $email->id]) : '#';

      return response()->json(['redir' => $redir]);
    }

    /**
     * Email preview
     */
    public function previewTemplate($template)
    {

      $template = view('template.emails::' . $template . '.index');

      /*libxml_use_internal_errors(true);
      $dom = \phpQuery::newDocumentHTML($template);
      \phpQuery::selectDocument($dom);

      // Add demo
      pq('head')->find('script[src]:first')->before(PHP_EOL . '<script>var lf_demo = true;</script>');
*/

      // Beautify html
      $html = Core\Parser::beautifyHtml($template);

      return $html;
    }
}
