<!DOCTYPE html>
<html lang="{{ \App::getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" type="image/x-icon" href="{{ \Platform\Controllers\Core\Reseller::get()->favicon }}" />

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ \Platform\Controllers\Core\Reseller::get()->name }}</title>

  <!-- Styles -->
  <link href="{{ asset('assets/css/styles.min.css') }}" rel="stylesheet">
  <link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  <style type="text/css">
  html, body {
    height: 100%;
    width: 100%;
    text-align: center;
    display: table;
  }

  #box {
    display: table-cell;
    vertical-align: middle;
  }

  #logo {
    font-size: 5rem;
  }

  #logo i {
    font-size: 5rem;
    top: 6px;
    position: relative;
  }
  </style>

</head>
<body>

  <div id="box">
    <div id="logo"><a href="{{ url('login') }}"><img src="{{ \Platform\Controllers\Core\Reseller::get()->logo_square }}" style="height: 128px; margin: 2rem" alt="{{ \Platform\Controllers\Core\Reseller::get()->name }}"></a></div>
  </div>

  <!-- Scripts -->
  <script src="{{ url('assets/js/scripts.min.js') }}"></script>
</body>
</html>