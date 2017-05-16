<!DOCTYPE html>
<html class="editor-modal">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ \Platform\Controllers\Core\Reseller::get()->name }}</title>

  <script src="{{ url('assets/javascript?lang=' . \App::getLocale()) }}"></script>
  <link rel="stylesheet" type="text/css" href="{{ url('assets/bs4/css/style.min.css') }}" />
  <script src="{{ url('assets/bs4/js/scripts.min.js') }}"></script>
</head>
<body>
@yield('content')

  <!-- Editor -->
  <script>var lf_published_url = "";var lf_sl = "";var lf_csrf_token = "'";</script>
  <script src="{{ url('assets/js/scripts.editor.min.js') }}"></script>

@yield('script')
</body>