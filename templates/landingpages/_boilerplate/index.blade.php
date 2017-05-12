<!DOCTYPE html>
<html>
<head>
  <title>Landing Page</title>

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

  <link rel="stylesheet" type="text/css" href="{{ url('assets/bs4/css/style.min.css') }}" />
  <script src="{{ url('assets/bs4/js/scripts.min.js') }}"></script>

  <link href="//fonts.googleapis.com/css?family=Dosis:200,400|Open+Sans:300,400,700" rel="stylesheet">
  <style type="text/css">
    body {
      font-family: 'Open Sans', sans-serif;
      font-weight: 300;
    }

    h1, h2, h3, h4, h5, h6 {
      font-family: 'Dosis', sans-serif;
      font-weight: 300 !important;
    }
  </style>
</head>
<body>

<section class="-x-block">
  <div class="header text-light">
    <div class="header-overlay -x-block-bg-gradient" style="background: #4776e6; background: linear-gradient(to bottom, #4776e6 0%,#8e54e9 100%);">
      <div class="container">
        <div class="header-padding-xl text-center no-padding-b">
          <div class="row">
            <div class="col-12">
              <h1 class="display-3 -x-text">{!! trans('landingpages::block.header_01_head') !!}</h1>
              <p class="lead -x-text">{!! trans('landingpages::block.header_01_line') !!}</p>
              <div class="btn-container mt-3 mb-1">
                <a class="btn btn-outline-ghost btn-xlg btn-pill -x-link" href="#" role="button">{!! trans('landingpages::block.header_01_button') !!}</a>
              </div>
              <img src="{{ url('templates/assets/images/visuals/software01.png') }}" alt="" class="img-fluid -x-img" style="margin:auto">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="-x-block">
  <div class="content content-padding-l -x-block-bg-img" style="">
    <div class="content-overlay -x-block-bg-color" style="background-color:rgba(0,0,0,0.05)">
      <div class="container">
        <div class="row">
          <div class="col-12 col-md-4 text-center">
            <div class="row">
              <div class="col-8 push-2 col-sm-6 push-sm-3 col-lg-6 push-lg-3 text-center">
                <i class="mb-3 icon-xl color-blue -x-icon mi flight_takeoff" data-attachment="bottom left" data-target-attachment="top left"></i>
              </div>
              <div class="col-12">
                <h2 class="mt-1 -x-text">{!! trans('landingpages::block.content_title1') !!}</h2>
                <p class="lead -x-text">{!! trans('landingpages::block.content_lead1') !!}</p>
                <p class="btn-container">
                  <a class="btn btn-outline-blue btn-pill -x-link" href="#" role="button">{!! trans('landingpages::block.content_button') !!}</a>
                </p>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-4 text-center">
            <div class="row">
              <div class="col-8 push-2 col-sm-6 push-sm-3 col-lg-6 push-lg-3 text-center">
                <i class="mb-3 icon-xl color-blue -x-icon mi important_devices" data-attachment="bottom left" data-target-attachment="top left"></i>
              </div>
              <div class="col-12">
                <h2 class="mt-1 -x-text">{!! trans('landingpages::block.content_title2') !!}</h2>
                <p class="lead -x-text">{!! trans('landingpages::block.content_lead1') !!}</p>
                <p class="btn-container">
                  <a class="btn btn-outline-blue btn-pill -x-link" href="#" role="button">{!! trans('landingpages::block.content_button') !!}</a>
                </p>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-4 text-center">
            <div class="row">
              <div class="col-8 push-2 col-sm-6 push-sm-3 col-lg-6 push-lg-3 text-center">
                <i class="mb-3 icon-xl color-blue -x-icon mi room" data-attachment="bottom left" data-target-attachment="top left"></i>
              </div>
              <div class="col-12">
                <h2 class="mt-1 -x-text">{!! trans('landingpages::block.content_title3') !!}</h2>
                <p class="lead -x-text">{!! trans('landingpages::block.content_lead1') !!}</p>
                <p class="btn-container">
                  <a class="btn btn-outline-blue btn-pill -x-link" href="#" role="button">{!! trans('landingpages::block.content_button') !!}</a>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="-x-block">
  <div class="content content-padding-l -x-block-bg-img" style="">
    <div class="content-overlay -x-block-bg-color" style="background-color:rgba(255,255,255,1)">
      <div class="container">
        <div class="row">
          <div class="col-sm-12 col-md-4 text-center text-lg-right mt-3 no-margin-md-t">
            <div class="content-padding">
              <i class="mb-3 icon-xs color-deep-purple -x-icon mi timeline" data-attachment="bottom left" data-target-attachment="top left"></i>
              <h2 class="-x-text">{!! trans('landingpages::block.content_feature') !!}</h2>
              <p class="lead -x-text">{!! trans('landingpages::block.content_feature_line') !!}</p>
            </div>
            <div class="content-padding">
              <i class="mb-3 icon-xs color-deep-purple -x-icon mi lightbulb_outline" data-attachment="bottom left" data-target-attachment="top left"></i>
              <h2 class="-x-text">{!! trans('landingpages::block.content_feature') !!}</h2>
              <p class="lead -x-text">{!! trans('landingpages::block.content_feature_line') !!}</p>
            </div>
          </div>
          <div class="col-6 push-3 col-sm-6 push-sm-3 col-md-4 push-md-0 text-center">
            <img src="/templates/assets/images/visuals/iPhone-6-4,7-inch-Mockup.png" alt="" class="-x-img img-fluid">
          </div>
          <div class="col-sm-12 col-md-4 text-center text-lg-left mt-3 no-margin-md-t">
            <div class="content-padding">
              <i class="mb-3 icon-xs color-deep-purple -x-icon mi touch_app" data-attachment="bottom left" data-target-attachment="top right" data-dropdown-position="left"></i>
              <h2 class="-x-text">{!! trans('landingpages::block.content_feature') !!}</h2>
              <p class="lead -x-text">{!! trans('landingpages::block.content_feature_line') !!}</p>
            </div>
            <div class="content-padding">
              <i class="mb-3 icon-xs color-deep-purple -x-icon mi fingerprint" data-attachment="bottom left" data-target-attachment="top right" data-dropdown-position="left"></i>
              <h2 class="-x-text">{!! trans('landingpages::block.content_feature') !!}</h2>
              <p class="lead -x-text">{!! trans('landingpages::block.content_feature_line') !!}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="-x-block">
  <div class="footer text-light flex-center-md -x-block-bg-img" style="">
    <div class="footer-overlay -x-block-bg-color" style="background-color:rgba(0,0,0,0.9)">
      <div class="container">
        <div class="row">
          <div class="col-md-12 text-center col-lg-3 text-lg-left text-secondary my-1">
            <a href="#"><img src="{{ url('templates/assets/images/logos/logo-icon-light.svg') }}" class="-x-img" style="height:64px" data-offset="0px 0px" data-attachment="bottom left" data-target-attachment="top right"></a>
          </div>
          <div class="col-md-12 text-center col-lg-9 text-lg-right text-secondary my-1">
            <p class="-x-text">&copy; {!! trans('landingpages::block.copyright') !!} {{ date('Y') }} &bull; {!! trans('landingpages::block.all_right_reserved') !!} | <a href="#">{!! trans('landingpages::block.disclaimer') !!}</a> | <a href="#">{!! trans('landingpages::block.contact') !!}</a></p>
            <span class="mt-1 hor-spacing-sm-a -x-list color-light" data-repeat="a" data-attachment="center right" data-target-attachment="top left" data-dropdown-position="left">
              <a href="#" role="button"><i class="fa fa-envelope-o" aria-hidden="true"></i></a>
              <a href="#" role="button"><i class="fa fa-twitter" aria-hidden="true"></i></a>
              <a href="#" role="button"><i class="fa fa-facebook" aria-hidden="true"></i></a>
              <a href="#" role="button"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

</body>
</html>