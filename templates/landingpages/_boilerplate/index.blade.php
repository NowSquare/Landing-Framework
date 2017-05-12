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
  <div class="header text-light -x-block-bg-img" style="background-image:url('{{ url('templates/assets/images/headers/city-landmark-lights-night.jpg') }}')">
    <div class="header-overlay -x-block-bg-color" style="background-color:rgba(64,46,84,0.6)">
      <div class="container">
        <div class="header-padding-xl no-padding-b">
          <div class="row">
            <div class="col-md-12 col-lg-7 push-lg-5 text-center text-lg-right">
              <img src="{{ url('templates/assets/images/logos/logo-icon-light.svg') }}" alt="" style="height:160px" class="mb-3 -x-img" data-offset="0px 0px" data-attachment="bottom left" data-target-attachment="top right" data-dropdown-position="left">
              <h1 class="display-3 -x-text">{!! trans('landingpages::block.header_03_head') !!}</h1>
              <p class="lead -x-text">{!! trans('landingpages::block.header_01_line') !!}</p>
              <div class="btn-container btn-stack-md hor-spacing-md-a mb-3">
                <a class="btn btn-outline-ghost btn-xlg btn-pill -x-link" href="#" role="button">{!! trans('landingpages::block.learn_more') !!}</a>
                <span class="-x-list" data-repeat="a" data-attachment="bottom right" data-target-attachment="top right" data-dropdown-position="left">
                  <a href="#" class="color-light text-l"><i class="fa fa-apple" aria-hidden="true"></i></a>
                  <a href="#" class="color-light text-l"><i class="fa fa-android" aria-hidden="true"></i></a>
                </span>
              </div>
            </div>
            <div class="col-6 push-3 push-lg-0 col-lg-4 pull-lg-6 img-container">
              <img src="{{ url('templates/assets/images/visuals/iPhone-CutOff.png') }}" alt="" class="img-fluid -x-img">
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
            <div class="mt-1 hor-spacing-sm-a -x-list" data-repeat="a" data-attachment="bottom right" data-target-attachment="top right" data-dropdown-position="left">
              <a href="#" role="button" class="color-dark"><i class="fa fa-envelope-o" aria-hidden="true"></i></a>
              <a href="#" role="button" class="color-dark"><i class="fa fa-twitter" aria-hidden="true"></i></a>
              <a href="#" role="button" class="color-dark"><i class="fa fa-facebook" aria-hidden="true"></i></a>
              <a href="#" role="button" class="color-dark"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
  
  <section class="-x-block">
    <div class="header text-light">
      <div class="header-overlay -x-block-bg-gradient" style="background: #4776e6; background: linear-gradient(to bottom,  #4776e6 0%,#8e54e9 100%);">
        <div class="container">
          <div class="header-padding-xl text-center no-padding-b">
            <div class="row">
              <div class="col-12">
                <h1 class="display-3 -x-text">Online Platform</h1>
                <p class="lead -x-text">High quality services for your business. We help entrepreneurs with great ideas.</p>
                <div class="btn-container mt-3 mb-1">
                  <a class="btn btn-outline-ghost btn-xlg btn-pill -x-link" href="#" role="button">Get Started Now</a>
                </div>
                <img src="{{ url('/templates/assets/images/visuals/software01.png') }}" alt="" class="img-fluid -x-img" style="margin:auto">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="-x-block">
    <div class="header text-light -x-block-bg-img" style="background-image:url('/templates/assets/images/headers/landscape-mountains-nature-sky.jpg')">
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
              <!-- /.col -->
            </div>
            <!-- /.row -->
            <div class="row">
              <div class="col-sm-12 col-md-6">
                <h1 class="display-2 text-md-left my-3 no-margin-smb -x-text">Creative<br>Studio</h1>
                <p class="lead -x-text">We help entrepreneurs achieving their goal faster.</p>
                <div class="btn-container my-3 btn-stack-lg">
                  <a class="btn btn-outline-ghost btn-xlg btn-pill -x-link" href="#" role="button">Contact Us</a>
                </div>
              </div>
              <!-- /.col -->
              <div class="col-sm-12 col-md-6 push-lg-1 col-lg-5 text-center text-md-right img-container my-2">
                <img src="{{ url('/templates/assets/images/visuals/iWatch-White-2.png') }}" alt="" class="-x-img img-fluid">
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
          </div>
          <!-- /.header-padding -->
        </div>
        <!-- /.container -->
      </div>
      <!-- /.header-overlay -->
    </div>
    <!-- /.header -->
  </section>


