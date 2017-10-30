<?php

namespace Modules\LandingPages\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use \Platform\Controllers\Core;
use Modules\LandingPages\Http\Models;
use Embed\Embed;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class LandingPagesController extends Controller
{
    /**
     * Landing page public home
     */
    public function homePage($local_domain)
    {
      if(isset($local_domain) && $local_domain != '') {

        $preview = (boolean) request()->input('preview', false);

        $site = Models\Site::where('local_domain', $local_domain)->first();

        if (! empty($site)) {
          $landing_page_id = $site->pages->first()->id;

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

            $page = Models\Page::where('user_id', $user->id)->where('id', $landing_page_id)->first();

            if (! empty($page)) {
              $view = 'public.landingpages::' . Core\Secure::staticHash($page->user_id) . '.' . Core\Secure::staticHash($page->landing_site_id, true) . '.' . $local_domain . '.' . $variant . '.index';
            } else {
              return response()->view('layouts.simple-message', ['icon' => '&#xE8F5;', 'msg' => trans('global.page_not_published')], 404);
            }
          } else {
            $page = Models\Page::where('id', $landing_page_id)->first();

            if (! empty($page)) {

              // Redirect if custom domain is set, but local domain is called
              if (isset($page->site) && $page->site->local_domain == request()->segment(2) && $page->site->domain != '') {
                return redirect('http://' . $page->site->domain, 301);
              }
  
              $view = 'public.landingpages::' . Core\Secure::staticHash($page->user_id) . '.' . Core\Secure::staticHash($page->landing_site_id, true) . '.' . $local_domain . '.' . $variant . '.published.index';
            } else {
              return response()->view('layouts.simple-message', ['icon' => '&#xE8F5;', 'msg' => trans('global.page_not_published')], 404);
            }
          }

          try {
            $template = view($view);
          } catch(\Exception $e) {
            return response()->view('layouts.simple-message', ['icon' => '&#xE8F5;', 'msg' => trans('global.page_not_published')], 404);
          }

          // Stats
          if (! $preview && Core\Secure::userId() != $page->user_id) {
            FunctionsController::addStat($page);
          }

          libxml_use_internal_errors(true);
          $dom = \phpQuery::newDocumentHTML($template);
          \phpQuery::selectDocument($dom);

          // Add sl + translations
          $sl = Core\Secure::array2string(['landing_page_id' => $landing_page_id]);
          pq('head')->find('script[src]:first')->before(PHP_EOL . '<script src="' . url('assets/translations?lang=' . $page->site->language) . '"></script>');
          pq('head')->find('script[src]:first')->before(PHP_EOL . '<script>var sl_lp = "' . $sl . '";</script>');

          // Beautify html
          $html = Core\Parser::beautifyHtml($dom);

          return response($html, 200);
        } else {
          return response()->view('layouts.simple-message', ['icon' => '&#xE14B;', 'msg' => trans('global.page_not_found')], 404);
        }
      } else {
        return response()->view('layouts.simple-message', ['icon' => '&#xE14B;', 'msg' => trans('global.page_not_found')], 404);
      }
    }

    /**
     * Landing page editor
     */
    public function editor($local_domain)
    {
      if(isset($local_domain) && $local_domain != '') {

        $site = Models\Site::where('user_id', Core\Secure::userId())->where('local_domain', $local_domain)->first();

        if (! empty($site)) {
          $page = $site->pages->first();

          $variant = 1;

          $sl = Core\Secure::array2string(['landing_page_id' => $page->id]);

          $published_url = ($page->site->domain != '') ? '//' . $page->site->domain : url('lp/' . $page->site->local_domain);

          $view = 'public.landingpages::' . Core\Secure::staticHash($page->user_id) . '.' . Core\Secure::staticHash($page->landing_site_id, true) . '.' . $local_domain . '.' . $variant . '.index';

          // Put template html into variable.
          $template = view($view);

          // Suppress libxml errors, resolves an issue with some servers.
          libxml_use_internal_errors(true);

          // Create a new PHPQuery object to manipulate the DOM in a similar way as jQuery.
          $dom = \phpQuery::newDocumentHTML($template);
          \phpQuery::selectDocument($dom);

          // Insert scripts right after last js include to make sure jQuery and Bootstrap 4 js are included in template, while inline <script>'s can safely run below.
          pq('head')->find('script[src]:first')->before(PHP_EOL . '<script class="-x-editor-asset" src="' . url('assets/translations?lang=' . $page->site->language) . '&editor=1"></script>');
          pq('head')->find('script[src]:last')->before(PHP_EOL . '<script class="-x-editor-asset">var lf_published_url = "' . $published_url . '";var lf_sl = "' . $sl . '";var lf_csrf_token = "' . csrf_token() . '";</script>');
          pq('head')->find('script[src]:last')->after(PHP_EOL . '<script class="-x-editor-asset" src="' . url('assets/javascript?lang=' . app()->getLocale()) . '"></script>');
          pq('head')->find('script[src]:last')->after(PHP_EOL . '<script class="-x-editor-asset" src="' . url('assets/js/scripts.editor.min.js?v=' . config('version.editor')) . '"></script>');

          // End stylesheet right before </head> to make sure it overrides other stylesheets.
          pq('head')->append(PHP_EOL . '<link class="-x-editor-asset" rel="stylesheet" type="text/css" href="' . url('assets/css/styles.editor.min.css?v=' . config('version.editor')) . '" />');

          // Init editor
          pq('head')->append(PHP_EOL . '<script class="-x-editor-asset">$(function(){ lfInitEditor(); });</script>');

          //$dom = str_replace('</section><section', "</section>\n\n<section", $dom);

          // Beautify html
          $html = Core\Parser::beautifyHtml($dom);

          return response($html, 200);
          //return $html;
        } else {
          return response()->view('errors.404', [], 404);
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
     * Landing page source editor
     */
    public function sourceEditor()
    {
      $sl = request()->input('sl', '');

      if($sl != '') {
        $qs = Core\Secure::string2array($sl);

        if (isset($qs['landing_page_id'])) {
          $page = Models\Page::where('user_id', Core\Secure::userId())->where('id', $qs['landing_page_id'])->first();

          $variant = 1;

          // Get html
          $storage_root = 'landingpages/site/' . Core\Secure::staticHash(Core\Secure::userId()) . '/' .  Core\Secure::staticHash($page->landing_site_id, true) . '/' . Core\Secure::staticHash($page->id, true) . '/' . $variant;

          // Beautify html
          $html = \Storage::disk('public')->get($storage_root . '/index.blade.php');

          if (! empty($page)) {
            return view('landingpages::source', compact('page', 'sl', 'html'));
          }
        }
      }
    }

    /**
     * Landing page source editor post
     */
    public function postSourceEditor()
    {
      $sl = request()->input('sl', '');
      $html = request()->input('html', '');
      $publish = (boolean) request()->input('publish', false);

      if($sl != '') {
        $qs = Core\Secure::string2array($sl);

        if (isset($qs['landing_page_id'])) {
          $page = Models\Page::where('user_id', Core\Secure::userId())->where('id', $qs['landing_page_id'])->first();

          // Sanitize html
          $html = preg_replace('/<\\?.*(\\?>|$)/Us', '', $html);
          $html = preg_replace('/{{[^[]*}}/Us', '', $html);

          //$html = \Storage::disk('public')->get($storage_root . '/index.blade.php');

          if (! empty($page)) {
            // Save html
            $variant = 1;
            $storage_root = 'landingpages/site/' . Core\Secure::staticHash(Core\Secure::userId()) . '/' .  Core\Secure::staticHash($page->landing_site_id, true) . '/' . Core\Secure::staticHash($page->id, true) . '/' . $variant;

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
     * Landing pages backend main
     */
    public function index()
    {
      $order = request()->input('order', '');
      $cookie = null;

      if ($order != '') {
        $cookie = \Cookie::queue('lp_order', $order, 60 * 24 * 7 * 4 * 6);
        
      } else {
        $order = request()->cookie('lp_order', 'new_first');
      }

      switch($order) {
        case 'new_first': $order_column = 'created_at'; $order_by = 'desc'; break;
        case 'old_first': $order_column = 'created_at'; $order_by = 'asc'; break;
        case 'high_converting_first': $order_column = 'conversion'; $order_by = 'desc'; break;
        case 'low_converting_first': $order_column = 'conversion'; $order_by = 'asc'; break;
        case 'most_visited_first': $order_column = 'visits'; $order_by = 'desc'; break;
        case 'least_visited_first': $order_column = 'visits'; $order_by = 'asc'; break;
        default: $order_column = 'created_at'; $order_by = 'desc';
      }

      $sites = Models\Site::where('user_id', Core\Secure::userId())->where('funnel_id', Core\Secure::funnelId())->select('landing_sites.*')->addSelect(\DB::raw('((landing_sites.conversions / landing_sites.visits) * 100) as conversion'))->orderBy($order_column, $order_by)->get();

      if (count($sites) == 0) {
        return $this->create();
      } else {
        return view('landingpages::overview', compact('sites', 'order'))->withCookie($cookie);
      }
    }

    /**
     * Landing page preview
     */
    public function previewTemplate($template)
    {

      $template = view('template.landingpages::' . $template . '.index');

      libxml_use_internal_errors(true);
      $dom = \phpQuery::newDocumentHTML($template);
      \phpQuery::selectDocument($dom);

      // Add demo
      pq('head')->find('script[src]:first')->before(PHP_EOL . '<script>var lf_demo = true;</script>');
      pq('head')->find('script[src]:last')->after(PHP_EOL . '<script>$(function() { $("a.btn").on("click", function() { swal("' . trans('global.preview') . '");return false; }); });</script>');

      // Beautify html
      $html = Core\Parser::beautifyHtml($dom); // $template

      return response($html, 200);
      //return $html;
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

      return view('landingpages::create-category', compact('category', 'templates'));
    }

    /**
     * Create a landing page and return redir url
     */
    public function createPage(Request $request)
    {
      $template = $request->input('template', '');
      $name = $request->input('name', '');

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

      // Verify limit
      $current_count = Models\Page::where('user_id', '=', Core\Secure::userId())->count();
      $current_count_limit = \Auth::user()->plan->limitations['landingpages']['max'];

      if ($current_count > $current_count_limit) {
        return response()->json([
          'type' => 'error', 
          'msg' => trans('global.account_limit_reached'),
          'reset' => false
        ]);
      }

      $page = FunctionsController::createPage($template, $name);

      $redir = ($page !== false) ? urlencode(Core\Secure::array2string(['landing_page_id' => $page->id])) : '#';

      return response()->json(['redir' => $redir]);
    }

    /**
     * Save page
     */
    public function savePage(Request $request)
    {
      $sl = $request->input('sl', '');
      $html = $request->input('html', '');

      $save = FunctionsController::savePage($sl, $html);

      if ($save) {
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
      $html = $request->input('html', '');

      $publish = FunctionsController::savePage($sl, $html, true);

      if ($publish) {
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
          Models\Site::where('user_id', Core\Secure::userId())->where('id', $site_id)->first()->delete();

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
     * Create screenshots for all blocks
     */
    public function createBlockScreenshots()
    {
      set_time_limit(0);
      $lang = 'en';
      \App::setLocale($lang);

      $categories = FunctionsController::getBlockCategories();
      foreach ($categories as $category) {
        $cat_path = public_path() . '/blocks/landingpages/' . $category['dir'];

        $blocks = FunctionsController::getBlocksByCategory($category['dir']);
        $path = '';
        foreach ($blocks as $block) {
          $local_image = $cat_path . '/' . str_replace('.blade.php', '.' . $lang . '.png', $block['file']);

          if (! \File::exists($local_image)) {
            $url = 'http://prtscn.landingframework.test/grab?url=' . rawurlencode($block['preview'] . '&lang=' . $lang) . '&browser_width=1768&empty_cache=1';
            $remote_image = file_get_contents($url);
            \File::copy($remote_image, $local_image);
          }
        }
      }
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
      $lang = $request->input('lang', '');

      if ($lang != '') {
        \App::setLocale($lang);
      }

      //$view = \Cache::remember('landingpages_block_preview_' . $category . '_' . $block, 0, function () use($category, $block) {

        $html = view('block.landingpages::' . $category . '.' . $block);
        return view('landingpages::block-preview', compact('html'))->render();
      //});

      //return $view;
    }

    /**
     * Get block html
     */
    public function editorBlockHtml(Request $request)
    {
      $category = $request->input('c', '');
      $block = $request->input('b', '');
      $lang = $request->input('lang', '');

      if ($lang != '') {
        \App::setLocale($lang);
      }

      $html = view('block.landingpages::' . $category . '.' . $block);
      return response($html, 200);
      //return $html;
      //return view('landingpages::block-preview', compact('html'))->render();
    }

    /**
     * Editor modal to configure link
     */
    public function editorModalLink(Request $request)
    {
      $el_class = $request->input('el_class', '');
      $color = (boolean) $request->input('color', false);
      $submit = (boolean) $request->input('submit', false);
      $form = (boolean) $request->input('form', false);
      $vcard = (boolean) $request->input('vcard', false);
      $tab = 'url';
      if ($form) $tab = 'form';
      if ($vcard) $tab = 'vcard';

      if (\Gate::allows('limitation', 'forms.visible') && ! $submit) {
        $forms = \Modules\Forms\Http\Models\Form::where('user_id', Core\Secure::userId())->where('funnel_id', Core\Secure::funnelId())->orderBy('name', 'asc')->get();
      } else {
        $forms = null;
      }

      return view('landingpages::modals.link', compact('el_class', 'color', 'submit', 'tab', 'forms'));
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
     * Editor modal to configure countdown
     */
    public function editorModalCountdown(Request $request)
    {
      $el_class = $request->input('el_class', '');

      return view('landingpages::modals.countdown', compact('el_class'));
    }

    /**
     * Editor modal to configure form
     */
    public function editorModalForm(Request $request)
    {
      $el_class = $request->input('el_class', '');

      return view('landingpages::modals.form', compact('el_class'));
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
     * Editor modal to configure video
     */
    public function editorModalVideo(Request $request)
    {
      $el_class = $request->input('el_class', '');

      return view('landingpages::modals.video', compact('el_class'));
    }

    /**
     * Parse url to see if there's an embedable version
     */
    public function editorParseEmbed(Request $request)
    {
      $url = $request->input('url', '');

      if ($url != '') {

        try {
          $info = Embed::create($url);
          $code = $info->code;
          preg_match('/src="([^"]+)"/', $code, $match);
          $url = $match[1];
        } catch (\Embed\Exceptions\InvalidUrlException $e) {
          return response()->json([
            'success' => false, 
            'msg' => $e->getMessage()
          ]);
        }

        $response = ['success' => true, 'msg' => trans('landingpages::global.url_embed_success'), 'url' => $url];

        return response()->json($response);
      }
    }

    /**
     * Editor modal to configure iframe
     */
    public function editorModalFrame(Request $request)
    {
      $el_class = $request->input('el_class', '');

      return view('landingpages::modals.frame', compact('el_class'));
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
     * Editor modal to configure share settings
     */
    public function editorModalShare(Request $request)
    {
      $el_class = $request->input('el_class', '');

      return view('landingpages::modals.share', compact('el_class'));
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
     * Page SEO
     */
    public function editorModalSeo(Request $request)
    {
      $sl = $request->input('sl', '');

      if ($sl != '') {
        $qs = Core\Secure::string2array($sl);
        $page_id = $qs['landing_page_id'];

        if (is_numeric($page_id)) {
          $page = Models\Page::where('user_id', Core\Secure::userId())->where('id', $qs['landing_page_id'])->first();

          // Get page title & meta
          /*
          $variant = 1;
          $view = 'public.landingpages::' . Core\Secure::staticHash($page->user_id) . '.' . Core\Secure::staticHash($page->landing_site_id, true) . '.' . $page->site->local_domain . '.' . $variant . '.index';
          $template = view($view);
          libxml_use_internal_errors(true);
          $dom = \phpQuery::newDocumentHTML($template);
          \phpQuery::selectDocument($dom);

          $page_title = pq('title')->text();
          $page_description = pq('meta[name=description]')->attr('content');
          */

          return view('landingpages::modals.seo', compact('page', 'sl'));
        }
      }
    }

    /**
     * Post page SEO
     */
    public function editorPostSeo(Request $request)
    {
      $sl = $request->input('sl', '');
      $name = $request->input('name', '');
      $name = substr($name, 0, 200);

      if ($sl != '') {
        $qs = Core\Secure::string2array($sl);
        $page_id = $qs['landing_page_id'];

        if (is_numeric($page_id)) {
          $page = Models\Page::where('user_id', Core\Secure::userId())->where('id', $qs['landing_page_id'])->first();
          $page->name = $name;
          $page->save();

          $site = Models\Site::where('user_id', Core\Secure::userId())->where('id', $page->landing_site_id)->first();
          $site->name = $name;
          $site->save();

          return response()->json(['success' => true]);
        }
      }
    }

    /**
     * Domain
     */
    public function editorModalDomain(Request $request)
    {
      $sl = $request->input('sl', '');

      if ($sl != '') {
        $qs = Core\Secure::string2array($sl);
        $page_id = $qs['landing_page_id'];

        if (is_numeric($page_id)) {
          $page = Models\Page::where('user_id', Core\Secure::userId())->where('id', $qs['landing_page_id'])->first();

          return view('landingpages::modals.domain', compact('page', 'sl'));
        }
      }
    }

    /**
     * Post domain
     */
    public function editorPostDomain(Request $request)
    {
      $sl = $request->input('sl', '');
      $domain = $request->input('domain', '');

      if ($sl != '') {
        $qs = Core\Secure::string2array($sl);
        $page_id = $qs['landing_page_id'];

        if (is_numeric($page_id)) {
          $page = Models\Page::where('user_id', Core\Secure::userId())->where('id', $qs['landing_page_id'])->first();
          $site = Models\Site::where('user_id', Core\Secure::userId())->where('id', $page->landing_site_id)->first();
          $site->domain = $domain;
          $site->save();

          return response()->json(['success' => true]);
        }
      }
    }

    /**
     * View QR
     */
    public function editorModalQr(Request $request)
    {
      $url = $request->input('url', '');

      // Create a JWT token
      $jwt_token = JWTAuth::fromUser(auth()->user());

      return view('landingpages::modals.qr', compact('url', 'jwt_token'));
    }
}
