<?php

namespace Modules\LandingPages\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use \Platform\Controllers\Core;
use Modules\LandingPages\Http\Models;

class LandingPagesController extends Controller
{
    /**
     * Landing page public home
     */
    public function homePage($local_domain)
    {
      if(isset($local_domain) && $local_domain != '') {

        $preview = (boolean) request()->input('preview', false);
        $landing_page_id = Core\Secure::staticHashDecode($local_domain, true);

        if (is_numeric($landing_page_id)) {

          $variant = 1;

          if ($preview) {
            $page = Models\Page::where('user_id', Core\Secure::userId())->where('id', $landing_page_id)->first();

            if (! empty($page)) {
              $view = 'public.landingpages::' . Core\Secure::staticHash($page->user_id) . '.' . Core\Secure::staticHash($page->landing_site_id, true) . '.' . $local_domain . '.' . $variant . '.index';
            } else {
              return response()->view('errors.unpublished', ['route_name' => '404'], 404);
            }
          } else {
            $page = Models\Page::where('id', $landing_page_id)->first();

            if (! empty($page)) {
              $view = 'public.landingpages::' . Core\Secure::staticHash($page->user_id) . '.' . Core\Secure::staticHash($page->landing_site_id, true) . '.' . $local_domain . '.' . $variant . '.published.index';
            } else {
              return response()->view('errors.unpublished', ['route_name' => '404'], 404);
            }
          }

          try {
            $template = view($view);
          } catch(\Exception $e) {
            return response()->view('errors.unpublished', ['route_name' => '404'], 404);
          }

          // Stats
          if (! $preview && Core\Secure::userId() != $page->user_id) {
            // Fingerprint hash
            $hash = request()->ip() . '|' . date('Y-m-d-H');
            $hash = \Hash::make($hash);
          }

          return $template;
        } else {
          return response()->view('errors.404', ['route_name' => '404'], 404);
        }
      } else {
        return response()->view('errors.404', ['route_name' => '404'], 404);
      }
    }

    /**
     * Landing page editor
     */
    public function editor($local_domain)
    {
      if(isset($local_domain) && $local_domain != '') {

        $landing_page_id = Core\Secure::staticHashDecode($local_domain, true);

        if (is_numeric($landing_page_id)) {

          $variant = 1;

          $page = Models\Page::where('user_id', Core\Secure::userId())->where('id', $landing_page_id)->first();

          $sl = \Platform\Controllers\Core\Secure::array2string(['landing_page_id' => $page->id]);

          $published_url = ($page->site->domain != '') ? '//' . $page->site->domain : url('lp/' . $page->site->local_domain);

          $view = 'public.landingpages::' . Core\Secure::staticHash($page->user_id) . '.' . Core\Secure::staticHash($page->landing_site_id, true) . '.' . $local_domain . '.' . $variant . '.index';

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
          pq('head')->find('script[src]:last')->after(PHP_EOL . '<script class="-x-editor-asset" src="' . url('assets/js/scripts.editor.min.js') . '"></script>');

          // End stylesheet right before </head> to make
          // sure it overrides other stylesheets.
          pq('head')->append(PHP_EOL . '<link class="-x-editor-asset" rel="stylesheet" type="text/css" href="' . url('assets/css/styles.editor.min.css') . '" />');

          // Init editor
          pq('head')->append(PHP_EOL . '<script class="-x-editor-asset">$(function(){ lfInitEditor(); });</script>');

          //$dom = str_replace('</section><section', "</section>\n\n<section", $dom);

          return $dom;
        }
      }
    }

    /**
     * Landing page editor iframe
     */
    public function editorFrame()
    {
      $sl = request()->input('sl', '');

      if($sl != '') {
        $qs = Core\Secure::string2array($sl);

        if (isset($qs['landing_page_id'])) {
          $page = Models\Page::where('user_id', Core\Secure::userId())->where('id', $qs['landing_page_id'])->first();

          if (! empty($page)) {
            $url = url('lp/edit/' . Core\Secure::staticHash($qs['landing_page_id'], true));

            return view('landingpages::editor', compact('url', 'page'));
          }
        }
      }
    }

    /**
     * Landing pages backend main
     */
    public function index()
    {
      $sites = Models\Site::where('user_id', Core\Secure::userId())->orderBy('created_at', 'desc')->get();

      if (count($sites) == 0) {
        return $this->create();
      } else {
        return view('landingpages::overview', compact('sites'));
      }
    }

    /**
     * Landing page preview
     */
    public function previewTemplate($template)
    {
      return view('template.landingpages::' . $template . '.index');
    }

    /**
     * Create a new landing page step 1
     */
    public function create()
    {
      $categories = FunctionsController::getCategories();

      return view('landingpages::create', compact('categories'));
    }

