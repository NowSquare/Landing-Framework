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
            //FunctionsController::addStat($form);
          }

          return $template;
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

          $published_url = ($form->domain != '') ? '//' . $form->domain : url('lp/' . $form->local_domain);

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
          pq('head')->find('script[src]:last')->before(PHP_EOL . '<script class="-x-editor-asset">var lf_published_url = "' . $published_url . '";var lf_sl = "' . $sl . '";var lf_csrf_token = "' . csrf_token() . '";</script>');
          pq('head')->find('script[src]:last')->after(PHP_EOL . '<script class="-x-editor-asset" src="' . url('assets/javascript?lang=' . \App::getLocale()) . '"></script>');
          pq('head')->find('script[src]:last')->after(PHP_EOL . '<script class="-x-editor-asset" src="' . url('assets/js/scripts.editor.min.js?v=' . config('version.editor')) . '"></script>');

          // End stylesheet right before </head> to make
          // sure it overrides other stylesheets.
          pq('head')->append(PHP_EOL . '<link class="-x-editor-asset" rel="stylesheet" type="text/css" href="' . url('assets/css/styles.editor.min.css?v=' . config('version.editor')) . '" />');

          // Init editor
          pq('head')->append(PHP_EOL . '<script class="-x-editor-asset">$(function(){ lfInitEditor(\'forms\'); });</script>');

          //$dom = str_replace('</section><section', "</section>\n\n<section", $dom);

          return $dom;
        }
      }
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
      return view('template.forms::' . $template . '.index');
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
        $form = new Models\form;

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
        $page_html = \phpQuery::newDocumentHTML($html);
        \phpQuery::selectDocument($page_html);

        // Update page
        pq('title')->text($name);
        pq('head')->find('title')->after('<link rel="icon" type="image/x-icon" href="' . url('public/' . $storage_root . '/favicon.ico') . '">');
        pq('head')->find('title')->after('<meta name="description" content="">');

        //$page_html = str_replace('</section><section', "</section>\n\n<section", $page_html);
        $page_html = str_replace(url('/'), '', $page_html);

        $variant = 1;

        $storage_root_full = $storage_root . '/' . $variant;

        \Storage::disk('public')->makeDirectory($storage_root_full . '/' . date('Y-m-d-H-i-s'));
        \Storage::disk('public')->put($storage_root_full . '/' . date('Y-m-d-H-i-s') . '/index.blade.php', $page_html);
        \Storage::disk('public')->put($storage_root_full . '/index.blade.php', $page_html);

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
}
