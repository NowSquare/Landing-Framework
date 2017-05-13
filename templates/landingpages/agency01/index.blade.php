<!DOCTYPE html>
<html>
<head>
  <title>Landing Page</title>

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

  <link rel="stylesheet" type="text/css" href="{{ url('assets/bs4/css/style.min.css') }}" />
  <script src="{{ url('assets/bs4/js/scripts.min.js') }}"></script>

  <link href="//fonts.googleapis.com/css?family=Montserrat:200,400|Hind:300,400,700" rel="stylesheet">
  <style type="text/css">
    body {
      font-family: 'Hind', sans-serif;
      font-weight: 300;
    }

    h1, h2, h3, h4, h5, h6 {
      font-family: 'Montserrat', sans-serif;
      font-weight: 200 !important;
    }
  </style>
</head>
<body>

<section class="-x-block">
  <div class="header text-light -x-block-bg-img" style="background-image:url('{{ url('templates/assets/images/headers/landscape-mountains-nature-sky.jpg') }}')">
    <div id="particles-js-connect" class="header-overlay -x-block-bg-color" style="background-color:rgba(37,75,98,0.7)">
      <div class="container">
        <div class="header-padding no-padding-b">
          <div class="row">
            <div class="col-12 text-center text-md-right">
              <div class="mb-1 mt-2 hor-spacing-md-a -x-list" data-repeat="a" data-attachment="bottom right" data-target-attachment="top right" data-dropdown-position="left">
                <a href="#" role="button" class="color-light"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                <a href="#" role="button" class="color-light"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                <a href="#" role="button" class="color-light"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                <a href="#" role="button" class="color-light"><i class="fa fa-google-plus" aria-hidden="true"></i></a>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12 col-md-6">
              <h1 class="display-2 text-md-left my-3 no-margin-smb -x-text">{!! trans('landingpages::block.header_02_head') !!}</h1>
              <p class="lead -x-text">{!! trans('landingpages::block.header_02_line') !!}</p>
              <div class="btn-container my-3 btn-stack-lg">
                <a class="btn btn-outline-ghost btn-xlg btn-pill -x-link" href="#" role="button">{!! trans('landingpages::block.header_02_button') !!}</a>
              </div>
            </div>
            <div class="col-sm-12 col-md-6 push-lg-1 col-lg-5 text-center text-md-right img-container my-2">
              <img src="{{ url('templates/assets/images/visuals/iWatch-White-2.png') }}" alt="" class="-x-img img-fluid">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="-x-block">
  <div class="content content-padding-xxl -x-block-bg-img text-light" style="">
    <div class="content-overlay -x-block-bg-color" style="background-color:rgba(0,0,0,0.9)">
      <div class="container">
        <div class="row">
          <div class="col-12 col-sm-6 col-lg-4 push-lg-2 text-center">
            <div class="row">
              <div class="col-6 push-3 col-sm-6 push-sm-3 col-lg-6 push-lg-3 text-center">
                <i class="mb-3 icon-xl color-orange -x-icon mi fingerprint" data-attachment="bottom left" data-target-attachment="top left"></i>
              </div>
              <div class="col-12">
                <h2 class="mt-1 -x-text">{!! trans('landingpages::block.content_title1') !!}</h2>
                <p class="lead -x-text">{!! trans('landingpages::block.content_lead1') !!}</p>
                <p class="btn-container">
                  <a class="btn btn-orange btn-pill -x-link" href="#" role="button">{!! trans('landingpages::block.content_button') !!}</a>
                </p>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-lg-4 push-lg-2 text-center">
            <div class="row">
              <div class="col-6 push-3 col-sm-6 push-sm-3 col-lg-6 push-lg-3 text-center">
                <i class="mb-3 icon-xl color-orange -x-icon mi show_chart" data-attachment="bottom left" data-target-attachment="top left"></i>
              </div>
              <div class="col-12">
                <h2 class="mt-1 -x-text">{!! trans('landingpages::block.content_title2') !!}</h2>
                <p class="lead -x-text">{!! trans('landingpages::block.content_lead1') !!}</p>
                <p class="btn-container">
                  <a class="btn btn-orange btn-pill -x-link" href="#" role="button">{!! trans('landingpages::block.content_button') !!}</a>
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
  <div class="content content-padding-xxl -x-block-bg-img" style="">
    <div class="content-overlay -x-block-bg-color" style="background-color:rgba(255,255,255,1)">
      <div class="container">
        <div class="row">
          <div class="col-12 col-sm-6 col-lg-4 push-lg-2 text-center">
            <div class="row">
              <div class="col-6 push-3 col-sm-6 push-sm-3 col-lg-6 push-lg-3 text-center">
                <img src="{{ url('templates/assets/images/icons/assistant.svg') }}" alt="" class="-x-img img-fluid">
              </div>
              <div class="col-12">
                <h2 class="mt-1 -x-text">{!! trans('landingpages::block.content_title1') !!}</h2>
                <p class="lead -x-text">{!! trans('landingpages::block.content_lead1') !!}</p>
                <p class="btn-container">
                  <a class="btn btn-grey btn-pill -x-link" href="#" role="button">{!! trans('landingpages::block.content_button') !!}</a>
                </p>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-lg-4 push-lg-2 text-center">
            <div class="row">
              <div class="col-6 push-3 col-sm-6 push-sm-3 col-lg-6 push-lg-3 text-center">
                <img src="{{ url('templates/assets/images/icons/landscape.svg') }}" alt="" class="-x-img img-fluid">
              </div>
              <div class="col-12">
                <h2 class="mt-1 -x-text">{!! trans('landingpages::block.content_title2') !!}</h2>
                <p class="lead -x-text">{!! trans('landingpages::block.content_lead1') !!}</p>
                <p class="btn-container">
                  <a class="btn btn-grey btn-pill -x-link" href="#" role="button">{!! trans('landingpages::block.content_button') !!}</a>
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