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

function lfInitEditor(editor) {
  lfInitBlocks();
  lfInitImages();
  lfInitVideos();
  lfInitFrames();
  lfInitIcons();
  lfInitLinks();
  lfInitLists();
  lfInitCountdowns();
  lfInitShares();
  lfInitForms();
  lfInitText();

  if (typeof editor === 'undefined') {
    lfInitFabLandingpages();
  } else if (editor == 'forms') {
    lfInitFabForms();
  } else if (editor == 'emails') {
    var emailEditor;
    lfInitFabEmails();
  }

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
      if ($(this).attr('href') == '#' || $(this).attr('href').substring(0, 2) == '--') {
        return false;
      } else if ($(this).attr('href') != 'javascript:void(0);') {
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