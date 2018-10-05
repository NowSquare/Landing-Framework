<!DOCTYPE html>
<html>

<head>
  <title></title>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="stylesheet" type="text/css" href="{{ url('assets/bs4/css/style.min.css') }}"/>
  <link href="//fonts.googleapis.com/css?family=Roboto:400,900" rel="stylesheet">
  <style type="text/css">
    body {
      font-family: 'Roboto', sans-serif;
      font-weight: 400;
    }
    
    h1, h2, h3, h4, h5, h6 {
      font-family: 'Roboto', sans-serif;
      font-weight: 900 !important;
  </style>

  <script src="{{ url('assets/bs4/js/scripts.min.js') }}"></script>
</head>

<body>

  <div class="container-fluid">
     <div class="m-4">
      <div class="row">
        <div class="col-12 col-sm-12 col-md-8 col-lg-6 offset-sm-0 offset-md-2 offset-lg-3">

          <div class="-x-text mb-4 mt-md-4">
            <h1 class="mb-3">Stay Tuned</h1>
            <p class="lead">Get notified about important updates.</p>
          </div>

          <form class="form ajax -x-form">
            <div class="form-group">
              <input type="email" class="form-control form-control-lg" id="email" required name="email" placeholder="Enter your email address">
            </div>

            <button type="submit" class="btn btn-xlg btn-block btn-blue mb-4 ladda-button -x-link" data-attachment="bottom right" data-target-attachment="top left" data-style="zoom-in" data-spinner-color="#ffffff"><span class="ladda-label">Submit</span></button>
          </form>
        </div>

      </div>
    </div>
  </div>

</body>

</html>