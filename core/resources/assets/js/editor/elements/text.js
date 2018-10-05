function lfInitText() {
  /*
   * Show and focus inline TinyMCE editor when creating a new editor
   */

  tinyMCE.on('AddEditor', function(e) {
    e.editor.on('NodeChange', function(e) {  // now that we know the editor set a callback at "NodeChange."
      e.target.fire("focusin");     // NodeChange is at the end of editor create. Fire focusin to render and show it
    });
  });

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

  /*
   * Bind TinyMCE on click text-editable
   */
  $(function() {
    // Get all -x-blocks for linking named anchors
    var link_list = [];

    $('.-x-block').each(function() {
      var id = $(this).attr('id');
      var title = $(this).attr('data-title');

      if (typeof id !== typeof undefined && id !== false && typeof title !== typeof undefined && title !== false) { // Block has id and title
        link_list.push({
          title: title, 
          value: '#' + id
        });
      } else if (typeof id !== typeof undefined && id !== false) { // Block only has id
        link_list.push({
          title: id, 
          value: '#' + id
        });
      }
    });

    // Get all fonts used on page, and those to the editor
    var font_formats_page = styleInPage('fontFamily');
    var font_prefix = '';

    for(var i= 0; i < font_formats_page.length; i++) {
      var f = font_formats_page[i];
      if (f.indexOf('"') == 0 && f != '"Material Icons"') {
        var font = f.substring(f.indexOf('"') + 1,f.lastIndexOf('"'));
        if (font != '') font_prefix += font + '=' + f + ';';
      } else if (f != '"Material Icons"') {
        var font = f.substring(0,f.indexOf(','));
        if (font != '') font_prefix += font + '=' + f + ';';
      }
    }

    $('body').on('click', '.-x-text', function() {
      var $el = $(this);

      // Check if TinyMCE already is attached
      if ($el.hasClass('mce-content-body')) return false;

      // Check if element has id, if not generate a semi-unique id.
      // This is needed for TinyMCE to have separate inline editors
      // per element.
      var id = $el.attr('id');

      if (typeof id == 'undefined') {
        var timestamp = new Date().getTime();
        var id = 'mce_' + timestamp;
        $el.attr('id', id);
      }

      // Set toolbar based on element
      var tag = $el.prop('tagName').toLowerCase();

      switch (tag) {
        case 'h1':
        case 'h2':
        case 'h3':
        case 'h4':
        case 'h5':
        case 'p':
          var toolbar = 'undo redo | fontselect fontsizeselect | bold italic link | image | forecolor';
          var plugins = 'advlist autolink lists link image anchor media contextmenu paste colorpicker textcolor';
          break;
        default: 
          var toolbar = 'undo redo | bold italic link | fontselect fontsizeselect styleselect | image | bullist | forecolor | table';
          var plugins = 'advlist autolink lists link image anchor code media table contextmenu paste colorpicker textcolor';
      }

      //var font_formats = font_prefix + 'Arial=arial,helvetica,sans-serif;Courier New=courier new,courier;Times New Roman=times new roman,times;Verdana=verdana,geneva';
      var font_formats = font_prefix;

      tinymce.init({
        selector: '#' + id,
        skin: 'dark',
        inline: true,
        menubar: false,
        schema: 'html5',
        relative_urls: false,
        apply_source_formatting: false, 
        extended_valid_elements : "@[itemscope|itemtype|itemprop|content],div,meta[*],span[style,class],link[*],script[charset|defer|language|src|type]",
        valid_children : "+body[meta|title|link],+div[h2|span|object],+object[param|embed]",
        verify_html: false, 
        file_browser_callback: lfelFinderBrowser,
        plugins: plugins,
        toolbar: toolbar,
        table_default_attributes: {
          class: 'table'
        },
        font_formats: font_formats,
        fontsize_formats: "8pt 9pt 10pt 11pt 12pt 14pt 15pt 16pt 18pt 20pt 21pt 22pt 24pt 26pt 28pt 36pt 48pt 54pt 72pt",
        link_list: link_list,
        init_instance_callback : function(editor) {
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
        },
        setup: function (editor) {
          editor.on('Change', function (e) {
            lfSetPageIsDirty();
            if (typeof Tether !== 'undefined') {
              Tether.position();
            }
          });
        }
      });

    });

    if ($('.-x-mail').length) {
      //$('body').on('click', '.-x-mail', function() {
        var $el = $(this);
      //  var $el = $('.-x-mail');

        // Check if TinyMCE already is attached
        if ($el.hasClass('mce-content-body')) return false;

        // Check if element has id, if not generate a semi-unique id.
        // This is needed for TinyMCE to have separate inline editors
        // per element.
        var id = $el.attr('id');

        if (typeof id == 'undefined') {
          var timestamp = new Date().getTime();
          var id = 'mce_' + timestamp;
          $el.attr('id', id);
        }

        var toolbar = 'mail_vars | undo redo | bold italic link | styleselect | image | bullist | forecolor';
        var plugins = 'advlist autolink lists link image anchor code media table contextmenu paste colorpicker textcolor';

        $.getJSON(_lang["url"] + '/emailcampaigns/emails/editor/variables')
        .done(function(menu) {
          var menu = menu;

          tinymce.init({
            /*selector: '#' + id,*/
            selector: '.-x-mail',
            skin: 'dark',
            fixed_toolbar_container: '#editor_toolbar',
            inline: true,
            menubar: false,
            schema: 'html5',
            convert_urls: false,
            relative_urls: false,
            apply_source_formatting: false, 
            extended_valid_elements: 'span[style,class],script[charset|defer|language|src|type]',
            verify_html: false, 
            file_browser_callback: lfelFinderBrowser,
            plugins: plugins,
            toolbar: toolbar,
            init_instance_callback : function(editor) {
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
            },
            setup: function (editor) {
              emailEditor = editor;

              editor.addButton('mail_vars', {
                type: 'listbox',
                text: _lang['variables'],
                icon: false,
                autofocus: false,
                onselect: function (e) {
                  editor.insertContent(this.value());
                  this.value(null);
                },
                menu: menu,
                onPostRender: function () {
                  // Select the second item by default
                  //this.value('&nbsp;<em>Some italic text!</em>');
                }
              });

              editor.on('Change', function (e) {
                lfSetPageIsDirty();
                if (typeof Tether !== 'undefined') {
                  Tether.position();
                }
              });

              // Hack to prevent editor from hiding
              editor.on('blur', function () {
                return false;
              });
            }
          }); // tinymce.init
        }); // getJSON
      //});
    };
  });
}

/* 
  Duplicate wysiwyg, remove TinyMCE references
*/

function lfDuplicateBlockText($new_block) {
  // Remove TinyMCE attributes
  $new_block.find('[contenteditable]').attr('contenteditable', null);
  $new_block.find('[spellcheck]').attr('spellcheck', null);

  // Remove all TinyMCE classes starting with mce-
  $new_block.find('[class*=mce-]').each(function() {
    this.className = this.className.replace(/(^| )mce-[^ ]*/g, '');
  });

  // Remove attributes starting with data-mce
  $new_block.find('div,span,img').each(function() {
    lfRemoveAttributesStartingWith($(this), 'data-mce-');
  });

  // Remove TinyMCE ids + attributes starting with data-mce
  $new_block.find('[id*=mce_]').each(function() {
    $(this).attr('id', null);
  });

  // Remove TinyMCE style
  $new_block.find('[id*=mceDefaultStyles]').remove();
  $new_block.find('[id*=mce]').remove();
}