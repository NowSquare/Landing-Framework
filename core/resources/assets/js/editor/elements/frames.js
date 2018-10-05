function lfInitFrame($frame, unique_class) {
  var timestamp = new Date().getTime();

  if (typeof unique_class === 'undefined') {

    // Set unique class
    var unique_class = '-x-data-frame-' + timestamp;

    // Clone edit button
    var $edit_button = $(xTplFrameButton).clone().appendTo('body');
  } else {
    var frame_class = unique_class;

    // New unique class
    var unique_class = '-x-data-frame-' + timestamp;

    // Clone frame and replace with new class
    $frame.removeClass(frame_class);
    $frame.addClass(unique_class);
    $frame.attr('data-x-el', unique_class);

    // Settings
    var $edit_button = $('.-x-el-frame-edit-clone[data-x-el=' + frame_class + ']').clone().insertAfter('.-x-el-frame-edit-clone[data-x-el=' + frame_class + ']');
    $edit_button.attr('data-x-el', unique_class);
  }

  // Attribute settings
  var attachment = $frame.attr('data-attachment');
  attachment = (typeof attachment !== typeof undefined && attachment !== false) ? attachment : 'bottom left';

  var targetAttachment = $frame.attr('data-target-attachment');
  targetAttachment = (typeof targetAttachment !== typeof undefined && targetAttachment !== false) ? targetAttachment : 'top left';

  var offset = $frame.attr('data-offset');
  offset = (typeof offset !== typeof undefined && offset !== false) ? offset : '5px 0px';

  var dropdownPosition = $frame.attr('data-dropdown-position');
  dropdownPosition = (typeof dropdownPosition !== typeof undefined && dropdownPosition !== false) ? dropdownPosition : 'right';

  $frame.addClass(unique_class);
  $frame.attr('data-x-el', unique_class);
  $edit_button.attr('data-x-el', unique_class);

  // Set reference to parent block
  $edit_button.attr('data-x-parent-block', $frame.parents('.-x-block').attr('data-x-el'));

  // Replace class so it won't be cloned in next loop
  $edit_button.removeClass('-x-el-frame-edit').addClass('-x-el-frame-edit-clone -x-el-inline-button-clone');

  // Add class so dropdown opens on the left side
  if (dropdownPosition == 'left') {
    $edit_button.find('.-x-el-dropdown').addClass('-x-el-dropdown-left');
  }

  // Check visibility
  if ($edit_button.hasClass('invisible')) {
    $edit_button.find('.-x-el-frame-visible .-x-el-checkmark').removeClass('-x-checked');
  } else {
    $edit_button.find('.-x-el-frame-visible .-x-el-checkmark').addClass('-x-checked');
  }

  if ($frame.hasClass('embed-responsive-21by9')) {
    $edit_button.find('.-x-el-frame-ratio-select[data-x-ratio=21by9] .-x-el-checkmark').addClass('-x-checked');
  } else if ($frame.hasClass('embed-responsive-16by9')) {
    $edit_button.find('.-x-el-frame-ratio-select[data-x-ratio=16by9] .-x-el-checkmark').addClass('-x-checked');
  } else if ($frame.hasClass('embed-responsive-4by3')) {
    $edit_button.find('.-x-el-frame-ratio-select[data-x-ratio=4by3] .-x-el-checkmark').addClass('-x-checked');
  } else if ($frame.hasClass('embed-responsive-1by1')) {
    $edit_button.find('.-x-el-frame-ratio-select[data-x-ratio=1by1] .-x-el-checkmark').addClass('-x-checked');
  }

  // Check shadow
  $edit_button.find('.-x-el-frame-shadow-select .-x-el-checkmark').removeClass('-x-checked');

  if ($frame.hasClass('mdl-shadow--2dp')) {
    $edit_button.find('.-x-el-frame-shadow-select[data-x-shadow=mdl-shadow--2dp] .-x-el-checkmark').addClass('-x-checked');
  } else if ($frame.hasClass('mdl-shadow--3dp')) {
    $edit_button.find('.-x-el-frame-shadow-select[data-x-shadow=mdl-shadow--3dp] .-x-el-checkmark').addClass('-x-checked');
  } else if ($frame.hasClass('mdl-shadow--4dp')) {
    $edit_button.find('.-x-el-frame-shadow-select[data-x-shadow=mdl-shadow--4dp] .-x-el-checkmark').addClass('-x-checked');
  } else if ($frame.hasClass('mdl-shadow--6dp')) {
    $edit_button.find('.-x-el-frame-shadow-select[data-x-shadow=mdl-shadow--6dp] .-x-el-checkmark').addClass('-x-checked');
  } else if ($frame.hasClass('mdl-shadow--8dp')) {
    $edit_button.find('.-x-el-frame-shadow-select[data-x-shadow=mdl-shadow--8dp] .-x-el-checkmark').addClass('-x-checked');
  } else if ($frame.hasClass('mdl-shadow--16dp')) {
    $edit_button.find('.-x-el-frame-shadow-select[data-x-shadow=mdl-shadow--16dp] .-x-el-checkmark').addClass('-x-checked');
  } else if ($frame.hasClass('mdl-shadow--xlarge')) {
    $edit_button.find('.-x-el-frame-shadow-select[data-x-shadow=mdl-shadow--xlarge] .-x-el-checkmark').addClass('-x-checked');
  } else {
    $edit_button.find('.-x-el-frame-shadow-select[data-x-shadow=none] .-x-el-checkmark').addClass('-x-checked');
  }

  new Tether({
    element: $edit_button,
    target: $frame,
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

function lfInitFrames() {
  /*
    Loop through all frames
  */

  $('.-x-frame').each(function() {
    var $frame = $(this);
    lfInitFrame($frame);
  });

  lfParseFrames(true);

  /* 
    Open modal to configure frame
  */

  $('body').on('click', '.-x-el-frame-update', function() {
    var el_class = $(this).parents('.-x-el-frame-edit-clone').attr('data-x-el');

    if (! $(this).hasClass('-x-el-disabled') && typeof el_class !== typeof undefined && el_class !== false) {

      // Hide dropdown after option has been clicked
      $(this).parents('.-x-el-dropdown').trigger('mouseleave', [{immediate: true}]);

      // Check what settings can be configured in the modal
      var $el = $('.' + el_class);

      // Is immediate parent a link?
      //var link = ($el.parent('a').length > 0) ? 1 : 0;

      lfOpenModal(_lang["url"] + '/landingpages/editor/modal/frame', el_class);
    }
  });

  /* 
    Frame ratio
  */

  $('body').on('click', '.-x-el-frame-ratio-select', function() {
    var el_class = $(this).parents('.-x-el-frame-edit-clone').attr('data-x-el');

    if (! $(this).hasClass('-x-el-disabled') && typeof el_class !== typeof undefined && el_class !== false) {

      var selected_ratio = $(this).attr('data-x-ratio');

      // Unselect all
      $(this).parents('ul').find('.-x-el-frame-ratio-select .-x-el-checkmark').removeClass('-x-checked');

      // Select size
      $(this).parents('ul').find('.-x-el-frame-ratio-select[data-x-ratio=' + selected_ratio + '] .-x-el-checkmark').addClass('-x-checked');

      // Remove all size classes
      var $el = $('.' + el_class);
      $el.removeClass('embed-responsive-21by9 embed-responsive-16by9 embed-responsive-4by3 embed-responsive-1by1');

      // Add size class
      $el.addClass('embed-responsive-' + selected_ratio);

      // Hide dropdown after option has been clicked
      $(this).parents('.-x-el-dropdown').trigger('mouseleave', [{immediate: true}]);
    }
  });


  /* 
    Toggle frame visibility
  */

  $('body').on('click', '.-x-el-frame-visible', function() {
    var el_class = $(this).parents('.-x-el-frame-edit-clone').attr('data-x-el');

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
    Frame shadow
  */

  $('body').on('click', '.-x-el-frame-shadow-select', function() {
    var el_class = $(this).parents('.-x-el-frame-edit-clone').attr('data-x-el');

    if (! $(this).hasClass('-x-el-disabled') && typeof el_class !== typeof undefined && el_class !== false) {

      var selected_shadow = $(this).attr('data-x-shadow');

        // Unselect all
      $(this).parents('ul').find('.-x-el-frame-shadow-select .-x-el-checkmark').removeClass('-x-checked');

      // Select shadow
      $(this).parents('ul').find('.-x-el-frame-shadow-select[data-x-shadow=' + selected_shadow + '] .-x-el-checkmark').addClass('-x-checked');

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
  Duplicate frame buttons and references
*/

function lfDuplicateBlockFrames($new_block) {

  // Loop through all frames in new block
  $new_block.find('.-x-frame').each(function() {
    var timestamp = new Date().getTime();
    var $new_frame = $(this);
    var frame_class = $new_frame.attr('data-x-el');

    lfInitFrame($new_frame, frame_class);
  });

  // Timeout to make sure dom has changed
  setTimeout(lfParseFrames, 70);
}

/* 
  Loop through frame settings to set attributes
  and fix z-index overlapping. 
*/

function lfParseFrames(init) {
  var zIndex = 100;
  
  $('.-x-frame').each(function() {
    var frame_class = $(this).attr('data-x-el');
    var $frame_settings = $('.-x-el-frame-edit-clone[data-x-el=' + frame_class + ']');

    // Set z-index to prevent overlapping of dropdown menus
    $frame_settings.css('cssText', 'z-index: ' + zIndex + ' !important;');
    $frame_settings.find('.-x-el-dropdown').css('cssText', 'z-index: ' + zIndex + ' !important;');
    zIndex--;
  });

  // Always reposition tethered elements.
  // Also initially because $frame_settings.css('cssText', ...); 
  // seems to reset position
  Tether.position();
}