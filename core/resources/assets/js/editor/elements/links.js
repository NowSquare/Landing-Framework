/*
  Generate semi-unique class
  to reference links for use in the editor. Add `-clone`
  suffix to class to prevent cloning to the power and
  link settings link with dropdown to link (Tether).
*/

function lfInitLink($link, unique_class) {
  var timestamp = new Date().getTime();

  if (typeof unique_class === 'undefined') {

    // Set unique class
    var unique_class = '-x-data-link-' + timestamp;

    // Clone edit button
    var $edit_button = $(xTplLinkButton).clone().appendTo('body');
  } else {
    var link_class = unique_class;

    // New unique class
    var unique_class = '-x-data-link-' + timestamp;

    // Clone link and replace with new class
    $link.removeClass(link_class);
    $link.addClass(unique_class);
    $link.attr('data-x-el', unique_class);

    // Settings
    var $edit_button = $('.-x-el-link-edit-clone[data-x-el=' + link_class + ']').clone().insertAfter('.-x-el-link-edit-clone[data-x-el=' + link_class + ']');
    $edit_button.attr('data-x-el', unique_class);
  }

  // Attribute settings
  var attachment = $link.attr('data-attachment');
  attachment = (typeof attachment !== typeof undefined && attachment !== false) ? attachment : 'top left';

  var targetAttachment = $link.attr('data-target-attachment');
  targetAttachment = (typeof targetAttachment !== typeof undefined && targetAttachment !== false) ? targetAttachment : 'bottom left';

  var offset = $link.attr('data-offset');
  offset = (typeof offset !== typeof undefined && offset !== false) ? offset : '-5px 0';

  var dropdownPosition = $link.attr('data-dropdown-position');
  dropdownPosition = (typeof dropdownPosition !== typeof undefined && dropdownPosition !== false) ? dropdownPosition : 'right';

  $link.addClass(unique_class);
  $link.attr('data-x-el', unique_class);
  $edit_button.attr('data-x-el', unique_class);

  // Set reference to parent block
  $edit_button.attr('data-x-parent-block', $link.parents('.-x-block').attr('data-x-el'));

  // Replace class so it won't be cloned in next loop
  $edit_button.removeClass('-x-el-link-edit').addClass('-x-el-link-edit-clone -x-el-inline-button-clone');

  // Add class so dropdown opens on the left side
  if (dropdownPosition == 'left') {
    $edit_button.find('.-x-el-dropdown').addClass('-x-el-dropdown-left');
  }

  // Check if button option is available
  if ($link.hasClass('btn')) {
    $edit_button.find('.-x-el-link-shape').removeClass('-x-el-disabled');
    // Check selected shape
    if ($link.hasClass('btn-pill')) {
      $edit_button.find('.-x-el-link-shape-pill .-x-el-checkmark').addClass('-x-checked');
      $edit_button.find('.-x-el-link-shape-regular .-x-el-checkmark').removeClass('-x-checked');
    } else {
      $edit_button.find('.-x-el-link-shape-pill .-x-el-checkmark').removeClass('-x-checked');
      $edit_button.find('.-x-el-link-shape-regular .-x-el-checkmark').addClass('-x-checked');
    }
  } else {
    $edit_button.find('.-x-el-link-shape').addClass('-x-el-disabled');
  }

  new Tether({
    element: $edit_button,
    target: $link,
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

}

function lfInitLinks() {

  /*
    Loop through all links
  */

  $('.-x-link').each(function() {
    var $link = $(this);
    lfInitLink($link);
  });

  lfParseLinks(true);

  /* 
    Open modal to configure link
  */

  $('body').on('click', '.-x-el-link-edit', function() {
    var el_class = $(this).parents('.-x-el-link-edit-clone').attr('data-x-el');

    if (! $(this).hasClass('-x-el-disabled') && typeof el_class !== typeof undefined && el_class !== false) {

      // Hide dropdown after option has been clicked
      $(this).parents('.-x-el-dropdown').trigger('mouseleave');

      // Check what settings can be configured in the modal
      //var $el = $('.' + el_class);

      lfOpenModal(_lang["url"] + '/landingpages/editor/modal/link', el_class);
    }
  });

  /* 
    Button regular
  */

  $('body').on('click', '.-x-el-link-shape-regular', function() {
    var el_class = $(this).parents('.-x-el-link-edit-clone').attr('data-x-el');

    if (! $(this).hasClass('-x-el-disabled') && typeof el_class !== typeof undefined && el_class !== false) {

      // Checkmark selected shape
      $(this).parents('ul').find('.-x-el-link-shape-pill .-x-el-checkmark').removeClass('-x-checked');
      $(this).parents('ul').find('.-x-el-link-shape-regular .-x-el-checkmark').addClass('-x-checked');

      // Hide dropdown after option has been clicked
      $(this).parents('.-x-el-dropdown').trigger('mouseleave');

      // Remove pill class
      var $el = $('.' + el_class);
      $el.removeClass('btn-pill');
    }
  });

  /* 
    Button pill
  */

  $('body').on('click', '.-x-el-link-shape-pill', function() {
    var el_class = $(this).parents('.-x-el-link-edit-clone').attr('data-x-el');

    if (! $(this).hasClass('-x-el-disabled') && typeof el_class !== typeof undefined && el_class !== false) {

      // Checkmark selected shape
      $(this).parents('ul').find('.-x-el-link-shape-pill .-x-el-checkmark').addClass('-x-checked');
      $(this).parents('ul').find('.-x-el-link-shape-regular .-x-el-checkmark').removeClass('-x-checked');

      // Hide dropdown after option has been clicked
      $(this).parents('.-x-el-dropdown').trigger('mouseleave');

      // First remove pill class to prevent double,
      // then add again.
      var $el = $('.' + el_class);
      $el.removeClass('btn-pill').addClass('btn-pill');
    }
  });
}

/* 
  Duplicate links and references
*/

function lfDuplicateBlockLinks($new_block) {

  // Loop through all links in new block
  $new_block.find('.-x-link').each(function() {
    var timestamp = new Date().getTime();
    var $new_link = $(this);
    var link_class = $new_link.attr('data-x-el');

    lfInitLink($new_link, link_class);
  });

  // Timeout to make sure dom has changed
  setTimeout(lfParseLinks, 70);
}

/* 
  Loop through link settings to set attributes
  and fix z-index overlapping. 
*/

function lfParseLinks(init) {
  var zIndex = 200;
  
  $('.-x-link').each(function() {
    var link_class = $(this).attr('data-x-el');
    var $link_settings = $('.-x-el-link-edit-clone[data-x-el=' + link_class + ']');

    // Set z-index to prevent overlapping of dropdown menus
    $link_settings.css('cssText', 'z-index: ' + zIndex + ' !important;');
    $link_settings.find('.-x-el-dropdown').css('cssText', 'z-index: ' + zIndex + ' !important;');
    zIndex--;
  });

  // Always reposition tethered elements.
  // Also initially because $link_settings.css('cssText', ...); 
  // seems to reset position
  Tether.position();
}
