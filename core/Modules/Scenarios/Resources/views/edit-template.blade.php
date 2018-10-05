<!DOCTYPE html>
<html lang="{{ \App::getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Styles -->
  <link href="{{ asset('assets/css/styles.min.css') }}" rel="stylesheet">

  <!-- Scripts -->
  <script src="{{ url('assets/javascript?lang=' . \App::getLocale()) }}"></script>
  <script>var app_root = "{{ url('/') }}";</script>
  <script src="{{ url('assets/js/scripts.min.js') }}"></script>
  <script src="{{ url('assets/js/tinymce.min.js') }}"></script>

  <script type="text/javascript">
    $(function() {
      $(window.top.document).find('#cboxClose').hide();

      function lfelFinderBrowser(field_name, url, type, win) {
        tinyMCE.activeEditor.windowManager.open({
          file: _lang["url"] + '/elfinder/tinymce',
          title: 'Files',
          width: 940,
          height: 450,
          resizable: 'yes',
          inline: 'yes',  // This parameter only has an effect if you use the inlinepopups plugin!
          popup_css: false, // Disable TinyMCE's default popup CSS
          close_previous: 'no'
          }, {
          setUrl: function (url) {
            win.document.getElementById(field_name).value = url;
          }
        });
        return false;
      }

      var editor = tinymce.init( {
        selector: "#content",
        skin: 'dark',
        menubar: true,
        schema: 'html5',
        relative_urls: false,
        apply_source_formatting: false, 
        extended_valid_elements : "@[itemscope|itemtype|itemprop|content],div,meta[*],span,link[*],title",
        valid_children : "+body[meta|title|link],+div[h2|span|object],+object[param|embed]",
        verify_html: false, 
        file_browser_callback: lfelFinderBrowser,
        table_default_attributes: {
          class: 'table'
        },
        forced_root_block: "",
        plugins: [
          "advlist autolink lists link image charmap preview hr anchor",
          "code fullscreen",
          "nonbreaking save table contextmenu",
          "<?php /*template*/ ?> paste textcolor colorpicker textpattern imagetools"
        ],
        toolbar1: "closeButton save insertfile undo redo | styleselect | forecolor backcolor | bold italic",
        toolbar2: "alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        image_advtab: true,
        content_css: "{{ asset('assets/bs4/css/style.min.css') }}",
        resize: false,
        convert_urls: false,
        paste_use_dialog: true,
        save_enablewhendirty: false,
        save_onsavecallback: function () {
          var ed = tinyMCE.get( 'content' );
          var content = ed.getContent();

          parent.saveTemplateEditor({{ $i }}, content);
        },
        setup: function (ed) {
          ed.on('init', function (e) {
            var w = window,
              d = document,
              e = d.documentElement,
              g = d.getElementsByTagName( 'body' )[ 0 ],
              x = w.innerWidth || e.clientWidth || g.clientWidth,
              y = w.innerHeight || e.clientHeight || g.clientHeight;

            tinyMCE.DOM.setStyle( tinyMCE.DOM.get( "content" + '_ifr' ), 'height', parseInt( y ) - 142 + 'px' );
            tinyMCE.DOM.setStyle( tinyMCE.DOM.get( "content" + '_ifr' ), 'width', 480 + 'px' );

            /* Scrollbar */
            document.getElementById( 'content_ifr' ).contentWindow.document.getElementById( 'tinymce' ).style.height = parseInt( y ) - 143 + 'px';

            /* Set content */
            var content = parent.getTemplateContent({{ $i }});
            tinymce.activeEditor.selection.setContent(content);
          });

          ed.addButton('closeButton', {
            type: 'button',
            text: '{{ trans('global.close') }}',
            icon: false,
            autofocus: false,
            onclick: function (e) {
              $(window.top.document).find('#cboxClose').show();
              $(window.top.document).find('#cboxClose').trigger('click');
            },
          });
        },
        init_instance_callback : function(editor) {
          // Remove CDATA
          editor.serializer.addNodeFilter('script,style', function(nodes, name) {
              var i = nodes.length, node, value, type;

              function trim(value) {
                  return value.replace(/(<!--\[CDATA\[|\]\]-->)/g, '\n')
                          .replace(/^[\r\n]*|[\r\n]*$/g, '')
                          .replace(/^\s*((<!--)?(\s*\/\/)?\s*<!\[CDATA\[|(<!--\s*)?\/\*\s*<!\[CDATA\[\s*\*\/|(\/\/)?\s*<!--|\/\*\s*<!--\s*\*\/)\s*[\r\n]*/gi, '')
                          .replace(/\s*(\/\*\s*\]\]>\s*\*\/(-->)?|\s*\/\/\s*\]\]>(-->)?|\/\/\s*(-->)?|\]\]>|\/\*\s*-->\s*\*\/|\s*-->\s*)\s*$/g, '');
              }
              while (i--) {
                  node = nodes[i];
                  value = node.firstChild ? node.firstChild.value : '';

                  if (value.length > 0) {
                      node.firstChild.value = trim(value);
                  }
              }
          });
        }
      });

      $(window).resize(resizeEditor);

      function resizeEditor() {
        var w = window,
          d = document,
          e = d.documentElement,
          g = d.getElementsByTagName( 'body' )[ 0 ],
          x = w.innerWidth || e.clientWidth || g.clientWidth,
          y = w.innerHeight || e.clientHeight || g.clientHeight;

        tinyMCE.DOM.setStyle( tinyMCE.DOM.get( "content" + '_ifr' ), 'height', parseInt( y ) - 142 + 'px' );
        tinyMCE.DOM.setStyle( tinyMCE.DOM.get( "content" + '_ifr' ), 'width', 480 + 'px' );
      }
    });
  </script>

  <style type="text/css">
    html,
    body {
      margin: 0;
      padding: 0;
    }
    .mce-edit-area {
      background-color: #ccc !important;
    }
    .mce-branding-powered-by {
      display: none;
    }
    .mce-menu-item .mce-ico, .mce-menu-item .mce-text {
      color: #ddd !important;
    }
    #cboxWrapper #cboxClose {
      display: none !important;
      background-image: url({{ url('assets/images/icons/close-circle.svg') }}) !important;
    }
  </style>

</head>
<body>

  <textarea id="content" style="width:100%"></textarea>

</body>
</html>