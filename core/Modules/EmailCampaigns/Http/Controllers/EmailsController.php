<?php

namespace Modules\EmailCampaigns\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use \Platform\Controllers\Core;
use Modules\EmailCampaigns\Http\Models;
use Modules\EmailCampaigns\Jobs\SendTestEmail;
use Modules\EmailCampaigns\Jobs\SendMailing;

class EmailsController extends Controller
{
    /**
     * Send email
     */
    public function sendEmail()
    {

      die();
      $email = Models\Email::where('id', 1)->first();
      $form = \Modules\Forms\Http\Models\Form::where('id', 1)->first();

    }

    /**
     * Confirm email
     */
    public function confirmEmail($email, $local_domain, $entry_id)
    {
      $form_id = Core\Secure::staticHashDecode($local_domain, true);
      $entry_id = Core\Secure::staticHashDecode($entry_id, true);

      // Get entry
      $form = \Modules\Forms\Http\Models\Form::whereId($form_id)->first();

      //$tbl_name = 'x_form_entries_' . $form->user_id;
      //$Entry = new \Modules\Forms\Http\Models\Entry([]);
      //$Entry->setTable($tbl_name);

      $form_entry = \Modules\Forms\Http\Models\Entry::where('form_id', $form->id)->where('email', $email)->where('id', $entry_id)->where('confirmed', 0)->first();

      if (empty($form_entry)) {
        return response()->view('layouts.simple-message', ['icon' => '&#xE5CD;', 'msg' => trans('emailcampaigns::global.confirmation_not_found')], 200);
      } else {
        //$form_entry->setTable($tbl_name);
        $form_entry->confirmed = 1;
        $form_entry->save();

        return response()->view('layouts.simple-message', ['icon' => '&#xE5CA;', 'msg' => trans('emailcampaigns::global.confirmation_thank_you')], 200);
      }
    }

    /**
     * Confirm email demo page
     */
    public function confirmEmailTest()
    {
      return response()->view('layouts.simple-message', ['icon' => '&#xE157;', 'msg' => trans('emailcampaigns::global.confirmation_demo')], 200);
    }

    /**
     * Unsubscribe email
     */
    public function unsubscribeEmail($email, $local_domain, $entry_id)
    {
      $form_id = Core\Secure::staticHashDecode($local_domain, true);
      $entry_id = Core\Secure::staticHashDecode($entry_id, true);

      // Get entry
      $form = \Modules\Forms\Http\Models\Form::whereId($form_id)->first();

      //$tbl_name = 'x_form_entries_' . $form->user_id;
      //$Entry = new \Modules\Forms\Http\Models\Entry([]);
      //$Entry->setTable($tbl_name);

      $form_entry = \Modules\Forms\Http\Models\Entry::where('form_id', $form->id)->where('email', $email)->where('id', $entry_id)->where('confirmed', 1)->first();

      if (empty($form_entry)) {
        return response()->view('layouts.simple-message', ['icon' => '&#xE5CD;', 'msg' => trans('emailcampaigns::global.unsubscribe_not_found')], 200);
      } else {
        //$form_entry->setTable($tbl_name);
        $form_entry->confirmed = 0;
        $form_entry->save();
        return response()->view('layouts.simple-message', ['icon' => '&#xE5CA;', 'msg' => trans('emailcampaigns::global.unsubscribe_thank_you')], 200);
      }
    }

