$(function() {
  $('#export_html').on('click', function() {

    // Get a cloned version of the html object
    var $html = $('html').clone();

    // Remove all classes starting with -lf-data-
    $html.find('[class*=-lf-data-]').each(function() {
      this.className = this.className.replace(/(^| )-lf[^ ]*/g, '');

      // Remove all attributes starting with 
      removeAttributesStartingWith($(this), 'data-lf-');
    });

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