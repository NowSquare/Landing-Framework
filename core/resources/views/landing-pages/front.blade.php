<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" type="text/css" href="{{ url('assets/bs4/css/style.min.css') }}" />

    <script src="{{ url('assets/bs4/js/scripts.min.js') }}"></script>

    <link rel="stylesheet" type="text/css" href="{{ url('assets/css/styles.editor.min.css') }}" />
    <script src="{{ url('assets/js/scripts.editor.min.js') }}"></script>
    <script>
/*
tinymce.init({
  selector: 'h2.editable',
  inline: true,
  toolbar: 'undo redo',
  menubar: false
});

tinymce.init({
  selector: 'div.editable',
  inline: true,
  plugins: [
    'advlist autolink lists link image anchor',
    'code',
    'media table contextmenu paste'
  ],
  toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image'
});
*/

$(function() {

  /*
    Loop through all links, generate semi-unique class
    to reference links for use in the editor. Add `-clone`
    suffix to class to prevent cloning to the power and
    link settings link with dropdown to link (Tether).
  */

  $('.-lf-link').each(function() {
    // Attribute settings
    var attachment = $(this).attr('data-attachment');
    attachment = (typeof attachment !== typeof undefined && attachment !== false) ? attachment : 'top left';

    var targetAttachment = $(this).attr('data-taget-attachment');
    targetAttachment = (typeof targetAttachment !== typeof undefined && targetAttachment !== false) ? targetAttachment : 'bottom left';

    var offset = $(this).attr('data-offset');
    offset = (typeof offset !== typeof undefined && offset !== false) ? offset : '-5px 0';

    var $el = $('.-lf-el-link-edit').clone().appendTo('body');

    // Set unique class
    var timestamp = new Date().getTime();
    var unique_class = '-lf-data-link-' + timestamp;

    $(this).addClass(unique_class);
    $(this).attr('data-lf-el', unique_class);
    $el.attr('data-lf-el', unique_class);

    // Set reference to parent block
    $el.attr('data-lf-parent-block', $(this).parents('.-lf-block').attr('data-lf-el'));

    // Replace class so it won't be cloned in next loop
    $el.removeClass('-lf-el-link-edit').addClass('-lf-el-link-edit-clone -lf-el-inline-button-clone');

    new Tether({
      element: $el,
      target: $(this),
      attachment: attachment,
      offset: offset,
      targetAttachment: targetAttachment,
      classPrefix: '-lf-data',
      constraints: [{
        to: 'scrollParent',
        attachment: 'together'
      }],
      optimizations: {
        moveElement: true,
        gpu: true
      }
    });
  });

  lf_ParseLinks(true);
});

/* 
  Duplicate links and references
*/

function lf_DuplicateBlockLinks($new_block) {
  // Loop through all links in new block
  $new_block.find('.-lf-link').each(function() {
    var timestamp = new Date().getTime();
    var $new_btn = $(this);
    var btn_class = $new_btn.attr('data-lf-el');

    if (typeof btn_class !== typeof undefined && btn_class !== false) {
      // Attribute settings
      var attachment = $new_btn.attr('data-attachment');
      attachment = (typeof attachment !== typeof undefined && attachment !== false) ? attachment : 'top left';

      var targetAttachment = $new_btn.attr('data-taget-attachment');
      targetAttachment = (typeof targetAttachment !== typeof undefined && targetAttachment !== false) ? targetAttachment : 'top left';

      var offset = $new_btn.attr('data-offset');
      offset = (typeof offset !== typeof undefined && offset !== false) ? offset : '-5px -5px';

      // Clone btn and replace with new class
      $new_btn.removeClass(btn_class);
      $new_btn.addClass('-lf-data-link-' + timestamp);
      $new_btn.attr('data-lf-el', '-lf-data-link-' + timestamp);

      // Settings
      var $new_btn_settings = $('.-lf-el-link-edit-clone[data-lf-el=' + btn_class + ']').clone().insertAfter('.-lf-el-link-edit-clone[data-lf-el=' + btn_class + ']');
      $new_btn_settings.attr('data-lf-el', '-lf-data-link-' + timestamp);

      new Tether({
        element: $new_btn_settings,
        target: $new_btn,
        attachment: attachment,
        offset: offset,
        targetAttachment: targetAttachment,
        classPrefix: '-lf-data',
        constraints: [{
          to: 'scrollParent',
          attachment: 'together'
        }],
        optimizations: {
          moveElement: true,
          gpu: true
        }
      });
    }

  });

  // Timeout to make sure dom has changed
  setTimeout(lf_ParseLinks, 70);
}

/* 
  Loop through link settings to set attributes
  and fix z-index overlapping. 
*/

