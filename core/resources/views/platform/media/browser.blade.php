<link rel="stylesheet" href="<?= url('assets/packages/jquery-ui/jquery-ui.min.css') ?>" />
<link rel="stylesheet" type="text/css" href="<?= url('assets/css/elfinder.min.css') ?>">
<script src="<?= url('assets/packages/elfinder/js/elfinder.min.js') ?>"></script>
<style type="text/css">
  html, body {
    overflow: hidden;
  }
</style>
      <div id="elfinder"></div>

<script type="text/javascript" charset="utf-8">
  // Documentation for client options:
  // https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
  $().ready(function() {
    var $elf
    var $window = $(window);

    // Keep bootstrap from messing up our buttons.
    if($.fn.button.noConflict) {
      $.fn.btn = $.fn.button.noConflict();
    }

    setTimeout(function() {

      $elf = $('#elfinder').elfinder({
        customData: { 
          _token: '<?= csrf_token() ?>'
        },
        url : '<?= url('elfinder/connector') ?>',  // connector URL
        resizable: false,
        rememberLastDir: false,
        useBrowserHistory: false,
        /*
        uiOptions: {
          toolbar : [
            // toolbar configuration
            ['open'],
            ['back', 'forward'],
            ['reload'],
            ['home', 'up'],
            ['mkdir', 'mkfile', 'upload'],
            ['info'],
            ['quicklook'],
            ['copy', 'cut', 'paste'],
            ['rm'],
            ['duplicate', 'rename', 'resize', 'edit'],
            ['extract', 'archive'],
            ['search'],
            ['view'],
            ['help']
          ]
        },*/
        uiOptions: {
          toolbar : [
            ['back', 'forward'],
            ['mkdir', 'mkfile', 'upload'],
            ['info'],
            ['quicklook'],
            [ 'paste'],
            ['rm'],
            ['duplicate', 'rename', 'resize'],
            ['search'],
            ['view']
          ]
        },
        contextmenu : {
          files  : [
            'getfile', '|','open', '|', 'copy', 'cut', 'paste', 'duplicate', '|',
            'rm', '|', 'edit', 'rename', '|', 'archive', 'extract', '|', 'info'
          ]
        },
      });

      $('.elfinder-button[title]').attr('data-toggle', 'tooltip');
      bsTooltipsPopovers();

      $window.resize(resizeElFinder);
      resizeElFinder();
    }, 100);

    function resizeElFinder()
    {
      var offset = ($window.width() < 992) ? 62 : 62;
      var win_height = parseInt($window.height()) - offset;
      if( $elf.height() != win_height ){
        $elf.height(win_height).resize();
      }
    }
  });
</script>