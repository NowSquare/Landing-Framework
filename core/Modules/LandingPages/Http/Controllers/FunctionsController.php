<?php

namespace Modules\LandingPages\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use \Platform\Controllers\Core;
use Modules\LandingPages\Http\Models;

class FunctionsController extends Controller
{
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
      return $dir;
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
    $category_dir = base_path('../blocks/landingpages/' . $category);

    if (\File::exists($category_dir)) {

      // Get all blocks
      $blocks = [];

      $category_blocks = array_sort(\File::files($category_dir), function($dir) {
        return $dir;
      });

      foreach ($category_blocks as $category_block) {
        if (ends_with($category_block, '.blade.php')) {
          $block = basename($category_block);

          $blocks[] = [
            'file' => $block,
            'preview' => url('landingpages/editor/block-preview?c=' . $category . '&b=' . str_replace('.blade.php', '', $block)),
            'blocks' => $blocks
          ];
        }
      }
    }
    return $blocks;
  }
}
