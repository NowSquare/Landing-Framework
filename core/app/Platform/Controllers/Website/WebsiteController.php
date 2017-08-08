<?php namespace Platform\Controllers\Website;

class WebsiteController extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | Website Controller
   |--------------------------------------------------------------------------
   |
   | Reseller website related logic
   |--------------------------------------------------------------------------
   */

  /**
   * Homepage
   */

  public function home()
  {
    $reseller = \Platform\Controllers\Core\Reseller::get();

    // Get all plans
    $plans = \Platform\Controllers\App\PlanController::getAllPlans();
    $all_plans = $plans['all_plans'];
    $annual_plans_exist = $plans['annual_plans_exist'];

    // Reseller specific design/content related settings
    $header_gradient_start = (isset($reseller->settings['header_gradient_start'])) ? $reseller->settings['header_gradient_start'] : '#138dfa';
    $header_gradient_end = (isset($reseller->settings['header_gradient_end'])) ? $reseller->settings['header_gradient_end'] : '#43cfd0';
    $header_image = (isset($reseller->settings['header_image'])) ? $reseller->settings['header_image'] : '/templates/assets/images/visuals/landing-screens-en.png';
    $header_title = (isset($reseller->settings['header_title'])) ? $reseller->settings['header_title'] : trans('website.header_01_line');
    $header_cta = (isset($reseller->settings['header_cta'])) ? $reseller->settings['header_cta'] : trans('website.header_cta');

    return view('website.home', compact('reseller', 'all_plans', 'annual_plans_exist', 'header_gradient_start', 'header_gradient_end', 'header_image', 'header_title', 'header_cta'));
  }

}