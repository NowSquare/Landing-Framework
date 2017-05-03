<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" type="text/css" href="{{ url('assets/bs4/css/style.min.css') }}" />

    <script src="{{ url('assets/bs4/js/scripts.min.js') }}"></script>

    <link rel="stylesheet" type="text/css" href="{{ url('assets/css/styles.editor.min.css') }}" />
    <script src="{{ url('assets/js/scripts.editor.min.js') }}"></script>
<script>

</script>
<style type="text/css">
  .mce-edit-focus {
    outline: 1px solid #0080FF !important;
  }

</style><style type="text/css">
::-webkit-scrollbar {
  width: 10px;
  height: 2px;
}
::-webkit-scrollbar-button {
  width: 0px;
  height: 0px;
}
::-webkit-scrollbar-thumb {
  background: #3c424e;
  border: 0px none #ffffff;
}
::-webkit-scrollbar-thumb:hover {
  background: #3c424e;
}
::-webkit-scrollbar-thumb:active {
  background: #3c424e;
}
::-webkit-scrollbar-track {
  background: #282c34;
  border: 0px none #ffffff;
  border-radius: 50px;
}
::-webkit-scrollbar-track:hover {
  background: #282c34;
}
::-webkit-scrollbar-track:active {
  background: #282c34;
}
::-webkit-scrollbar-corner {
  background: transparent;
}
</style>
    <title>Editor</title>
</head>
<body>


<div class="-x-el-inline-button -x-el-block-edit -x-el-reset">
  <img src="{{ url('assets/images/editor/icons/settings.svg') }}" class="-x-el-icon"
    onMouseOver="this.src = '{{ url('assets/images/editor/icons/settings-hover.svg') }}';"
    onMouseOut="this.src = '{{ url('assets/images/editor/icons/settings.svg') }}';"
  >
  <ul class="-x-el-dropdown -x-el-reset">
    <li class="-x-el-block-background"><a href="javascript:void(0);">Background</a></li>
    <li class="separator"><hr></li>
    <li class="-x-el-block-move"><a href="javascript:void(0);">Move <div class="-x-el-caret"></div></a>
      <ul>
        <li class="-x-el-block-move-up"><a href="javascript:void(0);">Move up</a></li>
        <li class="-x-el-block-move-down"><a href="javascript:void(0);">Move down</a></li>
      </ul>
    </li>
    <li class="-x-el-block-insert"><a href="javascript:void(0);">Insert <div class="-x-el-caret"></div></a>
      <ul>
        <li class="-x-el-block-insert-above"><a href="javascript:void(0);">Above</a></li>
        <li class="-x-el-block-insert-below"><a href="javascript:void(0);">Below</a></li>
      </ul>
    </li>
    <li class="-x-el-block-edit-menu"><a href="javascript:void(0);">Edit <div class="-x-el-caret"></div></a>
      <ul>
        <li class="-x-el-block-edit-duplicate"><a href="javascript:void(0);">Duplicate</a></li>
        <li class="-x-el-block-edit-delete"><a href="javascript:void(0);">Delete</a></li>
      </ul>
    </li>
  </ul>
</div>

<div class="-x-el-inline-button -x-el-img-edit -x-el-reset">
  <img src="{{ url('assets/images/editor/icons/image.svg') }}" class="-x-el-icon"
    onMouseOver="this.src = '{{ url('assets/images/editor/icons/image-hover.svg') }}';"
    onMouseOut="this.src = '{{ url('assets/images/editor/icons/image.svg') }}';"
  >
  <ul class="-x-el-dropdown -x-el-reset">
    <li class="-x-el-img-select"><a href="javascript:void(0);">Browse...</a></li>
    <li class="-x-el-img-link"><a href="javascript:void(0);">Link...</a></li>
    <li class="separator"><hr></li>
    <li class="-x-el-img-hide"><a href="javascript:void(0);">Hide image</a></li>
  </ul>
</div>

