<?php namespace Platform\Controllers\Categories;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

use \Platform\Controllers\Core;
use \Platform\Models\Categories;
use Illuminate\Http\Request;

class ApiCategoryController extends \App\Http\Controllers\Controller
{
  /*
  |--------------------------------------------------------------------------
  | Card Category Controller
  |--------------------------------------------------------------------------
  |
  | Category Api related logic
  |--------------------------------------------------------------------------
  */

  /**
   * Get categories from reseller
   */

  public function getCategories() {
    $reseller_id = request()->input('r', Core\Reseller::get()->id);
    $lang = request()->input('lang', 'en');

    app()->setLocale($lang);

    $categories = Categories\Category::where('reseller_id', $reseller_id)
      ->select(['categories.id', 'categories.name', 'categories.icon'])
      ->orderBy('order', 'asc')
      ->get();

    $found_categories = [];

    foreach($categories as $category) {
      $found_categories[] = [
        'id' => $category->id,
        'name' => $category->name,
        'trans' => [
          'en' => trans('global.app_categories.' . $category->name)
        ],
        'icon' => $category->icon
      ];
    }

    return response()->json($found_categories);
  }
}