<div class="container">

  <div class="row m-t">
    <div class="col-sm-12">
      <nav class="navbar navbar-default card-box sub-navbar">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('global.account') }}</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">\</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('global.plan') }}</a>
<?php if ($expiration_string != '') { ?>
            <a class="navbar-brand no-link" href="javascript:void(0);">\</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">{!! $expiration_string !!}</a>
<?php } ?>
          </div>
        </div>
      </nav>
    </div>
  </div>

  <div class="row">

    <div class="col-md-3 col-lg-2">

        <div class="list-group">
          <a href="#/profile" class="list-group-item">{{ trans('global.profile') }}</a>
          <a href="#/plan" class="list-group-item active">{{ trans('global.plan') }}</a>
        </div>
  
    </div>
    <div class="col-md-9 col-lg-10">
      <div class="row">
        <div class="col-lg-12">
          <div class="row">
<?php
$plan_count = $plans->count() + 1;

$disabled = false;
$col_span = 'col-md-12';

if ($plan_count == 2) $col_span = 'col-md-6';
if ($plan_count%3 == 0) $col_span = 'col-md-4';
if ($plan_count%4 == 0) $col_span = 'col-md-4';

if (! empty($default_plan) && $default_plan->active == 1) {
?>
        <article class="pricing-column {{ $col_span }}" style="margin-bottom: 0">
<?php if (auth()->user()->plan_id == $default_plan->id) { ?>
            <div class="ribbon"><span>{{ trans('global.current') }}</span></div>
<?php } ?>
            <div class="inner-box card-box">
                <div class="plan-header text-center"<?php if ($default_plan->description != '') echo ' style="padding-bottom:23px"'; ?>>
                    <h3 class="plan-title">{!! $default_plan->name !!}</h3>
                    <h2 class="plan-price price-monthly"><?php echo $currencyFormatter->formatCurrency($default_plan->monthly_price, $currencyRepository->get($default_plan->currency, auth()->user()->language)); ?></h2>
                    <h2 class="plan-price price-annual"><?php echo $currencyFormatter->formatCurrency($default_plan->annual_price, $currencyRepository->get($default_plan->currency, auth()->user()->language)); ?></h2>
                    <div class="plan-duration">

{{ trans('global.monthly') }}
<label class="switch">
  <input type="checkbox" class="price_switch" checked>
  <div class="slider round"></div>
</label>
{{ trans('global.annual') }}

                    </div>
<?php if ($default_plan->description != '') { ?>
                    <h4 class="m-b-0">{!! $default_plan->description !!}</h4>
<?php } else { ?>
<?php } ?>
                </div>

                <ul class="plan-stats list-unstyled text-center">
<?php
foreach($items as $item) {
  if ($item['creatable']) {

    $max = ($item['in_plan_amount']) ? ' (' . $default_plan->limitations[$item['namespace']]['max'] . ')' : '';
?>
                 <li class="plan-item"><?php echo ($default_plan->limitations[$item['namespace']]['visible'] == 1) ? '<i class="ti-check text-success"></i>' : '<i class="ti-na text-danger"></i>'; ?> {{ $item['name'] . $max }}</li>
<?php 
    if (isset($item['extra_plan_config_string']) && count($item['extra_plan_config_string']) > 0) { 
      foreach ($item['extra_plan_config_string'] as $config => $value) {
        $val = (isset($default_plan->limitations[$item['namespace']][$config])) ? $default_plan->limitations[$item['namespace']][$config] : '-';
        if (is_numeric($val)) $val = $decimalFormatter->format($val);
?>
                 <li><span class="sub">{{ trans($item['namespace'] . '::global.' . $config) . ': ' . $val }}</span></li>
<?php 
      }
    }

    if (isset($item['extra_plan_config_boolean']) && count($item['extra_plan_config_boolean']) > 0) { 
      foreach ($item['extra_plan_config_boolean'] as $config => $value) {
        $val = (isset($default_plan->limitations[$item['namespace']][$config]) && $default_plan->limitations[$item['namespace']][$config]== 1) ? '<i class="ti-check text-success" style="font-size:12px; top:-1px; position:relative"></i>' : '<i class="ti-na text-danger" style="font-size:12px; top:-1px; position:relative"></i>';
        if ($config != 'edit_html') {
?>
                 <li><span class="sub">{!! $val . ' ' . trans($item['namespace'] . '::global.' . $config) !!}</span></li>
<?php 
        }
      }
    }
  } 
}
?>
                </ul>

                <div class="text-center">
<?php

if (\Auth::user()->plan_id == $default_plan->id) {
  $btn_text = trans('global.current_plan');
  $btn_link = 'javascript:void(0);';
  $btn_target = '';
  $disabled = false;
  $btn_class = 'primary';
} elseif (! $disabled) {

  $order_url = (isset($default_plan->order_url)) ? $default_plan->order_url . '&CUSTOMERID=' . \Auth::user()->id : '';

  $btn_text = trans('global.expired');
  //$btn_text = (\Auth::user()->plan->order > $default_plan->order) ? trans('global.downgrade') : trans('global.upgrade');

  $btn_link = ($order_url != '') ? $order_url : 'javascript:void(0);';
  $btn_target = '';
  //$btn_target = ($order_url != '') ? '_blank' : '';
  $btn_class = 'warning';
} else {
  $btn_text = trans('global.order_now');
  $btn_link = 'javascript:void(0);';
  $btn_target = '';
  $btn_class = 'warning';
}

?>
                    <a href="{{ $btn_link }}" class="select-plan btn btn-{{ $btn_class }} btn-bordred btn-rounded waves-effect waves-light"<?php if ($disabled || \Auth::user()->plan_id == $default_plan->id || $btn_link == 'javascript:void(0);') echo ' disabled'; ?><?php if ($btn_target != '') echo ' target="' . $btn_target . '"'; ?>>{{ $btn_text }}</a>
                </div>
            </div>
        </article>
<?php
} else {
  // Default free plan
?>
        <article class="pricing-column {{ $col_span }}" style="margin-bottom: 0">
<?php if (auth()->user()->plan_id == null) { ?>
            <div class="ribbon"><span>{{ trans('global.current') }}</span></div>
<?php } ?>
            <div class="inner-box card-box">
                <div class="plan-header text-center">
                    <h3 class="plan-title">&nbsp;</h3>
                    <h2 class="plan-price">{{ trans('global.free') }}</h2>
                    <div class="plan-duration">

{{ trans('global.monthly') }}
<label class="switch">
  <input type="checkbox" class="price_switch" checked>
  <div class="slider round"></div>
</label>
{{ trans('global.annual') }}

                    </div>
                </div>
                <ul class="plan-stats list-unstyled text-center">
<?php
foreach($items as $item) {
  if ($item['creatable']) {

    $max = ($item['in_free_plan_default_amount'] && $item['in_free_plan']) ? ' (' . $item['in_free_plan_default_amount'] . ')' : '';
?>
                 <li class="plan-item"><?php echo ($item['in_free_plan']) ? '<i class="ti-check text-success"></i>' : '<i class="ti-na text-danger"></i>'; ?> {{ $item['name'] . $max }}</li>
<?php 
    if (isset($item['extra_plan_config_string']) && count($item['extra_plan_config_string']) > 0) { 
      foreach ($item['extra_plan_config_string'] as $config => $value) {
        $val = (isset($plan->limitations[$item['namespace']][$config])) ? $plan->limitations[$item['namespace']][$config] : '-';
        if (is_numeric($val)) $val = $decimalFormatter->format($val);
?>
                 <li><span class="sub">{{ trans($item['namespace'] . '::global.' . $config) . ': ' . $val }}</span></li>

<?php 
      }
    }

    if (isset($item['extra_plan_config_boolean']) && count($item['extra_plan_config_boolean']) > 0) { 
      foreach ($item['extra_plan_config_boolean'] as $config => $value) {
        $val = (isset($plan->limitations[$item['namespace']][$config]) && $plan->limitations[$item['namespace']][$config]== 1) ? '<i class="ti-check text-success" style="font-size:12px; top:-1px; position:relative"></i>' : '<i class="ti-na text-danger" style="font-size:12px; top:-1px; position:relative"></i>';
        if ($config != 'edit_html') {
?>
                 <li><span class="sub">{!! $val . ' ' . trans($item['namespace'] . '::global.' . $config) !!}</span></li>
<?php 
        }
      }
    }
  } 
}
?>
                </ul>

                <div class="text-center">
<?php
if (\Auth::user()->plan_id == 0) {
  $btn_text = trans('global.current_plan');
  $btn_link = 'javascript:void(0);';
  $disabled = false;
  $btn_class = 'primary';
} else {
  $btn_text = trans('global.free');
  $btn_link = 'javascript:void(0);';
  $btn_class = 'default';
}
?>
                    <a href="{{ $btn_link }}" class="select-plan btn btn-{{ $btn_class }} btn-bordred btn-rounded waves-effect waves-light" disabled>{{ $btn_text }}</a>
                </div>
            </div>
        </article>
<?php
}

