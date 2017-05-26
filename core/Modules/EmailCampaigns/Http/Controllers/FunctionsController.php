<?php

namespace Modules\EmailCampaigns\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use \Platform\Controllers\Core;
use Modules\EmailCampaigns\Http\Models;
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

    $items[] = [
      "icon" => 'heartenvelope.svg',
      "category" => "transactional_email",
      "name" => trans('emailcampaigns::global.transactional_email'),
      "desc" => trans('emailcampaigns::global.transactional_email_desc')
    ];

    $items[] = [
      "icon" => 'letter.svg',
      "category" => "marketing_email",
      "name" => trans('emailcampaigns::global.marketing_email'),
      "desc" => trans('emailcampaigns::global.marketing_email_desc')
    ];

    $items[] = [
      "icon" => 'calendar.svg',
      "category" => "drip_campaign",
      "name" => trans('emailcampaigns::global.drip_campaign'),
      "desc" => trans('emailcampaigns::global.drip_campaign_desc')
    ];

    return $items;
  }

  /**
   * Get all form templates from a category
   */
  public static function getTemplatesByCategory($category)
  {
    $category_templates = [];

    $templates = array_sort(\File::directories(base_path('../templates/emails/')), function($dir) {
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
          $preview01_path = base_path('../templates/emails/' . $dir . '/preview-01.png');
          $preview01_thumb = 'emails/template/' . $dir . '/preview/01-600.jpg';

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
