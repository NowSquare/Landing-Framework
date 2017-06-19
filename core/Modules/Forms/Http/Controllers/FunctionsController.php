<?php

namespace Modules\Forms\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use \Platform\Controllers\Core;
use Modules\Forms\Http\Models;
use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\DeviceParserAbstract;

class FunctionsController extends Controller
{
  /**
   * Get all form categories
   */
  public static function getCategories()
  {
    $items = [];

    $items['contact'] = [
      'icon' => 'handshake.svg',
      'category' => 'contact',
      'name' => trans('forms::global.contact'),
      'desc' => trans('forms::global.contact_desc')
    ];

    $items['opt_in'] = [
      'icon' => 'avatarmaleplus.svg',
      'category' => 'opt_in',
      'name' => trans('forms::global.opt_in'),
      'desc' => trans('forms::global.opt_in_desc')
    ];
/*
    $items['download'] = [
      'icon' => 'attachmentadd.svg',
      'category' => 'download',
      'name' => trans('forms::global.download'),
      'desc' => trans('forms::global.download_desc')
    ];
*/
    return $items;
  }

  /**
   * Add stat
   */
  public static function addStat($form, $ua = null)
  {
    // Fingerprint hash
    if ($ua == null) $ua = request()->header('User-Agent');
    $ip = request()->ip();

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

    //$tbl_name = 'x_form_stats_' . $form->user_id;
    $tbl_name = 'form_stats';

    $stats = \DB::table($tbl_name)
              ->where('fingerprint', $hash)
              ->where('form_id', $form->id)
              ->first();

    if (empty($stats)) {
      // Increment visits
      \DB::table('forms')->whereId($form->id)->increment('visits');

      // Insert visit
      \DB::table($tbl_name)->insert(
        [
          'form_id' => $form->id,
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
          'bot_producer_url' => $bot_producer_url
        ]
      );

    }
  }

  /**
   * Form entry
   */
  public static function addEntry($form, $form_vars, $custom_vars, $page, $ua = null)
  {
    // Fingerprint hash
    if ($ua == null) $ua = request()->header('User-Agent');
    $ip = request()->ip();

    $dd = new DeviceDetector($ua);

    $dd->setCache(new \Doctrine\Common\Cache\PhpFileCache(storage_path() . '/app/piwik_cache/'));

    // OPTIONAL: If called, getBot() will only return true if a bot was detected  (speeds up detection a bit)
    $dd->discardBotInformation();

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

      $hash = $ip . '|' . date('Y-m-d-H-i') . '|' . $name . '|' . $category . '|' . $url . '|' . $producer_name . '|' . $producer_url;

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

      $hash = $ip . '|' . date('Y-m-d-H-i') . '|' . implode($clientInfo, '-') . '|' . implode($osInfo, '-') . '|' . $device . '|' . $brand . '|' . $model;
    }

    $language = Core\Localization::getBrowserLocale();
    $language = (isset($language[0])) ? $language[0] : null;

    $hash = md5($hash);

    //$tbl_name = 'x_form_entries_' . $form->user_id;
    $tbl_name = 'form_entries';

    $landing_site_id = null;
    $landing_page_id = null;

    if ($page !== false) {
      // Form is linked to landing page.
      $landing_site_id = $page->site->id;
      $landing_page_id = $page->id;
    }

    $stats = \DB::table($tbl_name)
              ->where('fingerprint', $hash)
              ->where('form_id', $form->id)
              ->first();

    if (empty($stats)) {

      // Increment conversions
      \DB::table('landing_sites')->whereId($landing_site_id)->increment('conversions');
      \DB::table('landing_pages')->whereId($landing_page_id)->increment('conversions');

      // Increment entries
      \DB::table('forms')->whereId($form->id)->increment('entries');

      $stats = [
        'user_id' => $form->user_id,
        'form_id' => $form->id,
        'landing_site_id' => $landing_site_id,
        'landing_page_id' => $landing_page_id,
        'fingerprint' => $hash,
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
        'model' => $model
      ];

      $insert = array_merge($stats, $form_vars);
      $insert['entry'] = json_encode($custom_vars);

      \DB::table($tbl_name)->insert($insert);

      // Check for transaction emails linked to this form
      $forms = Models\Form::whereId($form->id)->get();

      foreach ($forms as $form) {
        $emails = $form->emails;
        if ($emails->count() > 0) {
          foreach ($emails as $email) {
            if ($email->emailCampaign->type == 'transactional_email') {
              $job = (new \Modules\EmailCampaigns\Jobs\SendEmail($form_vars['email'], $email, $form));
              dispatch($job);
            }
          }
        }
      }

      return true;
    } else {
      return false;
    }
  }

  /**
   * Get all form templates from a category
   */
  public static function getTemplatesByCategory($category)
  {
    $category_templates = [];

    $templates = \File::directories(base_path('../templates/forms/'));

    usort($templates, function ($dir1, $dir2) {
      if (\File::exists($dir1 . '/config.php')) {
        $config1 = include $dir1 . '/config.php';
      } else {
        return false;
      }

      if (\File::exists($dir2 . '/config.php')) {
        $config2 = include $dir2 . '/config.php';
      } else {
        return false;
      }

      return $config2['created_at'] <=> $config1['created_at'];
    });

    foreach ($templates as $template) {
      if (\File::exists($template . '/config.php') && \File::exists($template . '/index.blade.php')) {
        $config = include $template . '/config.php';

        if ($config['active'] && in_array($category, $config['categories'])) {

          $dir = basename($template);

          // Create thumbnail for preview if not exists
          $preview01_path = base_path('../templates/forms/' . $dir . '/preview-01.png');
          $preview01_thumb = 'forms/template/' . $dir . '/preview/01-600.jpg';

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
}
