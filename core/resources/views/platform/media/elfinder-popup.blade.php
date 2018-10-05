<!DOCTYPE html>
<!--[if IE 8]>         <html class="ie8" lang="{{ App::getLocale() }}"> <![endif]-->
<!--[if IE 9]>         <html class="ie9 gt-ie8" lang="{{ App::getLocale() }}"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="gt-ie8 gt-ie9 not-ie" lang="{{ App::getLocale() }}"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
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
    <style type="text/css">
    body, html {
        margin:0;
        padding:0;
    }
    </style>
</head>
<body class="elfinder-picker">
  <div id="elfinder" class="el-picker"></div>

    <script type="text/javascript">
        $().ready(function () {

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
                  ['mkdir', 'upload'],
                  ['rm'],
                  ['resize'],
                  ['search']
                ]
              },
              contextmenu : {
                files  : [
                  'getfile', '|','open', '|', 'copy', 'cut', 'paste', 'duplicate', '|',
                  'rm', '|', 'edit', 'rename', '|', 'archive', 'extract', '|', 'info'
                ]
              },
              commandsOptions: {
                getfile: {
                  oncomplete: 'destroy'
                }
              },
              getFileCallback: function (file) {
                window.parent.<?php echo $callback ?>(file.url, '<?= $input_id ?>');
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


</body>
</html>