<div class="-x-el-inline-button -x-el-link-edit -x-el-reset">
  <img src="{{ url('assets/images/editor/icons/link.svg') }}" class="-x-el-icon"
    onMouseOver="this.src = '{{ url('assets/images/editor/icons/link-hover.svg') }}';"
    onMouseOut="this.src = '{{ url('assets/images/editor/icons/link.svg') }}';"
  >
  <ul class="-x-el-dropdown -x-el-reset">
    <li class="-x-el-link-link"><a href="javascript:void(0);">Link...</a></li>
  </ul>
</div>

<div class="-x-el-inline-button -x-el-list-edit -x-el-reset">
  <img src="{{ url('assets/images/editor/icons/layers.svg') }}" class="-x-el-icon"
    onMouseOver="this.src = '{{ url('assets/images/editor/icons/layers-hover.svg') }}';"
    onMouseOut="this.src = '{{ url('assets/images/editor/icons/layers.svg') }}';"
  >
  <ul class="-x-el-dropdown -x-el-reset">
    <li class="-x-el-list-edit"><a href="javascript:void(0);">Edit...</a></li>
  </ul>
</div>

<div class="container2" id="page">
        <!-- Header dark, text left, visual right
  ================================================== -->
<section class="typography-1 -x-block">
  <div class="header text-light img-bottom-lg -x-block-bg-img" style="background-image:url('assets/bs4/images/headers/landscape-mountains-nature-sky.jpg')">
    <div id="particles-js-connect" class="header-overlay -x-block-bg-color" style="background-color:rgba(37,75,98,0.7)">
      <div class="container">
        <div class="header-padding no-padding-b">
          <div class="row">
            <div class="col-xs-12 text-xs-center text-md-right">
              <div class="mb-1 mt-2 hor-spacing-md-a -x-list">
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
            <div class="col-sm-12 col-md-6 editable">
              <h1 class="display-2 text-md-left my-3 no-margin-smb">Creative<br>Studio</h1>
              <p class="lead -x-text">We help entrepreneurs achieving their goal faster.</p>
              <div class="btn-container my-3 btn-stack-lg">
                <a class="btn btn-outline-ghost btn-xlg btn-pill -x-link" href="#" role="button">Contact Us</a>
