/*
  Generic button related functions.
*/

function lf_initDropdowns() {
  /*
    Show dropdown on click
  */
  
  $('body').on('click', '.-x-el-inline-button-clone', function() {
    var $block_edit_dropdown = $(this).find('.-x-el-dropdown');

    if (typeof $block_edit_dropdown !== typeof undefined && $block_edit_dropdown !== false) {
      $block_edit_dropdown.css('cssText', 'display: block !important;');

      // Set z-index of all buttons temporary to a high value
      $(this).attr('data-x-zIndex', $(this).css('z-index'));
      $(this).css('cssText', 'z-index: 1000000 !important;');

      // Reposition tethered elements because $block_settings.css('cssText', ...); seems to reset position
      Tether.position();
    }
  });

  /*
    Hide dropdown on mouse leave with delay
  */

  var lfMouseLeaveDropDown;

  $('body').on('mouseleave', '.-x-el-dropdown', function() {
    var that = this;

    lfMouseLeaveDropDown = setTimeout( function(){
      $(that).css('cssText', 'display: none !important;');

      // Set z-index back to old value
      var $button = $(that).parents('.-x-el-inline-button-clone');
      $button.css('cssText', 'z-index: ' + $button.attr('data-x-zIndex') + ' !important;');
      $button.attr('data-x-zIndex', null);

      // Reposition tethered elements because $block_settings.css('cssText', ...); seems to reset position
      Tether.position();
    }, 200);
  });

  $('body').on('mouseenter', '.-x-el-dropdown', function() {
    clearTimeout(lfMouseLeaveDropDown);
  });
};

/* 
  Swap two elements.
  http://stackoverflow.com/a/8034949
*/

function lf_SwapElements(elm1, elm2) {
  var parent1, next1,
      parent2, next2;

  parent1 = elm1.parentNode;
  next1   = elm1.nextSibling;
  parent2 = elm2.parentNode;
  next2   = elm2.nextSibling;

  parent1.insertBefore(elm2, next1);
  parent2.insertBefore(elm1, next2);
}

/* 
  Get clean HTML for export and saving
*/

function lf_getHtml() {

  // Get a cloned version of the html object
  var $html = $('html').clone();

  // Remove editor elements
  $html.find('.-x-el-inline-button, .-x-editor-asset').remove();

  // Remove all classes starting with -x-data-
  $html.find('[class*=-x-data-]').each(function() {
    this.className = this.className.replace(/(^| )-x-data-[^ ]*/g, '');

    // Remove all attributes starting with 
    lf_removeAttributesStartingWith($(this), 'data-x-');
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
    lf_removeAttributesStartingWith($(this), 'data-mce-');
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

  console.log($html.html());
}

/* 
  Remove html attributes starting with string
*/

function lf_removeAttributesStartingWith(target, starts_with) {
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