function lf_ParseLinks(init) {
  var zIndex = 200;
  
  $('.-lf-link').each(function() {
    var btn_class = $(this).attr('data-lf-el');
    var $btn_settings = $('.-lf-el-link-edit-clone[data-lf-el=' + btn_class + ']');

    // Set z-index to prevent overlapping of dropdown menus
    $btn_settings.css('cssText', 'z-index: ' + zIndex + ' !important;');
    $btn_settings.find('.-lf-el-dropdown').css('cssText', 'z-index: ' + zIndex + ' !important;');
    zIndex--;
  });

  // Always reposition tethered elements.
  // Also initially because $btn_settings.css('cssText', ...); 
  // seems to reset position
  Tether.position();
}

</script>
<style type="text/css">
  .mce-edit-focus {
    outline: 1px solid #0080FF !important;
  }

</style>
    <title>Editor</title>
</head>
<body>

<button type="button" id="export_html">Export HTML</button>

<div class="-lf-el-inline-button -lf-el-block-edit -lf-el-reset">
  <img src="{{ url('assets/images/editor/icons/settings.svg') }}" class="-lf-el-icon"
    onMouseOver="this.src = '{{ url('assets/images/editor/icons/settings-hover.svg') }}';"
    onMouseOut="this.src = '{{ url('assets/images/editor/icons/settings.svg') }}';"
  >
  <ul class="-lf-el-dropdown -lf-el-reset">
    <li class="-lf-el-block-background"><a href="javascript:void(0);">Background</a></li>
    <li class="separator"><hr></li>
    <li class="-lf-el-block-move"><a href="javascript:void(0);">Move <div class="-lf-el-caret"></div></a>
      <ul>
        <li class="-lf-el-block-move-up"><a href="javascript:void(0);">Move up</a></li>
        <li class="-lf-el-block-move-down"><a href="javascript:void(0);">Move down</a></li>
      </ul>
    </li>
    <li class="-lf-el-block-insert"><a href="javascript:void(0);">Insert <div class="-lf-el-caret"></div></a>
      <ul>
        <li class="-lf-el-block-insert-above"><a href="javascript:void(0);">Above</a></li>
        <li class="-lf-el-block-insert-below"><a href="javascript:void(0);">Below</a></li>
      </ul>
    </li>
    <li class="-lf-el-block-edit-menu"><a href="javascript:void(0);">Edit <div class="-lf-el-caret"></div></a>
      <ul>
        <li class="-lf-el-block-edit-duplicate"><a href="javascript:void(0);">Duplicate</a></li>
        <li class="-lf-el-block-edit-delete"><a href="javascript:void(0);">Delete</a></li>
      </ul>
    </li>
  </ul>
</div>

<div class="-lf-el-inline-button -lf-el-img-edit -lf-el-reset">
  <img src="{{ url('assets/images/editor/icons/image.svg') }}" class="-lf-el-icon"
    onMouseOver="this.src = '{{ url('assets/images/editor/icons/image-hover.svg') }}';"
    onMouseOut="this.src = '{{ url('assets/images/editor/icons/image.svg') }}';"
  >
  <ul class="-lf-el-dropdown -lf-el-reset">
    <li class="-lf-el-img-select"><a href="javascript:void(0);">Browse...</a></li>
    <li class="-lf-el-img-link"><a href="javascript:void(0);">Link...</a></li>
    <li class="separator"><hr></li>
    <li class="-lf-el-img-hide"><a href="javascript:void(0);">Hide image</a></li>
  </ul>
</div>

<div class="-lf-el-inline-button -lf-el-link-edit -lf-el-reset">
  <img src="{{ url('assets/images/editor/icons/link.svg') }}" class="-lf-el-icon"
    onMouseOver="this.src = '{{ url('assets/images/editor/icons/link-hover.svg') }}';"
    onMouseOut="this.src = '{{ url('assets/images/editor/icons/link.svg') }}';"
  >
  <ul class="-lf-el-dropdown -lf-el-reset">
    <li class="-lf-el-link-link"><a href="javascript:void(0);">Link...</a></li>
  </ul>
</div>

<div class="container" id="page">
        <!-- Header dark, text left, visual right
  ================================================== -->
