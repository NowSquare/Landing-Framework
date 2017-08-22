<!DOCTYPE html>
<html>
<head>
  <title>Block Preview</title>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="stylesheet" type="text/css" href="{{ url('assets/bs4.0/css/style.min.css') }}" />
  <script src="{{ url('assets/bs4.0/js/scripts.min.js') }}"></script>

  <link href="//fonts.googleapis.com/css?family=Roboto:100,200|Open+Sans:300,400,700" rel="stylesheet">
  <style type="text/css">
    body, html {
      overflow: hidden;
    }
    body {
      font-family: 'Open Sans', sans-serif;
      font-weight: 300;
    }

    h1, h2, h3, h4, h5, h6 {
      font-family: 'Roboto', sans-serif;
      font-weight: 100 !important;
    }
  </style>
</head>
<body>
@yield('content')
</body>
</html>