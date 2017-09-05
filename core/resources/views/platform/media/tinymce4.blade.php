<!DOCTYPE html>
<html>
  <head>
  <meta charset="utf-8">
  <title>elFinder 2.0</title>
  <style type="text/css">
  html, body {
    background-color: transparent !important;
    overflow: hidden !important;
  }
  </style>
  <link rel="stylesheet" href="<?= url('assets/packages/jquery-ui/jquery-ui.min.css') ?>" />
  <link href="{{ asset('assets/css/styles.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="<?= url('assets/css/elfinder.min.css') ?>">
  <script src="{{ url('assets/javascript?lang=' . \App::getLocale()) }}"></script>
  <script src="<?php echo url('assets/js/scripts.min.js'); ?>"></script>
  <script src="<?= url('assets/packages/elfinder/js/elfinder.min.js') ?>"></script>
  <script type="text/javascript">
    var FileBrowserDialogue = {
      init: function() {
        // Here goes your code for setting your custom things onLoad.
      },
      mySubmit: function (URL) {
        // pass selected file path to TinyMCE
        parent.tinymce.activeEditor.windowManager.getParams().setUrl(URL);

        // close popup window
        parent.tinymce.activeEditor.windowManager.close();
      }
    }

  $().ready(function() {
    var $elf
    var $window = $(window);

    setTimeout(function() {

      $elf = $('#elfinder').elfinder({
        customData: { 
          _token: '<?= csrf_token() ?>'
        },
        url : '<?= url('elfinder/connector') ?>',  // connector URL
        resizable: false,
        rememberLastDir: false,
        useBrowserHistory: false,
        getFileCallback: function(file) { // editor callback
          //var path = file.url.replace('{{ url('/') }}', '');

          FileBrowserDialogue.mySubmit(file.url); // pass selected file path to TinyMCE
        },
        /*
      });

      $('.elfinder-button[title]').attr('data-placement', 'bottom');
      $('.elfinder-button[title]').attr('data-toggle', 'tooltip');
      bsTooltipsPopovers();https://secure.avangate.com/order/checkout.php?PRODS=4709793&QTY=1

      $window.resize(resizeElFinder);
      resizeElFinder();
    }, 100);

    function resizeElFinder()
    {
      var win_height = parseInt($window.height()) - 2;
      if( $elf.height() != win_height ){
        $elf.height(win_height).resize();
      }
    }
  });


  </script>
  </head>
  <body>
  <div id="elfinder"></div>
</body>
</html>
