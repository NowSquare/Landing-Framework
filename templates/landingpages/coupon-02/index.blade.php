<!DOCTYPE html>
<html style="">
  <head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Coupon</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!--======================================================
			Styles
		======================================================-->

    <link rel="stylesheet" type="text/css" href="/assets/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/framework/1.0.0/css/style.min.css">

    <link href="//fonts.googleapis.com/css?family=Raleway:400%Open+Sans:400" rel="stylesheet">

    <style type="text/css">
      body { 
        font-family: 'Open Sans', sans-serif; 
        font-weight: 400; 
      } 
      h1, h2, h3, h4, h5 { 
        font-family: 'Raleway', sans-serif; 
        font-weight: 400;
      } 
      .text-light h1, .text-light h2, .text-light h3, .text-light h4, .text-light h5 { 
        color: #fff; 
      }
    </style>

		<!--======================================================
			Scripts (keep in head)
		======================================================-->

    <script src="/assets/framework/1.0.0/js/scripts.min.js"></script>
    <script src="/assets/bootstrap/4.0.0/js/bootstrap.min.js"></script>

  </head>
  <body style="background-image: url('{{ url('templates/landingpages/coupon-02/images/wov.png') }}'); background-color: rgb(255, 255, 255);">

    <section class="-x-block">
      <div class="content content-padding-s text-light -x-block-bg-img" style="">
        <div class="content-overlay -x-block-bg-color" style="background-color:rgba(233,30,99,1)">
          <div class="container" style="max-width: 600px">
            <div class="row">
              <div class="col-12">
                  <h1 class="mb-0 mb-md-2 -x-text text-center">My Business</h1>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="-x-block">
      <div class="photos photos-padding-none -x-block-bg-img" style="">
        <div class="photos-overlay -x-block-bg-color" style="background-color:rgba(255,255,255,0)">
          <div class="container" style="max-width: 600px">
            <div class="row">
              <div class="col-12 text-center">
                <img src="{{ url('/templates/landingpages/coupon-02/images/limited-time.png') }}" alt="" class="img-fluid -x-img">
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="-x-block">
      <div class="content content-padding-none -x-block-bg-img" style="">
        <div class="content-overlay -x-block-bg-color" style="background-color:rgba(255,255,255,0)">
          <div class="container" style="max-width: 600px">
            <div class="row">
              <div class="col-12">
                <div class="mt-4 mt-md-5">
                  <h1 class="mb-0 mb-md-2 -x-text">Limited Time Offer</h1>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="-x-block">
      <div class="content content-padding-none -x-block-bg-img" style="">
        <div class="content-overlay -x-block-bg-color" style="background-color:rgba(255,255,255,0)">
          <div class="container" style="max-width: 600px">
            <div class="row">
              <div class="col-12">
                <div class="-x-text">
                  <p class="lead">Take 20% off your next purchase!</p>
                  <small class="text-muted">Valid from {{ \Carbon\Carbon::now('UTC')->addDays(1)->toDayDateTimeString() }} until {{ \Carbon\Carbon::now('UTC')->addMonths(2)->toDayDateTimeString() }}.</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="-x-block mb-5">
      <div class="content content-padding-none -x-block-bg-img" style="">
        <div class="content-overlay -x-block-bg-color" style="background-color:rgba(255,255,255,0)">
          <div class="container" style="max-width: 600px">
            <div class="row">
              <div class="col-12">
                <div class="mt-4 mt-md-5">
                  <div class="row mt-4">
                    <div class="col-12">
                      <a href="#" class="btn btn-pink btn-xlg rounded-0 btn-block mb-4 -x-link">Redeem</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

  </body>
</html>