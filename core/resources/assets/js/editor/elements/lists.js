/*
  Generate semi-unique class
  to reference lists for use in the editor. Add `-clone`
  suffix to class to prevent cloning to the power and
  link dropdown to list (Tether).
*/

function lfInitList($list, unique_class) {
  var timestamp = new Date().getTime();

  if (typeof unique_class === 'undefined') {

    // Set unique class
    var unique_class = '-x-data-list-' + timestamp;

    // Clone edit button
    var $edit_button = $(xTplListButton).clone().appendTo('body');
  } else {
    var list_class = unique_class;

    // New unique class
    var unique_class = '-x-data-list-' + timestamp;

    // Clone list and replace with new class
    $list.removeClass(list_class);
    $list.addClass(unique_class);
    $list.attr('data-x-el', unique_class);

    // Settings
    var $edit_button = $('.-x-el-list-edit-clone[data-x-el=' + list_class + ']').clone().insertAfter('.-x-el-list-edit-clone[data-x-el=' + list_class + ']');
    $edit_button.attr('data-x-el', unique_class);
  }

  // Attribute settings
  var attachment = $list.attr('data-attachment');
  attachment = (typeof attachment !== typeof undefined && attachment !== false) ? attachment : 'top left';

  var targetAttachment = $list.attr('data-target-attachment');
  targetAttachment = (typeof targetAttachment !== typeof undefined && targetAttachment !== false) ? targetAttachment : 'bottom left';

  var offset = $list.attr('data-offset');
  offset = (typeof offset !== typeof undefined && offset !== false) ? offset : '0 0';

  var dropdownPosition = $list.attr('data-dropdown-position');
  dropdownPosition = (typeof dropdownPosition !== typeof undefined && dropdownPosition !== false) ? dropdownPosition : 'right';

  $list.addClass(unique_class);
  $list.attr('data-x-el', unique_class);
  $edit_button.attr('data-x-el', unique_class);

  // Set reference to parent block
  $edit_button.attr('data-x-parent-block', $list.parents('.-x-block').attr('data-x-el'));

  // Replace class so it won't be cloned in next loop
  $edit_button.removeClass('-x-el-list-edit').addClass('-x-el-list-edit-clone -x-el-inline-button-clone');

  // Add class so dropdown opens on the left side
  if (dropdownPosition == 'left') {
    $edit_button.find('.-x-el-dropdown').addClass('-x-el-dropdown-left');
  }

  // Check visibility
  if ($edit_button.hasClass('invisible')) {
    $edit_button.find('.-x-el-list-visible .-x-el-checkmark').removeClass('-x-checked');
  } else {
    $edit_button.find('.-x-el-list-visible .-x-el-checkmark').addClass('-x-checked');
  }

  new Tether({
    element: $edit_button,
    target: $list,
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

function lfInitLists() {

  /*
    Loop through all lists, generate semi-unique class
    to reference lists for use in the editor. Add `-clone`
    suffix to class to prevent cloning to the power and
    link dropdown to list (Tether).
  */

  $('.-x-list').each(function() {
    var $list = $(this);
    lfInitList($list);
  });

  lfParseLists(true);

  /* 
    Open modal to configure list
  */

  $('body').on('click', '.-x-el-list-edit', function() {
    var el_class = $(this).parents('.-x-el-list-edit-clone').attr('data-x-el');

    if (! $(this).hasClass('-x-el-disabled') && typeof el_class !== typeof undefined && el_class !== false) {

      // Hide dropdown after option has been clicked
      $(this).parents('.-x-el-dropdown').trigger('mouseleave', [{immediate: true}]);

      // Check what settings can be configured in the modal
      var $el = $('.' + el_class);
      var repeat = $el.attr('data-repeat');
      if (repeat === typeof undefined || repeat === false) repeat = 'a';

      lfOpenModal(_lang["url"] + '/landingpages/editor/modal/list?repeat=' + repeat, el_class);
    }
  });

  /* 
    Toggle list visibility
  */

  $('body').on('click', '.-x-el-list-visible', function() {
    var el_class = $(this).parents('.-x-el-list-edit-clone').attr('data-x-el');

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
  Duplicate lists and references
*/

function lfDuplicateBlockLists($new_block) {

  // Loop through all lists in new block
  $new_block.find('.-x-list').each(function() {
    var timestamp = new Date().getTime();
    var $new_list = $(this);
    var list_class = $new_list.attr('data-x-el');

    lfInitList($new_list, list_class);
  });

  // Timeout to make sure dom has changed
  setTimeout(lfParseLists, 70);
}

/* 
  Loop through list settings to set attributes
  and fix z-index overlapping. 
*/

function lfParseLists(init) {
  var zIndex = 200;
  
  $('.-x-list').each(function() {
    var list_class = $(this).attr('data-x-el');
    var $list_settings = $('.-x-el-list-edit-clone[data-x-el=' + list_class + ']');

    // Set z-index to prevent overlapping of dropdown menus
    $list_settings.css('cssText', 'z-index: ' + zIndex + ' !important;');
    $list_settings.find('.-x-el-dropdown').css('cssText', 'z-index: ' + zIndex + ' !important;');
    zIndex--;
  });

  // Always reposition tethered elements.
  // Also initially because $list_settings.css('cssText', ...); 
  // seems to reset position
  Tether.position();
}