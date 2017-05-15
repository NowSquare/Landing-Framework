<!DOCTYPE html>
<html lang="{{ \App::getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <link href="{{ asset('assets/css/styles.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="<?= url('assets/packages/jquery-ui/jquery-ui.min.css') ?>" />
  <link rel="stylesheet" type="text/css" href="<?= url('assets/css/elfinder.min.css') ?>">
  <script>var url = '{{ url('/') }}';</script>
  <script src="{{ url('assets/javascript?lang=' . \App::getLocale()) }}"></script>
  <script src="<?php echo url('assets/js/scripts.min.js') ?>"></script>
  <script src="<?= url('assets/packages/elfinder/js/elfinder.min.js') ?>"></script>
<?php if($locale != 'en'){ ?>
  <script src="<?= url("assets/packages/elfinder/js/i18n/elfinder.$locale.js") ?>"></script>
<?php } ?>
</head>
<body class="elfinder-picker">
<div id="elfinder" class="el-picker"></div>
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
        <?php if($locale){ ?>
          lang: '<?= $locale ?>', // locale
        <?php } ?>
        customData: { 
          _token: '<?= csrf_token() ?>'
        },
        url : '<?= url('elfinder/connector') ?>',  // connector URL
        resizable: false,
        rememberLastDir: true,
        useBrowserHistory: false,
        commands : [
          /*'open', */'reload', 'home', 'up', 'back', 'forward', 
          'download', 'rm', 'rename', 'mkdir', 'upload', 'copy', 
          'paste'/*, 'edit'*/, 'search', 'info', 'view',
          'resize', 'sort'
        ],
        commandsOptions: {
          getfile: {
            oncomplete: 'destroy'
          }
        },
        getFileCallback: function (file) {
          var path = file.url.replace('{{ url('/') }}', '');

          window.parent.processSelectedFile(path, '{{ $id }}', '{{ $preview }}');
          parent.$.colorbox.close();
        }
      });

      $('.elfinder-button[title]').attr('data-toggle', 'tooltip');

      $('[data-toggle~=tooltip]').tooltip(
      {
        container: 'body',
        placement: 'bottom'
      });

      $window.resize(resizeElFinder);
      resizeElFinder();
    }, 100);

    function resizeElFinder()
    {
      var win_height = parseInt($window.height());
      if( $elf.height() != win_height ){
        $elf.height(win_height).resize();
      }
    }
  });
</script>
</body></html>