<div class="container">

  <div class="row m-t">
    <div class="col-sm-12">
      <nav class="navbar navbar-default card-box sub-navbar">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('global.account') }}</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">\</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('global.plan') }}</a>
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
if ($plan_count%4 == 0) $col_span = 'col-md-3';


if (! empty($default_plan) && $default_plan->active == 1) {
?>
        <article class="pricing-column {{ $col_span }}" style="margin-bottom: 0">
<?php if ($default_plan->ribbon != '') { ?>
            <div class="ribbon"><span>{{ $default_plan->ribbon }}</span></div>
<?php } ?>
            <div class="inner-box card-box">
                <div class="plan-header text-center"<?php if ($default_plan->price1_subtitle != '') echo ' style="padding-bottom:23px"'; ?>>
                    <h3 class="plan-title">{!! $default_plan->name !!}</h3>
                    <h2 class="plan-price">{!! $default_plan->price1_string !!}</h2>
                    <div class="plan-duration"><?php echo (\Lang::has('global.' . $default_plan->price1_period_string)) ? trans('global.' . $default_plan->price1_period_string) : $default_plan->price1_period_string; ?></div>
<?php if ($default_plan->price1_subtitle != '') { ?>
                    <h4 class="m-b-0">{!! $default_plan->price1_subtitle !!}</h4>
<?php } else { ?>
<?php } ?>
                </div>

                <ul class="plan-stats list-unstyled text-center">
<?php
foreach($items as $item) {
  if ($item['creatable']) {

    $max = ($item['in_plan_amount']) ? ' (' . $default_plan->limitations[$item['namespace']]['max'] . ')' : '';
?>
                 <li><?php echo ($default_plan->limitations[$item['namespace']]['visible'] == 1) ? '<i class="ti-check text-success"></i>' : '<i class="ti-na text-danger"></i>'; ?> {{ $item['name'] . $max }}</li>
<?php 
    if (isset($item['extra_plan_config_string']) && count($item['extra_plan_config_string']) > 0) { 
      foreach ($item['extra_plan_config_string'] as $config => $value) {
        $val = (isset($default_plan->limitations[$item['namespace']][$config])) ? $default_plan->limitations[$item['namespace']][$config] : '-';
?>
                 <li>{{ trans($item['namespace'] . '::global.' . $config) . ': ' . $val }}</li>
<?php 
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

  $btn_text = trans('global.order_now');
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

if ($btn_link != 'javascript:void(0);') $btn_link = 'javascript:openExternalPurchaseUrl(\'' . $btn_link . '\');';
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
            <div class="inner-box card-box">
                <div class="plan-header text-center">
                    <h3 class="plan-title">&nbsp;</h3>
                    <h2 class="plan-price">{{ trans('global.free') }}</h2>
                    <div class="plan-duration">&nbsp;</div>
                </div>
                <ul class="plan-stats list-unstyled text-center">
<?php
foreach($items as $item) {
  if ($item['creatable']) {

    $max = ($item['in_free_plan_default_amount'] && $item['in_free_plan']) ? ' (' . $item['in_free_plan_default_amount'] . ')' : '';
?>
                 <li><?php echo ($item['in_free_plan']) ? '<i class="ti-check text-success"></i>' : '<i class="ti-na text-danger"></i>'; ?> {{ $item['name'] . $max }}</li>
<?php 
    if (isset($item['extra_plan_config_string']) && count($item['extra_plan_config_string']) > 0) { 
      foreach ($item['extra_plan_config_string'] as $config => $value) {
        $val = (isset($plan->limitations[$item['namespace']][$config])) ? $plan->limitations[$item['namespace']][$config] : '-';
?>
                 <li>{{ trans($item['namespace'] . '::global.' . $config) . ': ' . $val }}</li>

<?php 
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

if ($btn_link != 'javascript:void(0);') $btn_link = 'javascript:openExternalPurchaseUrl(\'' . $btn_link . '\');';
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
<?php if ($plan->ribbon != '') { ?>
            <div class="ribbon"><span>{{ $plan->ribbon }}</span></div>
<?php } ?>
            <div class="inner-box card-box">
                <div class="plan-header text-center"<?php if ($plan->price1_subtitle != '') echo ' style="padding-bottom:23px"'; ?>>
                    <h3 class="plan-title">{!! $plan->name !!}</h3>
                    <h2 class="plan-price">{!! $plan->price1_string !!}</h2>
                    <div class="plan-duration"><?php echo (\Lang::has('global.' . $plan->price1_period_string)) ? trans('global.' . $plan->price1_period_string) : $plan->price1_period_string; ?></div>
<?php if ($plan->price1_subtitle != '') { ?>
                    <h4 class="m-b-0">{!! $plan->price1_subtitle !!}</h4>
<?php } else { ?>
<?php } ?>
                </div>

                <ul class="plan-stats list-unstyled text-center">
<?php
foreach($items as $item) {
  if ($item['creatable']) {

    $max = ($item['in_plan_amount'] && $plan->limitations[$item['namespace']]['visible'] == 1) ? ' (' . $plan->limitations[$item['namespace']]['max'] . ')' : '';
?>
                 <li><?php echo ($plan->limitations[$item['namespace']]['visible'] == 1) ? '<i class="ti-check text-success"></i>' : '<i class="ti-na text-danger"></i>'; ?> {{ $item['name'] . $max }}</li>
<?php 
    if (isset($item['extra_plan_config_string']) && count($item['extra_plan_config_string']) > 0) { 
      foreach ($item['extra_plan_config_string'] as $config => $value) {
        $val = (isset($plan->limitations[$item['namespace']][$config])) ? $plan->limitations[$item['namespace']][$config] : '-';
?>
                 <li>{{ trans($item['namespace'] . '::global.' . $config) . ': ' . $val }}</li>

<?php 
      }
    }
  } 
}
?>
                </ul>

                <div class="text-center">
<?php

if (\Auth::user()->plan_id == $plan->id) {
  $btn_text = trans('global.current_plan');
  $btn_link = 'javascript:void(0);';
  $btn_target = '';
  $disabled = false;
  $btn_class = 'primary';
} elseif (! $disabled) {

  $order_url = (isset($plan->order_url)) ? $plan->order_url . '&CUSTOMERID=' . \Auth::user()->id : '';

  $btn_text = trans('global.order_now');
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

if ($btn_link != 'javascript:void(0);') $btn_link = 'javascript:openExternalPurchaseUrl(\'' . $btn_link . '\');';
?>
                    <a href="{{ $btn_link }}" class="select-plan btn btn-{{ $btn_class }} btn-bordred btn-rounded waves-effect waves-light"<?php if ($disabled || \Auth::user()->plan_id == $plan->id || $btn_link == 'javascript:void(0);') echo ' disabled'; ?><?php if ($btn_target != '') echo ' target="' . $btn_target . '"'; ?>>{{ $btn_text }}</a>
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
<script>
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