<?php

namespace Modules\LandingPages\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use \Platform\Controllers\Core;
use Modules\LandingPages\Http\Models;
use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\DeviceParserAbstract;

class FunctionsController extends Controller
{
  /**
   * Add stat
   */
  public static function addStat($page, $ua = null, $ip = null, $created_at = null)
  {
    if ($created_at == null) $created_at = date('Y-m-d H:i:s');

    // Fingerprint hash
    if ($ua == null) $ua = request()->header('User-Agent');
    if ($ip == null) $ip = request()->ip();

    $dd = new DeviceDetector($ua);

    $dd->setCache(new \Doctrine\Common\Cache\PhpFileCache(storage_path() . '/app/piwik_cache/'));

    // OPTIONAL: If called, getBot() will only return true if a bot was detected  (speeds up detection a bit)
    //$dd->discardBotInformation();

    $dd->parse();

    // Defaults
    $bot_name = null;
    $bot_category = null;
    $bot_url = null;
    $bot_producer_name = null;
    $bot_producer_url = null;
    $device = null;
    $brand = null;
    $model = null;
    $client_type =  null;
    $client_name = null;
    $client_short_name = null;
    $client_version = null;
    $os_name = null;
    $os_short_name = null;
    $os_version = null;
    $os_platform = null;

    if ($dd->isBot()) {
      // handle bots,spiders,crawlers,...
      $botInfo = $dd->getBot();

      $is_bot = true;

      $bot_name = (isset($botInfo['name'])) ? $botInfo['name'] : null;
      $bot_category = (isset($botInfo['category'])) ? $botInfo['category'] : null;
      $bot_url = (isset($botInfo['url'])) ? $botInfo['url'] : null;
      $bot_producer_name = (isset($botInfo['producer']['name'])) ? $botInfo['producer']['name'] : null;
      $bot_producer_url = (isset($botInfo['producer']['url'])) ? $botInfo['producer']['url'] : null;

      $hash = $ip . '|' . date('Y-m-d-H') . '|' . $name . '|' . $category . '|' . $url . '|' . $producer_name . '|' . $producer_url;

    } else {
      $clientInfo = $dd->getClient(); // holds information about browser, feed reader, media player, ...
      $osInfo = $dd->getOs();
      $device = $dd->getDevice();
      $brand = $dd->getBrandName();
      if ($brand == '') $brand = null;
      $model = $dd->getModel();
      if ($model == '') $model = null;

      $is_bot = false;

      $client_type = (isset($clientInfo['type'])) ? $clientInfo['type'] : null;
      $client_name = (isset($clientInfo['name']) && $clientInfo['name'] != '') ? $clientInfo['name'] : null;
      $client_short_name = (isset($clientInfo['short_name']) && $clientInfo['short_name'] != '') ? $clientInfo['short_name'] : null;
      $client_version = (isset($clientInfo['version']) && $clientInfo['version'] != '') ? $clientInfo['version'] : null;

      $os_name = (isset($osInfo['name']) && $osInfo['name'] != '') ? $osInfo['name'] : null;
      $os_short_name = (isset($osInfo['short_name']) && $osInfo['short_name'] != '') ? $osInfo['short_name'] : null;
      $os_version = (isset($osInfo['version']) && $osInfo['version'] != '') ? $osInfo['version'] : null;
      $os_platform = (isset($osInfo['platform']) && $osInfo['platform'] != '') ? $osInfo['platform'] : null;

      $hash = $ip . '|' . date('Y-m-d-H') . '|' . implode($clientInfo, '-') . '|' . implode($osInfo, '-') . '|' . $device . '|' . $brand . '|' . $model;
    }

    $language = Core\Localization::getBrowserLocale();
    $language = (isset($language[0])) ? $language[0] : null;

    $hash = md5($hash);

    //$tbl_name = 'x_landing_stats_' . $page->user_id;
    $tbl_name = 'landing_stats';

    $stats = \DB::table($tbl_name)
              ->where('fingerprint', $hash)
              ->where('landing_site_id', $page->landing_site_id)
              ->where('landing_page_id', $page->id)
              ->first();

    if (empty($stats)) {
      // Increment visits
      \DB::table('landing_sites')->whereId($page->landing_site_id)->increment('visits');
      \DB::table('landing_pages')->whereId($page->id)->increment('visits');

      // Insert visit
      \DB::table($tbl_name)->insert(
        [
          'landing_site_id' => $page->landing_site_id,
          'landing_page_id' => $page->id,
          'fingerprint' => $hash,
          'is_bot' => $is_bot,
          'ip' => $ip,
          'language' => $language,
          'client_type' => $client_type,
          'client_name' => $client_name,
          'client_version' => $client_version,
          'os_name' => $os_name,
          'os_version' => $os_version,
          'os_platform' => $os_platform,
          'device' => $device,
          'brand' => $brand,
          'model' => $model,
          'bot_name' => $bot_name,
          'bot_category' => $bot_category,
          'bot_url' => $bot_url,
          'bot_producer_name' => $bot_producer_name,
          'bot_producer_url' => $bot_producer_url,
          'created_at' => $created_at
        ]
      );

    }
  }

