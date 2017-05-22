<?php

namespace Modules\Forms\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use \Platform\Controllers\Core;
use Modules\Forms\Http\Models;

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

          if ($preview) {
            $form = Models\Form::where('user_id', Core\Secure::userId())->where('id', $form_id)->first();

            if (! empty($form)) {
              $view = 'public.forms::' . Core\Secure::staticHash($form->user_id) . '.' . $local_domain . '.' . $variant . '.index';
            } else {
              return response()->view('errors.unpublished', ['msg' => trans('global.form_not_published')], 404);
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
              return response()->view('errors.unpublished', ['msg' => trans('global.form_not_published')], 404);
            }
          }

          try {
            $template = view($view);
          } catch(\Exception $e) {
            return response()->view('errors.unpublished', ['msg' => trans('global.form_not_published')], 404);
          }

          // Stats
          if (! $preview && Core\Secure::userId() != $form->user_id) {
            FunctionsController::addStat($form);
          }

          libxml_use_internal_errors(true);
          $dom = \phpQuery::newDocumentHTML($template);
          \phpQuery::selectDocument($dom);

          // Add sl
          $sl = Core\Secure::array2string(['form_id' => $form->id]);

          pq('head')->find('script[src]:first')->before(PHP_EOL . '<script src="' . url('assets/translations?lang=' . $form->language) . '"></script>');
          pq('head')->find('script[src]:first')->before(PHP_EOL . '<script>var sl_f = "' . $sl . '";</script>');
          //pq('head')->prepend(PHP_EOL . '<script>var sl_f = "' . $sl . '";</script>');

          // Beautify html
          $html = Core\Parser::beautifyHtml($dom);

          return $html;
        } else {
          return response()->view('errors.404', [], 404);
        }
      } else {
        return response()->view('errors.404', [], 404);
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

          return $html;
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
              $page = Models\Page::where('id', $qs_lp['landing_page_id'])->first();
            }

            // Post form
            $form = Models\Form::where('id', $qs_f['form_id'])->first();

            if (! empty($form)) {
              // Check if this form belongs to the logged in user
              if (Core\Secure::userId() != $form->user_id) {
                dd($custom_vars);
                //$inserted = FunctionsController::addEntry($form, $form_vars, $custom_vars, $page);

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
                  'success' => false,
                  'title' => trans('forms::global.demo'),
                  'text' => trans('forms::global.demo_text')
                ];
                return response()->json($response);
              }
            }
          }

          $response = [
            'success' => true,
            'title' => trans('forms::global.thank_you_title'),
            'text' => trans('forms::global.thank_you_text')
          ];
        }
      }
      return response()->json($response);
    }

    /**
     * Forms backend main
     */
    public function index()
    {
      $forms = Models\Form::where('user_id', Core\Secure::userId())->orderBy('created_at', 'desc')->get();

      if (count($forms) == 0) {
        return $this->create();
      } else {
        return view('forms::overview', compact('forms'));
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
      pq('head')->find('script[src]:first')->before(PHP_EOL . '<script>var lf_demo = true;</script>');

      $html = Core\Parser::beautifyHtml($dom);

      return $html;
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

      $template_path = base_path('../templates/forms/');

      if (\File::exists($template_path . $template . '/index.blade.php')) {
        // Create form
        $form = new Models\Form;

        $form->user_id = Core\Secure::userId();
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

        $redir = Core\Secure::array2string(['form_id' => $form_id]);
      } else {
        $redir = '#';
      }

      return response()->json(['redir' => $redir]);
    }

    /**
     * Landing page editor iframe
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
          $tbl_name = 'x_form_entries_' . Core\Secure::userId();
          \DB::table($tbl_name)->where('form_id', $form_id)->delete();
  
          // Delete files
          $storage_root = 'forms/form/' . Core\Secure::staticHash(Core\Secure::userId()) . '/' . Core\Secure::staticHash($form_id, true);
          \Storage::disk('public')->deleteDirectory($storage_root);

          return response()->json(['success' => true]);
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
