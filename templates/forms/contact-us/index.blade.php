<!DOCTYPE html>
<html>

<head>
  <title></title>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="stylesheet" type="text/css" href="{{ url('assets/bs4/css/style.min.css') }}"/>
  <link href="//fonts.googleapis.com/css?family=Catamaran:500|Istok+Web:400" rel="stylesheet">
  <style type="text/css">
    body {
      font-family: 'Istok Web', sans-serif;
      font-weight: 400;
    }
    
    h1, h2, h3, h4, h5, h6 {
      font-family: 'Catamaran', sans-serif;
      font-weight: 500 !important;
    }
  </style>

  <script src="{{ url('assets/bs4/js/scripts.min.js') }}"></script>
</head>

<body>

  <div class="container-fluid">
     <div class="m-5">
      <div class="row">
        <div class="col-12 col-sm-12 col-md-8 offset-md-2 col-lg-6 offset-lg-0 push-lg-6 text-center text-lg-left">
          <img src="/templates/assets/images/photo-small/architecture-building-business-city.jpg" alt="" class="-x-img rounded mdl-shadow--8dp img-fluid mb-5">
        </div>

        <div class="col-12 col-sm-12 col-md-8 offset-md-2 col-lg-6 offset-lg-0 pull-lg-6 text-center text-md-left">

          <div class="-x-text mb-4">
            <h1>Contact Us</h1>
            <p class="lead">Whether you're looking for answers, would like to solve a problem, or just want to let us know how we did, you can use this form.</p>
          </div>

          <form class="form ajax -x-form lead">

            <div class="form-group">
              <label for="personal_name">Your name</label>
              <input type="text" class="form-control" id="personal_name" name="personal_name" placeholder="" required="">
            </div>
            <div class="form-group">
              <label for="email">Your email address</label>
              <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="" required="">
            </div>
            <div class="form-group">
              <label for="personal_phone">Phone</label>
              <input type="tel" class="form-control" id="personal_phone" name="personal_phone" placeholder="">
            </div>
            <div class="form-group">
              <label for="general_textarea">Message</label>
              <textarea class="form-control" id="general_textarea-1" name="general_textarea[]" placeholder="" required="" rows="4"></textarea>
            </div>
            <button type="submit" class="btn btn-xlg btn-block btn-blue-grey mb-4 ladda-button -x-link disabled" data-attachment="bottom right" data-target-attachment="top left" data-style="zoom-in" data-spinner-color="#ffffff"><span class="ladda-label">Send message</span></button>

          </form>
        </div>
      </div>
    </div>
  </div>

</body>

</html>