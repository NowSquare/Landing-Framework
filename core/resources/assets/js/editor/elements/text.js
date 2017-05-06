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
          var toolbar = 'undo redo | bold italic link | image | forecolor'; break;
        default: 
          var toolbar = 'undo redo | bold italic link | styleselect | image | bullist | forecolor';
      }

      // $el.attr('contenteditable', true);

      tinymce.init({
        selector: '#' + id,
        skin: 'dark',
        inline: true,
        menubar: false,
        schema: 'html5',
        relative_urls: false,
        apply_source_formatting: false, 
        extended_valid_elements: 'span[style,class],script[charset|defer|language|src|type]',
        verify_html: false, 
        file_browser_callback: lfelFinderBrowser,
        plugins: [
          'advlist autolink lists link image anchor',
          'code',
          'media table contextmenu paste colorpicker'
        ],
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
          editor.on('Change', function (e) {
            lfSetPageIsDirty();
            if (typeof Tether !== 'undefined') {
              Tether.position();
            }
          });
        }
      });
    });
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