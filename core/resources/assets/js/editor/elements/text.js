function lf_initText() {
  /*
   * Show and focus inline TinyMCE editor when creating a new editor
   */

  tinyMCE.on('AddEditor', function(e) {
    e.editor.on('NodeChange', function(e) {  // now that we know the editor set a callback at "NodeChange."
      e.target.fire("focusin");     // NodeChange is at the end of editor create. Fire focusin to render and show it
    });
  });

  function lf_elFinderBrowser(field_name, url, type, win) {
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
      var el = this;

      // Check if TinyMCE already is attached
      if ($(this).hasClass('mce-content-body')) return false;

       // $(this).attr('contenteditable', true);

      tinymce.init({
        skin: 'dark',
        selector: '.-x-text',
        inline: true,
        menubar: false,
        schema: 'html5',
        relative_urls: false,
        apply_source_formatting: false, 
        extended_valid_elements: 'span[style,class],script[charset|defer|language|src|type]',
        verify_html: false, 
        file_browser_callback: lf_elFinderBrowser,
        plugins: [
          'advlist autolink lists link image anchor',
          'code',
          'media table contextmenu paste'
        ],
        toolbar: 'bold italic | bullist | link image',
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

function lf_DuplicateBlockText($new_block) {
  // Remove TinyMCE attributes
  $new_block.find('[contenteditable]').attr('contenteditable', null);
  $new_block.find('[spellcheck]').attr('spellcheck', null);

  // Remove all TinyMCE classes starting with mce-
  $new_block.find('[class*=mce-]').each(function() {
    this.className = this.className.replace(/(^| )mce-[^ ]*/g, '');
  });

  // Remove attributes starting with data-mce
  $new_block.find('div,span,img').each(function() {
    lf_removeAttributesStartingWith($(this), 'data-mce-');
  });

  // Remove TinyMCE ids + attributes starting with data-mce
  $new_block.find('[id*=mce_]').each(function() {
    $(this).attr('id', null);
  });

  // Remove TinyMCE style
  $new_block.find('[id*=mceDefaultStyles]').remove();
  $new_block.find('[id*=mce]').remove();
}