<button type="button" id="export_html">Export HTML</button>
              </div>
            </div>
            <!-- /.col -->
            <div class="col-sm-12 col-md-6 push-lg-1 col-lg-5 text-xs-center text-md-right img-container my-2">
              <img src="assets/bs4/images/visuals/iWatch-White-2.png" alt="" class="-x-img img-fluid">
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


    <section class="typography-1 -x-block">
      <nav class="navbar navbar-dark bg-inverse">
        <div class="container editable">
            <a class="navbar-brand" href="#" target="_blank"><img src="assets/bs4/images/logos/ns-light.svg" class="-x-img" data-offset="10px 10px" alt="" style="height:42px"></a>
            <button class="navbar-toggler pull-right hidden-md-up" type="button" data-toggle="collapse" data-target="#collapsingNavbarGlobal" aria-controls="collapsingNavbarGlobal" aria-expanded="false" aria-label="Toggle navigation">
              &#9776;
            </button>
            <div class="collapse navbar-toggleable-sm" id="collapsingNavbarGlobal">
            <ul class="nav navbar-nav pull-right">
              <li class="nav-item">
                <a class="nav-link" href="index.html">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="https://bootstrap-ui-kit.com" target="_blank">Bootstrap UI Kit</a>
              </li>
            </ul>
            </div>
        </div>
      </nav>
    </section>

    <section class="-x-block">
      <div class="content text-light">
        <div class="content-overlay polygon-bg" data-color-bg="47306b" data-color-light="57407d" style="background-color:#503778">
          <div class="container">
            <div class="row">
              <div class="col-md-12 editable">
                <h1><a href="index.html" style="color: #ccc" class="-x-link">Home</a> &rsaquo; Content</h1>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
   


    <!-- Callout, left aligned text, call to action right, dark background
      ================================================== -->
    <section class="typography-1 -x-block">
      <div class="content text-light">
        <div class="content-overlay polygon-bg" data-color-bg="111111" data-color-light="cccccc">
          <div class="container">
            <div class="row">
              <div class="col-md-8">
                <h1 class="mt-1">Let's Discuss the Plan</h1>
              </div>
              <!-- /.col -->
              <div class="col-md-4 text-sm-left text-md-right">
                <div class="btn-container mt-sm-1">
                  <a class="btn btn-xlg btn-outline-ghost btn-pill" href="#" role="button"><i class="fa fa-coffee" aria-hidden="true"></i> Contact Us</a>
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
                  <a class="btn btn-xlg btn-deep-orange" href="#" role="button"><i class="fa fa-coffee" aria-hidden="true"></i> Contact Us</a>
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

    <!-- Callout, left aligned text, call to action right, dark background
      ================================================== -->
    <section class="typography-2 -x-block">
      <div class="content text-light" style="background-image:url(assets/bs4/images/headers/bokeh-blurred-blurry-lights.jpg)">
        <div class="content-overlay" style="background-color:rgba(0,0,0,0.85)">
          <div class="container">
            <div class="row">
              <div class="col-md-8">
                <h1>Get Support</h1>
                <p class="lead no-margin-b">Contact us with any questions you may have!</p>
              </div>
              <!-- /.col -->
              <div class="col-md-4 text-sm-left text-md-right">
                <div class="btn-container mt-sm-1">
                  <a class="btn btn-xlg btn-outline-deep-orange btn-pill" href="#" role="button"><i class="fa fa-support" aria-hidden="true"></i> Support</a>
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

    <!-- Callout, centered text, call to action center, light background
      ================================================== -->
    <section class="typography-1 -x-block">
      <div class="content" style="background-image:url()">
        <div class="content-overlay" style="background-color:rgba(0,0,0,0.15)">
          <div class="container">
            <div class="row">
              <div class="col-xs-12 text-xs-center">
                <h1>Let's talk Business</h1>
                <p class="lead">Let's chat and see how we can help each other.</p>
                <div class="btn-container mt-sm-1">
                  <a class="btn btn-lg btn-blue btn-pill" href="#" role="button"><i class="fa fa-phone" aria-hidden="true"></i> Call Us</a>
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

    <!-- Photo on the right with lightbox, light background, text aligned left
      ================================================== -->
    <section class="typography-1 -x-block">
      <div class="content" style="background-image:url()">
        <div class="content-overlay" style="background-color:rgba(255,255,255,0)">
          <div class="container">
            <div class="row">
              <div class="col-md-5 push-md-7">
                <!-- image -->
                <a href="assets/bs4/images/photos/laptop-technology-ipad-tablet.jpg" data-toggle="lightbox"><img src="assets/bs4/images/photos/laptop-technology-ipad-tablet.jpg" alt="" class="-x-img img-fluid mdl-shadow--8dp"></a>
              </div>
              <!-- /.col -->
              <div class="col-md-7 pull-md-5">
                <div class="content-padding">
                  <h2>First make it work, then make it better</h2>
                  <p class="lead">NowSquare provides high quality assets/bs4 for your business. We help <a href="#" class="link">entrepreneurs</a> with great ideas achieving their goals faster with self-hosted, white label software.</p>
                  <div class="btn-container">
                    <a class="btn btn-outline-pink btn-pill -x-link" data-offset="5px 0px" data-attachment="right top" data-target-attachment="left top" href="#" role="button">More <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
                  </div>
                  <div class="mt-2 hor-spacing-sm-a -x-list">
                    <a href="#" role="button" class="color-pink"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                    <a href="#" role="button" class="color-pink"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                    <a href="#" role="button" class="color-pink"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                    <a href="#" role="button" class="color-pink"><i class="fa fa-google-plus" aria-hidden="true"></i></a>
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

    <!-- Visual right, text aligned right
      ================================================== -->
    <section class="typography-1 -x-block">
      <div class="content content-padding-l" style="background-image:url()">
        <div class="content-overlay" style="background-color:rgba(255,255,255,0)">
          <div class="container">
            <div class="row">
              <div class="col-md-6 push-md-6">
                <!-- image -->
                <img src="assets/bs4/images/visuals/iPhone-6S---isometric-view-right.png" alt="" class="-x-img img-fluid">
              </div>
              <!-- /.col -->
              <div class="col-md-6 pull-md-6 text-xs-center text-md-right">
                <div class="content-padding">
                  <h2>Awesome Features</h2>
                  <p class="lead">NowSquare provides high quality assets/bs4 for your business. We help entrepreneurs with great ideas achieving their goals faster with self-hosted, white label software.</p>
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
    <section class="typography-1 -x-block">
      <div class="content content-padding-l" style="background-image:url()">
        <div class="content-overlay" style="background-color:rgba(255,255,255,0)">
          <div class="container">
            <div class="row">
              <div class="col-sm-12 col-md-4 text-xs-center text-lg-right mt-3 no-margin-md-t">
                <div class="content-padding">
                  <!-- icon -->
                  <i class="material-icons icon-xs color-deep-purple">&#xE922;</i>
                  <h2>Feature</h2>
                  <p class="lead">This is an awesome feature.</p>
                </div>
                <!-- /.content-padding -->
                <div class="content-padding">
                  <!-- icon -->
                  <i class="material-icons icon-xs color-deep-purple">&#xE90F;</i>
                  <h2>Feature</h2>
                  <p class="lead">This is an awesome feature.</p>
                </div>
                <!-- /.content-padding -->
              </div>
              <!-- /.col -->
              <div class="col-sm-12 col-md-4">
                <!-- image -->
                <img src="assets/bs4/images/visuals/iPhone-6-4,7-inch-Mockup.png" alt="" class="-x-img img-fluid">
              </div>
              <!-- /.col -->
              <div class="col-sm-12 col-md-4 text-xs-center text-lg-left mt-3 no-margin-md-t">
                <div class="content-padding">
                  <!-- icon -->
                  <i class="material-icons icon-xs color-deep-purple">&#xE913;</i>
                  <h2>Feature</h2>
                  <p class="lead">This is an awesome feature.</p>
                </div>
                <!-- /.content-padding -->
                <div class="content-padding">
                  <!-- icon -->
                  <i class="material-icons icon-xs color-deep-purple">&#xE90D;</i>
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
    <section class="typography-1 -x-block">
      <div class="content content-padding-l" style="background-image:url()">
        <div class="content-overlay" style="background-color:rgba(255,255,255,0)">
          <div class="container">
            <div class="row">
              <div class="col-xs-6 push-xs-3 col-sm-4 push-sm-4 col-md-4 push-md-4 col-xl-4 push-xl-4 text-xs-center">
                <!-- image -->
                <img src="assets/bs4/images/icons/biotech.svg" alt="" class="-x-img img-fluid">
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
            <div class="row">
              <div class="col-md-12 col-lg-6 push-lg-3 text-xs-center">
                <h1 class="mt-1">Science News</h1>
                <p class="lead">NowSquare provides high quality assets/bs4 for your business. We help entrepreneurs with great ideas achieving their goals faster with self-hosted, white label software.</p>
                <p class="btn-container">
                  <a class="btn btn-outline-purple btn-pill" href="#" role="button"><i class="fa fa-play-circle-o" aria-hidden="true"></i> Watch Video</a>
                  <a class="btn btn-purple btn-pill" href="#" role="button"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Order Tickets</a>
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
    <section class="typography-1 -x-block">
      <div class="content content-padding-l" style="background-image:url()">
        <div class="content-overlay" style="background-color:rgba(255,255,255,0)">
          <div class="container">
            <div class="row">
              <div class="col-xs-12 col-sm-6 col-lg-4 push-lg-2 text-xs-center">
                <div class="row">
                  <div class="col-xs-12 col-sm-6 push-sm-3 col-lg-8 push-lg-2 text-xs-center">
                    <!-- image -->
                    <img src="assets/bs4/images/icons/assistant.svg" alt="" class="-x-img img-fluid">
                  </div>
                  <!-- /.col -->
                  <div class="col-xs-12">
                    <h2 class="mt-1">Support</h2>
                    <p class="lead">NowSquare provides high quality assets/bs4 for your business. We help entrepreneurs with great ideas achieving their goals faster with self-hosted, white label software.</p>
                    <p class="btn-container">
                      <a class="btn btn-outline-green btn-pill" href="#" role="button">More</a>
                    </p>
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
              <!-- /.col -->
              <div class="col-xs-12 col-sm-6 col-lg-4 push-lg-2 text-xs-center">
  
                <div class="row">
                  <div class="col-xs-12 col-sm-6 push-sm-3 col-lg-8 push-lg-2 text-xs-center">
                    <!-- image -->
                    <img src="assets/bs4/images/icons/landscape.svg" alt="" class="-x-img img-fluid">
                  </div>
                  <!-- /.col -->
                  <div class="col-xs-12">
                    <h2 class="mt-1">Reports</h2>
                    <p class="lead">NowSquare provides high quality assets/bs4 for your business. We help entrepreneurs with great ideas achieving their goals faster with self-hosted, white label software.</p>
                    <p class="btn-container">
                      <a class="btn btn-outline-green btn-pill" href="#" role="button">More</a>
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
    <section class="typography-1 -x-block">
      <div class="content content-padding-l" style="background-image:url()">
        <div class="content-overlay" style="background-color:rgba(255,255,255,0)">
          <div class="container">
            <div class="row">
              <!-- /.col -->
              <div class="col-xs-12 col-md-4 text-xs-center">
                <div class="row">
                  <div class="col-xs-12 col-sm-8 push-sm-2 col-lg-8 push-lg-2 text-xs-center">
                    <!-- image -->
                    <img src="assets/bs4/images/icons/electrical_sensor.svg" alt="" class="img-fluid">
                  </div>
                  <!-- /.col -->
                  <div class="col-xs-12">
                    <h2 class="mt-1">Customize</h2>
                    <p class="lead">NowSquare provides high quality assets/bs4 for your business. We help entrepreneurs with great ideas achieving their goals faster with self-hosted, white label software.</p>
                    <p class="btn-container">
                      <a class="btn btn-light-blue btn-pill" href="#" role="button">More</a>
                    </p>
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
              <!-- /.col -->
              <div class="col-xs-12 col-md-4 text-xs-center">
                <div class="row">
                  <div class="col-xs-12 col-sm-8 push-sm-2 col-lg-8 push-lg-2 text-xs-center">
                    <!-- image -->
                    <img src="assets/bs4/images/icons/camcorder.svg" alt="" class="img-fluid">
                  </div>
                  <!-- /.col -->
                  <div class="col-xs-12">
                    <h2 class="mt-1">Purchase</h2>
                    <p class="lead">NowSquare provides high quality assets/bs4 for your business. We help entrepreneurs with great ideas achieving their goals faster with self-hosted, white label software.</p>
                    <p class="btn-container">
                      <a class="btn btn-light-blue btn-pill" href="#" role="button">More</a>
                    </p>
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
              <!-- /.col -->
              <div class="col-xs-12 col-md-4 text-xs-center">
                <div class="row">
                  <div class="col-xs-12 col-sm-8 push-sm-2 col-lg-8 push-lg-2 text-xs-center">
                    <!-- image -->
                    <img src="assets/bs4/images/icons/mind_map.svg" alt="" class="img-fluid">
                  </div>
                  <!-- /.col -->
                  <div class="col-xs-12">
                    <h2 class="mt-1">Documentation</h2>
                    <p class="lead">NowSquare provides high quality assets/bs4 for your business. We help entrepreneurs with great ideas achieving their goals faster with self-hosted, white label software.</p>
                    <p class="btn-container">
                      <a class="btn btn-light-blue btn-pill" href="#" role="button">More</a>
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

</div>
       
           

</body>
</html>