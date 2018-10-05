<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Simple Launch</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" href="/assets/bs4/css/style.min.css">
    <link href="//fonts.googleapis.com/css?family=Poppins:200,400%7CNoto%20Sans:300,400,700" rel="stylesheet">
    <style type="text/css"> body, html { height:100%; min-height: 720px; } body { font-family: 'Noto Sans', sans-serif; font-weight: 300; background-size: cover; background-repeat: no-repeat; background-position: center center; } h1, h2, h3, h4, h5, h6 { font-family: 'Poppins', sans-serif; font-weight: 300 !important; } </style>
    <script src="/assets/bs4/js/scripts.min.js"></script>
  </head>
  <body style="background-image: url('{{ url('templates/assets/images/headers/starry-sky-during-night.jpg') }}'); background-color: rgb(255, 255, 255);">
    <section class="-x-block">
      <div class="header text-light -x-block-bg-img" style="">
        <div class="header-overlay -x-block-bg-color" style="">
          <div class="container">
            <div class="header-padding-xxl">
              <div class="row">
                <div class="col-12 col-lg-10 push-lg-1 text-center my-5">
                  <div class="-x-text mt-3">
                    <p class="lead">We'll launch in</p>
                  </div>
                  <h1 class="display-4 mt-3 -x-countdown my-5" data-countdown="{{ \Carbon\Carbon::now('UTC')->addDays(30)->format('Y-m-d H:i:s') }}">
                    <span class="day">_</span> day(s) and <span class="hour">__</span>h <span class="minute">__</span>m <span class="second">__</span>s 
                  </h1>
                  <div class="btn-container btn-stack-md mb-3"> <a class="btn btn-outline-ghost btn-xlg btn-pill -x-link" href="#" role="button">Get Notified On Launch</a> </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </body>
</html>