    /**
     * Unsubscribe email demo page
     */
    public function unsubscribeEmailTest()
    {
      return response()->view('layouts.simple-message', ['icon' => '&#xE157;', 'msg' => trans('emailcampaigns::global.unsubscribe_demo')], 200);
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
          pq('head')->append(PHP_EOL . '<script class="-x-editor-asset">var lfCampaignType = \'' . $email->emailCampaign->type . '\'; $(function(){ lfInitEditor(\'emails\'); });</script>');

          // Editor toolbar
          pq('body')->prepend(PHP_EOL . '<div class="-x-editor-asset" id="editor_toolbar"></div>');

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
      $order = request()->input('order', '');
      $sl = request()->input('sl', '');
      $cookie = null;

      if($sl != '') {
        $qs = Core\Secure::string2array($sl);
        $sl = urlencode($sl);

        if ($order != '') {
          $cookie = \Cookie::queue('e_order', $order, 60 * 24 * 7 * 4 * 6);
        } else {
          $order = request()->cookie('e_order', 'new_first');
        }

        switch($order) {
          case 'new_first': $order_column = 'created_at'; $order_by = 'desc'; break;
          case 'old_first': $order_column = 'created_at'; $order_by = 'asc'; break;
          default: $order_column = 'created_at'; $order_by = 'desc';
        }

        $email_campaign_id = $qs['email_campaign_id'];
        $email_campaign = Models\EmailCampaign::where('user_id', Core\Secure::userId())->where('id', $email_campaign_id)->first();

        // Emails
        $emails = Models\Email::where('user_id', Core\Secure::userId())->where('email_campaign_id', $email_campaign_id)->orderBy($order_column, $order_by)->get();

        // All campaigns
        $email_campaigns = Models\EmailCampaign::where('user_id', Core\Secure::userId())->where('funnel_id', Core\Secure::funnelId())->orderBy('name', 'asc')->get();

        if (count($emails) == 0) {
          return $this->showCreateEmail();
        } else {
          return view('emailcampaigns::emails', compact('sl', 'order', 'email_campaigns', 'email_campaign', 'emails'));
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
          //$tbl_name = 'x_email_mailings_' . Core\Secure::userId();
          //\DB::table($tbl_name)->where('email_id', $email_id)->delete();

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
      $forms = $request->input('forms', '');
      $from_name = $request->input('from_name', '');
      $from_email = $request->input('from_email', '');

      $save = FunctionsController::saveEmail($sl, $forms, $subject, $from_name, $from_email, $html);

      if ($save) {
        $response = ['success' => true, 'msg' => trans('javascript.save_succes')];
      } else {
        $response = ['success' => false, 'msg' => 'An error occured'];
      }

      return response()->json($response);
    }

    /**
     * Email settings
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
     * Post email settings
     */
    public function editorPostSettings(Request $request)
    {
      $sl = $request->input('sl', '');
      $name = $request->input('name', '');
      $name = substr($name, 0, 200);

      if ($sl != '') {
        $qs = Core\Secure::string2array($sl);
        $email_id = $qs['email_id'];

        if (is_numeric($email_id)) {
          $email = Models\Email::where('user_id', Core\Secure::userId())->where('id', $qs['email_id'])->first();
          $email->name = $name;
          $email->save();

          return response()->json(['success' => true]);
        }
      }
    }

    /**
     * Send mailing
     */
    public function editorModalSendMailing(Request $request)
    {
      $sl = $request->input('sl', '');

      if ($sl != '') {
        $qs = Core\Secure::string2array($sl);
        $email_id = $qs['email_id'];

        if (is_numeric($email_id)) {
          $email = Models\Email::where('user_id', Core\Secure::userId())->where('id', $qs['email_id'])->first();

          $scheduled = ($email->scheduled_at != null) ? true : false;
          $scheduled_at = ($email->scheduled_at != null) ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $email->scheduled_at, 'UTC')->tz(\Auth::user()->timezone)->format('Y-m-d H:i:s')  : \Carbon\Carbon::now(\Auth::user()->timezone)->addDay()->format('Y-m-d H:00:00');

          return view('landingpages::modals.send-mailing', compact('email', 'sl', 'scheduled', 'scheduled_at'));
        }
      }
    }

    /**
     * Post mailing
     */
    public function editorPostSendMailing(Request $request)
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

          //$email->tests = $email->tests + 1;
          //$email->last_test = date('Y-m-d H:i:s');
          //$email->last_test_email = $mailto;
          //$email->save();

          //$job = (new SendTestEmail($mailto, $email));
          //dispatch($job);

          return response()->json([
            'type' => 'success', 
            'reset' => false, 
            'msg' => trans('emailcampaigns::global.test_email_sent')
          ]);
        }
      }
    }

    /**
     * Send mailing now
     */
    public function postSendMailing(Request $request)
    {
      $sl = $request->input('sl', '');

      if ($sl != '') {
        $qs = Core\Secure::string2array($sl);
        $email_id = $qs['email_id'];

        if (is_numeric($email_id)) {
          $email = Models\Email::where('user_id', Core\Secure::userId())->where('id', $qs['email_id'])->first();
          $email->scheduled_at = null;
          $email->save();

          $job = (new SendMailing($email));
          dispatch($job);

          return response()->json([
            'type' => 'success', 
            'reset' => false, 
            'msg' => trans('emailcampaigns::global.mailing_sent')
          ]);
        }
      }
    }

    /**
     * Schedule mailing
     */
    public function postScheduleMailing(Request $request)
    {
      $sl = $request->input('sl', '');
      $scheduled_at = $request->input('scheduled_at', '');

      if ($sl != '') {
        $qs = Core\Secure::string2array($sl);
        $email_id = $qs['email_id'];

        if (is_numeric($email_id)) {
          $email = Models\Email::where('user_id', Core\Secure::userId())->where('id', $qs['email_id'])->first();
          $email->scheduled_at = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $scheduled_at, \Auth::user()->timezone)->tz('UTC');
          $email->save();

          return response()->json([
            'type' => 'success', 
            'reset' => false, 
            'msg' => trans('emailcampaigns::global.mailing_scheduled')
          ]);
        }
      }
    }

    /**
     * Remove schedule mailing
     */
    public function postRemoveScheduleMailing(Request $request)
    {
      $sl = $request->input('sl', '');

      if ($sl != '') {
        $qs = Core\Secure::string2array($sl);
        $email_id = $qs['email_id'];

        if (is_numeric($email_id)) {
          $email = Models\Email::where('user_id', Core\Secure::userId())->where('id', $qs['email_id'])->first();
          $email->scheduled_at = null;
          $email->save();

          return response()->json([
            'type' => 'success', 
            'reset' => false, 
            'msg' => trans('emailcampaigns::global.mailing_schedule_removed')
          ]);
        }
      }
    }

    /**
     * Process scheduled mailings
     */
    public static function processScheduledMailings()
    {
      $emails = Models\Email::where('scheduled_at', '<=', \Carbon\Carbon::now('UTC')->format('Y-m-d H:i:s'))->get();

      if (count($emails) > 0) {
        foreach ($emails as $email) {

          $job = (new SendMailing($email));
          dispatch($job);

          $email->scheduled_at = null;
          $email->save();
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
          $i = 0;
          foreach ($items as $item => $translation) {
            $alt = '';

            switch ($item) {
              case 'first_name': 
              case 'name': 
                $alt = '=there';
                break;
            }

            if ($category == 'personal' && $i == 0) {
              $submenu[] = [
                'text' => trans('global.email_address'),
                'value' => '--' . $category . '_email--'
              ];
            }

            $submenu[] = [
              'text' => $translation,
              'value' => '--' . $category . '_' . $item . $alt . '--'
            ];

            $i++;
          }

          $vars[] = [
            'text' => $category_translation,
            'menu' => $submenu
          ];
        }
      }

      return response()->json($vars);
    }

    /**
     * Mailgun webhook
     */
    public function mgEvent()
    {
      $message_id = request()->get('message-id', '');
      $event = request()->get('event', '');
      $tag = request()->get('tag', '');
      $link = request()->get('url', null);
      $recipient = request()->get('recipient', null);

      $tags = explode('_', $tag);

      $user_id = (isset($tags[0])) ? $tags[0] : 0;
      $form_id = (isset($tags[1])) ? $tags[1] : 0;
      $email_id = (isset($tags[2])) ? $tags[2] : 0;
      $entry_id = (isset($tags[3])) ? $tags[3] : 0;
/*
      $html = 'Tag: ' . $tag . '<br>';
      $html .= 'Event: ' . $event . '<br>';
      $html .= 'User id: ' . $user_id . '<br>';
      $html .= 'Form id: ' . $form_id . '<br>';
      $html .= 'Email id: ' . $email_id . '<br>';
      $html .= 'Entry id: ' . $entry_id . '<br>';

      $response = \Mailgun::raw($html, function ($message) {
        $message
          ->subject('Mailgun webhook test')
          ->from('noreply@landingframework.com', 'Landing Framework')
          ->to('info@s3m.nl')
          ->trackClicks(false)
          ->trackOpens(false);
      });
*/
      if ($user_id > 0 && $form_id > 0 && $email_id > 0 && $entry_id > 0) {

        // Insert mail event
        //$tbl_name = 'x_email_events_' . $user_id;
        $tbl_name = 'email_events';

        \DB::table($tbl_name)->insert(
          [
            'form_id' => $form_id,
            'email_id' => $email_id,
            'entry_id' => $entry_id,
            'message_id' => $message_id,
            'event' => $event,
            'recipient' => $recipient,
            'link' => $link
          ]
        );
      }

      return response()->json(['message' => 'Post received. Thanks!']);
    }
}