  /**
   * Get all landing page categories
   */
  public static function getCategories()
  {
    $items = [];

    $items[] = [
      "icon" => 'presentation.svg',
      "category" => 'business_services',
      "name" => trans('landingpages::global.business_services'),
      "desc" => trans('landingpages::global.business_services_desc')
    ];

    $items[] = [
      "icon" => 'chefavatar-food-grocery-kitchen-restaurant-drink.svg',
      "category" => 'food_drinks',
      "name" => trans('landingpages::global.food_drinks'),
      "desc" => trans('landingpages::global.food_drinks_desc')
    ];

    $items[] = [
      "icon" => 'plan.svg',
      "category" => 'digital_tech',
      "name" => trans('landingpages::global.digital_tech'),
      "desc" => trans('landingpages::global.digital_tech_desc')
    ];

    $items[] = [
      "icon" => 'pictureprofile.svg',
      "category" => 'personal',
      "name" => trans('landingpages::global.personal'),
      "desc" => trans('landingpages::global.personal_desc')
    ];

    return $items;
  }

  /**
   * Get all landing page templates from a category
   */
  public static function getTemplatesByCategory($category)
  {
    $category_templates = [];

    $templates = array_sort(\File::directories(base_path('../templates/landingpages/')), function($dir) {
      if (\File::exists($dir . '/config.php')) {
        $config = include $dir . '/config.php';
        return $config['created_at'];
      } else {
        return $dir;
      }
    });

    foreach ($templates as $template) {
      if (\File::exists($template . '/config.php') && \File::exists($template . '/index.blade.php')) {
        $config = include $template . '/config.php';

        if ($config['active'] && in_array($category, $config['categories'])) {

          $dir = basename($template);

          // Create thumbnail for preview if not exists
          $preview01_path = base_path('../templates/landingpages/' . $dir . '/preview-01.png');
          $preview01_thumb = 'landingpages/template/' . $dir . '/preview/01-600.jpg';

          $exists = Storage::disk('public')->exists($preview01_thumb);

          if (! $exists) {
            $img = \Image::make($preview01_path);

            $img->resize(600, null, function ($constraint) {
              $constraint->aspectRatio();
            });

            $img_string = $img->encode('jpg', 60);

            Storage::disk('public')->put($preview01_thumb, $img_string->__toString());
            $preview01_url = Storage::disk('public')->url($preview01_thumb);
          } else {
            $preview01_url = Storage::disk('public')->url($preview01_thumb);
          }

          $category_templates[] = [
            'dir' => $dir,
            'created_at' => $config['created_at'],
            'updated_at' => $config['updated_at'],
            'preview01' => $preview01_url
          ];

        }
      }
    }

    return $category_templates;
  }

  /**
   * Get all landing page block categories
   */
  public static function getBlockCategories()
  {
    $categories = [];

    $block_categories = array_sort(\File::directories(base_path('../blocks/landingpages/')), function($dir) {
      return $dir;
    });

    foreach ($block_categories as $block_category) {
      $category = basename($block_category);
      $category_name = explode('-', $block_category)[1];

      $categories[] = [
        'dir' => $category,
        'name' => trans('landingpages::block.' . $category_name),
        'desc' => trans('landingpages::block.' . $category . '_desc'),
        'icon' => url('blocks/landingpages/' . $category . '/icon.svg')
      ];
    }

    return $categories;
  }

  /**
   * Get all landing page blocks from a category
   */
  public static function getBlocksByCategory($category)
  {
    $lang = \App::getLocale();
    $category_dir = base_path('../blocks/landingpages/' . $category);
    $category_path = '/blocks/landingpages/' . $category;

    if (\File::exists($category_dir)) {

      // Get all blocks
      $blocks = [];

      $category_blocks = array_sort(\File::files($category_dir), function($dir) {
        return $dir;
      });

      foreach ($category_blocks as $category_block) {
        if (ends_with($category_block, '.blade.php')) {
          $block = basename($category_block);

          // Preview screenshot
          $local_image = $category_path . '/' . str_replace('.blade.php', '.' . $lang . '.png', $block);

          // Create thumbnail for preview if not exists
          $preview_path = base_path('../' . $local_image);
          $preview_thumb = 'landingpages/blocks/' . $category . '/' . str_replace('.blade.php', '.' . $lang . '', $block) . '-600.jpg';

          $exists = Storage::disk('public')->exists($preview_thumb);

          if (! $exists && \File::exists(base_path() . '/..' . $local_image)) {
            $img = \Image::make($preview_path);

            $img->resize(600, null, function ($constraint) {
              $constraint->aspectRatio();
            });

            $img_string = $img->encode('jpg', 60);

            Storage::disk('public')->put($preview_thumb, $img_string->__toString());
            $preview_url = Storage::disk('public')->url($preview_thumb);
          } else {
            $preview_url = Storage::disk('public')->url($preview_thumb);
          }

          $blocks[] = [
            'file' => $block,
            'preview' => url('landingpages/editor/block-preview?c=' . $category . '&lang=' . $lang . '&b=' . str_replace('.blade.php', '', $block)),
            'screenshot' => $preview_url,
            'blocks' => $blocks
          ];
        }
      }
    }
    return $blocks;
  }

