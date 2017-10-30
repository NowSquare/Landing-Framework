<?php namespace Platform\Controllers\App;

use \Platform\Controllers\Core;
use Illuminate\Http\Request;

class PlanController extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | Plan Controller
   |--------------------------------------------------------------------------
   |
   | Plan related logic
   |--------------------------------------------------------------------------
   */

  /**
   * Plan management
   */
  public function showPlans() {
    return view('platform.admin.plans.plans');
  }

  /**
   * New plan
   */
  public function showNewPlan() {

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
          "in_plan_amount" => config($namespace . '.in_plan_amount'),
          "in_plan_default_amount" => config($namespace . '.in_plan_default_amount'),
          "extra_plan_config_boolean" => config($namespace . '.extra_plan_config_boolean'),
          "extra_plan_config_string" => config($namespace . '.extra_plan_config_string'),
          "order" => config($namespace . '.order'),
          "name" => trans($namespace . '::global.module_name'),
          "desc" => trans($namespace . '::global.module_desc')
        ];
      }
    }

    $items = array_values(array_sort($items, function ($value) {
      return $value['order'];
    }));

    $currencies = \Platform\Controllers\Core\Localization::getAllCurrencies();

    return view('platform.admin.plans.plan-new', compact('items', 'currencies'));
  }

  /**
   * Edit plan
   */
  public function showEditPlan() {
    $sl = request()->input('sl', '');

    if($sl != '') {
      $qs = Core\Secure::string2array($sl);
      $plan = \App\Plan::where('reseller_id', Core\Reseller::get()->id)->where('id', $qs['plan_id'])->first();

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
            "in_plan_amount" => config($namespace . '.in_plan_amount'),
            "in_plan_default_amount" => config($namespace . '.in_plan_default_amount'),
            "extra_plan_config_boolean" => config($namespace . '.extra_plan_config_boolean'),
            "extra_plan_config_string" => config($namespace . '.extra_plan_config_string'),
            "order" => config($namespace . '.order'),
            "name" => trans($namespace . '::global.module_name'),
            "desc" => trans($namespace . '::global.module_desc')
          ];
        }
      }

      $items = array_values(array_sort($items, function ($value) {
        return $value['order'];
      }));

      $currencies = \Platform\Controllers\Core\Localization::getAllCurrencies();

      return view('platform.admin.plans.plan-edit', compact('sl', 'plan', 'items', 'currencies'));
    }
  }

  /**
   * Add new plan
   */
  public function postNewPlan() {
    $input = array(
      'name' => request()->input('name'),
      'currency' => request()->input('currency'),
      'monthly_price' => request()->input('monthly_price'),
      'monthly_remote_product_id' => request()->input('monthly_remote_product_id'),
      'monthly_order_url' => request()->input('monthly_order_url'),
      'monthly_upgrade_url' => request()->input('monthly_upgrade_url'),
      'annual_price' => request()->input('annual_price'),
      'annual_remote_product_id' => request()->input('annual_remote_product_id'),
      'annual_order_url' => request()->input('annual_order_url'),
      'annual_upgrade_url' => request()->input('annual_upgrade_url'),
      'default' => (bool) request()->input('default', false),
      'active' => (bool) request()->input('active', false),
      'trial_days' => request()->input('trial_days', null),
      'limitations' => request()->input('limitations', [])
    );

    $rules = array(
      'name' => 'required',
      'monthly_price' => 'required',
      'monthly_order_url' => 'nullable|url',
      'monthly_upgrade_url' => 'nullable|url',
      'annual_order_url' => 'nullable|url',
      'annual_upgrade_url' => 'nullable|url',
      'trial_days' => 'nullable|integer|min:4'
    );

    $validator = \Validator::make($input, $rules);

    if($validator->fails())
    {
      $response = array(
        'type' => 'error', 
        'reset' => false, 
        'msg' => $validator->messages()->first()
      );
    }
    else
    {
      $reseller = Core\Reseller::get();

      // Get max order
      $order = \DB::table('plans')->where('reseller_id', $reseller->id)->max('order');
      $order = ($order == null) ? 1 : $order + 1;

      $plan = new \App\Plan;

      $plan->order = $order;
      $plan->reseller_id = $reseller->id;
      $plan->name = $input['name'];
      $plan->currency = $input['currency'];
      $plan->monthly_price = $input['monthly_price'];
      $plan->monthly_remote_product_id = $input['monthly_remote_product_id'];
      $plan->monthly_order_url = $input['monthly_order_url'];
      $plan->monthly_upgrade_url = $input['monthly_upgrade_url'];
      $plan->annual_price = $input['annual_price'];
      $plan->annual_remote_product_id = $input['annual_remote_product_id'];
      $plan->annual_order_url = $input['annual_order_url'];
      $plan->annual_upgrade_url = $input['annual_upgrade_url'];
      $plan->limitations = $input['limitations'];
      $plan->default = $input['default'];
      $plan->active = $input['active'];
      $plan->trial_days = $input['trial_days'];

      if($plan->save())
      {
        // Set other plans to non-default
        if ($input['default']) {
          $result = \App\Plan::where('reseller_id', Core\Reseller::get()->id)->where('id', '<>', $plan->id)->update(['default' => 0]);
        }

        $response = array(
          'type' => 'success',
          'redir' => '#/admin/plans'
        );
      }
      else
      {
        $response = array(
          'type' => 'error',
          'reset' => false, 
          'msg' => $plan->errors()->first()
        );
      }
    }
    return response()->json($response);
  }

  /**
   * Save plan changes
   */
  public function postPlan() {
    $sl = request()->input('sl', '');

    if($sl != '')
    {
      $qs = Core\Secure::string2array($sl);
      $plan = \App\Plan::where('reseller_id', Core\Reseller::get()->id)->find($qs['plan_id']);

      $input = array(
        'name' => request()->input('name'),
        'currency' => request()->input('currency'),
        'monthly_price' => request()->input('monthly_price'),
        'monthly_remote_product_id' => request()->input('monthly_remote_product_id'),
        'monthly_order_url' => request()->input('monthly_order_url'),
        'monthly_upgrade_url' => request()->input('monthly_upgrade_url'),
        'annual_price' => request()->input('annual_price'),
        'annual_remote_product_id' => request()->input('annual_remote_product_id'),
        'annual_order_url' => request()->input('annual_order_url'),
        'annual_upgrade_url' => request()->input('annual_upgrade_url'),
        'default' => (bool) request()->input('default', false),
        'active' => (bool) request()->input('active', false),
        'trial_days' => request()->input('trial_days', null),
        'limitations' => request()->input('limitations', [])
      );

      $rules = array(
        'name' => 'required',
        'monthly_price' => 'required',
        'monthly_order_url' => 'nullable|url',
        'monthly_upgrade_url' => 'nullable|url',
        'annual_order_url' => 'nullable|url',
        'annual_upgrade_url' => 'nullable|url',
        'trial_days' => 'nullable|integer|min:4'
      );

      $validator = \Validator::make($input, $rules);

      if($validator->fails())
      {
        $response = array(
          'type' => 'error', 
          'reset' => false, 
          'msg' => $validator->messages()->first()
        );
      }
      else
      {
        $plan->name = $input['name'];
        $plan->currency = $input['currency'];
        $plan->monthly_price = $input['monthly_price'];
        $plan->monthly_remote_product_id = $input['monthly_remote_product_id'];
        $plan->monthly_order_url = $input['monthly_order_url'];
        $plan->monthly_upgrade_url = $input['monthly_upgrade_url'];
        $plan->annual_price = $input['annual_price'];
        $plan->annual_remote_product_id = $input['annual_remote_product_id'];
        $plan->annual_order_url = $input['annual_order_url'];
        $plan->annual_upgrade_url = $input['annual_upgrade_url'];
        $plan->trial_days = $input['trial_days'];

        if ($qs['plan_id'] != 1) {
          $plan->limitations = $input['limitations'];
          $plan->active = $input['active'];
          $plan->default = $input['default'];

          // Set other plans to non-default
          if ($input['default']) {
            $result = \App\Plan::where('reseller_id', Core\Reseller::get()->id)->where('id', '<>', $qs['plan_id'])->update(['default' => 0]);
          }
        }

        if($plan->save())
        {
          $response = array(
            'redir' => '#/admin/plans'
          );
        }
        else
        {
          $response = array(
            'type' => 'error',
            'reset' => false, 
            'msg' => $plan->errors()->first()
          );
        }
      }
      return response()->json($response);
    }
  }

  /**
   * Delete plan
   */
  public function postPlanDelete() {
    $sl = request()->input('sl', '');

    if($sl != '') {
      $qs = Core\Secure::string2array($sl);
      $response = array('result' => 'success');

      if($qs['plan_id'] != 1) {
        $plan = \App\Plan::where('id', '=',  $qs['plan_id'])->where('reseller_id', Core\Reseller::get()->id)->forceDelete();
      }
    }
    return response()->json($response);
  }

  /**
   * Re-order plans
   */
  public function postPlanOrder() {
    $rows = request()->input('rows', '');

    if($rows != '') {
      foreach($rows as $sl => $order) {
        $qs = Core\Secure::string2array($sl);
        $plan = \App\Plan::where('id', '=',  $qs['plan_id'])->where('reseller_id', Core\Reseller::get()->id)->update(['order' => $order]);
      }
    }
    return response()->json(['result' => 'success']);
  }

  /**
   * Get plan data
   */
  public function getPlanData(Request $request) {
    $order_by = $request->input('order.0.column', 0);
    $order = $request->input('order.0.dir', 'asc');
    $search = $request->input('search.regex', '');
    $q = $request->input('search.value', '');
    $start = $request->input('start', 0);
    $draw = $request->input('draw', 1);
    $length = $request->input('length', 10);
    $data = array();

    $aColumn = array('order', 'name', 'monthly_price', 'domain', 'created_at', 'active');

    if($q != '')
    {
      $count = \App\Plan::where('reseller_id', Core\Reseller::get()->id)->where(function ($query) use($q) {
          $query->orWhere('name', 'like', '%' . $q . '%');
        })
        ->count();

      $oData = \App\Plan::where('reseller_id', Core\Reseller::get()->id)->orderBy($aColumn[$order_by], $order)
        ->where(function ($query) use($q) {
          $query->orWhere('name', 'like', '%' . $q . '%');
        })
        ->take($length)->skip($start)->get();
    }
    else
    {
      $count = \App\Plan::where('reseller_id', Core\Reseller::get()->id)->count();
      $oData = \App\Plan::where('reseller_id', Core\Reseller::get()->id)->orderBy($aColumn[$order_by], $order)->take($length)->skip($start)->get();
    }

    if($length == -1) $length = $count;

    $recordsTotal = $count;
    $recordsFiltered = $count;

    $currencyRepository = new \CommerceGuys\Intl\Currency\CurrencyRepository;
    $numberFormatRepository = new \CommerceGuys\Intl\NumberFormat\NumberFormatRepository;
    $numberFormat = $numberFormatRepository->get(auth()->user()->language);
    $decimalFormatter = new \CommerceGuys\Intl\Formatter\NumberFormatter($numberFormat);
    $currencyFormatter = new \CommerceGuys\Intl\Formatter\NumberFormatter($numberFormat, \CommerceGuys\Intl\Formatter\NumberFormatter::CURRENCY);

    foreach($oData as $row) {
      // Make undeletable if plan has users
      $undeletable = ($row->id ==1 ) ? 1 : 0;

      $name = $row->name;
      if ($row->id == 1) $name .= ' <i class="fa fa-lock" aria-hidden="true"></i>';

      if (trans('i18n.default_currency') != $row->currency && isset($row->monthly_price_currencies[trans('i18n.default_currency')])) {
        $monthly_price = $currencyFormatter->formatCurrency($row->monthly_price_currencies[trans('i18n.default_currency')], $currencyRepository->get(trans('i18n.default_currency'), auth()->user()->language));
        $price = $monthly_price;
        if (isset($row->annual_price_currencies[trans('i18n.default_currency')]) && $row->annual_price_currencies[trans('i18n.default_currency')] != null) {
          $annual_price = $currencyFormatter->formatCurrency($row->annual_price_currencies[trans('i18n.default_currency')], $currencyRepository->get(trans('i18n.default_currency'), auth()->user()->language));
          $price = $monthly_price . ' / ' . $annual_price;
        }
      } else {
        $monthly_price = $currencyFormatter->formatCurrency($row->monthly_price, $currencyRepository->get($row->currency, auth()->user()->language));
        $price = $monthly_price;
        if ($row->annual_price != null) {
          $annual_price = $currencyFormatter->formatCurrency($row->annual_price, $currencyRepository->get($row->currency, auth()->user()->language));
          $price = $monthly_price . ' / ' . $annual_price;
        }
      }

      if ($row->id == 1) $price = '-';

      $data[] = array(
        'DT_RowId' => 'row_' . $row->id,
        'order' => $row->order,
        'name' => $name,
        'price' => $price,
        'default' => $row->default,
        'active' => $row->active,
        'created_at' => $row->created_at->timezone(\Auth::user()->timezone)->format('Y-m-d H:i:s'),
        'sl' => Core\Secure::array2string(array('plan_id' => $row->id)),
        'undeletable' => $undeletable
      );
    }

    $response = array(
      'draw' => $draw,
      'recordsTotal' => $recordsTotal,
      'recordsFiltered' => $recordsFiltered,
      'data' => $data
    );

    echo json_encode($response);
  }

  /**
   * Get all plans in one array including free/trial 
   */
  public static function getAllPlans() {
    $reseller = \Platform\Controllers\Core\Reseller::get();
    $user_id = (auth()->check()) ? auth()->user()->id : 0;
    $language = (auth()->check()) ? auth()->user()->language : $reseller->default_language;
    $current_plan_id = (auth()->check()) ? auth()->user()->plan_id : 0;  

    //$plans = \App\Plan::where('reseller_id', Core\Reseller::get()->id)->where('active', 1)->where('default', 0)->orderBy('order', 'asc')->get();
    //$default_plan = \App\Plan::where('reseller_id', Core\Reseller::get()->id)->where('active', 1)->where('default', 1)->first();

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

    // Payment provider, check if setting exists
    if (isset($reseller->settings['payment_provider'])) {
      $payment_provider = $reseller->settings['payment_provider'];
    } else {
      // Setting does not exist, check .env config
      if (env('AVANGATE_KEY', '') != '' && $reseller->stripe_key == null) {
        $payment_provider = 'AVANGATE';
      } elseif ($reseller->stripe_key != null) {
        $payment_provider = 'STRIPE';
      }
    }

    $custom_affiliate_id = (isset($reseller->settings['custom_affiliate_id'])) ? $reseller->settings['custom_affiliate_id'] : '';
    $user_query_parameter = (isset($reseller->settings['user_query_parameter'])) ? $reseller->settings['user_query_parameter'] : 'user_id';
    $affiliate_query_parameter = (isset($reseller->settings['affiliate_query_parameter'])) ? $reseller->settings['affiliate_query_parameter'] : 'source';

    $payment_link_suffix = '';

    if ($payment_provider == 'AVANGATE') {
      $payment_link_suffix = ($reseller->avangate_affiliate != '') ? '&AVGAFFILIATE=' . $reseller->avangate_affiliate : '';
      if (env('PAYMENT_TEST', false)) $payment_link_suffix .= '&DOTEST=1';
    } else {
      // Get custom affiliate query parameter
      if ($custom_affiliate_id != '') {
        $payment_link_suffix .= '&' . $affiliate_query_parameter . '=' . $custom_affiliate_id;
      }
    }

    $currencyRepository = new \CommerceGuys\Intl\Currency\CurrencyRepository;
    $numberFormatRepository = new \CommerceGuys\Intl\NumberFormat\NumberFormatRepository;
    $numberFormat = $numberFormatRepository->get($language);
    $decimalFormatter = new \CommerceGuys\Intl\Formatter\NumberFormatter($numberFormat);
    $currencyFormatter = new \CommerceGuys\Intl\Formatter\NumberFormatter($numberFormat, \CommerceGuys\Intl\Formatter\NumberFormatter::CURRENCY);

    $all_plans = [];
    $disabled = false;
    $annual_plans_exist = false;

    if (! empty($default_plan) && $default_plan->active == 1) {
      $currency = $default_plan->currency;
      if (trans('i18n.default_currency') != $currency && isset($default_plan->monthly_price_currencies[trans('i18n.default_currency')])) {
        $monthly_price = $currencyFormatter->formatCurrency($default_plan->monthly_price_currencies[trans('i18n.default_currency')], $currencyRepository->get(trans('i18n.default_currency'), $language));
        $fractionDigits = $currencyRepository->get(trans('i18n.default_currency'), $language)->getFractionDigits();
      } else {
        $monthly_price = $currencyFormatter->formatCurrency($default_plan->monthly_price, $currencyRepository->get($default_plan->currency, $language));
        $fractionDigits = $currencyRepository->get($default_plan->currency, $language)->getFractionDigits();
      }

      if (trans('i18n.default_currency') != $currency && isset($default_plan->annual_price_currencies[trans('i18n.default_currency')])) {
        $annual_plans_exist = true;
        $annual_price = $currencyFormatter->formatCurrency($default_plan->annual_price_currencies[trans('i18n.default_currency')], $currencyRepository->get(trans('i18n.default_currency'), $language));
      } elseif ($default_plan->annual_price != null) {
        $annual_plans_exist = true;
        $annual_price = $currencyFormatter->formatCurrency($default_plan->annual_price, $currencyRepository->get($default_plan->currency, $language));
      } else {
        $annual_price = null;
      }

      $ends_with = str_repeat('0', $fractionDigits);
      if (ends_with($monthly_price, $ends_with)) $monthly_price = substr($monthly_price, 0, strlen($monthly_price) - ($fractionDigits + 1));
      if (ends_with($annual_price, $ends_with)) $annual_price = substr($annual_price, 0, strlen($annual_price) - ($fractionDigits + 1));

      // Plan items
      $plan_items = [];
      foreach($items as $item) {
        if ($item['creatable']) {

          $plan_sub_items = [];

          if (isset($item['extra_plan_config_string']) && count($item['extra_plan_config_string']) > 0) { 
            foreach ($item['extra_plan_config_string'] as $config => $value) {
              $val = (isset($default_plan->limitations[$item['namespace']][$config])) ? $default_plan->limitations[$item['namespace']][$config] : '-';
              if (is_numeric($val)) $val = $decimalFormatter->format($val);
              $plan_sub_items[] = [
                'type' => 'string',
                'name' => trans($item['namespace'] . '::global.' . $config),
                'val' => $val
              ];
            }
          }

          if (isset($item['extra_plan_config_boolean']) && count($item['extra_plan_config_boolean']) > 0) { 
            foreach ($item['extra_plan_config_boolean'] as $config => $value) {
              $val = (isset($default_plan->limitations[$item['namespace']][$config]) && $default_plan->limitations[$item['namespace']][$config]== 1) ? true : false;
              if ($config != 'edit_html') {
                $plan_sub_items[] = [
                  'type' => 'boolean',
                  'name' => trans($item['namespace'] . '::global.' . $config),
                  'val' => $val
                ];
              }
            }
          }

          $plan_items[] = [
            'visible' => (boolean) $default_plan->limitations[$item['namespace']]['visible'],
            'name' => $item['name'],
            'max' => ($item['in_plan_amount']) ? $default_plan->limitations[$item['namespace']]['max'] : '',
            'sub_items' => $plan_sub_items
          ];
        }
      }

      if ($current_plan_id == $default_plan->id) {
        $btn_text = trans('global.current_plan');
        $btn_link = 'javascript:void(0);';
        $btn_target = '';
        $disabled = false;
        $btn_class = 'primary';
      } elseif (! $disabled) {
        $btn_text = trans('global.expired');
        $btn_link = 'javascript:void(0);';
        $btn_target = '';
        $btn_class = 'warning';
      } else {
        $btn_text = trans('global.order_now');
        $btn_link = 'javascript:void(0);';
        $btn_target = '';
        $btn_class = 'warning';
      }

      $all_plans[] = [
        'id' => $default_plan->id,
        'current' => ($current_plan_id == $default_plan->id),
        'name' => $default_plan->name,
        'monthly_price' => $monthly_price,
        'monthly_link' => $btn_link,
        'monthly_text' => $btn_text,
        'annual_price' => $annual_price,
        'annual_link' => $btn_link,
        'annual_text' => $btn_text,
        'btn_target' => $btn_target,
        'btn_class' => $btn_class,
        'disabled' => $disabled,
        'description' => $default_plan->description,
        'plan_items' => $plan_items
      ];

    } else {
      // Default free plan
      // Plan items
      $plan_items = [];
      foreach($items as $item) {
        if ($item['creatable']) {

          $plan_sub_items = [];

          if (isset($item['extra_plan_config_string']) && count($item['extra_plan_config_string']) > 0) { 
            foreach ($item['extra_plan_config_string'] as $config => $value) {
              $val = '-';
              if (is_numeric($val)) $val = $decimalFormatter->format($val);
              $plan_sub_items[] = [
                'type' => 'string',
                'name' => trans($item['namespace'] . '::global.' . $config),
                'val' => $val
              ];
            }
          }

          if (isset($item['extra_plan_config_boolean']) && count($item['extra_plan_config_boolean']) > 0) { 
            foreach ($item['extra_plan_config_boolean'] as $config => $value) {
              $val = false;
              if ($config != 'edit_html') {
                $plan_sub_items[] = [
                  'type' => 'boolean',
                  'name' => trans($item['namespace'] . '::global.' . $config),
                  'val' => $val
                ];
              }
            }
          }

          $plan_items[] = [
            'visible' => (boolean) $item['in_free_plan'],
            'name' => $item['name'],
            'max' => ($item['in_free_plan_default_amount'] && $item['in_free_plan']) ? $item['in_free_plan_default_amount'] : '',
            'sub_items' => $plan_sub_items
          ];
        }
      }

      $all_plans[] = [
        'id' => null,
        'current' => null,
        'name' => trans('global.free'),
        'monthly_price' => 0,
        'monthly_link' => 'javascript:void(0);',
        'monthly_text' => trans('global.free'),
        'annual_price' => null,
        'annual_link' => null,
        'annual_text' => null,
        'btn_target' => '',
        'btn_class' => 'default',
        'disabled' => true,
        'description' => '',
        'plan_items' => $plan_items
      ];
    }

    // Other plans
    foreach($plans as $plan) {
      $currency = $plan->currency;
      if (trans('i18n.default_currency') != $currency && isset($plan->monthly_price_currencies[trans('i18n.default_currency')])) {
        $monthly_price = $currencyFormatter->formatCurrency($plan->monthly_price_currencies[trans('i18n.default_currency')], $currencyRepository->get(trans('i18n.default_currency'), $language));
        $fractionDigits = $currencyRepository->get(trans('i18n.default_currency'), $language)->getFractionDigits();
      } else {
        $monthly_price = $currencyFormatter->formatCurrency($plan->monthly_price, $currencyRepository->get($plan->currency, $language));
        $fractionDigits = $currencyRepository->get($plan->currency, $language)->getFractionDigits();
      }

      if (trans('i18n.default_currency') != $currency && isset($plan->annual_price_currencies[trans('i18n.default_currency')])) {
        $annual_plans_exist = true;
        $annual_price = $currencyFormatter->formatCurrency($plan->annual_price_currencies[trans('i18n.default_currency')], $currencyRepository->get(trans('i18n.default_currency'), $language));
      } elseif ($plan->annual_price != null) {
        $annual_plans_exist = true;
        $annual_price = $currencyFormatter->formatCurrency($plan->annual_price, $currencyRepository->get($plan->currency, $language));
      } else {
        $annual_price = null;
      }

      $ends_with = str_repeat('0', $fractionDigits);
      if (ends_with($monthly_price, $ends_with)) $monthly_price = substr($monthly_price, 0, strlen($monthly_price) - ($fractionDigits + 1));
      if (ends_with($annual_price, $ends_with)) $annual_price = substr($annual_price, 0, strlen($annual_price) - ($fractionDigits + 1));

      // Plan items
      $plan_items = [];
      foreach($items as $item) {
        if ($item['creatable']) {

          $plan_sub_items = [];

          if (isset($item['extra_plan_config_string']) && count($item['extra_plan_config_string']) > 0) { 
            foreach ($item['extra_plan_config_string'] as $config => $value) {
              $val = (isset($plan->limitations[$item['namespace']][$config])) ? $plan->limitations[$item['namespace']][$config] : '-';
              if (is_numeric($val)) $val = $decimalFormatter->format($val);
              $plan_sub_items[] = [
                'type' => 'string',
                'name' => trans($item['namespace'] . '::global.' . $config),
                'val' => $val
              ];
            }
          }

          if (isset($item['extra_plan_config_boolean']) && count($item['extra_plan_config_boolean']) > 0) { 
            foreach ($item['extra_plan_config_boolean'] as $config => $value) {
              $val = (isset($plan->limitations[$item['namespace']][$config]) && $plan->limitations[$item['namespace']][$config]== 1) ? true : false;
              if ($config != 'edit_html') {
                $plan_sub_items[] = [
                  'type' => 'boolean',
                  'name' => trans($item['namespace'] . '::global.' . $config),
                  'val' => $val
                ];
              }
            }
          }

          $plan_items[] = [
            'visible' => (boolean) $plan->limitations[$item['namespace']]['visible'],
            'name' => $item['name'],
            'max' => ($item['in_plan_amount']) ? $plan->limitations[$item['namespace']]['max'] : '',
            'sub_items' => $plan_sub_items
          ];
        }
      }

      if ($current_plan_id == $plan->id) {
        $btn_text_monthly = trans('global.current_plan');
        $btn_text_annual = trans('global.current_plan');
        $btn_link_monthly = 'javascript:void(0);';
        $btn_link_annual = 'javascript:void(0);';
        $btn_target = '';
        $disabled = false;
        $btn_class = 'primary';
      } elseif (! $disabled) {

        if ($payment_provider == 'AVANGATE') {
          // Add Avangate CUSTOMERID
          $monthly_order_url = (isset($plan->monthly_order_url)) ? $plan->monthly_order_url . '&CUSTOMERID=' . $user_id : '';
          $annual_order_url = (isset($plan->annual_order_url)) ? $plan->annual_order_url . '&CUSTOMERID=' . $user_id : '';

          $monthly_order_url = "'" . $monthly_order_url . $payment_link_suffix . "'";
          $annual_order_url = "'" . $annual_order_url . $payment_link_suffix . "'";
        } elseif ($payment_provider == 'STRIPE') {
          $monthly_order_url = "'" . str_replace("'", "\'", $plan->name) . "', '" . str_replace("'", "\'", $monthly_price) . trans('global.monthly_abbr') . "', '" . $currency . "', " . ($plan->monthly_price * 100) . ", '" . str_replace("'", "\'", $plan->monthly_remote_product_id) . "', " . $plan->id . "";
          $annual_order_url = "'" . str_replace("'", "\'", $plan->name) . "', '" . str_replace("'", "\'", $annual_price) . trans('global.monthly_abbr') . "', '" . $currency . "', " . ($plan->annual_price * 100) . ", '" . str_replace("'", "\'", $plan->annual_remote_product_id) . "', " . $plan->id . "";
        } else {
          $monthly_order_url = (isset($plan->monthly_order_url)) ? $plan->monthly_order_url . '&' . $user_query_parameter . '=' . $user_id : '';
          $annual_order_url = (isset($plan->annual_order_url)) ? $plan->annual_order_url . '&' . $user_query_parameter . '=' . $user_id : '';

          $monthly_order_url = "'" . $monthly_order_url . $payment_link_suffix . "'";
          $annual_order_url = "'" . $annual_order_url . $payment_link_suffix . "'";
        }

        $btn_text_monthly = trans('global.order_1_month');
        $btn_text_annual = trans('global.order_1_year');
        if ($plan->annual_price == null) $btn_text_monthly = trans('global.order');
        $btn_link_monthly = 'javascript:void(0);';
        $btn_link_annual = 'javascript:void(0);';

        if (env('APP_DEMO', false)) {
          if ($monthly_order_url != '') $btn_link_monthly = 'javascript:openExternalPurchaseUrlDemo(' . $monthly_order_url . ');';
          if ($annual_order_url != '') $btn_link_annual = 'javascript:openExternalPurchaseUrlDemo(' . $annual_order_url . ');';
        } else {
          if ($monthly_order_url != '') $btn_link_monthly = 'javascript:openExternalPurchaseUrl(' . $monthly_order_url . ');';
          if ($annual_order_url != '') $btn_link_annual = 'javascript:openExternalPurchaseUrl(' . $annual_order_url . ');';
        }

        $btn_target = '';
        $btn_class = 'warning';
      } else {
        $btn_text_monthly = trans('global.order_1_month');
        $btn_text_annual = trans('global.order_1_year');
        if ($plan->annual_price == null) $btn_text_monthly = trans('global.order');
        $btn_link_monthly = 'javascript:void(0);';
        $btn_link_annual = 'javascript:void(0);';
        $btn_target = '';
        $btn_class = 'warning';
      }

      $all_plans[] = [
        'id' => $plan->id,
        'current' => ($current_plan_id == $plan->id),
        'name' => $plan->name,
        'monthly_price' => $monthly_price,
        'monthly_link' => $btn_link_monthly,
        'monthly_text' => $btn_text_monthly,
        'annual_price' => $annual_price,
        'annual_link' => $btn_link_annual,
        'annual_text' => $btn_text_annual,
        'btn_target' => $btn_target,
        'btn_class' => $btn_class,
        'disabled' => $disabled,
        'description' => $plan->description,
        'plan_items' => $plan_items
      ];
    }
    
    return [
      'annual_plans_exist' => $annual_plans_exist,
      'all_plans' => $all_plans
    ];
  }
}