<?php namespace Platform\Controllers\Website;

class WebsiteController extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | Website Controller
   |--------------------------------------------------------------------------
   |
   | Website related logic
   |--------------------------------------------------------------------------
   */

  /**
   * Index
   */

  public function home()
  {
    $reseller = \Platform\Controllers\Core\Reseller::get();

    $plans = \App\Plan::where('active', 1)->where('default', 0)->orderBy('order', 'asc')->get();
    $default_plan = \App\Plan::where('active', 1)->where('default', 1)->first();

    $modules = \Module::enabled();
    $items = [];

    foreach ($modules as $module) {
      $namespace = $module->getLowerName();
      $enabled = config($namespace . '.enabled');
      $in_plan = config($namespace . '.in_plan');

      if ($enabled && $in_plan) {
        $items[] = [
          "namespace" => $namespace,
          "icon" => config($namespace . '.icon'),
          "order" => config($namespace . '.order'),
          "creatable" => config($namespace . '.creatable'),
          "in_free_plan" => config($namespace . '.in_free_plan'),
          "in_free_plan_default_amount" => config($namespace . '.in_free_plan_default_amount'),
          "in_plan_amount" => config($namespace . '.in_plan_amount'),
          "in_plan_default_amount" => config($namespace . '.in_plan_default_amount'),
          "extra_plan_config_boolean" => config($namespace . '.extra_plan_config_boolean'),
          "extra_plan_config_string" => config($namespace . '.extra_plan_config_string'),
          "order" => config($namespace . '.order'),
          "name" => trans($namespace . '::global.module_name_plan'),
          "desc" => trans($namespace . '::global.module_desc')
        ];
      }
    }

    $items = array_values(array_sort($items, function ($value) {
      return $value['order'];
    }));

    $currencyRepository = new \CommerceGuys\Intl\Currency\CurrencyRepository;
    $numberFormatRepository = new \CommerceGuys\Intl\NumberFormat\NumberFormatRepository;
    $numberFormat = $numberFormatRepository->get($reseller->default_language);
    $decimalFormatter = new \CommerceGuys\Intl\Formatter\NumberFormatter($numberFormat);
    $currencyFormatter = new \CommerceGuys\Intl\Formatter\NumberFormatter($numberFormat, \CommerceGuys\Intl\Formatter\NumberFormatter::CURRENCY);

    $header_image = (\File::exists(base_path() . '../../templates/assets/images/visuals/landing-screens-' . \App::getLocale() . '.png')) ? url('templates/assets/images/visuals/landing-screens-' . \App::getLocale() . '.png') : url('templates/assets/images/visuals/landing-screens-en.png');

    return view('website.home', compact('header_image', 'reseller', 'plans', 'default_plan', 'items', 'currencyRepository', 'decimalFormatter', 'currencyFormatter'));
  }

}