  /**
   * Create a landing page
   */
  public static function createPage($template, $name, $user_id = null, $funnel_id = null)
  {
    if ($user_id == null) $user_id = Core\Secure::userId();
    if ($funnel_id == null) $funnel_id = Core\Secure::funnelId();

    $name = substr($name, 0, 200);
    $template_path = base_path('../templates/landingpages/');

    if (\File::exists($template_path . $template . '/config.php') && \File::exists($template_path . $template . '/index.blade.php')) {
      $config = include $template_path . $template . '/config.php';

      // First create site
      $site = new Models\Site;

      $site->user_id = $user_id;
      $site->funnel_id = $funnel_id;
      $site->name = $name;
      $site->language = (auth()->check()) ? auth()->user()->language : Core\Reseller::get()->default_language;
      $site->timezone = (auth()->check()) ? auth()->user()->timezone : Core\Reseller::get()->default_timezone;
      $site->save();

      $site_id = $site->id;

      // Then, create page for site
      $page = new Models\Page;

      $page->user_id = $user_id;
      $page->landing_site_id = $site_id;
      $page->name = $name;
      $page->template = $template;
      $page->type = $config['type'];
      $page->save();

      $local_domain = Core\Secure::staticHash($site_id, true);

      $site->local_domain = $local_domain;
      $site->save();

      // Finally, create directory with files
      $storage_root = 'landingpages/site/' . Core\Secure::staticHash($user_id) . '/' . $local_domain;

      // Get template HTML and replace title
      $html = view('template.landingpages::' . $template . '.index');

      // Suppress libxml errors
      // Resolves an issue with some servers.
      libxml_use_internal_errors(true);

      // Create a new PHPQuery object to manipulate
      // the DOM in a similar way as jQuery.
      $html = \phpQuery::newDocumentHTML($html);
      \phpQuery::selectDocument($html);

      // Update page
      pq('title')->text($name);
      pq('head')->find('title')->after('<link rel="icon" type="image/x-icon" href="' . url('public/' . $storage_root . '/favicon.ico') . '">');
      pq('head')->find('title')->after('<meta name="description" content="">');

      //$html = str_replace('</section><section', "</section>\n\n<section", $html);
      $html = str_replace(url('/'), '', $html);

      // Beautify html
      $html = Core\Parser::beautifyHtml($html);

      $variant = 1;

      $storage_root_full = $storage_root . '/' . Core\Secure::staticHash($page->id, true) . '/' . $variant;

      \Storage::disk('public')->makeDirectory($storage_root_full . '/' . date('Y-m-d-H-i-s'));
      \Storage::disk('public')->put($storage_root_full . '/' . date('Y-m-d-H-i-s') . '/index.blade.php', $html);
      \Storage::disk('public')->put($storage_root_full . '/index.blade.php', $html);
      \Storage::disk('public')->put($storage_root . '/favicon.ico', \File::get($template_path . $template . '/favicon.ico'));

      return $page;
    } else {
      return false;
    }
  }

  /**
   * Save / publish page
   */
  public static function savePage($sl, $html, $publish = false, $user_id = null)
  {
    if ($user_id == null) $user_id = Core\Secure::userId();

    if($sl != '') {
      $qs = Core\Secure::string2array($sl);

      $landing_page_id = $qs['landing_page_id'];
      $page = Models\Page::where('user_id', $user_id)->where('id', $landing_page_id)->first();

      $variant = 1;

      // Update files
      $storage_root = 'landingpages/site/' . Core\Secure::staticHash($user_id) . '/' .  Core\Secure::staticHash($page->landing_site_id, true) . '/' . Core\Secure::staticHash($page->id, true) . '/' . $variant;

      $html = str_replace(url('/'), '', $html);

      // Beautify html
      $html = Core\Parser::beautifyHtml($html);

      \Storage::disk('public')->makeDirectory($storage_root . '/' . date('Y-m-d-H-i-s'));
      \Storage::disk('public')->put($storage_root . '/' . date('Y-m-d-H-i-s') . '/index.blade.php', $html);
      \Storage::disk('public')->put($storage_root . '/index.blade.php', $html);

      if ($publish) {
        \Storage::disk('public')->put($storage_root . '/published/index.blade.php', $html);
      }

      return true;
    } else {
      return false;
    }
  }
}
