<!DOCTYPE html>
<html style="">
  <head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Deal</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!--======================================================
			Styles
		======================================================-->

    <link rel="stylesheet" type="text/css" href="/assets/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/framework/1.0.0/css/style.min.css">

    <link href="//fonts.googleapis.com/css?family=Merriweather:400%7CYantramanav:400" rel="stylesheet">

    <style type="text/css">
      body { 
        font-family: 'Yantramanav', sans-serif; 
        font-weight: 400; 
      } 
      h1, h2, h3, h4, h5 { 
        font-family: 'Merriweather', serif; 
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
  <body>

    <section class="-x-block">
      <div class="content content-padding-none -x-block-bg-img" style="">
        <div class="content-overlay -x-block-bg-color" style="background-color:rgba(255,255,255,1)">
          <div class="container" style="max-width: 600px">
            <div class="row">
              <div class="col-12">
                <div class="mt-4 mt-md-5">
                  <h1 class="mb-0 mb-md-2 text-center -x-text">Run Unlimited Deals $50/Month</h1>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="-x-block">
      <div class="photos photos-padding-xl -x-block-bg-img" style="">
        <div class="photos-overlay -x-block-bg-color" style="background-color:rgba(255,255,255,1)">
          <div class="container" style="max-width: 600px">
            <div class="row">
              <div class="col-12">
                <img src="{{ url('/templates/landingpages/deal-01/images/promoteyourbusiness.jpg') }}" alt="" class="img-fluid -x-img mdl-shadow--2dp" style="min-width: 100%">
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="-x-block">
      <div class="content content-padding-none -x-block-bg-img" style="">
        <div class="content-overlay -x-block-bg-color" style="background-color:rgba(255,255,255,1)">
          <div class="container" style="max-width: 600px">
            <div class="row">
              <div class="col-12">

                  <div class="-x-text">

                    <p class="lead">Nearby Notifications show when a user pulls their screen down to open their Android phone, or open their notification shade. It looks like a regular notification, but it's only visible as long as the phone is within reach of the beacon broadcasting the message. Run Unlimited Deals for $50 a month!</p>

                    <div class="row mt-4">
                      <div class="col-12">
                        <p class="lead font-weight-bold">Phone: 1234567890</p>
                        <p class="lead font-weight-bold">Website: www.example.com</p>
                      </div>
                    </div>
                    <small class="text-muted">Expires {{ \Carbon\Carbon::now('UTC')->addDays(30)->toDayDateTimeString() }}</small>

                  </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="-x-block mb-5">
      <div class="content content-padding-none -x-block-bg-img" style="">
        <div class="content-overlay -x-block-bg-color" style="background-color:rgba(255,255,255,1)">
          <div class="container" style="max-width: 600px">
            <div class="row">
              <div class="col-12">
                <div class="mt-4 mt-md-5">

                  <div class="row mt-4">
                    <div class="col-12">
                      <a href="#" class="btn btn-success btn-xlg rounded-0 btn-block mb-4 -x-link">Get It Now</a>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-12 col-sm-6">
                      <a href="tel:1234567890" class="btn btn-primary btn-lg rounded-0 btn-block mb-4 -x-link"><i class="mi phone"></i> Call</a>
                    </div>
                    <div class="col-12 col-sm-6">
                      <a href="#" class="btn btn-primary btn-lg rounded-0 btn-block mb-4 -x-link" target="_blank"><i class="mi info_outline"></i> More</a>
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