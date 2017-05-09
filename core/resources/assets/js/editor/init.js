/* 
  Set page is dirty when content has changed
*/
var lfPageIsDirty = false;

function lfSetPageIsDirty() {
  lfPageIsDirty = true;
}

/* 
  Init editor related features
*/

function lfInitEditor() {
  lfInitBlocks();
  lfInitImages();
  lfInitIcons();
  lfInitLinks();
  lfInitLists();
  lfInitText();
  lfInitFab();
  lfInitDropdowns();
  lfInitModal();

  /* 
    jQuery filter to select elements with certain class
  */

  jQuery.expr[':'].parents = function(a, i, m){
    return jQuery(a).parents(m[3]).length < 1;
  };

  /* 
    Confirm before following link on page
  */

  $('body').on('click', 'a:parents(.-x-el-dropdown)', function(e) {
    if ($(this).attr('data-toggle') != 'lightbox') {
      if ($(this).attr('href') == '#') {
        return false;
      } else {
        if (! confirm(_lang['confirm_follow_link'])) {
          e.preventDefault();
        }
      }
    }
  });

  /* 
    Confirm when leaving page with unsaved changes
  */

  window.onload = function() {
    window.addEventListener("beforeunload", function (e) {
      if (! lfPageIsDirty) {
          return undefined;
      }

    (e || window.event).returnValue = _lang['confirm_leaving_page']; //Gecko + IE
      return _lang['confirm_leaving_page']; //Gecko + Webkit, Safari, Chrome etc.
    });
  };
}