<section class="-x-block">
  <div class="content -x-block-bg-img text-light" style="">
    <div class="content-overlay -x-block-bg-color" style="background-color:rgba(0,0,0,0.9)">
      <div class="container">
        <div class="row">
          <div class="col-md-5 push-md-7">
            <a href="{{ url('templates/assets/images/photos/laptop-technology-ipad-tablet.jpg') }}" data-toggle="lightbox"><img src="{{ url('templates/assets/images/photos/laptop-technology-ipad-tablet.jpg') }}" alt="" class="-x-img img-fluid mdl-shadow--8dp"></a>
          </div>
          <div class="col-md-7 pull-md-5">
            <div class="content-padding">
              <h2 class="-x-text">{!! trans('landingpages::block.photo_01_head') !!}</h2>
              <div class="-x-text mt-4">
                <p class="lead">{!! trans('landingpages::block.photo_p1') !!}</p>
                <p class="lead">{!! trans('landingpages::block.photo_p2') !!}</p>
              </div>
              <div class="btn-container">
                <a class="btn btn-outline-yellow btn-pill -x-link" data-offset="5px 0px" data-attachment="right top" data-target-attachment="right bottom" href="#" role="button">More</i></a>
              </div>
              <div class="mt-2 hor-spacing-sm-a -x-list color-yellow" data-repeat="a">
                <a href="#" role="button"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                <a href="#" role="button"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                <a href="#" role="button"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                <a href="#" role="button"><i class="fa fa-google-plus" aria-hidden="true"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


    <!-- Callout, left aligned text, call to action right, dark background
      ================================================== -->
    <section class="-x-block">
      <div class="content text-light">
        <div class="content-overlay polygon-bg" data-color-bg="111111" data-color-light="cccccc">
          <div class="container">
            <div class="row">
              <div class="col-md-8">
                <h1 class="mt-1 -x-text">Let's Discuss the Plan</h1>
              </div>
              <!-- /.col -->
              <div class="col-md-4 text-sm-left text-md-right">
                <div class="btn-container mt-sm-1">
                  <a class="btn btn-xlg btn-outline-ghost btn-pill -x-link" href="#" role="button"><i class="fa fa-coffee" aria-hidden="true"></i> Contact Us</a>
                </div>
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
          </div>
          <!-- /.container -->
        </div>
        <!-- /.content-overlay -->
      </div>
      <!-- /.content -->
    </section>

    <!-- Callout, left aligned text, call to action right, light background
      ================================================== -->
    <section class="typography-2 -x-block">
      <div class="content -x-block-bg-img" style="background-image:url()">
        <div class="content-overlay -x-block-bg-color" style="background-color:rgba(0,0,0,0.15)">
          <div class="container">
            <div class="row">
              <div class="col-md-8">
                <h1>How Can We Help You</h1>
                <p class="lead no-margin-b">We'd love to chat and see how we can help.</p>
              </div>
              <!-- /.col -->
              <div class="col-md-4 text-sm-left text-md-right">
                <div class="btn-container mt-sm-1">
                  <a class="btn btn-xlg btn-outline-ghost -x-link" href="#" role="button"><i class="fa fa-coffee" aria-hidden="true"></i> Contact Us</a>
                </div>
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
          </div>
          <!-- /.container -->
        </div>
        <!-- /.content-overlay -->
      </div>
      <!-- /.content -->
    </section>


    <!-- Visual right, text aligned right
      ================================================== -->
    <section class="-x-block">
      <div class="content content-padding-l" style="background-image:url()">
        <div class="content-overlay" style="background-color:rgba(255,255,255,0)">
          <div class="container">
            <div class="row">
              <div class="col-md-6 push-md-6">
                <!-- image -->
                <img src="/templates/assets/images/visuals/iPhone-6S---isometric-view-right.png" alt="" class="-x-img img-fluid">
              </div>
              <!-- /.col -->
              <div class="col-md-6 pull-md-6 text-center text-md-right">
                <div class="content-padding">
                  <h2>Awesome Features</h2>
                  <p class="lead">We provide high quality assets for your business. We help entrepreneurs with great ideas achieving their goals faster with self-hosted, white label software.</p>
                  <div class="btn-container">
                    <a class="btn btn-blue btn-pill" href="#" role="button"><i class="fa fa-search" aria-hidden="true"></i> Details</a>
                  </div>
                </div>
                <!-- /.content-padding -->
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
          </div>
          <!-- /.container -->
        </div>
        <!-- /.content-overlay -->
      </div>
      <!-- /.content -->
    </section>

    <!-- Visual center, text left and right
      ================================================== -->
    <section class="-x-block">
      <div class="content content-padding-l" style="background-image:url()">
        <div class="content-overlay" style="background-color:rgba(255,255,255,0)">
          <div class="container">
            <div class="row">
              <div class="col-sm-12 col-md-4 text-center text-lg-right mt-3 no-margin-md-t">
                <div class="content-padding">
                  <!-- icon -->
                  <i class="mb-3 icon-xs color-deep-purple -x-icon mi timeline" data-attachment="bottom left" data-target-attachment="top left"></i>
                  <h2>Feature</h2>
                  <p class="lead">This is an awesome feature.</p>
                </div>
                <!-- /.content-padding -->
                <div class="content-padding">
                  <!-- icon -->
                  <i class="mb-3 icon-xs color-deep-purple -x-icon mi lightbulb_outline" data-attachment="bottom left" data-target-attachment="top left"></i>
                  <h2>Feature</h2>
                  <p class="lead">This is an awesome feature.</p>
                </div>
                <!-- /.content-padding -->
              </div>
              <!-- /.col -->
              <div class="col-sm-12 col-md-4">
                <!-- image -->
                <img src="/templates/assets/images/visuals/iPhone-6-4,7-inch-Mockup.png" alt="" class="-x-img img-fluid">
              </div>
              <!-- /.col -->
              <div class="col-sm-12 col-md-4 text-center text-lg-left mt-3 no-margin-md-t">
                <div class="content-padding">
                  <!-- icon -->
                  <i class="mb-3 icon-xs color-deep-purple -x-icon mi touch_app" data-attachment="bottom left" data-target-attachment="top right"></i>
                  <h2>Feature</h2>
                  <p class="lead">This is an awesome feature.</p>
                </div>
                <!-- /.content-padding -->
                <div class="content-padding">
                  <!-- icon -->
                  <i class="mb-3 icon-xs color-deep-purple -x-icon mi fingerprint" data-attachment="bottom left" data-target-attachment="top right"></i>
                  <h2>Feature</h2>
                  <p class="lead">This is an awesome feature.</p>
                </div>
                <!-- /.content-padding -->
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
          </div>
          <!-- /.container -->
        </div>
        <!-- /.content-overlay -->
      </div>
      <!-- /.content -->
    </section>

    <!-- One column with responsive image on top
      ================================================== -->
    <section class="-x-block">
      <div class="content content-padding-l" style="background-image:url()">
        <div class="content-overlay" style="background-color:rgba(255,255,255,0)">
          <div class="container">
            <div class="row">
              <div class="col-6 push-3 col-sm-4 push-sm-4 col-md-4 push-md-4 col-xl-4 push-xl-4 text-center">
                <!-- image -->
                <img src="/templates/assets/images/icons/biotech.svg" alt="" class="-x-img img-fluid">
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
            <div class="row">
              <div class="col-md-12 col-lg-6 push-lg-3 text-center">
                <h1 class="mt-1">Science News</h1>
                <p class="lead">We provide high quality assets for your business. We help entrepreneurs with great ideas achieving their goals faster with self-hosted, white label software.</p>
                <p class="btn-container">
                  <a class="btn btn-outline-blue btn-pill" href="#" role="button"><i class="fa fa-play-circle-o" aria-hidden="true"></i> Watch Video</a>
                  <a class="btn btn-outline-blue btn-pill" href="#" role="button"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Order Tickets</a>
                </p>
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
          </div>
          <!-- /.container -->
        </div>
        <!-- /.content-overlay -->
      </div>
      <!-- /.content -->
    </section>

    <!-- Two columns with responsive image on top
      ================================================== -->
    <section class="-x-block">
      <div class="content content-padding-l" style="background-image:url()">
        <div class="content-overlay" style="background-color:rgba(255,255,255,0)">
          <div class="container">
            <div class="row">
              <div class="col-12 col-sm-6 col-lg-4 push-lg-2 text-center">
                <div class="row">
                  <div class="col-12 col-sm-6 push-sm-3 col-lg-8 push-lg-2 text-center">
                    <!-- image -->
                    <img src="/templates/assets/images/icons/assistant.svg" alt="" class="-x-img img-fluid">
                  </div>
                  <!-- /.col -->
                  <div class="col-12">
                    <h2 class="mt-1">Support</h2>
                    <p class="lead">We provide high quality assets for your business. We help entrepreneurs with great ideas achieving their goals faster with self-hosted, white label software.</p>
                    <p class="btn-container">
                      <a class="btn btn-blue btn-pill" href="#" role="button">More</a>
                    </p>
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
              <!-- /.col -->
              <div class="col-12 col-sm-6 col-lg-4 push-lg-2 text-center">
  
                <div class="row">
                  <div class="col-12 col-sm-6 push-sm-3 col-lg-8 push-lg-2 text-center">
                    <!-- image -->
                    <img src="/templates/assets/images/icons/landscape.svg" alt="" class="-x-img img-fluid">
                  </div>
                  <!-- /.col -->
                  <div class="col-12">
                    <h2 class="mt-1">Reports</h2>
                    <p class="lead">We provide high quality assets for your business. We help entrepreneurs with great ideas achieving their goals faster with self-hosted, white label software.</p>
                    <p class="btn-container">
                      <a class="btn btn-blue btn-pill" href="#" role="button">More</a>
                    </p>
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
          </div>
          <!-- /.container -->
        </div>
        <!-- /.content-overlay -->
      </div>
      <!-- /.content -->
    </section>

    <!-- Three columns with responsive image on top
      ================================================== -->
    <section class="-x-block">
      <div class="content content-padding-l" style="background-image:url()">
        <div class="content-overlay" style="background-color:rgba(255,255,255,0)">
          <div class="container">
            <div class="row">
              <!-- /.col -->
              <div class="col-12 col-md-4 text-center">
                <div class="row">
                  <div class="col-12 col-sm-8 push-sm-2 col-lg-8 push-lg-2 text-center">
                    <!-- image -->
                    <img src="/templates/assets/images/icons/electrical_sensor.svg" alt="" class="img-fluid">
                  </div>
                  <!-- /.col -->
                  <div class="col-12">
                    <h2 class="mt-1">Customize</h2>
                    <p class="lead">We provide high quality assets for your business. We help entrepreneurs with great ideas achieving their goals faster with self-hosted, white label software.</p>
                    <p class="btn-container">
                      <a class="btn btn-blue btn-pill" href="#" role="button">More</a>
                    </p>
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
              <!-- /.col -->
              <div class="col-12 col-md-4 text-center">
                <div class="row">
                  <div class="col-12 col-sm-8 push-sm-2 col-lg-8 push-lg-2 text-center">
                    <!-- image -->
                    <img src="/templates/assets/images/icons/camcorder.svg" alt="" class="img-fluid">
                  </div>
                  <!-- /.col -->
                  <div class="col-12">
                    <h2 class="mt-1">Purchase</h2>
                    <p class="lead">We provide high quality assets for your business. We help entrepreneurs with great ideas achieving their goals faster with self-hosted, white label software.</p>
                    <p class="btn-container">
                      <a class="btn btn-blue btn-pill" href="#" role="button">More</a>
                    </p>
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
              <!-- /.col -->
              <div class="col-12 col-md-4 text-center">
                <div class="row">
                  <div class="col-12 col-sm-8 push-sm-2 col-lg-8 push-lg-2 text-center">
                    <!-- image -->
                    <img src="/templates/assets/images/icons/mind_map.svg" alt="" class="img-fluid">
                  </div>
                  <!-- /.col -->
                  <div class="col-12">
                    <h2 class="mt-1">Documentation</h2>
                    <p class="lead">We provide high quality assets for your business. We help entrepreneurs with great ideas achieving their goals faster with self-hosted, white label software.</p>
                    <p class="btn-container">
                      <a class="btn btn-blue btn-pill" href="#" role="button">More</a>
                    </p>
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
          </div>
          <!-- /.container -->
        </div>
        <!-- /.content-overlay -->
      </div>
      <!-- /.content -->
    </section>

</body>
</html>