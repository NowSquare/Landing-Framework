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
    }
  </style>

  <script src="{{ url('assets/bs4/js/scripts.min.js') }}"></script>
</head>

<body>

  <div class="container-fluid">
     <div class="m-5">
      <div class="row">

        <div class="col-12 col-sm-12 col-md-8 offset-md-2 col-lg-6 offset-lg-0 push-lg-3">

          <div class="-x-text mb-4">
            <h1>Contact</h1>
            <p class="lead">Thanks for your interest. Please use this form if you have any questions about our products and we'll get back with you very soon.</p>
          </div>

          <form class="form ajax -x-form lead">
            <div class="form-group">
              <label for="personal_name">Name</label>
              <input type="text" class="form-control" id="personal_name" name="personal_name" placeholder="" required="">
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="" required="">
            </div>
            <div class="form-group">
              <label for="general_textarea">Message</label>
              <textarea class="form-control" id="general_textarea-1" name="general_textarea[]" placeholder="" rows="4" required=""></textarea>
            </div>
            <button type="submit" class="btn btn-xlg btn-block btn-outline-grey mb-4 ladda-button -x-link disabled" data-attachment="bottom right" data-target-attachment="top left" data-style="zoom-in" data-spinner-color="#ffffff"><span class="ladda-label">Send message</span></button>
          </form>

        </div>
      </div>
    </div>
  </div>

</body>

</html>