<!DOCTYPE html>
<html>

<head>
  <title></title>

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

  <link rel="stylesheet" type="text/css" href="{{ url('assets/bs4/css/style.min.css') }}"/>
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
  <link href="//fonts.googleapis.com/css?family=Dosis:200,400|Open+Sans:300,400,700" rel="stylesheet">

  <script src="{{ url('assets/translations?lang=en') }}"></script>
  <script src="{{ url('assets/bs4/js/scripts.min.js') }}"></script>
</head>

<body>

  <div class="container mt-5">
    <div class="row">
      <div class="col-12 col-sm-12 col-md-6 col-lg-4 text-center text-md-left offset-sm-0 offset-md-0 offset-lg-1">

        <div class="-x-text mb-4">
          <h1>Stay in the Loop</h1>
          <p class="lead">Stay up to date with the best curated news.</p>
        </div>

        <form class="form">
          <div class="form-group" id="formKey">
            <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="Enter your email address">
          </div>
          <button type="submit" class="btn btn-lg btn-block btn-blue mb-4">Get Free Updates</button>
        </form>
      </div>

      <div class="col-12 col-sm-12 col-md-6 col-lg-5 text-center text-md-left">
        <img src="/templates/assets/images/photos/man-using-stylus-pen-for-touching-the-digital-tablet-screen.jpg" alt="" class="-x-img img-fluid mdl-shadow--8dp">
      </div>
    </div>
  </div>

</body>

</html>