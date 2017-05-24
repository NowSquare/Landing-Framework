/*
  Button dropdown open on click and delay
  with temporary z-index change to make sure
  the dropdown isn't blocked.
*/

function lfInitDropdowns() {
  /*
    Show dropdown on click
  */
  
  $('body').on('click', '.-x-el-inline-button-clone', function() {
    var $button = $(this);
    var $block_edit_dropdown = $button.find('.-x-el-dropdown');

    if (typeof $block_edit_dropdown !== typeof undefined && $block_edit_dropdown !== false) {
      $block_edit_dropdown.css('cssText', 'display: block !important;');

      // Set z-index of button temporary to a high value
      if ($button.css('z-index') != 1000000) {
        $button.attr('data-x-zIndex', $button.css('z-index'));
        $button.css('cssText', 'z-index: 1000000 !important;');
      }

      // Reposition tethered elements because $block_edit_dropdown.css('cssText', ...); seems to reset position
      Tether.position();
    }
  });

  /*
    Hide dropdown on mouse leave with delay
  */

  var lfMouseLeaveDropDown;

  $('body').on('mouseleave', '.-x-el-dropdown', function(e, data) {
    var $dropdown = $(this);

    if (typeof data !== 'undefined' && data.immediate) {
      lfMouseLeaveDropDown = setTimeout(function() {
        $dropdown.css('cssText', 'display: none !important;');

        // Set z-index back to old value
        var $button = $dropdown.parents('.-x-el-inline-button-clone');
        $button.css('cssText', 'z-index: ' + $button.attr('data-x-zIndex') + ' !important;');
        $button.attr('data-x-zIndex', null);

        // Reposition tethered elements because $dropdown.css('cssText', ...); seems to reset position
        Tether.position();
      }, 0);
    } else {
      lfMouseLeaveDropDown = setTimeout(function() {
        $dropdown.css('cssText', 'display: none !important;');

        // Set z-index back to old value
        var $button = $dropdown.parents('.-x-el-inline-button-clone');
        $button.css('cssText', 'z-index: ' + $button.attr('data-x-zIndex') + ' !important;');
        $button.attr('data-x-zIndex', null);

        // Reposition tethered elements because $dropdown.css('cssText', ...); seems to reset position
        Tether.position();
      }, 200);
    }
  });

  $('body').on('mouseenter', '.-x-el-dropdown', function() {
    clearTimeout(lfMouseLeaveDropDown);
  });
};

/*
  Extracts icon class from css class.
  Requires a parent window to test classes.
*/

function lfExtractIconClass(css_class) {
  var icon = {};

  icon.font = '';
  icon.class = '';

  if (typeof css_class !== typeof undefined && css_class !== false) {
    var font_classes = ['fa', 'mi', 'iml'];
    var css_classes = css_class.split(/ +/);
    var css_classes_new = [];

    // Filter classes starting with -x-
    for (var i = 0, len = css_classes.length; i < len; i++) {
      if (css_classes[i].substring(0, 3) != '-x-') {
        css_classes_new.push(css_classes[i]);
      }
    }

    css_classes = css_classes_new;

    var icon_class_found = '';
    var font_found = '';

    // Check for service class
    for (var i = 0, len = font_classes.length; i < len; i++) {
      var font = font_classes[i];
      if ($.inArray(font, css_classes) != -1) {
        font_found = font;
        break;
      }
    }

    icon.font = font_found;

    // Check for icon class
    for (var i = 0, len = css_classes.length; i < len; i++) {
      var css_class = css_classes[i];

      if (font_found != css_class) {
        var $dummy = $('<i>', {class: font_found + ' ' + css_class}).appendTo($('body'));

        // Check if there is a class with ":before content" set
        var el = $dummy[0];// window.parent.document.getElementsByClassName(css_class);
        var before_content = window.getComputedStyle(el, ':before').getPropertyValue('content');

        if (before_content && before_content !== 'none') {
          //console.log(window.getMatchedCSSRules(el, '::before'));
          icon_class_found = css_class;

          // Cleanup
          $dummy.remove();
          break;
        }
      }
    }

    icon.class = icon_class_found;

    return icon;
  } else {
    return icon;
  }
}

/* 
  Swap two elements.
  http://stackoverflow.com/a/8034949
*/