foreach($plans as $plan) {
?>
        <article class="pricing-column {{ $col_span }}" style="margin-bottom: 0">
<?php if (auth()->user()->plan_id == $plan->id) { ?>
            <div class="ribbon"><span>{{ trans('global.current') }}</span></div>
<?php } ?>
            <div class="inner-box card-box">
                <div class="plan-header text-center"<?php if ($plan->description != '') echo ' style="padding-bottom:23px"'; ?>>
                    <h3 class="plan-title">{!! $plan->name !!}</h3>
                    <h2 class="plan-price price-monthly"><?php echo $currencyFormatter->formatCurrency($plan->monthly_price, $currencyRepository->get($plan->currency, auth()->user()->language)); ?></h2>
                    <h2 class="plan-price price-annual"><?php echo $currencyFormatter->formatCurrency($plan->annual_price, $currencyRepository->get($plan->currency, auth()->user()->language)); ?></h2>
                    <div class="plan-duration">

{{ trans('global.monthly') }}
<label class="switch">
  <input type="checkbox" class="price_switch" checked>
  <div class="slider round"></div>
</label>
{{ trans('global.annual') }}

                    </div>
<?php if ($plan->description != '') { ?>
                    <h4 class="m-b-0">{!! $plan->description !!}</h4>
<?php } else { ?>
<?php } ?>
                </div>

                <ul class="plan-stats list-unstyled text-center">
<?php
foreach($items as $item) {
  if ($item['creatable']) {

    $max = ($item['in_plan_amount'] && $plan->limitations[$item['namespace']]['visible'] == 1) ? ' (' . $plan->limitations[$item['namespace']]['max'] . ')' : '';
?>
                 <li class="plan-item"><?php echo ($plan->limitations[$item['namespace']]['visible'] == 1) ? '<i class="ti-check text-success"></i>' : '<i class="ti-na text-danger"></i>'; ?> {{ $item['name'] . $max }}</li>
<?php 
    if (isset($item['extra_plan_config_string']) && count($item['extra_plan_config_string']) > 0) { 
      foreach ($item['extra_plan_config_string'] as $config => $value) {
        $val = (isset($plan->limitations[$item['namespace']][$config])) ? $plan->limitations[$item['namespace']][$config] : '-';
        if (is_numeric($val)) $val = $decimalFormatter->format($val);
?>
                 <li><span class="sub">{{ trans($item['namespace'] . '::global.' . $config) . ': ' . $val }}</span></li>

<?php 
      }
    }

    if (isset($item['extra_plan_config_boolean']) && count($item['extra_plan_config_boolean']) > 0) { 
      foreach ($item['extra_plan_config_boolean'] as $config => $value) {
        $val = (isset($plan->limitations[$item['namespace']][$config]) && $plan->limitations[$item['namespace']][$config]== 1) ? '<i class="ti-check text-success" style="font-size:12px; top:-1px; position:relative"></i>' : '<i class="ti-na text-danger" style="font-size:12px; top:-1px; position:relative"></i>';
        if ($config != 'edit_html') {
?>
                 <li><span class="sub">{!! $val . ' ' . trans($item['namespace'] . '::global.' . $config) !!}</span></li>
<?php 
        }
      }
    }
  } 
}
?>
                </ul>

                <div class="text-center">
<?php

if (\Auth::user()->plan_id == $plan->id) {
  $btn_text_monthly = trans('global.current_plan');
  $btn_text_annual = trans('global.current_plan');

  $btn_link_monthly = 'javascript:void(0);';
  $btn_link_annual = 'javascript:void(0);';

  $btn_target = '';
  $disabled = false;
  $btn_class = 'primary';
} elseif (! $disabled) {

  $monthly_order_url = (isset($plan->monthly_order_url)) ? $plan->monthly_order_url . '&CUSTOMERID=' . \Auth::user()->id : '';
  $monthly_upgrade_url = (isset($plan->monthly_upgrade_url)) ? $plan->monthly_upgrade_url . '&CUSTOMERID=' . \Auth::user()->id : '';
  $annual_order_url = (isset($plan->annual_order_url)) ? $plan->annual_order_url . '&CUSTOMERID=' . \Auth::user()->id : '';
  $annual_upgrade_url = (isset($plan->annual_upgrade_url)) ? $plan->annual_upgrade_url . '&CUSTOMERID=' . \Auth::user()->id : '';

  //$btn_text = trans('global.order_now');
  //$btn_text = (\Auth::user()->plan->order > $plan->order) ? trans('global.downgrade') : trans('global.upgrade');
  $btn_text_monthly = trans('global.order_1_month');
  $btn_text_annual = trans('global.order_1_year');

  $btn_link_monthly = 'javascript:void(0);';
  $btn_link_annual = 'javascript:void(0);';

  if ($monthly_order_url != '') $btn_link_monthly = 'javascript:openExternalPurchaseUrl(\'' . $monthly_order_url . $payment_link_suffix . '\');';
  if ($annual_order_url != '') $btn_link_annual = 'javascript:openExternalPurchaseUrl(\'' . $annual_order_url . $payment_link_suffix . '\');';

  $btn_target = '';
  //$btn_target = ($order_url != '') ? '_blank' : '';
  $btn_class = 'warning';

  /*
  if (\Auth::user()->plan->order > $plan->order) {
    $disabled = true;

    $btn_text_monthly = trans('global.order_1_month');
    $btn_text_annual = trans('global.order_1_year');

    $btn_link_monthly = 'javascript:void(0);';
    $btn_link_annual = 'javascript:void(0);';
  }*/
} else {
  $btn_text_monthly = trans('global.order_1_month');
  $btn_text_annual = trans('global.order_1_year');

  $btn_link = 'javascript:void(0);';
  $btn_target = '';
  $btn_class = 'warning';
}

?>
                    <div class="order-btn-monthly">
                      <a href="{{ $btn_link_monthly }}" class="select-plan btn btn-{{ $btn_class }} btn-bordred btn-rounded waves-effect waves-light"<?php if ($disabled || \Auth::user()->plan_id == $plan->id || $btn_link_monthly == 'javascript:void(0);') echo ' disabled'; ?><?php if ($btn_target != '') echo ' target="' . $btn_target . '"'; ?>>{{ $btn_text_monthly }}</a>
                    </div>
                    <div class="order-btn-annual">
                      <a href="{{ $btn_link_annual }}" class="select-plan btn btn-{{ $btn_class }} btn-bordred btn-rounded waves-effect waves-light"<?php if ($disabled || \Auth::user()->plan_id == $plan->id || $btn_link_annual == 'javascript:void(0);') echo ' disabled'; ?><?php if ($btn_target != '') echo ' target="' . $btn_target . '"'; ?>>{{ $btn_text_annual }}</a>
                   </div>
                </div>
            </div>
        </article>
<?php
}
?>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
<style type="text/css">
  .price-monthly,
  .order-btn-monthly {
    display: none;
  }
  span.sub {
    font-size: 15px;
    font-weight: bold;
  }
  li.plan-item {
    font-size: 18px;
  }
  .pricing-column .plan-header {
    padding-bottom: 0 !important;
  }

  .plan-duration {
    margin-top: 30px;
    text-transform: uppercase;
  }
