/*
  Generate semi-unique class
  to reference shares for use in the editor. Add `-clone`
  suffix to class to prevent cloning to the power and
  link dropdown to list (Tether).
*/

function lfInitShare($share, unique_class) {
  var timestamp = new Date().getTime();

  if (typeof unique_class === 'undefined') {

    // Set unique class
    var unique_class = '-x-data-share-' + timestamp;

    // Clone edit button
    var $edit_button = $(xTplShareButton).clone().appendTo('body');
  } else {
    var share_class = unique_class;

    // New unique class
    var unique_class = '-x-data-share-' + timestamp;

    // Clone share and replace with new class
    $share.removeClass(share_class);
    $share.addClass(unique_class);
    $share.attr('data-x-el', unique_class);

    // Settings
    var $edit_button = $('.-x-el-share-edit-clone[data-x-el=' + share_class + ']').clone().insertAfter('.-x-el-share-edit-clone[data-x-el=' + share_class + ']');
    $edit_button.attr('data-x-el', unique_class);
  }

  // Attribute settings
  var attachment = $share.attr('data-attachment');
  attachment = (typeof attachment !== typeof undefined && attachment !== false) ? attachment : 'bottom left';

  var targetAttachment = $share.attr('data-target-attachment');
  targetAttachment = (typeof targetAttachment !== typeof undefined && targetAttachment !== false) ? targetAttachment : 'top left';

  var offset = $share.attr('data-offset');
  offset = (typeof offset !== typeof undefined && offset !== false) ? offset : '0 0';

  var dropdownPosition = $share.attr('data-dropdown-position');
  dropdownPosition = (typeof dropdownPosition !== typeof undefined && dropdownPosition !== false) ? dropdownPosition : 'right';

  $share.addClass(unique_class);
  $share.attr('data-x-el', unique_class);
  $edit_button.attr('data-x-el', unique_class);

  // Set reference to parent block
  $edit_button.attr('data-x-parent-block', $share.parents('.-x-block').attr('data-x-el'));

  // Replace class so it won't be cloned in next loop
  $edit_button.removeClass('-x-el-share-edit').addClass('-x-el-share-edit-clone -x-el-inline-button-clone');

  // Add class so dropdown opens on the left side
  if (dropdownPosition == 'left') {
    $edit_button.find('.-x-el-dropdown').addClass('-x-el-dropdown-left');
  }

  // Check visibility
  if ($edit_button.hasClass('invisible')) {
    $edit_button.find('.-x-el-share-visible .-x-el-checkmark').removeClass('-x-checked');
  } else {
    $edit_button.find('.-x-el-share-visible .-x-el-checkmark').addClass('-x-checked');
  }

  new Tether({
    element: $edit_button,
    target: $share,
    attachment: attachment,
    offset: offset,
    targetAttachment: targetAttachment,
    classPrefix: '-x-data',
    constraints: [{
      to: 'scrollParent',
      attachment: 'together'
    }],
    optimizations: {
      moveElement: true,
      gpu: true
    }
  });

  // Check position on page
  var offset_bottom = (parseInt($(document).outerHeight(true)) - parseInt($edit_button.position().top));
  var offset_right = (parseInt($(document).outerWidth(true)) - parseInt($edit_button.position().left));
  var $dropdown = $edit_button.find('.-x-el-dropdown');
  $dropdown.removeClass('-x-el-dropdown-up -x-el-dropdown-left');

  if (offset_bottom < 250) {
    $dropdown.addClass('-x-el-dropdown-up');
  }

  if (offset_right < 250) {
    $dropdown.addClass('-x-el-dropdown-left');
  }
}

function lfInitShares() {

  /*
    Loop through all shares, generate semi-unique class
    to reference shares for use in the editor. Add `-clone`
    suffix to class to prevent cloning to the power and
    link dropdown to list (Tether).
  */

  $('.-x-share').each(function() {
    var $share = $(this);
    lfInitShare($share);
  });

  lfParseShares(true);

  /* 
    Open modal to configure share
  */

  $('body').on('click', '.-x-el-share-edit', function() {
    var el_class = $(this).parents('.-x-el-share-edit-clone').attr('data-x-el');

    if (! $(this).hasClass('-x-el-disabled') && typeof el_class !== typeof undefined && el_class !== false) {

      // Hide dropdown after option has been clicked
      $(this).parents('.-x-el-dropdown').trigger('mouseleave', [{immediate: true}]);

      // Check what settings can be configured in the modal
      //var $el = $('.' + el_class);

      lfOpenModal(_lang["url"] + '/landingpages/editor/modal/share', el_class);
    }
  });

  /* 
    Toggle share visibility
  */

  $('body').on('click', '.-x-el-share-visible', function() {
    var el_class = $(this).parents('.-x-el-share-edit-clone').attr('data-x-el');

    if (! $(this).hasClass('-x-el-disabled') && typeof el_class !== typeof undefined && el_class !== false) {

      // Toggle .invisible class
      var $el = $('.' + el_class);
      $el.toggleClass('invisible');
      $(this).find('.-x-el-checkmark').toggleClass('-x-checked');

      // Hide dropdown after option has been clicked
      $(this).parents('.-x-el-dropdown').trigger('mouseleave', [{immediate: true}]);
    }
  });
}

/* 
  Duplicate shares and references
*/

function lfDuplicateBlockShares($new_block) {

  // Loop through all shares in new block
  $new_block.find('.-x-share').each(function() {
    var timestamp = new Date().getTime();
    var $new_share = $(this);
    var share_class = $new_share.attr('data-x-el');

    lfInitShare($new_share, share_class);
  });

  // Timeout to make sure dom has changed
  setTimeout(lfParseShares, 70);
}

/* 
  Loop through share settings to set attributes
  and fix z-index overlapping. 
*/

function lfParseShares(init) {
  var zIndex = 200;
  
  $('.-x-share').each(function() {
    var share_class = $(this).attr('data-x-el');
    var $share_settings = $('.-x-el-share-edit-clone[data-x-el=' + share_class + ']');

    // Set z-index to prevent overlapping of dropdown menus
    $share_settings.css('cssText', 'z-index: ' + zIndex + ' !important;');
    $share_settings.find('.-x-el-dropdown').css('cssText', 'z-index: ' + zIndex + ' !important;');
    zIndex--;
  });

  // Always reposition tethered elements.
  // Also initially because $share_settings.css('cssText', ...); 
  // seems to reset position
  Tether.position();
}