function lfSwapElements(elm1, elm2) {
  var parent1, next1,
      parent2, next2;

  parent1 = elm1.parentNode;
  next1 = elm1.nextSibling;
  parent2 = elm2.parentNode;
  next2 = elm2.nextSibling;

  parent1.insertBefore(elm2, next1);
  parent2.insertBefore(elm1, next2);
}

/* 
  Get clean HTML for export and saving
*/

function lfGetHtml() {

  // Get a cloned version of the html object
  var $html = $('html').clone();

  // Remove editor and plugin elements
  $html.find('.-x-tmp, .-x-el-inline-button, .-x-editor-asset, .-x-el-dropdown, .-x-editor-inline-modal-bg, #window-resizer-tooltip, #core-notify, #notify-bootstrap, #notify-metro, .notifyjs-corner').remove();

  // Remove generated form classes
  $html.find('.form-group').removeClass('has-error has-danger');

  // Remove all localhost script
  $html.find('script[src]').each(function() {
    var src = $(this).attr('src');
    var remove_src_starting_with = ['http://127.0.0.1', 'https://127.0.0.1', 'http://localhost', 'https://localhost', '//localhost', '//127.0.0.1'];

    for (var i = 0, len = remove_src_starting_with.length; i < len; i++) {
      if (src.substr(0, remove_src_starting_with[i].length) == remove_src_starting_with[i]) {
        $(this).remove();
      }
    }
  });

  // Remove empty style blocks
  $html.find('style[type="text/css"]').each(function() {
    var contents = $(this).text();

    if (contents == '') {
      $(this).remove();
    }
  });

  // Remove all classes starting with -x-data-
  $html.find('[class*=-x-data-]').each(function() {
    this.className = this.className.replace(/(^| )-x-data-[^ ]*/g, '');

    // Remove all attributes starting with 
    lfRemoveAttributesStartingWith($(this), 'data-x-');
  });

  // Remove TinyMCE  style="position: relative;"
  //$html.find('[class*=mce-]').each(function() {
  //  $(this).attr('id', null);
  //});

  // Remove TinyMCE attributes
  $html.find('[contenteditable]').attr('contenteditable', null);
  $html.find('[spellcheck]').attr('spellcheck', null);

  // Remove all TinyMCE classes starting with mce-
  $html.find('[class*=mce-]').each(function() {
    this.className = this.className.replace(/(^| )mce-[^ ]*/g, '');
  });

  // Remove attributes starting with data-mce
  $html.find('div,span,img').each(function() {
    lfRemoveAttributesStartingWith($(this), 'data-mce-');
  });

  // Remove TinyMCE ids + attributes starting with data-mce
  $html.find('[id*=mce_]').each(function() {
    $(this).attr('id', null);
  });

  /*$html.find('[id*=mce_]').attr('id', null);*/

  // Remove TinyMCE style
  $html.find('[id*=mceDefaultStyles]').remove();
  $html.find('[id*=mce]').remove();

  // Various
  $html.find('[data-tether-id]').remove();

  // Get full html including DOCTYPE
  var html = lfGetDocTypeAsString() + $html[0].outerHTML;

  return html;
}

var lfGetDocTypeAsString = function () { 
    var node = document.doctype;
    return node ? "<!DOCTYPE "
         + node.name
         + (node.publicId ? ' PUBLIC "' + node.publicId + '"' : '')
         + (!node.publicId && node.systemId ? ' SYSTEM' : '') 
         + (node.systemId ? ' "' + node.systemId + '"' : '')
         + '>\n' : '';
};

lfGetDocTypeAsString() + document.documentElement.outerHTML   

/* 
  Remove html attributes starting with string
*/

function lfRemoveAttributesStartingWith(target, starts_with) {
  var i,
      $target = $(target),
      attrName,
      dataAttrsToDelete = [],
      dataAttrs = $target.get(0).attributes,
      dataAttrsLen = dataAttrs.length;

  // loop through attributes and make a list of those
  // that begin with 'data-'
  for (i=0; i<dataAttrsLen; i++) {
    if ( starts_with === dataAttrs[i].name.substring(0,starts_with.length) ) {
      // Why don't you just delete the attributes here?
      // Deleting an attribute changes the indices of the
      // others wreaking havoc on the loop we are inside
      // b/c dataAttrs is a NamedNodeMap (not an array or obj)
      dataAttrsToDelete.push(dataAttrs[i].name);
    }
  }
  // delete each of the attributes we found above
  // i.e. those that start with "data-"
  $.each( dataAttrsToDelete, function( index, attrName ) {
    $target.removeAttr( attrName );
  })
};