<?php

namespace Modules\Forms\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use \Platform\Controllers\Core;
use Modules\Forms\Http\Models;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class FormsController extends Controller
{
    /**
     * Form public home
     */
    public function homePage($local_domain)
    {
      if(isset($local_domain) && $local_domain != '') {

        $preview = (boolean) request()->input('preview', false);
        $form_id = Core\Secure::staticHashDecode($local_domain, true);

        if (is_numeric($form_id)) {

          $variant = 1;

          if ($preview && auth()->check()) {
            // Check if token is set for preview
            $token = request()->input('token', false);

            if ($token !== false) { 
              // Get user from JWT token
              if (! $auth = JWTAuth::parseToken()) {
                throw \Exception('JWTAuth unable to parse token from request');
              }
              try {
                $user = $auth->toUser();
              } catch(\Exception $e) {
                return response()->view('layouts.simple-message', ['icon' => '&#xE157;', 'msg' => trans('global.link_expired')], 404);
              }
            } else {
              $user = auth()->user();
            }

            $form = Models\Form::where('user_id', $user->id)->where('id', $form_id)->first();

            if (! empty($form)) {
              $view = 'public.forms::' . Core\Secure::staticHash($form->user_id) . '.' . $local_domain . '.' . $variant . '.index';
            } else {
              return response()->view('layouts.simple-message', ['icon' => '&#xE8F5;', 'msg' => trans('global.form_not_published')], 404);
            }
          } else {
            $form = Models\Form::where('id', $form_id)->first();

            if (! empty($form)) {

              // Redirect if custom domain is set, but local domain is called
              if ($form->local_domain == request()->segment(2) && $form->domain != '') {
                return redirect('http://' . $form->domain, 301);
              }
  
              $view = 'public.forms::' . Core\Secure::staticHash($form->user_id) . '.' . $local_domain . '.' . $variant . '.published.index';
            } else {
              return response()->view('layouts.simple-message', ['icon' => '&#xE8F5;', 'msg' => trans('global.form_not_published')], 404);
            }
          }

          try {
            $template = view($view);
          } catch(\Exception $e) {
            return response()->view('layouts.simple-message', ['icon' => '&#xE8F5;', 'msg' => trans('global.form_not_published')], 404);
          }

          // Stats
          if (! $preview && Core\Secure::userId() != $form->user_id) {
            FunctionsController::addStat($form);
          }

          libxml_use_internal_errors(true);
          $dom = \phpQuery::newDocumentHTML($template);
          \phpQuery::selectDocument($dom);

          // Add sl
          $sl_f = Core\Secure::array2string(['form_id' => $form->id]);
          $sl_lp = request()->get('sl_lp', '');

          pq('head')->find('script[src]:first')->before(PHP_EOL . '<script src="' . url('assets/translations?lang=' . $form->language) . '"></script>');
          pq('head')->find('script[src]:first')->before(PHP_EOL . '<script>var sl_f = "' . $sl_f . '";var sl_lp = "' . $sl_lp . '";</script>');
          //pq('head')->prepend(PHP_EOL . '<script>var sl_f = "' . $sl . '";</script>');

          // Beautify html
          $html = Core\Parser::beautifyHtml($dom);

          return response($html, 200);
        } else {
          return response()->view('layouts.simple-message', ['icon' => '&#xE14B;', 'msg' => trans('global.form_not_found')], 404);
        }
      } else {
        return response()->view('layouts.simple-message', ['icon' => '&#xE14B;', 'msg' => trans('global.form_not_found')], 404);
      }
    }

    /**
     * Form editor iframe
     */
    public function editorFrame()
    {
      $sl = request()->input('sl', '');

      if($sl != '') {
        $qs = Core\Secure::string2array($sl);

        if (isset($qs['form_id'])) {
          $form = Models\Form::where('user_id', Core\Secure::userId())->where('id', $qs['form_id'])->first();

          if (! empty($form)) {
            $url = url('f/edit/' . Core\Secure::staticHash($qs['form_id'], true));

            return view('forms::editor', compact('url', 'form'));
          }
        }
      }
    }

    /**
     * Form editor
     */
    public function editor($local_domain)
    {
      if(isset($local_domain) && $local_domain != '') {

        $form_id = Core\Secure::staticHashDecode($local_domain, true);

        if (is_numeric($form_id)) {

          $variant = 1;

          $form = Models\Form::where('user_id', Core\Secure::userId())->where('id', $form_id)->first();

          if (empty($form)) {
            return response()->view('errors.404', [], 404);
          }

          $sl = Core\Secure::array2string(['form_id' => $form->id]);

          $published_url = ($form->domain != '') ? '//' . $form->domain : url('f/' . $form->local_domain);

          $view = 'public.forms::' . Core\Secure::staticHash($form->user_id) . '.' . $local_domain . '.' . $variant . '.index';

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
          pq('head')->find('script[src]:first')->before(PHP_EOL . '<script class="-x-editor-asset" src="' . url('assets/translations?lang=' . $form->language) . '&editor=1"></script>');
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

          return response($html, 200);
        }
      }
    }

    /**
     * Form post
     */
    public function formPost()
    {
      $response = [
        'success' => false,
        'title' => trans('forms::global.unknown_error'),
        'text' => trans('forms::global.unknown_error_text')
      ];

      $f = request()->get('f', '');
      $sl_lp = request()->get('sl_lp', '');
      $sl_f = request()->get('sl_f', '');

      if ($f != '' && isset($f['f']) && isset($f['sl_f']) && $f['sl_f'] != '') {
        $qs_f = Core\Secure::string2array($f['sl_f']);

        if (isset($qs_f['form_id'])) {

          $custom_vars = (isset($f['c'])) ? $f['c'] : [];
          $form_vars = $f['f'];

          if (isset($form_vars['email']) && $form_vars['email'] != '') {

            // Get landing page info if available
            $page = false;

            if ($f['sl_lp'] != '') {
              $qs_lp = Core\Secure::string2array($f['sl_lp']);
              $page = \Modules\LandingPages\Http\Models\Page::where('id', $qs_lp['landing_page_id'])->first();
            }

            // Post form
            $form = Models\Form::where('id', $qs_f['form_id'])->first();

            if (! empty($form)) {
              // Check entry limitation
              $entries_limit = $form->user->plan->limitations['forms']['max_entries'];
              $current_amount_of_entries = \DB::table('form_entries')->where('user_id', $form->user_id)->count();

              if ($entries_limit > 0 && $current_amount_of_entries >= $entries_limit) {
                $response = [
                  'success' => false,
                  'title' => trans('forms::global.rate_limit_error'),
                  'text' => trans('global.account_limit_reached')
                ];
                return response()->json($response);
              }

              // Check if this form belongs to the logged in user
              if (Core\Secure::userId() != $form->user_id) {
                $inserted = FunctionsController::addEntry($form, $form_vars, $custom_vars, $page);

                if ($inserted === false) {
                  $response = [
                    'success' => false,
                    'title' => trans('forms::global.rate_limit_error'),
                    'text' => trans('forms::global.rate_limit_error_text')
                  ];
                  return response()->json($response);
                }
              } else {
                $response = [
                  'success' => true,
                  'title' => trans('forms::global.demo'),
                  'text' => trans('forms::global.demo_text')
                ];
                return response()->json($response);
              }
            }
          }

          // Process response
          $after_submit = (isset($form->meta['after_submit'])) ? $form->meta['after_submit'] : 'message';

          $title = (isset($form->meta['title'])) ? $form->meta['title'] : trans('forms::global.thank_you_title');
          $text = (isset($form->meta['text'])) ? $form->meta['text'] : trans('forms::global.thank_you_text');

          if ($after_submit == 'url' && isset($form->meta['url']) && $form->meta['url'] != '') {
            return response()->json([
              'success' => true,
              'redir' => $form->meta['url']
            ]);
          }

          if ($after_submit == 'lp' && isset($form->meta['landing_page']) && $form->meta['landing_page'] != '') {
            $site = \Modules\LandingPages\Http\Models\Site::where('id', $form->meta['landing_page'])->first();

            return response()->json([
              'success' => true,
              'redir' => $site->pages->first()->url()
            ]);
          }

          // Else response with modal
          return response()->json([
            'success' => true,
            'title' => $title,
            'text' => $text
          ]);
        }
      }
      return response()->json($response);
    }

    /**
     * Forms backend main
     */
    public function index()
    {
      $order = request()->input('order', '');
      $cookie = null;

      if ($order != '') {
        $cookie = \Cookie::queue('f_order', $order, 60 * 24 * 7 * 4 * 6);
        
      } else {
        $order = request()->cookie('f_order', 'new_first');
      }

      switch($order) {
        case 'new_first': $order_column = 'created_at'; $order_by = 'desc'; break;
        case 'old_first': $order_column = 'created_at'; $order_by = 'asc'; break;
        case 'high_converting_first': $order_column = 'conversions'; $order_by = 'desc'; break;
        case 'low_converting_first': $order_column = 'conversions'; $order_by = 'asc'; break;
        case 'most_visited_first': $order_column = 'visits'; $order_by = 'desc'; break;
        case 'least_visited_first': $order_column = 'visits'; $order_by = 'asc'; break;
        default: $order_column = 'created_at'; $order_by = 'desc';
      }

      $forms = Models\Form::where('user_id', Core\Secure::userId())->where('funnel_id', Core\Secure::funnelId())->orderBy($order_column, $order_by)->get();

      if (count($forms) == 0) {
        return $this->create();
      } else {
        return view('forms::overview', compact('forms', 'order'))->withCookie($cookie);
      }
    }

    /**
     * Form preview
     */
    public function previewTemplate($template)
    {
      $template = view('template.forms::' . $template . '.index');

      libxml_use_internal_errors(true);
      $dom = \phpQuery::newDocumentHTML($template);
      \phpQuery::selectDocument($dom);

      // Add demo
      pq('head')->find('script[src]:first')->before(PHP_EOL . '<script class="-x-editor-asset" src="' . url('assets/translations?lang=' . \App::getLocale()) . '&editor=1"></script>');
      pq('head')->find('script[src]:first')->before(PHP_EOL . '<script>var lf_demo = true;</script>');

      $html = Core\Parser::beautifyHtml($dom);

      return response($html, 200);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
      $categories = FunctionsController::getCategories();

      return view('forms::create', compact('categories'));
    }

    /**
     * Create a new form step 2
     */
    public function createCategory($category)
    {
      $templates = FunctionsController::getTemplatesByCategory($category);

      return view('forms::create-category', compact('category', 'templates'));
    }

    /**
     * Create a form and return redir url
     */
    public function createForm(Request $request)
    {
      $template = $request->input('template', '');
      $name = $request->input('name', '');
      $name = substr($name, 0, 200);

      // Verify limit
      $current_count = Models\Form::where('user_id', '=', Core\Secure::userId())->count();
      $current_count_limit = \Auth::user()->plan->limitations['forms']['max'];

      if ($current_count >= $current_count_limit) {
        return response()->json([
          'type' => 'error', 
          'msg' => trans('global.account_limit_reached'),
          'reset' => false
        ]);
      }

      $template_path = base_path('../templates/forms/');

      if (\File::exists($template_path . $template . '/index.blade.php')) {
        // Create form
        $form = new Models\Form;

        $form->user_id = Core\Secure::userId();
        $form->funnel_id = Core\Secure::funnelId();
        $form->name = $name;
        $form->language = auth()->user()->language;
        $form->timezone = auth()->user()->timezone;
        $form->save();

        $form_id = $form->id;
        $local_domain = Core\Secure::staticHash($form_id, true);

        $form->local_domain = $local_domain;
        $form->save();

        // Create directory with files
        $storage_root = 'forms/form/' . Core\Secure::staticHash(Core\Secure::userId()) . '/' . $local_domain;

        // Get template HTML and replace title
        $html = view('template.forms::' . $template . '.index');

        // Suppress libxml errors
        // Resolves an issue with some servers.
        libxml_use_internal_errors(true);

        // Create a new PHPQuery object to manipulate
        // the DOM in a similar way as jQuery.
        $html = \phpQuery::newDocumentHTML($html);
        \phpQuery::selectDocument($html);

        // Update page
        pq('title')->text($name);
        //pq('head')->find('title')->after('<link rel="icon" type="image/x-icon" href="' . url('public/' . $storage_root . '/favicon.ico') . '">');
        //pq('head')->find('title')->after('<meta name="description" content="">');

        //$html = str_replace('</section><section', "</section>\n\n<section", $html);
        $html = str_replace(url('/'), '', $html);

        // Beautify html
        $html = Core\Parser::beautifyHtml($html);

        $variant = 1;

        $storage_root_full = $storage_root . '/' . $variant;

        \Storage::disk('public')->makeDirectory($storage_root_full . '/' . date('Y-m-d-H-i-s'));
        \Storage::disk('public')->put($storage_root_full . '/' . date('Y-m-d-H-i-s') . '/index.blade.php', $html);
        \Storage::disk('public')->put($storage_root_full . '/index.blade.php', $html);

        $redir = urlencode(Core\Secure::array2string(['form_id' => $form_id]));
      } else {
        $redir = '#';
      }

      return response()->json(['redir' => $redir]);
    }

    /**
     * Save form
     */
    public function saveForm(Request $request)
    {
      $sl = $request->input('sl', '');
      $html = $request->input('html', '');

      if($sl != '') {
        $qs = Core\Secure::string2array($sl);

        $form_id = $qs['form_id'];
        $form = Models\Form::where('user_id', Core\Secure::userId())->where('id', $form_id)->first();

        $variant = 1;

        // Update files
        $storage_root = 'forms/form/' . Core\Secure::staticHash(Core\Secure::userId()) . '/' . Core\Secure::staticHash($form->id, true) . '/' . $variant;

        $html = str_replace(url('/'), '', $html);

        // Beautify html
        $html = Core\Parser::beautifyHtml($html);

        \Storage::disk('public')->makeDirectory($storage_root . '/' . date('Y-m-d-H-i-s'));
        \Storage::disk('public')->put($storage_root . '/' . date('Y-m-d-H-i-s') . '/index.blade.php', $html);
        \Storage::disk('public')->put($storage_root . '/index.blade.php', $html);

        // Limit history
        $limit = 11;
        $saves = \Storage::disk('public')->directories($storage_root);

        usort($saves, function ($dir1, $dir2) {
          return $dir2 <=> $dir1;
        });

        if (count($saves) > $limit) {
          for($i = $limit; $i < count($saves); $i++) {
            \Storage::disk('public')->deleteDirectory($saves[$i]);
          }
        }

        $response = ['success' => true, 'msg' => trans('javascript.save_succes')];
      } else {
        $response = ['success' => false, 'msg' => 'An error occured'];
      }

      return response()->json($response);
    }

    /**
     * Publish form
     */
    public function publishForm(Request $request)
    {
      $sl = $request->input('sl', '');
      $html = $request->input('html', '');

      if($sl != '') {
        $qs = Core\Secure::string2array($sl);

        $form_id = $qs['form_id'];
        $form = Models\Form::where('user_id', Core\Secure::userId())->where('id', $form_id)->first();

        $variant = 1;

        // Update files
        $storage_root = 'forms/form/' . Core\Secure::staticHash(Core\Secure::userId()) . '/' . Core\Secure::staticHash($form->id, true) . '/' . $variant;

        $html = str_replace(url('/'), '', $html);

        // Beautify html
        $html = Core\Parser::beautifyHtml($html);

        \Storage::disk('public')->makeDirectory($storage_root . '/' . date('Y-m-d-H-i-s'));
        \Storage::disk('public')->put($storage_root . '/' . date('Y-m-d-H-i-s') . '/index.blade.php', $html);
        \Storage::disk('public')->put($storage_root . '/index.blade.php', $html);
        \Storage::disk('public')->put($storage_root . '/published/index.blade.php', $html);

        // Limit history
        $limit = 11;
        $saves = \Storage::disk('public')->directories($storage_root);

        usort($saves, function ($dir1, $dir2) {
          return $dir2 <=> $dir1;
        });

        if (count($saves) > $limit) {
          for($i = $limit; $i < count($saves); $i++) {
            \Storage::disk('public')->deleteDirectory($saves[$i]);
          }
        }

        $response = ['success' => true, 'msg' => trans('javascript.publish_succes')];
      } else {
        $response = ['success' => false, 'msg' => 'An error occured'];
      }

      return response()->json($response);
    }

    /**
     * Unpublish form
     */
    public function unpublishForm(Request $request)
    {
      $sl = $request->input('sl', '');

      if($sl != '') {
        $qs = Core\Secure::string2array($sl);

        $form_id = $qs['form_id'];
        $form = Models\Form::where('user_id', Core\Secure::userId())->where('id', $form_id)->first();

        $variant = 1;

        // Update files
        $storage_root = 'forms/form/' . Core\Secure::staticHash(Core\Secure::userId()) . '/' . Core\Secure::staticHash($form->id, true) . '/' . $variant;

        \Storage::disk('public')->deleteDirectory($storage_root . '/published');

        $response = ['success' => true, 'msg' => trans('javascript.unpublish_succes')];
      } else {
        $response = ['success' => false, 'msg' => 'An error occured'];
      }

      return response()->json($response);
    }

    /**
     * Delete form
     */
    public function deleteForm()
    {
      $sl = request()->input('sl', '');

      if($sl != '') {
        $qs = Core\Secure::string2array($sl);
        $form_id = $qs['form_id'];
        if (is_numeric($form_id)) {
          // Delete records
          Models\Form::where('user_id', Core\Secure::userId())->where('id', $form_id)->delete();

          // Delete records
          //$tbl_name = 'x_form_entries_' . Core\Secure::userId();
          //\DB::table($tbl_name)->where('form_id', $form_id)->delete();
  
          // Delete files
          $storage_root = 'forms/form/' . Core\Secure::staticHash(Core\Secure::userId()) . '/' . Core\Secure::staticHash($form_id, true);
          \Storage::disk('public')->deleteDirectory($storage_root);

          return response()->json(['success' => true]);
        }
      }
    }

    /**
     * Form source editor
     */
    public function sourceEditor()
    {
      $sl = request()->input('sl', '');

      if($sl != '') {
        $qs = Core\Secure::string2array($sl);

        if (isset($qs['form_id'])) {
          $form = Models\Form::where('user_id', Core\Secure::userId())->where('id', $qs['form_id'])->first();

          $variant = 1;

          // Get html
          $storage_root = 'forms/form/' . Core\Secure::staticHash(Core\Secure::userId()) . '/' . Core\Secure::staticHash($form->id, true) . '/' . $variant;

          // Beautify html
          $html = \Storage::disk('public')->get($storage_root . '/index.blade.php');

          if (! empty($form)) {
            return view('forms::source', compact('form', 'sl', 'html'));
          }
        }
      }
    }

    /**
     * Form source editor post
     */
    public function postSourceEditor()
    {
      $sl = request()->input('sl', '');
      $html = request()->input('html', '');
      $publish = (boolean) request()->input('publish', false);

      if($sl != '') {
        $qs = Core\Secure::string2array($sl);

        if (isset($qs['form_id'])) {
          $form = Models\Form::where('user_id', Core\Secure::userId())->where('id', $qs['form_id'])->first();

          // Sanitize html
          $html = preg_replace('/<\\?.*(\\?>|$)/Us', '', $html);
          $html = preg_replace('/{{[^[]*}}/Us', '', $html);

          //$html = \Storage::disk('public')->get($storage_root . '/index.blade.php');

          if (! empty($form)) {
            // Save html
            $variant = 1;
            $storage_root = 'forms/form/' . Core\Secure::staticHash(Core\Secure::userId()) . '/' . Core\Secure::staticHash($form->id, true) . '/' . $variant;

            // Beautify html
            $html = Core\Parser::beautifyHtml($html);

            \Storage::disk('public')->makeDirectory($storage_root . '/' . date('Y-m-d-H-i-s'));
            \Storage::disk('public')->put($storage_root . '/' . date('Y-m-d-H-i-s') . '/index.blade.php', $html);
            \Storage::disk('public')->put($storage_root . '/index.blade.php', $html);

            if ($publish) {
              \Storage::disk('public')->put($storage_root . '/published/index.blade.php', $html);
            }

            $msg = ($publish) ? trans('javascript.publish_succes'): trans('javascript.save_succes');

            return response()->json(['success' => true, 'msg' => $msg]);
          }
        }
      }
    }

    /**
     * Form settings
     */
    public function editorModalSettings(Request $request)
    {
      $sl = $request->input('sl', '');

      if ($sl != '') {
        $qs = Core\Secure::string2array($sl);
        $form_id = $qs['form_id'];

        if (is_numeric($form_id)) {
          $sites = \Modules\LandingPages\Http\Models\Site::where('user_id', Core\Secure::userId())->where('funnel_id', Core\Secure::funnelId())->orderBy('name', 'asc')->get();
          $form = Models\Form::where('user_id', Core\Secure::userId())->where('id', $qs['form_id'])->first();

          return view('landingpages::modals.form-settings', compact('sites', 'form', 'sl'));
        }
      }
    }

    /**
     * Post form settings
     */
    public function editorPostSettings(Request $request)
    {
      $sl = $request->input('sl', '');
      $after_submit = $request->input('after_submit', 'message');
      $title = $request->input('title', '');
      $text = $request->input('text', '');
      $landing_page = $request->input('landing_page', '');
      $url = $request->input('url', '');

      $input = array(
        'after_submit' => $after_submit,
        'title' => $title,
        'text' => $text,
        'landing_page' => $landing_page,
        'url' => $url
      );

      $rules = array(
        'title' => 'required|max:32',
        'text' => 'required|max:164',
        'url' => ($after_submit == 'url') ? 'required|url' : 'nullable|url',
        'landing_page' => ($after_submit == 'lp') ? 'required' : 'nullable'
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
        $form_id = $qs['form_id'];

        if (is_numeric($form_id)) {
          $form = Models\Form::where('user_id', Core\Secure::userId())->where('id', $qs['form_id'])->first();
          $form->meta = $input;
          $form->save();

          return response()->json([
            'type' => 'success', 
            'fn' => 'formSaved'
          ]);
        }
      }
    }

    /**
     * Form SEO
     */
    public function editorModalSeo(Request $request)
    {
      $sl = $request->input('sl', '');

      if ($sl != '') {
        $qs = Core\Secure::string2array($sl);
        $form_id = $qs['form_id'];

        if (is_numeric($form_id)) {
          $form = Models\Form::where('user_id', Core\Secure::userId())->where('id', $qs['form_id'])->first();

          return view('landingpages::modals.seo', compact('form', 'sl'));
        }
      }
    }

    /**
     * Post form SEO
     */
    public function editorPostSeo(Request $request)
    {
      $sl = $request->input('sl', '');
      $name = $request->input('name', '');
      $name = substr($name, 0, 200);

      if ($sl != '') {
        $qs = Core\Secure::string2array($sl);
        $form_id = $qs['form_id'];

        if (is_numeric($form_id)) {
          $form = Models\Form::where('user_id', Core\Secure::userId())->where('id', $qs['form_id'])->first();
          $form->name = $name;
          $form->save();

          return response()->json(['success' => true]);
        }
      }
    }

    /**
     * Form design
     */
    public function editorModalDesign(Request $request)
    {
      $sl = $request->input('sl', '');

      if ($sl != '') {
        $qs = Core\Secure::string2array($sl);
        $form_id = $qs['form_id'];

        if (is_numeric($form_id)) {
          $form = Models\Form::where('user_id', Core\Secure::userId())->where('id', $qs['form_id'])->first();

          return view('landingpages::modals.design', compact('form', 'sl'));
        }
      }
    }

    /**
     * Post form design
     */
    public function editorPostDesign(Request $request)
    {
      $sl = $request->input('sl', '');
      $name = $request->input('name', '');
      $name = substr($name, 0, 200);

      if ($sl != '') {
        $qs = Core\Secure::string2array($sl);
        $form_id = $qs['form_id'];

        if (is_numeric($form_id)) {
          $form = Models\Form::where('user_id', Core\Secure::userId())->where('id', $qs['form_id'])->first();
          $form->name = $name;
          $form->save();

          return response()->json(['success' => true]);
        }
      }
    }
}