    /**
     * Create a new landing page step 2
     */
    public function createCategory($category)
    {
      $templates = FunctionsController::getTemplatesByCategory($category);

      return view('landingpages::create-select-template', compact('category', 'templates'));
    }

    /**
     * Create a landing page and return redir url
     */
    public function createPage(Request $request)
    {
      $template = $request->input('template', '');
      $name = $request->input('name', '');
      $name = substr($name, 0, 200);

      $template_path = base_path('../templates/landingpages/');

      if (\File::exists($template_path . $template . '/config.php') && \File::exists($template_path . $template . '/index.blade.php')) {
        $config = include $template_path . $template . '/config.php';

        // First create site
        $site = new Models\Site;

        $site->user_id = Core\Secure::userId();
        $site->name = $name;
        $site->save();

        $site_id = $site->id;

        // Then, create page for site
        $page = new Models\Page;

        $page->user_id = Core\Secure::userId();
        $page->landing_site_id = $site_id;
        $page->name = $name;
        $page->template = $template;
        $page->type = $config['type'];
        $page->save();

        $local_domain = Core\Secure::staticHash($site_id, true);

        $site->local_domain = $local_domain;
        $site->save();

        // Finally, create directory with files
        $storage_root = 'landingpages/site/' . Core\Secure::staticHash(Core\Secure::userId()) . '/' . $local_domain;

        // Get template HTML and replace title
        $html = view('template.landingpages::' . $template . '.index');

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

        $storage_root_full = $storage_root . '/' . Core\Secure::staticHash($page->id, true) . '/' . $variant;

        \Storage::disk('public')->makeDirectory($storage_root_full . '/' . date('Y-m-d-H-i-s'));
        \Storage::disk('public')->put($storage_root_full . '/' . date('Y-m-d-H-i-s') . '/index.blade.php', $page_html);
        \Storage::disk('public')->put($storage_root_full . '/index.blade.php', $page_html);
        \Storage::disk('public')->put($storage_root . '/favicon.ico', \File::get($template_path . $template . '/favicon.ico'));

        $redir = Core\Secure::array2string(['landing_page_id' => $page->id]);
      } else {
        $redir = '#';
      }

      return response()->json(['redir' => $redir]);
    }

    /**
     * Save page
     */
    public function savePage(Request $request)
    {
      $sl = $request->input('sl', '');
      $page_html = $request->input('html', '');

      if($sl != '') {
        $qs = Core\Secure::string2array($sl);

        $landing_page_id = $qs['landing_page_id'];
        $page = Models\Page::where('user_id', Core\Secure::userId())->where('id', $landing_page_id)->first();

        $variant = 1;

        // Update files
        $storage_root = 'landingpages/site/' . Core\Secure::staticHash(Core\Secure::userId()) . '/' .  Core\Secure::staticHash($page->landing_site_id, true) . '/' . Core\Secure::staticHash($page->id, true) . '/' . $variant;

        $page_html = str_replace(url('/'), '', $page_html);

        \Storage::disk('public')->makeDirectory($storage_root . '/' . date('Y-m-d-H-i-s'));
        \Storage::disk('public')->put($storage_root . '/' . date('Y-m-d-H-i-s') . '/index.blade.php', $page_html);
        \Storage::disk('public')->put($storage_root . '/index.blade.php', $page_html);

        $response = ['success' => true, 'msg' => trans('javascript.save_succes')];
      } else {
        $response = ['success' => false, 'msg' => 'An error occured'];
      }

      return response()->json($response);
    }

    /**
     * Publish page
     */
    public function publishPage(Request $request)
    {
      $sl = $request->input('sl', '');
      $page_html = $request->input('html', '');

      if($sl != '') {
        $qs = Core\Secure::string2array($sl);

        $landing_page_id = $qs['landing_page_id'];
        $page = Models\Page::where('user_id', Core\Secure::userId())->where('id', $landing_page_id)->first();

        $variant = 1;

        // Update files
        $storage_root = 'landingpages/site/' . Core\Secure::staticHash(Core\Secure::userId()) . '/' .  Core\Secure::staticHash($page->landing_site_id, true) . '/' . Core\Secure::staticHash($page->id, true) . '/' . $variant;

        $page_html = str_replace(url('/'), '', $page_html);

        \Storage::disk('public')->makeDirectory($storage_root . '/' . date('Y-m-d-H-i-s'));
        \Storage::disk('public')->put($storage_root . '/' . date('Y-m-d-H-i-s') . '/index.blade.php', $page_html);
        \Storage::disk('public')->put($storage_root . '/index.blade.php', $page_html);
        \Storage::disk('public')->put($storage_root . '/published/index.blade.php', $page_html);

        $response = ['success' => true, 'msg' => trans('javascript.publish_succes')];
      } else {
        $response = ['success' => false, 'msg' => 'An error occured'];
      }

      return response()->json($response);
    }

