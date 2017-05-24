<!DOCTYPE html>
<html>

<head>
  <title></title>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="stylesheet" type="text/css" href="{{ url('assets/bs4/css/style.min.css') }}"/>
  <style type="text/css">
    body {
      font-family: Gotham, "Helvetica Neue", Helvetica, Arial, "sans-serif";
      font-weight: 300;
    }
    
    h1, h2, h3, h4, h5, h6 {
      font-family: "Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, "sans-serif";
      font-weight: 300 !important;
    }
  </style>

  <script src="{{ url('assets/bs4/js/scripts.min.js') }}"></script>
</head>

<body>

  <div class="container-fluid">
     <div class="m-5">
      <div class="row">
        <div class="col-12 col-sm-12 col-md-7 col-lg-5 text-center text-md-left offset-sm-0 offset-md-0 offset-lg-2">

          <div class="-x-text mb-4 mt-md-4">
            <h1 class="mb-3">{!! trans('forms::form.get_free_copy') !!}</h1>
            <p class="lead">We'll send you a link to download the latest version for free.</p>
          </div>

          <form class="form ajax form-rounded -x-form">
            <div class="form-group">
              <input type="email" class="form-control form-control-lg" id="email" required name="email" placeholder="Enter your email address">
            </div>

            <button type="submit" class="btn btn-lg btn-block btn-pill btn-pink mb-4 ladda-button -x-link" data-attachment="bottom right" data-target-attachment="top left" data-style="zoom-in" data-spinner-color="#ffffff"><span class="ladda-label">Download Now</span></button>
          </form>
        </div>

        <div class="col-12 col-sm-12 col-md-5 col-lg-4 text-center text-md-left">
          <img src="/templates/assets/images/visuals/software-box-blank.png" alt="" class="-x-img img-fluid">
        </div>
      </div>
    </div>
  </div>

</body>

</html>