<!DOCTYPE html>
<html lang="{{ \App::getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" type="image/x-icon" href="{{ \Platform\Controllers\Core\Reseller::get()->favicon }}" />

  <title>{{ \Platform\Controllers\Core\Reseller::get()->name }}</title>

  <!-- Styles -->
  <link href="{{ asset('assets/css/styles.min.css') }}" rel="stylesheet">

  <!-- Scripts -->
  <script src="{{ url('assets/javascript?lang=' . \App::getLocale()) }}"></script>
  <script>var app_root = "{{ url('/') }}";</script>

  @yield('head')
<?php
if (env('GOOGLE_ANALYTICS_TRACKING_ID', '') != '') {
?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', '<?php echo env('GOOGLE_ANALYTICS_TRACKING_ID', '') ?>', 'auto');
  ga('send', 'pageview');
</script>
<?php
}
?>
</head>
<body>

@yield('content')

  <!-- Scripts -->
  <script src="{{ url('assets/js/scripts.min.js') }}"></script>
<?php if (\App::getLocale() != 'en') { ?>
  <script src="{{ url('assets/js/moment/' . \App::getLocale() . '.js') }}"></script>
  <script>moment.lang('{{ \App::getLocale() }}');</script>
<?php } ?>

  <!-- Fonts -->
  <link href="//fonts.googleapis.com/css?family=Roboto:400,500,700" rel="stylesheet">
  <link href="//fonts.googleapis.com/css?family=Source+Sans+Pro:600,400,700" rel="stylesheet">

@yield('bottom')

  <div id="deviceWarning">
    <div>
      <p class="lead">{!! trans('global.device_warning2') !!}</p>
      <p class="lead">{!! trans('global.device_warning3') !!}</p>
      <p class="lead" style="color: #FFFF00">{!! trans('global.device_warning1') !!}</p>
      <p class="lead"><button type="button" class="btn btn-lg btn-primary" onclick="$('#deviceWarning').remove()">{{ trans('global.device_warning_remove') }}</button></p>
    </div>
  </div>
</body>
</html>