<!DOCTYPE html>
<html style="">
  <head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Back Soon</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" href="/assets/bs4/css/style.min.css">
    <link href="//fonts.googleapis.com/css?family=Khula:300|Shanti:400" rel="stylesheet">
    <style type="text/css"> body, html { height:100%; min-height: 720px; } body { font-family: 'Shanti', sans-serif; font-weight: 400; } h1, h2, h3, h4, h5 { font-family: 'Khula', sans-serif; font-weight: 300; } span.day, span.hour, span.minute, span.second { border: 3px solid #fff; border-radius: 50%; width: 100px; height: 100px; display: inline-block; line-height: 108px; } </style>
    <script src="/assets/bs4/js/scripts.min.js"></script>
  </head>
  <body style="background-image: url('/templates/assets/images/headers/abstract-art-background-blue.jpg');">
    <section class="-x-block">
      <div class="header text-light -x-block-bg-img" style="background-image: none;">
        <div class="header-overlay -x-block-bg-color" style="background-color: rgba(0, 0, 0, 0.5);">
          <div class="container">
            <div class="header-padding-xxl">
              <div class="row">
                <div class="col-12 col-lg-10 push-lg-1 text-center my-3">
                  <div class="-x-text mt-3">
                    <h1 class="display-2">We will be back soon</h1> 
                    <p class="lead">We're working hard to improve our website.</p>
                  </div>
                  <div class="btn-container btn-stack-md my-3" style="display: inline-block"> <a class="btn btn-blue btn-pill btn-xlg mdl-shadow--8dp -x-link" href="#" role="button">NOTIFY ME</a> </div>
                  <h1 class="display-4 -x-countdown my-5" data-countdown="{{ \Carbon\Carbon::now('UTC')->addDays(30)->format('Y-m-d H:i:s') }}">
                    <span class="day">_</span> days <span class="hour">__</span> : <span class="minute">__</span> : <span class="second">__</span> 
                  </h1>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </body>
</html>