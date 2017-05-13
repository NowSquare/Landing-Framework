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
    public function homePage($local_domain, $edit = false)
    {
      $sl = request()->input('sl', '');

      if(1==1 || $sl != '') {
        //$qs = Core\Secure::string2array($sl);
        //$landing_site = Pages::where('user_id', Core\Secure::userId())->where('id', $qs['landing_site_id'])->first();

        // Put template html into variable.
        $template = view('template.landingpages::_boilerplate.index');

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
        pq('head')->find('script[src]:last')->after(PHP_EOL . '<script class="-x-editor-asset" src="' . url('assets/javascript?lang=' . \App::getLocale()) . '"></script>');
        pq('head')->find('script[src]:last')->after(PHP_EOL . '<script class="-x-editor-asset" src="' . url('assets/js/scripts.editor.min.js') . '"></script>');

        // End stylesheet right before </head> to make
        // sure it overrides other stylesheets.
        pq('head')->append(PHP_EOL . '<link class="-x-editor-asset" rel="stylesheet" type="text/css" href="' . url('assets/css/styles.editor.min.css') . '" />');

        // Init editor
        pq('head')->append(PHP_EOL . '<script class="-x-editor-asset">$(function(){ lfInitEditor(); });</script>');

        return $dom;
      } else {
        return view('landingpages::index');
      }
    }

    /**
     * Landing page home
     */
    public function index()
    {
      return view('landingpages::index');
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
     * Landing page editor
     */
    public function editor()
    {
      $sl = request()->input('sl', '');

      if($sl != '') {
        $qs = Core\Secure::string2array($sl);
        //$landing_site = Pages::where('user_id', Core\Secure::userId())->where('id', $qs['landing_site_id'])->first();

        return view('landingpages::editor');
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

      $html = view('block.landingpages::' . $category . '.' . $block);

      return view('landingpages::block-preview', compact('html'));
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
}
