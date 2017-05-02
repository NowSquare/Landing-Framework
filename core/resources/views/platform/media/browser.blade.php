<link rel="stylesheet" href="<?= url('assets/packages/jquery-ui/jquery-ui.min.css') ?>" />
<link rel="stylesheet" type="text/css" href="<?= url('assets/css/elfinder.min.css') ?>">
<script src="<?= url('assets/packages/elfinder/js/elfinder.min.js') ?>"></script>

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
      var offset = ($window.width() < 992) ? 62 : 62;
      var win_height = parseInt($window.height()) - offset;
      if( $elf.height() != win_height ){
        $elf.height(win_height).resize();
      }
    }
  });
</script>