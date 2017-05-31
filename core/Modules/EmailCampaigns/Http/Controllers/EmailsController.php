<?php

namespace Modules\EmailCampaigns\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use \Platform\Controllers\Core;
use Modules\EmailCampaigns\Http\Models;
use Modules\EmailCampaigns\Jobs\SendTestEmail;

class EmailsController extends Controller
{
    /**
     * Send email
     */
    public function sendEmail()
    {
      $variant = 1;

      $email = Models\Email::where('id', 1)->first();
      $view = 'public.emails::' . Core\Secure::staticHash($email->user_id) . '.' . Core\Secure::staticHash($email->email_campaign_id, true) . '.' . $email->local_domain . '.' . $variant . '.index';

      dd($email->emailCampaign->mail_from);
      return view($view);

      dd($view);
      die();

      $email = [
        'text' => 'This is the text version',
        'var1' => 'val1'
      ];

      $job = (new SendTestEmail($email))
        ->delay(\Carbon\Carbon::now()->addMinutes(2));

      dispatch($job);

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
     * Send test email
     */
    public function sendTestEmail()
    {
      $mailto = request()->input('preview', false);

      $input = array(
        'name' => $mailto
      );

      $rules = array(
        'name' => 'required|email'
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
      
      $email = [
        'text' => 'This is the text version',
        'var1' => 'val1'
      ];

      $job = (new SendTestEmail($email))
        ->delay(\Carbon\Carbon::now()->addMinutes(2));

      dispatch($job);

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
              $view = 'public.emails::' . Core\Secure::staticHash($email->user_id) . '.' . Core\Secure::staticHash($email->email_campaign_id, true) . '.' . $local_domain . '.' . $variant . '.index';
            } else {
              return response()->view('errors.unpublished', ['msg' => trans('global.email_not_published')], 404);
            }
          }

          try {
            $template = view($view);
          } catch(\Exception $e) {
            return response()->view('errors.unpublished', ['msg' => trans('global.email_not_published')], 404);
          }

          $dom = $template;

/*
          libxml_use_internal_errors(true);
          $dom = \phpQuery::newDocumentHTML($template);
          \phpQuery::selectDocument($dom);

          // Add sl + translations
          $sl = Core\Secure::array2string(['email_id' => $email_id]);
          pq('head')->find('script[src]:first')->before(PHP_EOL . '<script src="' . url('assets/translations?lang=' . $email->language) . '"></script>');
          pq('head')->find('script[src]:first')->before(PHP_EOL . '<script>var sl_e = "' . $sl . '";</script>');
*/
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

          $forms = \Modules\Forms\Http\Models\Form::where('user_id', Core\Secure::userId())->where('funnel_id', Core\Secure::funnelId())->orderBy('name', 'asc')->get();

          if (! empty($email)) {
            $url = url('ec/edit/' . Core\Secure::staticHash($qs['email_id'], true));

            return view('emailcampaigns::editor', compact('url', 'email', 'forms'));
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
          pq('head')->append(PHP_EOL . '<link class="-x-editor-asset" rel="stylesheet" type="text/css" href="' . url('assets/bs4/css/style.min.css') . '">');
          pq('head')->append(PHP_EOL . '<script class="-x-editor-asset" src="' . url('assets/translations?lang=' . $email->language) . '&editor=1"></script>');
          pq('head')->append(PHP_EOL . '<script class="-x-editor-asset">var lf_published_url = "' . $published_url . '";var lf_demo = true;var lf_sl = "' . $sl . '";var lf_csrf_token = "' . csrf_token() . '";</script>');
          pq('head')->append(PHP_EOL . '<script class="-x-editor-asset" src="' . url('assets/javascript?lang=' . \App::getLocale()) . '"></script>');
          pq('head')->append(PHP_EOL . '<script class="-x-editor-asset" src="' . url('assets/bs4/js/scripts.lite.min.js?v=' . config('version.editor')) . '"></script>');
          pq('head')->append(PHP_EOL . '<script class="-x-editor-asset" src="' . url('assets/js/scripts.editor.min.js?v=' . config('version.editor')) . '"></script>');

          // End stylesheet right before </head> to make
          // sure it overrides other stylesheets.
          pq('head')->append(PHP_EOL . '<link class="-x-editor-asset" rel="stylesheet" type="text/css" href="' . url('assets/css/styles.editor.min.css?v=' . config('version.editor')) . '" />');

          // Init editor
          pq('head')->append(PHP_EOL . '<script class="-x-editor-asset">$(function(){ lfInitEditor(\'emails\'); });</script>');

          // Editor toolbar
          pq('body')->prepend(PHP_EOL . '<div class="-x-editor-asset" id="editor_toolbar"></div>');
          
          //$dom = str_replace('</section><section', "</section>\n\n<section", $dom);

          // Beautify html
          $html = Core\Parser::beautifyHtml($dom);

          return $html;
        }
      }
    }

    /**
     * Show emails from campaign
     */
    public function showEmails()
    {
      $sl = request()->input('sl', '');

      if($sl != '') {
        $qs = Core\Secure::string2array($sl);
        $sl = urlencode($sl);

        $email_campaign_id = $qs['email_campaign_id'];
        $email_campaign = Models\EmailCampaign::where('user_id', Core\Secure::userId())->where('id', $email_campaign_id)->first();

        // All campaigns
        $email_campaigns = Models\EmailCampaign::where('user_id', Core\Secure::userId())->where('funnel_id', Core\Secure::funnelId())->orderBy('name', 'asc')->get();

        if (count($email_campaign->emails) == 0) {
          return $this->showCreateEmail();
        } else {
          return view('emailcampaigns::emails', compact('sl', 'email_campaigns', 'email_campaign'));
        }
      }
    }

    /**
     * Show email categories
     */
    public function showCreateEmail()
    {
      $sl = request()->input('sl', '');

      if($sl != '') {
        $qs = Core\Secure::string2array($sl);
        $sl = urlencode($sl);

        $email_campaign_id = $qs['email_campaign_id'];
        $email_campaign = Models\EmailCampaign::where('user_id', Core\Secure::userId())->where('id', $email_campaign_id)->first();

        $categories = FunctionsController::getEmailCategories();

        return view('emailcampaigns::email-create', compact('sl', 'categories', 'email_campaign'));
      }
    }

    /**
     * Post create email
     */
    public function postCreateEmail()
    {
      $sl = request()->input('sl', '');
      $template = request()->input('template', '');
      $name = request()->input('name', '');

      if($sl != '' && $template != '' && $name != '') {
        $qs = Core\Secure::string2array($sl);
        $email_campaign_id = $qs['email_campaign_id'];
        $email_campaign = Models\EmailCampaign::where('user_id', Core\Secure::userId())->where('id', $email_campaign_id)->first();

        // Verify limit
        $current_count = Models\Email::where('user_id', '=', Core\Secure::userId())->count();
        $current_count_limit = \Auth::user()->plan->limitations['emailcampaigns']['max'];

        if ($current_count >= $current_count_limit) {
          return response()->json([
            'type' => 'error', 
            'msg' => trans('global.account_limit_reached'),
            'reset' => false
          ]);
        }

        $email = FunctionsController::createEmail($email_campaign, $template, $name);

        $redir = ($email !== false) ? Core\Secure::array2string(['email_id' => $email->id]) : '#';

        return response()->json(['redir' => $redir]);
      }
    }

    /**
     * Select email template
     */
    public function showSelectTemplate($category)
    {
      $sl = request()->input('sl', '');

      if($sl != '') {
        $qs = Core\Secure::string2array($sl);
        $sl = urlencode($sl);

        $email_campaign_id = $qs['email_campaign_id'];
        $email_campaign = Models\EmailCampaign::where('user_id', Core\Secure::userId())->where('id', $email_campaign_id)->first();

        $templates = FunctionsController::getTemplatesByCategory($category);

        return view('emailcampaigns::email-select-template', compact('sl', 'category', 'templates', 'email_campaign'));
      }
    }

    /**
     * Email preview
     */
    public function previewTemplate($template)
    {

      $template = view('template.emails::' . $template . '.index');

      // Beautify html
      $html = Core\Parser::beautifyHtml($template);

      return $html;
    }

    /**
     * Delete email
     */
    public function deleteEmail()
    {
      $sl = request()->input('sl', '');

      if($sl != '') {
        $qs = Core\Secure::string2array($sl);
        $email_id = $qs['email_id'];
        if (is_numeric($email_id)) {
          // Delete records
          $email = Models\Email::where('user_id', Core\Secure::userId())->where('id', $email_id)->first();

          // Delete records
          $tbl_name = 'x_email_mailings_' . Core\Secure::userId();
          \DB::table($tbl_name)->where('email_id', $email_id)->delete();

          // Delete files
          $storage_root = 'emails/email/' . Core\Secure::staticHash(Core\Secure::userId()) . '/' . Core\Secure::staticHash($email->email_campaign_id, true) . '/' . Core\Secure::staticHash($email_id, true);
          \Storage::disk('public')->deleteDirectory($storage_root);

          $email->delete();

          return response()->json(['success' => true]);
        }
      }
    }

    /**
     * Save email
     */
    public function saveEmail(Request $request)
    {
      $sl = $request->input('sl', '');
      $html = $request->input('html', '');

      $subject = $request->input('subject', '');
      $mailto = $request->input('mailto', '');
      $from_name = $request->input('from_name', '');
      $from_email = $request->input('from_email', '');

      $save = FunctionsController::saveEmail($sl, $mailto, $subject, $from_name, $from_email, $html);

      if ($save) {
        $response = ['success' => true, 'msg' => trans('javascript.save_succes')];
      } else {
        $response = ['success' => false, 'msg' => 'An error occured'];
      }

      return response()->json($response);
    }

    /**
     * Publish email
     */
    public function publishEmail(Request $request)
    {
      $sl = $request->input('sl', '');
      $html = $request->input('html', '');

      $publish = FunctionsController::saveEmail($sl, $html, true);

      if ($publish) {
        $response = ['success' => true, 'msg' => trans('javascript.publish_succes')];
      } else {
        $response = ['success' => false, 'msg' => 'An error occured'];
      }

      return response()->json($response);
    }

    /**
     * Unpublish email
     */
    public function unpublishEmail(Request $request)
    {
      $sl = $request->input('sl', '');

      if($sl != '') {
        $qs = Core\Secure::string2array($sl);

        $email_id = $qs['email_id'];
        $email = Models\Email::where('user_id', Core\Secure::userId())->where('id', $email_id)->first();

        $variant = 1;

        // Update files
        $storage_root = 'emails/email/' . Core\Secure::staticHash(Core\Secure::userId()) . '/' .  Core\Secure::staticHash($email->email_campaign_id, true) . '/' . Core\Secure::staticHash($email->id, true) . '/' . $variant;

        \Storage::disk('public')->deleteDirectory($storage_root . '/published');

        $response = ['success' => true, 'msg' => trans('javascript.unpublish_succes')];
      } else {
        $response = ['success' => false, 'msg' => 'An error occured'];
      }

      return response()->json($response);
    }

    /**
     * Settings
     */
    public function editorModalSettings(Request $request)
    {
      $sl = $request->input('sl', '');

      if ($sl != '') {
        $qs = Core\Secure::string2array($sl);
        $email_id = $qs['email_id'];

        if (is_numeric($email_id)) {
          $email = Models\Email::where('user_id', Core\Secure::userId())->where('id', $qs['email_id'])->first();

          return view('landingpages::modals.email-settings', compact('email', 'sl'));
        }
      }
    }

    /**
     * Post settings
     */
    public function editorPostSettings(Request $request)
    {
      $sl = $request->input('sl', '');
      $email = $request->input('email', '');

      $input = array(
        'email' => $email
      );

      $rules = array(
        'email' => 'required|max:64'
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

      if ($sl != '') {
        $qs = Core\Secure::string2array($sl);
        $email_id = $qs['email_id'];

        if (is_numeric($email_id)) {
          $email = Models\Email::where('user_id', Core\Secure::userId())->where('id', $qs['email_id'])->first();
          $email->tests = $email->tests + 1;
          $email->last_test = date('Y-m-d H:i:s');
          $email->save();

          return response()->json(['success' => true]);
        }
      }
    }

    /**
     * Test email
     */
    public function editorModalTestEmail(Request $request)
    {
      $sl = $request->input('sl', '');

      if ($sl != '') {
        $qs = Core\Secure::string2array($sl);
        $email_id = $qs['email_id'];

        if (is_numeric($email_id)) {
          $email = Models\Email::where('user_id', Core\Secure::userId())->where('id', $qs['email_id'])->first();

          return view('landingpages::modals.test-email', compact('email', 'sl'));
        }
      }
    }

    /**
     * Post test email
     */
    public function editorPostTestEmail(Request $request)
    {
      $sl = $request->input('sl', '');
      $mailto = $request->input('mailto', '');

      $input = array(
        'mailto' => $mailto
      );

      $rules = array(
        'mailto' => 'required|email|not_in:info@example.com'
      );

      $validator = \Validator::make($input, $rules);

      if($validator->fails()) {
        $response = [
          'type' => 'error', 
          'reset' => false, 
          'msg' => $validator->messages()->first()
        ];
        return response()->json($response);
      }

      if ($sl != '') {
        $qs = Core\Secure::string2array($sl);
        $email_id = $qs['email_id'];

        if (is_numeric($email_id)) {
          $email = Models\Email::where('user_id', Core\Secure::userId())->where('id', $qs['email_id'])->first();

          $email->tests = $email->tests + 1;
          $email->last_test = date('Y-m-d H:i:s');
          $email->last_test_email = $mailto;
          $email->save();

          $job = (new SendTestEmail($mailto, $email));
          dispatch($job);

          return response()->json([
            'type' => 'success', 
            'reset' => false, 
            'msg' => trans('emailcampaigns::global.test_email_sent')
          ]);
        }
      }
    }

    /**
     * Email variables for use in WYSIWYG editor
     */
    public function getEmailVariables()
    {
      $vars = [];

      foreach (trans('global.form_fields') as $category => $items) {
        $category_translation = trans('global.' . $category);
        if ($category != 'general') {
          $submenu = [];

          foreach ($items as $item => $translation) {
            $alt = '';

            switch ($item) {
              case 'first_name': 
              case 'name': 
                $alt = '=there';
                break;
            }

            $submenu[] = [
              'text' => $translation,
              'value' => '--' . $category . '_' . $item . $alt . '--'
            ];
          }

          $vars[] = [
            'text' => $category_translation,
            'menu' => $submenu
          ];
        }
      }

      return response()->json($vars);
    }
}
