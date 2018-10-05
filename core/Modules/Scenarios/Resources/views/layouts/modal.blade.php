<!DOCTYPE html>
<html class="editor-modal">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

  <title>{{ \Platform\Controllers\Core\Reseller::get()->name }}</title>

  <link href="{{ asset('assets/css/styles.min.css') }}" rel="stylesheet">

  <!-- Scripts -->
  <script src="{{ url('assets/javascript?lang=' . \App::getLocale()) }}"></script>

  <script>
    var app_root = "{{ url('/') }}";

    window.Laravel = <?php echo json_encode([
      'csrfToken' => csrf_token(),
    ]); ?>
  </script>

</head>

<body>
@yield('content')

  <!-- Scripts -->
  <script src="{{ url('assets/js/scripts.min.js') }}"></script>

  <script>
    $(function() {
      onPartialLoaded();

      $('.onClickClose').on('click', function() {
        $(window.top.document).find('#cboxClose').trigger('click');
      });

      // Focus window and bind escape to close
      $(window).focus();

      $(document).keyup(function(e) {
        if(e.keyCode === 27) {
          $(window.top.document).find('#cboxClose').trigger('click');
        }
      });
    });
  </script>

@yield('script')

  <!-- Fonts -->
  <link href="//fonts.googleapis.com/css?family=Roboto:400,500,700" rel="stylesheet">
  <link href="//fonts.googleapis.com/css?family=Source+Sans+Pro:600,400,700" rel="stylesheet">
</body>