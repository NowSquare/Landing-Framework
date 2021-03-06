function lfInitImage($img, unique_class) {
  var timestamp = new Date().getTime();

  if (typeof unique_class === 'undefined') {

    // Set unique class
    var unique_class = '-x-data-img-' + timestamp;

    // Clone edit button
    var $edit_button = $(xTplImgButton).clone().appendTo('body');
  } else {
    var img_class = unique_class;

    // New unique class
    var unique_class = '-x-data-img-' + timestamp;

    // Clone img and replace with new class
    $img.removeClass(img_class);
    $img.addClass(unique_class);
    $img.attr('data-x-el', unique_class);

    // Settings
    var $edit_button = $('.-x-el-img-edit-clone[data-x-el=' + img_class + ']').clone().insertAfter('.-x-el-img-edit-clone[data-x-el=' + img_class + ']');
    $edit_button.attr('data-x-el', unique_class);
  }

  // Attribute settings
  var attachment = $img.attr('data-attachment');
  attachment = (typeof attachment !== typeof undefined && attachment !== false) ? attachment : 'top left';

  var targetAttachment = $img.attr('data-target-attachment');
  targetAttachment = (typeof targetAttachment !== typeof undefined && targetAttachment !== false) ? targetAttachment : 'top left';

  var offset = $img.attr('data-offset');
  offset = (typeof offset !== typeof undefined && offset !== false) ? offset : '-5px -5px';

  var dropdownPosition = $img.attr('data-dropdown-position');
  dropdownPosition = (typeof dropdownPosition !== typeof undefined && dropdownPosition !== false) ? dropdownPosition : 'right';

  $img.addClass(unique_class);
  $img.attr('data-x-el', unique_class);
  $edit_button.attr('data-x-el', unique_class);

  // Set reference to parent block
  $edit_button.attr('data-x-parent-block', $img.parents('.-x-block').attr('data-x-el'));

  // Replace class so it won't be cloned in next loop
  $edit_button.removeClass('-x-el-img-edit').addClass('-x-el-img-edit-clone -x-el-inline-button-clone');

  // Add class so dropdown opens on the left side
  if (dropdownPosition == 'left') {
    $edit_button.find('.-x-el-dropdown').addClass('-x-el-dropdown-left');
  }

  // Check visibility
  if ($edit_button.hasClass('invisible')) {
    $edit_button.find('.-x-el-img-visible .-x-el-checkmark').removeClass('-x-checked');
  } else {
    $edit_button.find('.-x-el-img-visible .-x-el-checkmark').addClass('-x-checked');
  }

  // Check shape
  $edit_button.find('.-x-el-img-shape-select .-x-el-checkmark').removeClass('-x-checked');

  if ($img.hasClass('rounded')) {
    $edit_button.find('.-x-el-img-shape-select[data-x-shape=rounded] .-x-el-checkmark').addClass('-x-checked');
  } else if ($img.hasClass('round')) {
    $edit_button.find('.-x-el-img-shape-select[data-x-shape=round] .-x-el-checkmark').addClass('-x-checked');
  } else if ($img.hasClass('img-thumbnail')) {
    $edit_button.find('.-x-el-img-shape-select[data-x-shape=img-thumbnail] .-x-el-checkmark').addClass('-x-checked');
  } else {
    $edit_button.find('.-x-el-img-shape-select[data-x-shape=none] .-x-el-checkmark').addClass('-x-checked');
  }

  // Check shadow
  if ($img.hasClass('-x-no-shadow')) {
    $edit_button.find('.-x-el-img-shadow').addClass('-x-el-disabled');
  } else {
    $edit_button.find('.-x-el-img-shadow-select .-x-el-checkmark').removeClass('-x-checked');

    if ($img.hasClass('mdl-shadow--2dp')) {
      $edit_button.find('.-x-el-img-shadow-select[data-x-shadow=mdl-shadow--2dp] .-x-el-checkmark').addClass('-x-checked');
    } else if ($img.hasClass('mdl-shadow--3dp')) {
      $edit_button.find('.-x-el-img-shadow-select[data-x-shadow=mdl-shadow--3dp] .-x-el-checkmark').addClass('-x-checked');
    } else if ($img.hasClass('mdl-shadow--4dp')) {
      $edit_button.find('.-x-el-img-shadow-select[data-x-shadow=mdl-shadow--4dp] .-x-el-checkmark').addClass('-x-checked');
    } else if ($img.hasClass('mdl-shadow--6dp')) {
      $edit_button.find('.-x-el-img-shadow-select[data-x-shadow=mdl-shadow--6dp] .-x-el-checkmark').addClass('-x-checked');
    } else if ($img.hasClass('mdl-shadow--8dp')) {
      $edit_button.find('.-x-el-img-shadow-select[data-x-shadow=mdl-shadow--8dp] .-x-el-checkmark').addClass('-x-checked');
    } else if ($img.hasClass('mdl-shadow--16dp')) {
      $edit_button.find('.-x-el-img-shadow-select[data-x-shadow=mdl-shadow--16dp] .-x-el-checkmark').addClass('-x-checked');
    } else if ($img.hasClass('mdl-shadow--xlarge')) {
      $edit_button.find('.-x-el-img-shadow-select[data-x-shadow=mdl-shadow--xlarge] .-x-el-checkmark').addClass('-x-checked');
    } else {
      $edit_button.find('.-x-el-img-shadow-select[data-x-shadow=none] .-x-el-checkmark').addClass('-x-checked');
    }
  }

  new Tether({
    element: $edit_button,
    target: $img,
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

  if (offset_bottom < 370) {
    $dropdown.addClass('-x-el-dropdown-up');
  }

  if (offset_right < 250) {
    $dropdown.addClass('-x-el-dropdown-left');
  }
}

function lfInitImages() {
  /*
    Loop through all images
  */

  $('.-x-img').each(function() {
    var $img = $(this);
    lfInitImage($img);
  });

  lfParseImages(true);

  /* 
    Open modal to configure image
  */

  $('body').on('click', '.-x-el-img-update', function() {
    var el_class = $(this).parents('.-x-el-img-edit-clone').attr('data-x-el');

    if (! $(this).hasClass('-x-el-disabled') && typeof el_class !== typeof undefined && el_class !== false) {

      // Hide dropdown after option has been clicked
      $(this).parents('.-x-el-dropdown').trigger('mouseleave', [{immediate: true}]);

      // Check what settings can be configured in the modal
      var $el = $('.' + el_class);

      // Is immediate parent a link?
      var link = ($el.parent('a').length > 0) ? 1 : 0;

      lfOpenModal(_lang["url"] + '/landingpages/editor/modal/image?link=' + link, el_class);
    }
  });

  /* 
    Toggle image visibility
  */

  $('body').on('click', '.-x-el-img-visible', function() {
    var el_class = $(this).parents('.-x-el-img-edit-clone').attr('data-x-el');

    if (! $(this).hasClass('-x-el-disabled') && typeof el_class !== typeof undefined && el_class !== false) {

      // Toggle .invisible class
      var $el = $('.' + el_class);
      $el.toggleClass('invisible');
      $(this).find('.-x-el-checkmark').toggleClass('-x-checked');

      // Hide dropdown after option has been clicked
      $(this).parents('.-x-el-dropdown').trigger('mouseleave', [{immediate: true}]);
    }
  });

  /* 
    Remove image
  */

  $('body').on('click', '.-x-el-img-remove', function() {
    var el_class = $(this).parents('.-x-el-img-edit-clone').attr('data-x-el');

    if (! $(this).hasClass('-x-el-disabled') && typeof el_class !== typeof undefined && el_class !== false) {

      // Hide dropdown after option has been clicked
      $(this).parents('.-x-el-dropdown').trigger('mouseleave', [{immediate: true}]);

      // Toggle visibility
      var $el = $('.' + el_class);

      // Remove title
      $el.attr('alt', null);

      // Replace image with transparent pixel
      $el.attr('src', 'data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=');

      // Replace link if exists
      if ($el.parent('a').length > 0) $el.parent('a').attr('href', 'javascript:void(0);');

      // Changes detected
      lfSetPageIsDirty();
    }
  });

  /* 
    Image shape
  */

  $('body').on('click', '.-x-el-img-shape-select', function() {
    var el_class = $(this).parents('.-x-el-img-edit-clone').attr('data-x-el');

    if (! $(this).hasClass('-x-el-disabled') && typeof el_class !== typeof undefined && el_class !== false) {

      var selected_shape = $(this).attr('data-x-shape');

      // Unselect all
      $(this).parents('ul').find('.-x-el-img-shape-select .-x-el-checkmark').removeClass('-x-checked');

      // Select shape
      $(this).parents('ul').find('.-x-el-img-shape-select[data-x-shape=' + selected_shape + '] .-x-el-checkmark').addClass('-x-checked');

      // Remove all shape classes
      var $el = $('.' + el_class);
      $el.removeClass('rounded round img-thumbnail');

      // Add shape class
      if (selected_shape != 'none') {
        $el.addClass(selected_shape);
      }

      // Hide dropdown after option has been clicked
      $(this).parents('.-x-el-dropdown').trigger('mouseleave', [{immediate: true}]);
    }
  });

  /* 
    Image shadow
  */

  $('body').on('click', '.-x-el-img-shadow-select', function() {
    var el_class = $(this).parents('.-x-el-img-edit-clone').attr('data-x-el');

    if (! $(this).hasClass('-x-el-disabled') && typeof el_class !== typeof undefined && el_class !== false) {

      var selected_shadow = $(this).attr('data-x-shadow');

        // Unselect all
      $(this).parents('ul').find('.-x-el-img-shadow-select .-x-el-checkmark').removeClass('-x-checked');

      // Select shadow
      $(this).parents('ul').find('.-x-el-img-shadow-select[data-x-shadow=' + selected_shadow + '] .-x-el-checkmark').addClass('-x-checked');

      // Remove all shadow classes
      var $el = $('.' + el_class);
      $el.removeClass('mdl-shadow--2dp mdl-shadow--3dp mdl-shadow--4dp mdl-shadow--6dp mdl-shadow--8dp mdl-shadow--16dp mdl-shadow--xlarge');

      // Add shadow class
      if (selected_shadow != 'none') {
        $el.addClass(selected_shadow);
      }

      // Hide dropdown after option has been clicked
      $(this).parents('.-x-el-dropdown').trigger('mouseleave', [{immediate: true}]);
    }
  });
}

/* 
  Duplicate image buttons and references
*/

function lfDuplicateBlockImages($new_block) {

  // Loop through all images in new block
  $new_block.find('.-x-img').each(function() {
    var timestamp = new Date().getTime();
    var $new_img = $(this);
    var img_class = $new_img.attr('data-x-el');

    lfInitImage($new_img, img_class);
  });

  // Timeout to make sure dom has changed
  setTimeout(lfParseImages, 70);
}

/* 
  Loop through img settings to set attributes
  and fix z-index overlapping. 
*/

function lfParseImages(init) {
  var zIndex = 100;
  
  $('.-x-img').each(function() {
    var img_class = $(this).attr('data-x-el');
    var $img_settings = $('.-x-el-img-edit-clone[data-x-el=' + img_class + ']');

    // Set z-index to prevent overlapping of dropdown menus
    $img_settings.css('cssText', 'z-index: ' + zIndex + ' !important;');
    $img_settings.find('.-x-el-dropdown').css('cssText', 'z-index: ' + zIndex + ' !important;');
    zIndex--;
  });

  // Always reposition tethered elements.
  // Also initially because $img_settings.css('cssText', ...); 
  // seems to reset position
  Tether.position();
}