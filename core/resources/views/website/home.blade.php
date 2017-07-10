<!DOCTYPE html>
<html lang="{{ \App::getLocale() }}">

<head>
  <title>{{ $reseller->name }}</title>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="shortcut icon" type="image/x-icon" href="{{ $reseller->favicon }}"/>

  <link rel="stylesheet" type="text/css" href="{{ url('assets/bs4/css/style.min.css') }}"/>
  <link href="//fonts.googleapis.com/css?family=Dosis:300|Open+Sans:200,300" rel="stylesheet">
  <style type="text/css">
    body {
      font-family: 'Open Sans', sans-serif;
      font-weight: 300;
    }
    
    h1,
    h2,
    h3 {
      font-family: 'Dosis', sans-serif;
      font-weight: 300 !important;
    }
    
    .price-monthly,
    .order-btn-monthly {
      display: none;
    }
    .i-small {
      font-size: 12px;
      position: relative;
      bottom: 2px;
    }
    .plan-card {
      min-width: 180px;
    }
    .plan-card {
      font-weight: 200;
    }
    .plan-card h4 {
      font-size: 1.2rem;
    }
    .plan-duration {
      margin-top: 30px;
      text-transform: uppercase;
      font-size: 13px;
      font-weight: normal;
      color: #aaa;
      text-align: center;
    }

    .pricing.pricing-style-1 .table .price div {
      font-size: 2rem;
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
    
    .switch input {
      display: none;
    }
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
    
    input:checked+ .slider {
      background-color: #138dfa;
    }
    
    input:focus+ .slider {
      box-shadow: 0 0 1px #138dfa;
    }
    
    input:checked+ .slider:before {
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
    
    .owl-theme .owl-nav.disabled+.owl-dots {
      margin-top: 40px;
    }
  </style>
  <script src="{{ url('assets/bs4/js/scripts.min.js') }}"></script>
  <script>
    $(function(){
      $('.carousel').owlCarousel({
        loop: true,
        nav: false,
        responsive:{
          0:{
            items:1
          },
          600:{
            items:2
          },
          1000:{
            items:4
          }
        }
      });

      $( '.price_switch' ).change( function () {
        console.log(0);
        if ( this.checked ) {
          $( '.price_switch' ).prop( 'checked', true );
          $( '.price-monthly' ).hide();
          $( '.price-annual' ).show();
        } else {
          $( '.price_switch' ).prop( 'checked', false );
          $( '.price-monthly' ).show();
          $( '.price-annual' ).hide();
        }
      } );
    });
  </script>
<?php
if (env('GOOGLE_ANALYTICS_TRACKING_ID', '') != '') {
?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', '<?php echo env('GOOGLE_ANALYTICS_TRACKING_ID', '') ?>', 'auto');
  ga('send', 'pageview');
</script>
<?php
}
?>
</head>

<body>
  <section>
    <div class="content text-dark content-padding">
      <div class="content-overlay" style="background-color:rgba(38,38,38,1)">
        <div class="container">
          <div class="row">
            <div class="col-12 text-center col-sm-5 text-sm-left">
              <img src="{{ $reseller->logo }}" style="height: 42px;" alt="{{ $reseller->name }}">
            </div>
            <div class="col-12 col-sm-7 pt-4 pb-2 py-sm-0">
              <ul class="nav justify-content-center justify-content-sm-end">
                <li class="nav-item">
                  <a class="nav-link color-grey" href="#pricing">{!! trans('website.pricing') !!}</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link color-grey" href="{{ url('login') }}">{!! trans('website.login') !!}</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link color-grey" href="{{ url('register') }}">{!! trans('website.register') !!}</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


  <?php /*
  <section>
    <nav class="navbar navbar-toggleable-md navbar-light bg-light ">
      <div class="container">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#collapsingNavbarLight" aria-controls="collapsingNavbarLight" aria-expanded="false" aria-label="Toggle navigation">
            &#9776;
          </button>
      
        <div class="collapse navbar-collapse" id="collapsingNavbarLight">
          <ul class="nav navbar-nav pull-right">
            <li class="nav-item">
              <a class="nav-link" href="#">Features</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Pricing</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Login</a>
            </li>
          </ul>
        </div>
        <!-- /.collapse -->
      </div>
      <!-- /.container -->
    </nav>
  </section>
*/ ?>
  <section>
    <div class="header text-dark">
      <div class="header-overlay" style="background: #ccc; background: linear-gradient(to bottom, #fff 0%,#ccc 100%);">
        <div class="container">
          <div class="header-padding-l text-center no-padding-b">
            <div class="row">
              <div class="col-12">
<?php /*
                <img src="{{ $reseller->logo_square }}" style="height: 96px;" alt="{{ $reseller->name }}">*/ ?>
                <h1 class="display-3">{!! trans('website.header') !!}</h1>
                <p class="lead">{!! trans('website.header_01_line') !!}</p>
                <div class="btn-container mt-3 mb-5">
                  <a class="btn btn-outline-dark-ghost btn-xlg btn-pill" href="{{ url('login') }}" role="button">{!! trans('website.header_cta') !!}</a>
                </div>
                <img src="{{ url('templates/assets/images/visuals/landing-screens-' . \App::getLocale() . '.png') }}" alt="" class="img-fluid" style="margin:auto">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php /*
  <section>
    <div class="content content-padding-l" style="">
      <div class="content-overlay" style="background-color:rgba(0,0,0,0.05)">
        <div class="container">
          <div class="row">
            <div class="col-12 col-md-4 text-center">
              <div class="row">
                <div class="col-6 push-3 col-sm-6 push-sm-3 col-lg-6 push-lg-3 text-center">
                  <img src="{{ url('templates/assets/images/icons/electrical_sensor.svg') }}" alt="" class="-x-img img-fluid">
                </div>
                <div class="col-12">
                  <h2 class="mt-1">{!! trans('landingpages::block.content_title1') !!}</h2>
                  <p class="lead">{!! trans('landingpages::block.content_lead1') !!}</p>
                  <p class="btn-container">
                    <a class="btn btn-blue btn-pill" href="#" role="button">{!! trans('landingpages::block.content_button') !!}</a>
                  </p>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-4 text-center">
              <div class="row">
                <div class="col-6 push-3 col-sm-6 push-sm-3 col-lg-6 push-lg-3 text-center">
                  <img src="{{ url('templates/assets/images/icons/camcorder.svg') }}" alt="" class="-x-img img-fluid">
                </div>
                <div class="col-12">
                  <h2 class="mt-1">{!! trans('landingpages::block.content_title2') !!}</h2>
                  <p class="lead">{!! trans('landingpages::block.content_lead1') !!}</p>
                  <p class="btn-container">
                    <a class="btn btn-blue btn-pill" href="#" role="button">{!! trans('landingpages::block.content_button') !!}</a>
                  </p>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-4 text-center">
              <div class="row">
                <div class="col-6 push-3 col-sm-6 push-sm-3 col-lg-6 push-lg-3 text-center">
                  <img src="{{ url('templates/assets/images/icons/mind_map.svg') }}" alt="" class="-x-img img-fluid">
                </div>
                <div class="col-12">
                  <h2 class="mt-1">{!! trans('landingpages::block.content_title3') !!}</h2>
                  <p class="lead">{!! trans('landingpages::block.content_lead1') !!}</p>
                  <p class="btn-container">
                    <a class="btn btn-blue btn-pill" href="#" role="button">{!! trans('landingpages::block.content_button') !!}</a>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>




  <section>
    <div class="content content-padding-xxl" style="">
      <div class="content-overlay" style="background-color:rgba(255,255,255,1)">
        <div class="container">

          <div class="row">
            <div class="col-sm-12 col-md-4 text-center text-lg-right mt-3 no-margin-md-t">
              <div class="content-padding">
                <i class="mb-3 icon-5 color-deep-purple -x-icon mi timeline" data-attachment="bottom left" data-target-attachment="top left"></i>
                <h2>{!! trans('landingpages::block.content_feature') !!}</h2>
                <p class="lead">{!! trans('landingpages::block.content_feature_line') !!}</p>
              </div>
              <div class="content-padding">
                <i class="mb-3 icon-5 color-deep-purple -x-icon mi lightbulb_outline" data-attachment="bottom left" data-target-attachment="top left"></i>
                <h2>{!! trans('landingpages::block.content_feature') !!}</h2>
                <p class="lead">{!! trans('landingpages::block.content_feature_line') !!}</p>
              </div>
            </div>
            <div class="col-6 push-3 col-sm-6 push-sm-3 col-md-4 push-md-0 text-center">
              <img src="/templates/assets/images/visuals/iPhone-6-4,7-inch-Mockup.png" alt="" class="-x-img img-fluid">
            </div>
            <div class="col-sm-12 col-md-4 text-center text-lg-left mt-3 no-margin-md-t">
              <div class="content-padding">
                <i class="mb-3 icon-5 color-deep-purple -x-icon mi touch_app" data-attachment="bottom left" data-target-attachment="top right" data-dropdown-position="left"></i>
                <h2>{!! trans('landingpages::block.content_feature') !!}</h2>
                <p class="lead">{!! trans('landingpages::block.content_feature_line') !!}</p>
              </div>
              <div class="content-padding">
                <i class="mb-3 icon-5 color-deep-purple -x-icon mi fingerprint" data-attachment="bottom left" data-target-attachment="top right" data-dropdown-position="left"></i>
                <h2>{!! trans('landingpages::block.content_feature') !!}</h2>
                <p class="lead">{!! trans('landingpages::block.content_feature_line') !!}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

*/ ?>

  <section id="pricing_">
    <div class="pricing pricing-style-1 text-dark">
      <div class="pricing-overlay">
        <div class="container">
          <div class="row">

            <div class="carousel owl-carousel owl-theme">

              <?php
              $disabled = false;

              if ( !empty( $default_plan ) && $default_plan->active == 1 ) {
                $currency = $default_plan->currency;
                if ( trans( 'i18n.default_currency' ) != $currency && isset( $default_plan->monthly_price_currencies[ trans( 'i18n.default_currency' ) ] ) ) {
                  $monthly_price = $currencyFormatter->formatCurrency( $default_plan->monthly_price_currencies[ trans( 'i18n.default_currency' ) ], $currencyRepository->get( trans( 'i18n.default_currency' ), $reseller->default_language ) );
                } else {
                  $monthly_price = $currencyFormatter->formatCurrency( $default_plan->monthly_price, $currencyRepository->get( $default_plan->currency, $reseller->default_language ) );
                }

                if ( trans( 'i18n.default_currency' ) != $currency && isset( $default_plan->annual_price_currencies[ trans( 'i18n.default_currency' ) ] ) ) {
                  $annual_price = $currencyFormatter->formatCurrency( $default_plan->annual_price_currencies[ trans( 'i18n.default_currency' ) ], $currencyRepository->get( trans( 'i18n.default_currency' ), $reseller->default_language ) );
                } else {
                  $annual_price = $currencyFormatter->formatCurrency( $default_plan->annual_price, $currencyRepository->get( $default_plan->currency, $reseller->default_language ) );
                }

                $monthly_price = str_replace( [ '.00', ',00' ], '', $monthly_price );
                $annual_price = str_replace( [ '.00', ',00' ], '', $annual_price );
                ?>
              <div class="plan-card card color-blue mx-5 mx-sm-0 mr-sm-4">
                <table class="table">
                  <thead>
                    <tr>
                      <th>
                        <h2>{!! $default_plan->name !!}</h2>
                        <?php if ($default_plan->description != '') { ?>
                        <p>{!! $default_plan->description !!}</p>
                        <?php } ?>
                      </th>
                    </tr>
                    <tr class="price">
                      <th>
                        <div class="price-monthly">
                          <?php echo $monthly_price; ?>
                        </div>
                        <div class="price-annual">
                          <?php echo $annual_price; ?>
                        </div>
                      </th>
                    </tr>
                    <tr>
                      <th>
                        <p class="text-muted"><small>{{ trans('global.per_month') }}</small>
                        </p>
                        <div class="plan-duration">
                          {{ trans('global.month') }}
                          <label class="switch">
                            <input type="checkbox" class="price_switch" checked>
                            <div class="slider round"></div>
                          </label>
                          {{ trans('global.year') }}
                        </div>

                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    foreach ( $items as $item ) {
                      if ( $item[ 'creatable' ] ) {

                        $max = ( $item[ 'in_plan_amount' ] ) ? $default_plan->limitations[ $item[ 'namespace' ] ][ 'max' ] : '';
                        ?>
                    <tr>
                      <td>
                        <h4>
                          <?php echo ($default_plan->limitations[$item['namespace']]['visible'] == 1) ? '<span class="text-success">&#10004;</span>' : '<span class="text-danger">&#10005;</span>'; ?> {{ $item['name'] }}
                           ({{ $max }})
                        </h4>
                      </td>
                    </tr>
                    <?php 
    if (isset($item['extra_plan_config_string']) && count($item['extra_plan_config_string']) > 0) { 
      foreach ($item['extra_plan_config_string'] as $config => $value) {
        $val = (isset($default_plan->limitations[$item['namespace']][$config])) ? $default_plan->limitations[$item['namespace']][$config] : '-';
        if (is_numeric($val)) $val = $decimalFormatter->format($val);
?>
                    <tr>
                      <td>
                        <h4>{{ trans($item['namespace'] . '::global.' . $config) }}: {{ $val }}</h4>
                        <hr>
                      </td>
                    </tr>
                    <?php 
      }
    }

    if (isset($item['extra_plan_config_boolean']) && count($item['extra_plan_config_boolean']) > 0) { 
      foreach ($item['extra_plan_config_boolean'] as $config => $value) {
        $val = (isset($default_plan->limitations[$item['namespace']][$config]) && $default_plan->limitations[$item['namespace']][$config] == 1) ? '<span class="text-success i-small">&#10004;</span>' : '<span class="text-danger i-small">&#10005;</span>';
        if ($config != 'edit_html') {
?>
                    <tr>
                      <td>
                        <h4>{!! $val . ' ' . trans($item['namespace'] . '::global.' . $config) !!}</h4>
                        <hr>
                      </td> 
                    </tr>
                    <?php 
        }
      }
    }
  } 
}
?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td>
                        <a href="{{ url('register') }}" class="btn btn-secondary btn-block btn-lg">{!! trans('website.get_started') !!}</a>
                      </td>
                    </tr>
                  </tfoot>
                </table>
              </div>

              <?php
              }

              foreach ( $plans as $plan ) {

                $currency = $plan->currency;
                if ( trans( 'i18n.default_currency' ) != $currency && isset( $plan->monthly_price_currencies[ trans( 'i18n.default_currency' ) ] ) ) {
                  $monthly_price = $currencyFormatter->formatCurrency( $plan->monthly_price_currencies[ trans( 'i18n.default_currency' ) ], $currencyRepository->get( trans( 'i18n.default_currency' ), $reseller->default_language ) );
                } else {
                  $monthly_price = $currencyFormatter->formatCurrency( $plan->monthly_price, $currencyRepository->get( $plan->currency, $reseller->default_language ) );
                }

                if ( trans( 'i18n.default_currency' ) != $currency && isset( $plan->annual_price_currencies[ trans( 'i18n.default_currency' ) ] ) ) {
                  $annual_price = $currencyFormatter->formatCurrency( $plan->annual_price_currencies[ trans( 'i18n.default_currency' ) ], $currencyRepository->get( trans( 'i18n.default_currency' ), $reseller->default_language ) );
                } else {
                  $annual_price = $currencyFormatter->formatCurrency( $plan->annual_price, $currencyRepository->get( $plan->currency, $reseller->default_language ) );
                }

                $monthly_price = str_replace( [ '.00', ',00' ], '', $monthly_price );
                $annual_price = str_replace( [ '.00', ',00' ], '', $annual_price );
                ?>
              <div class="plan-card card color-secondary mx-5 mx-sm-0 mr-sm-4">
                <table class="table">
                  <thead>
                    <tr>
                      <th>
                        <h2>{!! $plan->name !!}</h2>
                        <?php if ($plan->description != '') { ?>
                        <p>{!! $plan->description !!}</p>
                        <?php } ?>
                      </th>
                    </tr>
                    <tr class="price">
                      <th>
                        <div class="price-monthly">
                          <?php echo $monthly_price; ?>
                        </div>
                        <div class="price-annual">
                          <?php echo $annual_price; ?>
                        </div>
                      </th>
                    </tr>
                    <tr>
                      <th>
                        <p class="text-muted"><small>{{ trans('global.per_month') }}</small>
                        </p>

                        <div class="plan-duration">
                          {{ trans('global.month') }}
                          <label class="switch">
                            <input type="checkbox" class="price_switch" checked>
                            <div class="slider round"></div>
                          </label>
                          {{ trans('global.year') }}
                        </div>

                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    foreach ( $items as $item ) {
                      if ( $item[ 'creatable' ] ) {

                        $max = ( $item[ 'in_plan_amount' ] ) ? $plan->limitations[ $item[ 'namespace' ] ][ 'max' ] : '';
                        ?>
                    <tr>
                      <td>
                        <h4>
                          <?php echo ($plan->limitations[$item['namespace']]['visible'] == 1) ? '<span class="text-success">&#10004;</span>' : '<span class="text-danger">&#10005;</span>'; ?> {{ $item['name'] }}
                           ({{ $max }})
                        </h4>
                      </td>
                    </tr>

                    <?php 
    if (isset($item['extra_plan_config_string']) && count($item['extra_plan_config_string']) > 0) { 
      foreach ($item['extra_plan_config_string'] as $config => $value) {
        $val = (isset($plan->limitations[$item['namespace']][$config])) ? $plan->limitations[$item['namespace']][$config] : '-';
        if (is_numeric($val)) $val = $decimalFormatter->format($val);
?>
                    <tr>
                      <td>
                        <h4>{{ trans($item['namespace'] . '::global.' . $config) }}: {{ $val }}</h4>
                        <hr>
                      </td>
                    </tr>
                    <?php 
      }
    }

    if (isset($item['extra_plan_config_boolean']) && count($item['extra_plan_config_boolean']) > 0) { 
      foreach ($item['extra_plan_config_boolean'] as $config => $value) {
        $val = (isset($plan->limitations[$item['namespace']][$config]) && $plan->limitations[$item['namespace']][$config]== 1) ? '<span class="text-success i-small">&#10004;</span>' : '<span class="text-danger i-small">&#10005;</span>';
        if ($config != 'edit_html') {
?>
                    <tr>
                      <td>
                        <h4>{!! $val . ' ' . trans($item['namespace'] . '::global.' . $config) !!}</h4>
                        <hr>
                      </td>
                    </tr>
                    <?php 
        }
      }
    }
  } 
}
?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td>
                        <a href="{{ url('register') }}" class="btn btn-secondary btn-block btn-lg">{!! trans('website.get_started') !!}</a>
                      </td>
                    </tr>
                  </tfoot>
                </table>
              </div>
<?php
}
?>
            </div>
          </div>
        </div>

  </section>

  <section>
    <div class="footer text-light flex-center-md" style="">
      <div class="footer-overlay" style="background-color:rgba(0,0,0,0.9)">
        <div class="container">
          <div class="row">
            <div class="col-md-12 text-center col-lg-3 text-lg-left text-secondary my-1">
              <img src="{{ $reseller->logo }}" style="height: 64px;" alt="{{ $reseller->name }}">
              </a>
            </div>
            <div class="col-md-12 text-center col-lg-9 text-lg-right text-secondary my-1">
              <p>{!! trans('website.copyright') !!} {{ date('Y') }} {{ $reseller->name }} &bull; {!! trans('website.all_rights_reserved') !!} | <a href="mailto:{{ $reseller->support_email }}">{!! trans('website.contact') !!}</a>
              </p>

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

</body>

</html>