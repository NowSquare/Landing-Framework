<!DOCTYPE html>
<html>
<head>
  <title>Block Preview</title>

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

  <link rel="stylesheet" type="text/css" href="{{ url('assets/bs4/css/style.min.css') }}" />
  <script src="{{ url('assets/bs4/js/scripts.min.js') }}"></script>

  <link href="//fonts.googleapis.com/css?family=Dosis:200,400|Open+Sans:300,400,700" rel="stylesheet">
  <style type="text/css">
    body, html {
      overflow: hidden;
    }
    body {
      font-family: 'Open Sans', sans-serif;
      font-weight: 300;
    }

    h1, h2, h3, h4, h5, h6 {
      font-family: 'Dosis', sans-serif;
      font-weight: 300 !important;
    }
  </style>
</head>
<body>
@yield('content')
</body>
</html>