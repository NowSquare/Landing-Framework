<?php namespace Platform\Controllers\App;

use \Platform\Controllers\Core;

class AccountController extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | Account Controller
   |--------------------------------------------------------------------------
   |
   | Account related logic
   |--------------------------------------------------------------------------
   */

  /**
   * Profile
   */

  public function showProfile() {
    $user = auth()->user();

    return view('platform.account.profile', compact('user'));
  }

  /**
   * Update profile
   */
  public function postProfile()
  {
    if (config('app.demo') && auth()->user()->id == 1) {
      return response()->json([
        'type' => 'error',
        'reset' => false, 
        'msg' => "This is disabled in the demo"
      ]);
    }

    $input = array(
      'timezone' => request()->input('timezone'),
      'name' => request()->input('name'),
      'email' => request()->input('email'),
      'new_password' => request()->input('new_password'),
      'current_password' => request()->input('current_password'),
      'language' => request()->input('language', config('app.fallback_locale'))
    );

    $rules = array(
      'name' => 'required|max:64',
      'email' => 'required|email|unique:users,email,' . auth()->user()->id,
      'new_password' => 'nullable|min:5|max:20',
      'timezone' => 'required',
      'current_password' => 'required'
    );

    $validator = \Validator::make($input, $rules);

    if($validator->fails())
    {
      $response = array(
        'type' => 'error', 
        'msg' => $validator->messages()->first()
      );
    }
    else
    {
      // Check password
      if(! \Hash::check($input['current_password'], auth()->user()->password))
      {
        return response()->json(array(
          'type' => 'error',
          'reset' => false, 
          'msg' => trans('global.incorrect_password')
        ));
      }

      $user = \App\User::find(auth()->user()->id);

      $user->name = $input['name'];
      $user->email = $input['email'];
      $user->timezone = $input['timezone'];
      $user->language = $input['language'];

      if($input['new_password'] != '')
      {
        $user->password = bcrypt($input['new_password']);
      }

      if($user->save())
      {
        $response = array(
          'type' => 'success',
          'reset' => false, 
          'msg' => trans('global.changes_saved')
        );
      }
      else
      {
        $response = array(
          'type' => 'error',
          'reset' => false, 
          'msg' => $user->errors()->first()
        );
      }
    }
    return response()->json($response);
  }

  /**
   * Upload avatar
   */
  public function postAvatar() {
    $input = array(
      'file' => \Request::file('file'),
      'extension'  => strtolower(\Request::file('file')->getClientOriginalExtension())
    );

    $rules = array(
      'file' => 'mimes:jpeg,gif,png',
      'extension'  => 'required|in:jpg,jpeg,png,gif'
    );

    $validator = \Validator::make($input, $rules);

    if($validator->fails()) {
       echo $validator->messages()->first();
    } else {
      auth()->user()->avatar = $input['file'];
      auth()->user()->save();

      echo auth()->user()->avatar->url('default');
    }
  }

  /**
   * Delete avatar
   */
  public function postDeleteAvatar() {
    auth()->user()->avatar = STAPLER_NULL;
    auth()->user()->save();

    return response()->json(['src' => auth()->user()->getAvatar()]);
  }

  /**
   * Plan
   */

  public function showPlan() {
    $user = auth()->user();
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

    $currencyRepository = new \CommerceGuys\Intl\Currency\CurrencyRepository;
    $numberFormatRepository = new \CommerceGuys\Intl\NumberFormat\NumberFormatRepository;
    $numberFormat = $numberFormatRepository->get(auth()->user()->language);
    $decimalFormatter = new \CommerceGuys\Intl\Formatter\NumberFormatter($numberFormat);
    $currencyFormatter = new \CommerceGuys\Intl\Formatter\NumberFormatter($numberFormat, \CommerceGuys\Intl\Formatter\NumberFormatter::CURRENCY);

    $reseller = \Platform\Controllers\Core\Reseller::get();

    $payment_link_suffix = ($reseller->avangate_affiliate != '') ? '&AVGAFFILIATE=' . $reseller->avangate_affiliate : '';
    if (env('PAYMENT_TEST', false)) $payment_link_suffix .= '&DOTEST=1';

    if ($user->trial_ends_at != NULL || $user->expires != NULL) {
      $expiration_string = ($user->trial_ends_at != NULL) ? trans('global.trial_expires_in', ['datetime' => '<span data-moment="fromNowDateTime">' . $user->trial_ends_at->timezone($user->timezone)->format('Y-m-d H:i:s') . '</span>']) : trans('global.subscription_expires_in', ['datetime' => '<span data-moment="fromNowDateTime">' . $user->expires->timezone($user->timezone)->format('Y-m-d H:i:s') . '</span>']);
    } else {
      $expiration_string = '';
    }

    // Get all plans in one array
    $all_plans = [];
    $disabled = false;
    $annual_plans_exist = false;

    if (! empty($default_plan) && $default_plan->active == 1) {
      $currency = $default_plan->currency;
      if (trans('i18n.default_currency') != $currency && isset($default_plan->monthly_price_currencies[trans('i18n.default_currency')])) {
        $monthly_price = $currencyFormatter->formatCurrency($default_plan->monthly_price_currencies[trans('i18n.default_currency')], $currencyRepository->get(trans('i18n.default_currency'), auth()->user()->language));
      } else {
        $monthly_price = $currencyFormatter->formatCurrency($default_plan->monthly_price, $currencyRepository->get($default_plan->currency, auth()->user()->language));
      }

      if (trans('i18n.default_currency') != $currency && isset($default_plan->annual_price_currencies[trans('i18n.default_currency')])) {
        $annual_plans_exist = true;
        $annual_price = $currencyFormatter->formatCurrency($default_plan->annual_price_currencies[trans('i18n.default_currency')], $currencyRepository->get(trans('i18n.default_currency'), auth()->user()->language));
      } elseif ($default_plan->annual_price != null) {
        $annual_plans_exist = true;
        $annual_price = $currencyFormatter->formatCurrency($default_plan->annual_price, $currencyRepository->get($default_plan->currency, auth()->user()->language));
      } else {
        $annual_price = null;
      }

      $monthly_price = str_replace(['.00', ',00'], '', $monthly_price);
      if ($annual_price != null) $annual_price = str_replace(['.00', ',00'], '', $annual_price);

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

      if (auth()->user()->plan_id == $default_plan->id) {
        $btn_text = trans('global.current_plan');
        $btn_link = 'javascript:void(0);';
        $btn_target = '';
        $disabled = false;
        $btn_class = 'primary';
      } elseif (! $disabled) {
        // Add Avangate CUSTOMERID
        $order_url = (isset($default_plan->order_url)) ? $default_plan->order_url . '&CUSTOMERID=' . auth()->user()->id : '';
        $btn_text = trans('global.expired');
        $btn_link = ($order_url != '') ? $order_url : 'javascript:void(0);';
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
        'current' => (auth()->user()->plan_id == $default_plan->id),
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
            'visible' => (boolean) $item['in_free_plan'],
            'name' => $item['name'],
            'max' => ($item['in_free_plan_default_amount'] && $item['in_free_plan']) ? $item['in_free_plan_default_amount'] : '',
            'sub_items' => $plan_sub_items
          ];
        }
      }

      if (auth()->user()->plan_id == 0) {
        $btn_text = trans('global.current_plan');
        $btn_link = 'javascript:void(0);';
        $disabled = false;
        $btn_class = 'primary';
      } else {
        $btn_text = trans('global.free');
        $btn_link = 'javascript:void(0);';
        $btn_class = 'default';
      }

      $all_plans[] = [
        'id' => $plan->id,
        'current' => (auth()->user()->plan_id == $plan->id),
        'name' => $plan->name,
        'monthly_price' => $monthly_price,
        'monthly_link' => $btn_link,
        'monthly_text' => $btn_text,
        'annual_price' => $annual_price,
        'annual_link' => $btn_link,
        'annual_text' => $btn_text,
        'btn_target' => $btn_target,
        'btn_class' => $btn_class,
        'disabled' => $disabled,
        'description' => $plan->description,
        'plan_items' => $plan_items
      ];
      
    }

    // Other plans
    foreach($plans as $plan) {
      $currency = $plan->currency;
      if (trans('i18n.default_currency') != $currency && isset($plan->monthly_price_currencies[trans('i18n.default_currency')])) {
        $monthly_price = $currencyFormatter->formatCurrency($plan->monthly_price_currencies[trans('i18n.default_currency')], $currencyRepository->get(trans('i18n.default_currency'), auth()->user()->language));
      } else {
        $monthly_price = $currencyFormatter->formatCurrency($plan->monthly_price, $currencyRepository->get($plan->currency, auth()->user()->language));
      }

      if (trans('i18n.default_currency') != $currency && isset($plan->annual_price_currencies[trans('i18n.default_currency')])) {
        $annual_plans_exist = true;
        $annual_price = $currencyFormatter->formatCurrency($plan->annual_price_currencies[trans('i18n.default_currency')], $currencyRepository->get(trans('i18n.default_currency'), auth()->user()->language));
      } elseif ($plan->annual_price != null) {
        $annual_plans_exist_exist = true;
        $annual_price = $currencyFormatter->formatCurrency($plan->annual_price, $currencyRepository->get($plan->currency, auth()->user()->language));
      } else {
        $annual_price = null;
      }

      $monthly_price = str_replace(['.00', ',00'], '', $monthly_price);
      if ($annual_price != null) $annual_price = str_replace(['.00', ',00'], '', $annual_price);

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

      if (auth()->user()->plan_id == $plan->id) {
        $btn_text_monthly = trans('global.current_plan');
        $btn_text_annual = trans('global.current_plan');
        $btn_link_monthly = 'javascript:void(0);';
        $btn_link_annual = 'javascript:void(0);';
        $btn_target = '';
        $disabled = false;
        $btn_class = 'primary';
      } elseif (! $disabled) {
        // Add Avangate CUSTOMERID
        $monthly_order_url = (isset($plan->monthly_order_url)) ? $plan->monthly_order_url . '&CUSTOMERID=' . auth()->user()->id : '';
        $monthly_upgrade_url = (isset($plan->monthly_upgrade_url)) ? $plan->monthly_upgrade_url . '&CUSTOMERID=' . auth()->user()->id : '';
        $annual_order_url = (isset($plan->annual_order_url)) ? $plan->annual_order_url . '&CUSTOMERID=' . auth()->user()->id : '';
        $annual_upgrade_url = (isset($plan->annual_upgrade_url)) ? $plan->annual_upgrade_url . '&CUSTOMERID=' . auth()->user()->id : '';
        $btn_text_monthly = trans('global.order_1_month');
        $btn_text_annual = trans('global.order_1_year');
        if ($plan->annual_price == null) $btn_text_monthly = trans('global.order');
        $btn_link_monthly = 'javascript:void(0);';
        $btn_link_annual = 'javascript:void(0);';

        if ($monthly_order_url != '') $btn_link_monthly = 'javascript:openExternalPurchaseUrl(\'' . $monthly_order_url . $payment_link_suffix . '\');';
        if ($annual_order_url != '') $btn_link_annual = 'javascript:openExternalPurchaseUrl(\'' . $annual_order_url . $payment_link_suffix . '\');';

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
        'current' => (auth()->user()->plan_id == $plan->id),
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

    return view('platform.account.plan', compact('user', 'all_plans', 'annual_plans_exist', 'plans', 'default_plan', 'items', 'expiration_string', 'currencyRepository', 'decimalFormatter', 'currencyFormatter', 'reseller', 'payment_link_suffix'));
  }
}