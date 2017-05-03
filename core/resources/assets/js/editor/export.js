$(function() {
  $('#export_html').on('click', function() {

    // Get a cloned version of the html object
    var $html = $('html').clone();

    // Remove editor elements
    $html.find('.-x-el-inline-button').remove();
    $html.find('#editor_styles').remove();
    $html.find('#editor_scripts').remove();

    // Remove all classes starting with -x-data-
    $html.find('[class*=-x-data-]').each(function() {
      this.className = this.className.replace(/(^| )-x-data-[^ ]*/g, '');

      // Remove all attributes starting with 
      removeAttributesStartingWith($(this), 'data-x-');
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
      removeAttributesStartingWith($(this), 'data-mce-');
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
  });
});

function removeAttributesStartingWith(target, starts_with) {
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