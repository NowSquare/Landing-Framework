<!DOCTYPE html>
<html>
  <head>
  <meta charset="utf-8">
  <title>elFinder 2.0</title>
  <style type="text/css">
html, body {
  background-color: transparent !important;
}
</style>
  <link rel="stylesheet" href="<?= url('assets/packages/jquery-ui/jquery-ui.min.css') ?>" />
  <link href="{{ asset('assets/css/styles.min.css') }}" rel="stylesheet">
  <script src="<?php echo url('assets/js/scripts.min.js'); ?>"></script>
  <link rel="stylesheet" type="text/css" href="<?= url('assets/css/elfinder.min.css') ?>">
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
          FileBrowserDialogue.mySubmit(file.url); // pass selected file path to TinyMCE
        },
        commands : [
          /*'open', */'reload', 'home', 'up', 'back', 'forward', 
          'download', 'rm', 'rename', 'mkdir', 'upload', 'copy', 
          'paste'/*, 'edit'*/, 'search', 'info', 'view',
          'resize', 'sort'
        ]
      });

      $('.elfinder-button[title]').attr('data-toggle', 'tooltip');
      bsTooltipsPopovers();

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