    /**
     * Unpublish page
     */
    public function unpublishPage(Request $request)
    {
      $sl = $request->input('sl', '');

      if($sl != '') {
        $qs = Core\Secure::string2array($sl);

        $landing_page_id = $qs['landing_page_id'];
        $page = Models\Page::where('user_id', Core\Secure::userId())->where('id', $landing_page_id)->first();

        $variant = 1;

        // Update files
        $storage_root = 'landingpages/site/' . Core\Secure::staticHash(Core\Secure::userId()) . '/' .  Core\Secure::staticHash($page->landing_site_id, true) . '/' . Core\Secure::staticHash($page->id, true) . '/' . $variant;

        \Storage::disk('public')->deleteDirectory($storage_root . '/published');

        $response = ['success' => true, 'msg' => trans('javascript.unpublish_succes')];
      } else {
        $response = ['success' => false, 'msg' => 'An error occured'];
      }

      return response()->json($response);
    }

    /**
     * Delete landing
     */
    public function deletePage()
    {
      $sl = request()->input('sl', '');

      if($sl != '') {
        $qs = Core\Secure::string2array($sl);
        $site_id = $qs['landing_site_id'];
        if (is_numeric($site_id)) {
          // Delete records
          Models\Site::where('user_id', Core\Secure::userId())->where('id', $site_id)->delete();

          // Delete files
          $storage_root = 'landingpages/site/' . Core\Secure::staticHash(Core\Secure::userId()) . '/' . Core\Secure::staticHash($site_id, true);
          \Storage::disk('public')->deleteDirectory($storage_root);

          return response()->json(['success' => true]);
        }
      }
    }

    /**
     * Editor modal to configure background (color, image, etc)
     */
    public function editorModalBackground(Request $request)
    {
      $el_class = $request->input('el_class', '');
      $bg_img = (boolean) $request->input('bg_img', false);
      $bg_color = (boolean) $request->input('bg_color', false);
      $bg_gradient = (boolean) $request->input('bg_gradient', false);

      return view('landingpages::modals.background', compact('el_class', 'bg_img', 'bg_color', 'bg_gradient'));
    }

    /**
     * Editor modal to insert a new block
     */
    public function editorModalInsertBlock(Request $request)
    {
      $el_class = $request->input('el_class', '');
      $position = $request->input('position', 'below');

      // Get all categories
      $categories = FunctionsController::getBlockCategories();

      return view('landingpages::modals.insert-block', compact('el_class', 'position', 'categories'));
    }

    /**
     * Editor modal to select a block
     */
    public function editorModalInsertBlockSelect(Request $request)
    {
      $el_class = $request->input('el_class', '');
      $position = $request->input('position', 'below');
      $category = $request->input('c', '');
      $category_name = explode('-', $category)[1];

      $blocks = FunctionsController::getBlocksByCategory($category);

      return view('landingpages::modals.insert-block-select', compact('el_class', 'position', 'blocks', 'category', 'category_name'));
    }

    /**
     * Block preview
     */
    public function editorBlockPreview(Request $request)
    {
      $category = $request->input('c', '');
      $block = $request->input('b', '');

      $view = \Cache::remember('landingpages_block_preview_' . $category . '_' . $block, (60 * 24 * 7), function () use($category, $block) {

        $html = view('block.landingpages::' . $category . '.' . $block);
        return view('landingpages::block-preview', compact('html'))->render();
      });

      return $view;
    }

    /**
     * Editor modal to configure link
     */
    public function editorModalLink(Request $request)
    {
      $el_class = $request->input('el_class', '');
      $color = (boolean) $request->input('color', false);

      return view('landingpages::modals.link', compact('el_class', 'color'));
    }

    /**
     * Editor modal to configure list
     */
    public function editorModalList(Request $request)
    {
      $el_class = $request->input('el_class', '');
      $repeat = $request->input('repeat', 'a');

      return view('landingpages::modals.list', compact('el_class', 'repeat'));
    }

    /**
     * Editor modal to configure image
     */
    public function editorModalImage(Request $request)
    {
      $el_class = $request->input('el_class', '');
      $link = (boolean) $request->input('link', false);

      return view('landingpages::modals.image', compact('el_class', 'link'));
    }

    /**
     * Editor modal to configure icon
     */
    public function editorModalIcon(Request $request)
    {
      $el_class = $request->input('el_class', '');

      return view('landingpages::modals.icon', compact('el_class'));
    }

    /**
     * Editor button picker
     */
    public function editorPickerButton(Request $request)
    {
      $input_id = $request->input('input_id', '');
      $selected = $request->input('selected', '');

      return view('landingpages::pickers.button', compact('input_id', 'selected'));
    }

    /**
     * View QR
     */
    public function editorModalQr(Request $request)
    {
      $url = $request->input('url', '');

      return view('landingpages::modals.qr', compact('url'));
    }
}