/* The switch - the box around the slider */
.switch {
  position: relative;
  display: inline-block;
  width: 50px;
  height: 24px;
  margin-bottom: -7px;
  margin-left: 5px;
  margin-right: 5px;
}

/* Hide default HTML checkbox */
.switch input {display:none;}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #138dfa;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 16px;
  width: 16px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #138dfa;
}

input:focus + .slider {
  box-shadow: 0 0 1px #138dfa;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 24px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
<script>

$('.price_switch').change(function() {
    if (this.checked) {
      $('.price_switch').prop('checked', true);
      $('.price-monthly').hide();
      $('.price-annual').show();
      $('.order-btn-monthly').hide();
      $('.order-btn-annual').show();
    } else {
      $('.price_switch').prop('checked', false);
      $('.price-monthly').show();
      $('.price-annual').hide();
      $('.order-btn-monthly').show();
      $('.order-btn-annual').hide();
    }
});

  
function openExternalPurchaseUrl(url) {

  swal({
    title: "{{ trans('global.change_plan') }}", 
    text: "{{ trans('global.upgrade_before_link') }}", 
    showCancelButton: true,
    cancelButtonText: "{{ trans('global.cancel') }}",
    confirmButtonColor: "#138dfa",
    confirmButtonText: "{{ trans('global.got_it') }}"
  }).then(function (result) {

    window.open(url);

  }, function (dismiss) {
    // Do nothing on cancel
    // dismiss can be 'cancel', 'overlay', 'close', and 'timer'
  });
}
</script>