<section class="typography-1 -lf-block">
  <div class="header text-light img-bottom-lg -lf-block-bg-img" style="background-image:url('assets/bs4/images/headers/landscape-mountains-nature-sky.jpg')">
    <div id="particles-js-connect" class="header-overlay -lf-block-bg-color" style="background-color:rgba(37,75,98,0.7)">
      <div class="container">
        <div class="header-padding no-padding-b">
          <div class="row">
            <div class="col-xs-12 text-xs-center text-md-right">
              <div class="mb-1 mt-2 hor-spacing-md-a">
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
              <p class="lead">We help entrepreneurs achieving their goal faster with <span class="typed" data-text="['development', 'marketing', 'design']"></span>.</p>
              <div class="btn-container my-3 btn-stack-lg">
                <a class="btn btn-outline-ghost btn-xlg btn-pill -lf-link" href="#" role="button">Contact Us</a>
              </div>
            </div>
            <!-- /.col -->
            <div class="col-sm-12 col-md-6 push-lg-1 col-lg-5 text-xs-center text-md-right img-container my-2">
              <img src="assets/bs4/images/visuals/iWatch-White-2.png" alt="" class="-lf-img img-fluid">
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


    <section class="typography-1 -lf-block">
      <nav class="navbar navbar-dark bg-inverse">
        <div class="container editable">
            <a class="navbar-brand" href="#" target="_blank"><img src="assets/bs4/images/logos/ns-light.svg" class="-lf-img" data-offset="10px 10px" alt="" style="height:42px"></a>
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

    <section class="-lf-block">
      <div class="content text-light">
        <div class="content-overlay polygon-bg" data-color-bg="47306b" data-color-light="57407d" style="background-color:#503778">
          <div class="container">
            <div class="row">
              <div class="col-md-12 editable">
                <h1><a href="index.html" style="color: #ccc" class="-lf-link">Home</a> &rsaquo; Content</h1>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
   


    <!-- Callout, left aligned text, call to action right, dark background
      ================================================== -->
    <section class="typography-1 -lf-block">
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
    <section class="typography-2 -lf-block">
      <div class="content -lf-block-bg-img" style="background-image:url()">
        <div class="content-overlay -lf-block-bg-color" style="background-color:rgba(0,0,0,0.15)">
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
    <section class="typography-2 -lf-block">
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
    <section class="typography-1 -lf-block">
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
    <section class="typography-1 -lf-block">
      <div class="content" style="background-image:url()">
        <div class="content-overlay" style="background-color:rgba(255,255,255,0)">
          <div class="container">
            <div class="row">
              <div class="col-md-5 push-md-7">
                <!-- image -->
                <a href="assets/bs4/images/photos/laptop-technology-ipad-tablet.jpg" data-toggle="lightbox"><img src="assets/bs4/images/photos/laptop-technology-ipad-tablet.jpg" alt="" class="-lf-img img-fluid mdl-shadow--8dp"></a>
              </div>
              <!-- /.col -->
              <div class="col-md-7 pull-md-5">
                <div class="content-padding">
                  <h2>First make it work, then make it better</h2>
                  <p class="lead">NowSquare provides high quality assets/bs4 for your business. We help <a href="#" class="link">entrepreneurs</a> with great ideas achieving their goals faster with self-hosted, white label software.</p>
                  <div class="btn-container">
                    <a class="btn btn-outline-pink btn-pill" href="#" role="button">More <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
                  </div>
                  <div class="mt-2 hor-spacing-sm-a">
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
    <section class="typography-1 -lf-block">
      <div class="content content-padding-l" style="background-image:url()">
        <div class="content-overlay" style="background-color:rgba(255,255,255,0)">
          <div class="container">
            <div class="row">
              <div class="col-md-6 push-md-6">
                <!-- image -->
                <img src="assets/bs4/images/visuals/iPhone-6S---isometric-view-right.png" alt="" class="-lf-img img-fluid">
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
    <section class="typography-1 -lf-block">
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
                <img src="assets/bs4/images/visuals/iPhone-6-4,7-inch-Mockup.png" alt="" class="-lf-img img-fluid">
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
    <section class="typography-1 -lf-block">
      <div class="content content-padding-l" style="background-image:url()">
        <div class="content-overlay" style="background-color:rgba(255,255,255,0)">
          <div class="container">
            <div class="row">
              <div class="col-xs-6 push-xs-3 col-sm-4 push-sm-4 col-md-4 push-md-4 col-xl-4 push-xl-4 text-xs-center">
                <!-- image -->
                <img src="assets/bs4/images/icons/biotech.svg" alt="" class="-lf-img img-fluid">
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
    <section class="typography-1 -lf-block">
      <div class="content content-padding-l" style="background-image:url()">
        <div class="content-overlay" style="background-color:rgba(255,255,255,0)">
          <div class="container">
            <div class="row">
              <div class="col-xs-12 col-sm-6 col-lg-4 push-lg-2 text-xs-center">
                <div class="row">
                  <div class="col-xs-12 col-sm-6 push-sm-3 col-lg-8 push-lg-2 text-xs-center">
                    <!-- image -->
                    <img src="assets/bs4/images/icons/assistant.svg" alt="" class="-lf-img img-fluid">
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
                    <img src="assets/bs4/images/icons/landscape.svg" alt="" class="-lf-img img-fluid">
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
    <section class="typography-1 -lf-block">
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