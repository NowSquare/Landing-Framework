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
     * Send email
     */
    public function sendEmail()
    {
      // <20170528181128.57811.4114FD6414B0E9F0@mg.landingframework.com>
      //$response = \Mailgun::api()->get('events', [
    //    'to' => 'info@s3m.nl',
      //]);

      //dd($response);
      die();

      $data = [
        'text' => 'This is the text version',
        'var1' => 'val1'
      ];

      $response = \Mailgun::send(['template.emails::basic.index', 'template.emails::_text.index'], $data, function ($message) {
        $message
          ->subject('Mailgun test mail')
          ->from('noreply@landingframework.com', 'LF')
          ->replyTo('noreply@landingframework.com', 'LF')
          ->to('info@s3m.nl', 'Sem')
          ->trackClicks(true)
          ->trackOpens(true);
      });
      dd($response);
    }

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
     * Email campaign editor iframe
     */
    public function editorFrame()
    {
      $sl = request()->input('sl', '');

      if($sl != '') {
        $qs = Core\Secure::string2array($sl);

        if (isset($qs['email_id'])) {
          $email = Models\Email::where('user_id', Core\Secure::userId())->where('id', $qs['email_id'])->first();

          if (! empty($email)) {
            $url = url('ec/edit/' . Core\Secure::staticHash($qs['email_id'], true));

            return view('emailcampaigns::editor', compact('url', 'email'));
          }
        }
      }
    }

    /**
     * Email campaign editor
     */
    public function editor($local_domain)
    {
      if(isset($local_domain) && $local_domain != '') {

        $email_id = Core\Secure::staticHashDecode($local_domain, true);

        if (is_numeric($email_id)) {

          $variant = 1;

          $email = Models\Email::where('user_id', Core\Secure::userId())->where('id', $email_id)->first();

          if (empty($email)) {
            return response()->view('errors.404', [], 404);
          }

          $sl = Core\Secure::array2string(['email_id' => $email->id]);

          $published_url = ($email->domain != '') ? '//' . $email->domain : url('f/' . $email->local_domain);

          $view = 'public.emails::' . Core\Secure::staticHash($email->user_id) . '.' . Core\Secure::staticHash($email->email_campaign_id, true) . '.' . $local_domain . '.' . $variant . '.index';

          // Put template html into variable.
          $template = view($view);

          // Suppress libxml errors
          // Resolves an issue with some servers.
          libxml_use_internal_errors(true);

          // Create a new PHPQuery object to manipulate
          // the DOM in a similar way as jQuery.
          $dom = \phpQuery::newDocumentHTML($template);
          \phpQuery::selectDocument($dom);

          // Insert scripts right after last js include
          // to make sure jQuery and Bootstrap 4 js are
          // included in template, while inline <script>'s
          // can safely run below.
          pq('head')->find('script[src]:first')->before(PHP_EOL . '<script class="-x-editor-asset" src="' . url('assets/translations?lang=' . $email->language) . '&editor=1"></script>');
          pq('head')->find('script[src]:last')->before(PHP_EOL . '<script class="-x-editor-asset">var lf_published_url = "' . $published_url . '";var lf_demo = true;var lf_sl = "' . $sl . '";var lf_csrf_token = "' . csrf_token() . '";</script>');
          pq('head')->find('script[src]:last')->after(PHP_EOL . '<script class="-x-editor-asset" src="' . url('assets/javascript?lang=' . \App::getLocale()) . '"></script>');
          pq('head')->find('script[src]:last')->after(PHP_EOL . '<script class="-x-editor-asset" src="' . url('assets/js/scripts.editor.min.js?v=' . config('version.editor')) . '"></script>');

          // End stylesheet right before </head> to make
          // sure it overrides other stylesheets.
          pq('head')->append(PHP_EOL . '<link class="-x-editor-asset" rel="stylesheet" type="text/css" href="' . url('assets/css/styles.editor.min.css?v=' . config('version.editor')) . '" />');

          // Init editor
          pq('head')->append(PHP_EOL . '<script class="-x-editor-asset">$(function(){ lfInitEditor(\'forms\'); });</script>');

          //$dom = str_replace('</section><section', "</section>\n\n<section", $dom);

          // Beautify html
          $html = Core\Parser::beautifyHtml($dom);

          return $html;
        }
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
          //$tbl_name = 'x_landing_stats_' . Core\Secure::userId();
          //\DB::table($tbl_name)->where('email_campaign_id', $campaign_id)->delete();
  
          // Delete files
          $storage_root = 'emails/email/' . Core\Secure::staticHash(Core\Secure::userId()) . '/' . Core\Secure::staticHash($campaign_id, true);
          \Storage::disk('public')->deleteDirectory($storage_root);

          return response()->json(['success' => true]);
        }
      }
    }
}
