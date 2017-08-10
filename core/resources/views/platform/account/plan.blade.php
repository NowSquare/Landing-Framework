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
$plan_count = count($all_plans);

$col_span = 'col-md-12';
if ($plan_count == 2 || $plan_count%5 == 0) $col_span = 'col-md-6';
if ($plan_count%3 == 0 || $plan_count%4 == 0 || $plan_count%7 == 0) $col_span = 'col-md-4';

foreach($all_plans as $plan) {
?>
          <article class="pricing-column {{ $col_span }}" style="margin-bottom: 0">
<?php if ($plan['current']) { ?>
            <div class="ribbon"><span>{{ trans('global.current') }}</span></div>
<?php } ?>
            <div class="inner-box card-box">
                <div class="plan-header text-center"<?php if ($plan['description'] != '') echo ' style="padding-bottom:23px"'; ?>>
                    <h3 class="plan-title">{!! $plan['name'] !!}</h3>
<?php if ($plan['annual_price'] != null) { ?>
                    <h2 class="plan-price price-monthly"><?php echo $plan['monthly_price']; ?></h2>
                    <h2 class="plan-price price-annual"><?php echo $plan['annual_price']; ?></h2>
<?php } else { ?>
                    <h2 class="plan-price"><?php echo $plan['monthly_price']; ?></h2>
<?php } ?>
                    <p class="text-muted"><small>{{ trans('global.per_month') }}</small></p>
                    <div class="plan-duration">

 <?php if ($plan['annual_price'] != null) { ?>
                    {{ trans('global.month') }}
                    <label class="switch">
                      <input type="checkbox" class="price_switch" checked>
                      <div class="slider round"></div>
                    </label>
                    {{ trans('global.year') }}
<?php } elseif ($annual_plans_exist) { ?>
                      <div style="float: left; width:100%;height:52px"></div>
<?php } ?>

                    </div>
<?php if ($plan['description'] != '') { ?>
                    <h4 class="m-b-0">{!! $plan['description'] !!}</h4>
<?php } else { ?>
<?php } ?>
                </div>

                <ul class="plan-stats list-unstyled text-center">
<?php
foreach($plan['plan_items'] as $item) {

    $max = ($item['max'] != '') ? ' (' . $item['max'] . ')' : '';
?>
                  <li class="plan-item"><?php echo ($item['visible']) ? '<i class="ti-check text-success"></i>' : '<i class="ti-na text-danger"></i>'; ?> {{ $item['name'] . $max }}</li>
<?php
  foreach($item['sub_items'] as $sub_item) {
    if ($sub_item['type'] == 'boolean') {
      $val = ($sub_item['val']) ? '<i class="ti-check text-success" style="font-size:12px; top:-1px; position:relative"></i>' : '<i class="ti-na text-danger" style="font-size:12px; top:-1px; position:relative"></i>';
?>
                  <li><span class="sub">{!! $val . ' ' . $sub_item['name'] !!}</span></li>
<?php
    } else {
?>
                  <li><span class="sub">{!! $sub_item['name'] . ': ' . $sub_item['val'] !!}</span></li>
<?php 
    }
  }
}
?>
                </ul>
                <div class="text-center">
                  <div class="<?php if ($plan['annual_price'] != null) { ?>order-btn-monthly<?php } ?>">
                    <a href="{{ $plan['monthly_link'] }}" class="select-plan btn btn-{{ $plan['btn_class'] }} btn-bordred btn-rounded waves-effect waves-light"<?php if ($plan['disabled'] || $plan['current'] || $plan['monthly_link'] == 'javascript:void(0);') echo ' disabled'; ?><?php if ($plan['btn_target'] != '') echo ' target="' . $plan['btn_target'] . '"'; ?>>{{ $plan['monthly_text'] }}</a>
                  </div>
<?php if ($plan['annual_price'] != null) { ?>
                  <div class="order-btn-annual">
                    <a href="{{ $plan['annual_link'] }}" class="select-plan btn btn-{{ $plan['btn_class'] }} btn-bordred btn-rounded waves-effect waves-light"<?php if ($plan['disabled'] || $plan['current'] || $plan['annual_link'] == 'javascript:void(0);') echo ' disabled'; ?><?php if ($plan['btn_target'] != '') echo ' target="' . $plan['btn_target'] . '"'; ?>>{{ $plan['annual_text'] }}</a>
                  </div>
<?php } ?>
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
<?php
if ($reseller->stripe_key != null) {
?>
<script>
var selected_plan_id, selected_stripe_plan_id;

var handler = StripeCheckout.configure({
  key: '{{ $reseller->stripe_key }}',
  locale: 'auto',
  zipCode: false,
  billingAddress: false,
  token: function(token) {
    // You can access the token ID with `token.id`.
    // Get the token ID to your server-side code for use.
    blockUI();

    var jqxhr = $.ajax({
      url: "{{ url('platform/stripe/token') }}",
      data: {token: token.id, email: token.email, type: token.type, plan_id: selected_plan_id, stripe_plan_id: selected_stripe_plan_id, _token: '<?= csrf_token() ?>'},
      method: 'POST'
    })
    .done(function(data) {
      
    })
    .fail(function() {
      console.log('error');
    })
    .always(function() {
      unblockUI();
    });
  }
}); 

function openExternalPurchaseUrl(plan_name, plan_description, plan_currency, plan_amount, stripe_plan_id, plan_id) {
  selected_stripe_plan_id = stripe_plan_id;
  selected_plan_id = plan_id;
  // Open Checkout with further options:
  handler.open({
    name: plan_name,
    description: plan_description,
    currency: plan_currency,
    amount: plan_amount,
    panelLabel: '{{ trans('global.subscribe') }}',
  });

  // Close Checkout on page navigation:
  window.addEventListener('popstate', function() {
    handler.close();
  });
}

function openExternalPurchaseUrlDemo() {
  swal({
    title: "{{ trans('global.change_plan') }}", 
    text: "{{ trans('global.login_demo_mode') }}", 
    showCancelButton: false,
    confirmButtonColor: "#138dfa",
    confirmButtonText: "{{ trans('global.got_it') }}"
  }).then(function (result) {


  }, function (dismiss) {
    // Do nothing on cancel
    // dismiss can be 'cancel', 'overlay', 'close', and 'timer'
  });
}
</script>
<?php } else { ?>
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

function openExternalPurchaseUrlDemo() {
  swal({
    title: "{{ trans('global.change_plan') }}", 
    text: "{{ trans('global.login_demo_mode') }}", 
    showCancelButton: false,
    confirmButtonColor: "#138dfa",
    confirmButtonText: "{{ trans('global.got_it') }}"
  }).then(function (result) {

  }, function (dismiss) {
    // Do nothing on cancel
    // dismiss can be 'cancel', 'overlay', 'close', and 'timer'
  });
}
</script>
<?php } ?>
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
</script>